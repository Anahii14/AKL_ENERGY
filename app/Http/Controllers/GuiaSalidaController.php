<?php

namespace App\Http\Controllers;

use App\Models\GuiaSalida;
use App\Models\DetalleGuiaSalida;
use App\Models\Pedido;
use App\Models\Proveedor;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\OrdenCompra;

class GuiaSalidaController extends Controller
{
    public function index()
    {
        $guias = GuiaSalida::with(['obra', 'pedido', 'user'])
            ->latest('fecha_emision')->paginate(20);

        $ordenes = OrdenCompra::with(['pedido.obra', 'proveedor'])
            ->latest('fecha_emision')->paginate(20);

        $guiasCount   = $guias->total();
        $ordenesCount = $ordenes->total();

        $pedidosParaOC = Pedido::with('obra')
            ->whereIn('estado', ['aprobado', 'en_proceso_de_compra'])
            ->orderByDesc('created_at')
            ->select(['id', 'codigo', 'obra_id', 'estado', 'created_at'])
            ->take(120)
            ->get();

        $proveedores = \App\Models\Proveedor::orderBy('nombre')
            ->get(['id', 'nombre', 'ruc']);

        return view('DashboardAlmacen.guias_orden', compact(
            'guias',
            'ordenes',
            'guiasCount',
            'ordenesCount',
            'pedidosParaOC',
            'proveedores'
        ));
    }


    /** PDF individual de una guía */
    public function pdf(GuiaSalida $guia)
    {
        $guia->load(['obra', 'pedido', 'detalles', 'user']);
        $pdf = Pdf::loadView('pdf.guia_salida', compact('guia'));
        return $pdf->download('Guia-' . $guia->codigo . '.pdf');
    }

    public function procesarPedido(Pedido $pedido, Request $request)
    {
        // Reutiliza store() pasando pedido_id
        $request->merge(['pedido_id' => $pedido->id]);
        return $this->store($request);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pedido_id' => ['required', 'exists:pedidos,id'],
        ]);

        $pedido = Pedido::with(['obra', 'detalles.material'])->findOrFail($data['pedido_id']);

        // Estados permitidos para procesar (deben existir en tu ENUM actual)
        $permitidos = ['aprobado', 'en_proceso_de_compra'];
        if (!in_array($pedido->estado, $permitidos)) {
            return back()->with('error', 'Este pedido aún no puede procesarse.');
        }

        // ===== VALIDACIÓN DE STOCK =====
        $faltantes = [];
        foreach ($pedido->detalles as $d) {
            $mat = $d->material;
            if (!$mat) {
                return back()->with('error', "Material ID {$d->material_id} no existe.");
            }
            $stock = (float) ($mat->stock_actual ?? 0);
            if ($d->cantidad > $stock) {
                $faltantes[] = [
                    'material' => $mat->nombre ?? ("ID {$d->material_id}"),
                    'req'      => (float)$d->cantidad,
                    'disp'     => $stock,
                ];
            }
        }

        // Si hay faltantes → no generamos guía; mandamos datos para SweetAlert y sugerir OC
        if (!empty($faltantes)) {
            if ($pedido->estado !== 'en_proceso_de_compra') {
                $pedido->update([
                    'estado'        => 'en_proceso_de_compra',
                    'observaciones' => trim(($pedido->observaciones ?? '') . "\n[ALM] Intento de guía: stock insuficiente → OC"),
                ]);
            }

            return back()
                ->with('faltantes', $faltantes)
                ->with('pedido_id', $pedido->id)
                ->with('warning', 'Faltan materiales para procesar el pedido.');
        }
        // ===== FIN VALIDACIÓN DE STOCK =====

        // Hay stock suficiente → generamos la Guía de Salida
        $guia = DB::transaction(function () use ($pedido) {
            $codigo = 'G-' . Str::upper(Str::random(7));

            // Cabecera
            $guia = GuiaSalida::create([
                'codigo'        => $codigo,
                'pedido_id'     => $pedido->id,
                'obra_id'       => $pedido->obra_id,
                'user_id'       => auth()->id(),
                'fecha_emision' => now()->toDateString(),
                'estado'        => 'borrador',
                'observaciones' => null,
            ]);

            // Detalles + rebaja de stock
            foreach ($pedido->detalles as $d) {
                $mat = Material::find($d->material_id);

                DetalleGuiaSalida::create([
                    'guia_salida_id' => $guia->id,
                    'material_id'    => $d->material_id,
                    'descripcion'    => $mat->nombre ?? ('ID ' . $d->material_id),
                    'unidad'         => $mat->unidad ?? null,
                    'cantidad'       => $d->cantidad,
                    'stock_anterior' => $mat->stock_actual,
                    'stock_nuevo'    => $mat->stock_actual - $d->cantidad,
                ]);

                $mat->decrement('stock_actual', $d->cantidad);
            }

            // Estado del pedido a "atendido" y traza
            $pedido->update([
                'estado'        => 'atendido',
                'observaciones' => trim(($pedido->observaciones ?? '') . "\n[ALM] Guía generada: {$codigo}"),
            ]);

            return $guia;
        });

        // PDF
        $guia->load(['obra', 'pedido', 'detalles', 'user']);
        $pdf = Pdf::loadView('pdf.guia_salida', compact('guia'));
        return $pdf->download('Guia-' . $guia->codigo . '.pdf');
    }
}
