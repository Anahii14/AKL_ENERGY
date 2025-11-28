<?php

namespace App\Http\Controllers;

use App\Models\GuiaSalida;
use App\Models\DetalleGuiaSalida;
use App\Models\Pedido;
use App\Models\Proveedor;
use App\Models\Material;
use App\Models\OrdenCompra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
// Importaciones necesarias para ejecutar Python
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

        // Estados permitidos para procesar
        $permitidos = ['aprobado', 'en_proceso_de_compra'];
        if (!in_array($pedido->estado, $permitidos)) {
            return back()->with('error', 'Este pedido aún no puede procesarse.');
        }

        // ===== 1. VALIDACIÓN DE STOCK (Técnica) =====
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

        // Si hay faltantes → no generamos guía; sugerimos OC
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


        // ===== 2. INTELIGENCIA ARTIFICIAL: DETECCIÓN DE ANOMALÍAS (Antirrobo) =====
        // Solo ejecutamos esto si el usuario NO ha confirmado explícitamente "ignorar_ia"
        if (!$request->has('ignorar_ia')) { 
            $anomalias = [];
            
            // CONFIGURACIÓN RUTA PYTHON:
            // Ajusta esto según tu sistema operativo.
            // Para Windows (con entorno virtual):
            $pythonExecutable = base_path('venv/Scripts/python.exe'); 
            
            // Para Linux/Mac o si usas Laragon directo:
            // $pythonExecutable = base_path('venv/bin/python');

            foreach ($pedido->detalles as $d) {
                // Obtenemos historial de salidas anteriores de este material para aprender el patrón
                $historial = DetalleGuiaSalida::where('material_id', $d->material_id)
                    ->select('cantidad')
                    ->latest()
                    ->limit(50) // Usamos los últimos 50 registros para entrenar
                    ->get()
                    ->toArray();

                // La IA necesita al menos unos pocos datos para poder juzgar (ej. 5 registros)
                if (count($historial) >= 5) {
                    $jsonHistorial = json_encode($historial);
                    
                    try {
                        // Ejecutamos el script de Python pasando el historial y la cantidad actual
                        $process = new Process([
                            $pythonExecutable, 
                            base_path('ai_scripts/deteccion_anomalias.py'), 
                            $jsonHistorial, 
                            $d->cantidad 
                        ]);
                        $process->run();

                        // La IA devuelve: 1 (Normal) o -1 (Anomalía)
                        $resultado = (int) trim($process->getOutput());

                        // Si es -1, es una anomalía
                        if ($resultado === -1) {
                            $anomalias[] = [
                                'material' => $d->material->nombre ?? 'Material ID '.$d->material_id,
                                'cantidad' => $d->cantidad,
                                'mensaje'  => "Cantidad inusualmente alta detectada por IA (Isolation Forest)."
                            ];
                        }
                    } catch (\Exception $e) {
                        // Si falla la ejecución de Python (ej. librería no instalada), 
                        // continuamos silenciosamente para no bloquear la operación.
                        // \Log::error("Error IA: " . $e->getMessage());
                    }
                }
            }

            // Si la IA encontró algo raro, detenemos el proceso y devolvemos la alerta a la vista
            if (!empty($anomalias)) {
                return back()
                    ->with('ia_anomalias', $anomalias) // Datos para el SweetAlert
                    ->with('pedido_id', $pedido->id)
                    ->with('error', '⚠️ ALERTA DE SEGURIDAD: La IA detectó cantidades sospechosas.');
            }
        }
        // ===== FIN AUDITORÍA IA =====


        // ===== 3. GENERAR GUÍA DE SALIDA (Proceso normal) =====
        // Si llegamos aquí, es porque hay stock Y (la IA dijo OK o el humano forzó la salida)
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

        // Generar y descargar PDF
        $guia->load(['obra', 'pedido', 'detalles', 'user']);
        $pdf = Pdf::loadView('pdf.guia_salida', compact('guia'));
        return $pdf->download('Guia-' . $guia->codigo . '.pdf');
    }
}