<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Obra;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PedidoController extends Controller
{
    // GET /administrador/nuevoPedido
    public function create()
    {
        $obras = Obra::orderBy('nombre')->get(['id', 'nombre']);
        $materiales = Material::orderBy('nombre')->get(['id', 'nombre']);

        return view('DashboardAdminObra.nuevoPedido', compact('obras', 'materiales'));
    }

    // POST /administrador/pedidos
    public function store(Request $request)
    {
        $data = $request->validate([
            'obra_id' => ['required', 'exists:obras,id'],
            'fecha_requerida' => ['required', 'date', 'after_or_equal:today'],
            'observaciones' => ['nullable', 'string', 'max:5000'],

            'materials' => ['required', 'array', 'min:1'],
            'materials.*.material_id' => ['required', 'exists:materiales,id'],
            'materials.*.cantidad' => ['required', 'numeric', 'min:1'],
        ], [
            'materials.required' => 'Agrega al menos un material al pedido.',
        ]);

        return DB::transaction(function () use ($data) {
            // Generar código único corto
            $codigo = Str::lower(Str::random(8));
            while (Pedido::where('codigo', $codigo)->exists()) {
                $codigo = Str::lower(Str::random(8));
            }

            // Crear cabecera
            $pedido = Pedido::create([
                'codigo'         => $codigo,
                'obra_id'        => $data['obra_id'],
                'user_id'        => Auth::id(),
                'fecha_requerida' => $data['fecha_requerida'],
                'estado'         => 'solicitado',
                'observaciones'  => $data['observaciones'] ?? null,
                'total_items'    => count($data['materials']),
            ]);

            // Agrupar por material (por si el usuario añade el mismo dos veces)
            $acum = [];
            foreach ($data['materials'] as $row) {
                $mid = (int) $row['material_id'];
                $qty = (float) $row['cantidad'];
                $acum[$mid] = ($acum[$mid] ?? 0) + $qty;
            }

            // Traer snapshot de precios
            $precios = Material::whereIn('id', array_keys($acum))
                ->pluck('precio_unitario', 'id');

            // Crear detalles con precio_unitario
            foreach ($acum as $materialId => $cantidad) {
                // Defensa: si no hay precio, usa 0
                $precio = $precios[$materialId] ?? 0;

                DetallePedido::create([
                    'pedido_id'       => $pedido->id,
                    'material_id'     => $materialId,
                    'cantidad'        => $cantidad,
                    'precio_unitario' => $precio,
                    'descuento'       => 0,
                ]);
            }

            return redirect()
                ->route('admin.misPedidos')
                ->with('success', 'Pedido creado correctamente.');
        });
    }

    // GET /administrador/misPedidos
    public function index()
    {
        $pedidos = Pedido::with('obra')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        if (view()->exists('DashboardAdminObra.misPedidos')) {
            return view('DashboardAdminObra.misPedidos', compact('pedidos'));
        }
        return view('empresa.pedidos.misPedidos', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        if ($pedido->user_id !== Auth::id()) {
            abort(403);
        }

        $pedido->load('obra', 'detalles.material', 'solicitante');

        if (view()->exists('DashboardAdminObra.pedidoShow')) {
            return view('DashboardAdminObra.pedidoShow', compact('pedido'));
        }
        return view('empresa.pedidos.show', compact('pedido'));
    }

    public function historial()
    {
        $pedidos = Pedido::with('obra')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(12);

        if (view()->exists('DashboardAdminObra.historial')) {
            return view('DashboardAdminObra.historial', compact('pedidos'));
        }
        return view('empresa.pedidos.historial', compact('pedidos'));
    }

    public function almacenIndex(Request $request)
    {
        $tab = $request->get('tab', 'pendientes');

        // Map de pestañas → conjuntos de estados
        $map = [
            'pendientes' => ['solicitado'],
            'proceso'    => ['aprobado', 'en_proceso_de_compra'],
            // NUEVO: terminados = fin de flujo operativo
            'terminados' => ['atendido', 'entregado'],
            'todos'      => ['borrador', 'solicitado', 'en_proceso_de_compra', 'aprobado', 'atendido', 'entregado', 'rechazado', 'cancelado'],
        ];

        if (!array_key_exists($tab, $map)) {
            $tab = 'pendientes';
        }

        $qBase = Pedido::with([
            'obra:id,nombre',
            'detalles.material:id,nombre,unidad,stock_actual',
        ])->latest();

        $pendientes = (clone $qBase)->whereIn('estado', $map['pendientes'])->get();
        $proceso    = (clone $qBase)->whereIn('estado', $map['proceso'])->get();
        $terminados = (clone $qBase)->whereIn('estado', $map['terminados'])->get();
        $todos      = (clone $qBase)->whereIn('estado', $map['todos'])->get();

        $counts = [
            'pendientes' => $pendientes->count(),
            'proceso'    => $proceso->count(),
            'terminados' => $terminados->count(),
            'todos'      => $todos->count(),
        ];

        $coleccion = match ($tab) {
            'proceso'    => $proceso,
            'terminados' => $terminados,
            'todos'      => $todos,
            default      => $pendientes,
        };

        return view('DashboardAlmacen.pedidos', compact('tab', 'counts', 'coleccion'));
    }



    public function marcarVisto(Pedido $pedido)
    {
        // Solo si está solicitado (pendiente)
        if ($pedido->estado !== 'solicitado') {
            return back()->with('warn', 'Este pedido ya fue procesado.');
        }

        $pedido->loadMissing('detalles.material');

        $hayStock   = true;
        $faltantes  = [];

        foreach ($pedido->detalles as $d) {
            $mat   = $d->material;
            $disp  = (float)($mat->stock_actual ?? 0);
            $req   = (float)$d->cantidad;

            if ($req > $disp) {
                $hayStock = false;
                $faltantes[] = [
                    'material' => $mat?->nombre ?? ("ID {$d->material_id}"),
                    'req'      => $req,
                    'disp'     => $disp,
                ];
            }
        }

        DB::transaction(function () use ($pedido, $hayStock) {
            if ($hayStock) {
                // Stock suficiente → pasa a aprobado
                $pedido->update([
                    'estado' => 'aprobado',
                    'observaciones' => trim(($pedido->observaciones ?? '') . "\n[ALM] Marcado como leído: stock OK"),
                ]);
            } else {
                // Sin stock → en proceso de compra
                $pedido->update([
                    'estado' => 'en_proceso_de_compra',
                    'observaciones' => trim(($pedido->observaciones ?? '') . "\n[ALM] Marcado como leído: stock insuficiente → OC"),
                ]);
            }
        });

        // SIN faltantes → mensaje normal y listo (tu flujo puede luego “Procesar Pedido” = Guía)
        if ($hayStock) {
            return back()->with('success', 'Pedido aprobado. Puedes proceder a generar la guía.');
        }

        // CON faltantes → enviamos datos a la vista para mostrar SweetAlert (confirmar generación de OC)
        return back()
            ->with('faltantes', $faltantes)
            ->with('pedido_id', $pedido->id)
            ->with('warning', 'Faltan materiales para procesar el pedido.');
    }


    public function cancelar(Pedido $pedido)
    {
        if (in_array($pedido->estado, ['entregado', 'atendido', 'cancelado', 'rechazado'])) {
            return back()->with('error', 'No se puede cancelar en este estado.');
        }
        $pedido->update(['estado' => 'cancelado']);
        return back()->with('ok', 'Pedido cancelado.');
    }

    // (Opcional) vista detalle para almacén
    public function showAlmacen(Pedido $pedido)
    {
        $pedido->load(['obra', 'detalles.material']);
        return view('DashboardAlmacen.pedidoShow', compact('pedido'));
    }

    // PATCH /almacen/pedidos/{pedido}/estado
    public function actualizarEstado(Request $request, Pedido $pedido)
    {
        // Bloqueos duros (no tocar finalizados)
        if (in_array($pedido->estado, ['entregado', 'cancelado', 'rechazado'])) {
            return back()->with('error', 'No se puede cambiar el estado de un pedido finalizado.');
        }

        $data = $request->validate([
            'estado' => ['required', 'string', 'in:solicitado,aprobado,en_proceso_de_compra,atendido,entregado'],
        ]);

        $actual = strtolower($pedido->estado);
        $nuevo  = strtolower($data['estado']);

        // Si existe una OC, no permitimos regresar a solicitado
        $tieneOC = method_exists($pedido, 'ordenCompra') && $pedido->ordenCompra;
        if ($nuevo === 'solicitado' && $tieneOC) {
            return back()->with('error', 'No puede volver a Pendiente porque ya tiene una Orden de Compra vinculada.');
        }

        // Matriz de transiciones permitidas (incluye volver a solicitado)
        $permitidas = [
            'solicitado'             => ['aprobado', 'en_proceso_de_compra'],
            'aprobado'               => ['solicitado', 'en_proceso_de_compra', 'atendido', 'entregado'],
            'en_proceso_de_compra'   => ['solicitado', 'aprobado', 'atendido', 'entregado'],
            'atendido'               => ['entregado'],
            // 'entregado','cancelado','rechazado' ya se bloquearon arriba
        ];

        if ($nuevo === $actual) {
            return back()->with('info', 'El estado ya estaba establecido.');
        }

        $validos = $permitidas[$actual] ?? [];
        if (!in_array($nuevo, $validos)) {
            return back()->with('error', 'Transición de estado no permitida.');
        }

        $pedido->update([
            'estado' => $nuevo,
            'observaciones' => trim(($pedido->observaciones ?? '') . "\n[ALM] Estado cambiado de {$actual} a {$nuevo}"),
        ]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
