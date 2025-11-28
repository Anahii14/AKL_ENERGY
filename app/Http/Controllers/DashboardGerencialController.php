<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\OrdenCompra;
use App\Models\Obra;
use App\Models\CotizacionGrua;
use App\Models\CotizacionElectrico;
use Illuminate\Support\Facades\DB;
use App\Models\DetallePedido;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class DashboardGerencialController extends Controller
{
    public function index(Request $request)
    {
        // 1) Filtros
        $obraId   = $request->input('obra_id');
        $fechaIni = $request->input('fecha_ini');
        $fechaFin = $request->input('fecha_fin');

        // 2) Query base por entidad
        $qPedidos  = Pedido::query()->with('obra'); 
        $qOrdenes  = OrdenCompra::query();
        $qCotGruas = CotizacionGrua::query();

        // Filtro por obra 
        if ($obraId) {
            $qPedidos->where('obra_id', $obraId);
            $qOrdenes->whereHas('pedido', fn($p) => $p->where('obra_id', $obraId));
        }

        // Filtro por fecha
        if ($fechaIni && $fechaFin) {
            $qPedidos->whereBetween('created_at', [$fechaIni, $fechaFin]);
            $qOrdenes->whereBetween('fecha_emision', [$fechaIni, $fechaFin]);
            $qCotGruas->whereBetween('created_at', [$fechaIni, $fechaFin]);
        }

        // Listas para el pane de Cotizaciones
        $qCotGruasList = CotizacionGrua::query();
        $qCotElecList  = CotizacionElectrico::query();

        // Filtro por fechas (estas tablas no tienen obra_id)
        if ($fechaIni && $fechaFin) {
            $qCotGruasList->whereBetween('created_at', [$fechaIni, $fechaFin]);
            $qCotElecList->whereBetween('created_at', [$fechaIni, $fechaFin]);
        }

        $cotGruas      = $qCotGruasList->latest('created_at')->take(10)->get();
        $cotElectricas = $qCotElecList->latest('created_at')->take(10)->get();


        // 3) Agregaciones
        $totalCotizacionesGruas = (clone $qCotGruas)->sum('precio_total');
        $totalOrdenesCompra     = (clone $qOrdenes)->sum('monto_total');
        $countOrdenes           = (clone $qOrdenes)->count();

        $pedidosTotal = (clone $qPedidos)->count();

        $pedidosPorEstado = (clone $qPedidos)
            ->select('estado', DB::raw('count(*) as cantidad'))
            ->groupBy('estado')
            ->pluck('cantidad', 'estado')
            ->toArray();

        $pedidosPorObra = Pedido::with('obra')
            ->select('obra_id', DB::raw('count(*) as cantidad'))
            ->when($obraId, fn($q) => $q->where('obra_id', $obraId))
            ->groupBy('obra_id')
            ->get()
            ->map(fn($p) => [
                'obra'     => optional($p->obra)->nombre ?? 'Sin obra',
                'cantidad' => $p->cantidad,
            ])
            ->toArray();

        $obrasActivas = Obra::count();

        // 4) KPIs
        $kpi = [
            'cot_total'         => $totalCotizacionesGruas,
            'pedidos_total'     => $pedidosTotal,
            'oc_count'          => $countOrdenes,
            'oc_total'          => $totalOrdenesCompra,
            'obras_activas'     => $obrasActivas,
            'pedidos_breakdown' => [
                'EN PROCESO'            => $pedidosPorEstado['EN PROCESO']            ?? 0,
                'LISTO'                 => $pedidosPorEstado['LISTO']                 ?? 0,
                'EN_PROCESO_DE_COMPRA'  => $pedidosPorEstado['EN_PROCESO_DE_COMPRA'] ?? 0,
            ],
        ];

        $gastos = [
            'oc_total'        => $totalOrdenesCompra,
            'cot_gruas_total' => $totalCotizacionesGruas,
        ];

        $pedidos = (clone $qPedidos)
            ->orderByDesc('created_at')
            ->paginate(10)       
            ->withQueryString();  

        $obras = Obra::select('id', 'nombre')->get();

        $ordenes = (clone $qOrdenes)
            ->with('proveedor')
            ->orderByDesc('fecha_emision')   
            ->paginate(10)
            ->withQueryString();

        // ---------------------------------------------------------
        // 5) INTEGRACIÓN IA: Predicción de Demanda (Idea 1)
        // ---------------------------------------------------------
        // Definimos un material crítico para predecir (Ej: ID 1). 
        // En el futuro podrías hacerlo dinámico o predecir el top 5.
        $materialCriticoId = 1; 
        $prediccion = $this->obtenerPrediccion($materialCriticoId);

        return view('DashboardGerente.dashboard_gerencial', compact(
            'kpi',
            'pedidosPorEstado',
            'pedidosPorObra',
            'gastos',
            'obras',
            'obraId',
            'fechaIni',
            'fechaFin',
            'pedidos',        
            'cotGruas',
            'cotElectricas', 
            'ordenes',
            'prediccion' // <--- Variable enviada a la vista con el resultado de Python
        ));
    }

    /**
     * Llama al script de Python para predecir la demanda del próximo mes.
     * Idea 1: Aprendizaje Supervisado (Regresión Lineal)
     */
    private function obtenerPrediccion($materialId)
    {
        // RUTA PYTHON: Ajusta esto según tu entorno (Linux vs Windows)
        // Opción Linux/Mac (Recomendada con venv):
        $pythonExecutable = base_path('venv/Scripts/python.exe'); 
        
        // Opción Windows (Descomentar si usas Windows):
        // $pythonExecutable = base_path('venv/Scripts/python.exe');

        // 1. Obtener historial de la BD
        $historial = DetallePedido::select(
                DB::raw('MONTH(pedidos.created_at) as mes'),
                DB::raw('SUM(detalle_pedidos.cantidad) as cantidad')
            )
            ->join('pedidos', 'detalle_pedidos.pedido_id', '=', 'pedidos.id')
            ->where('detalle_pedidos.material_id', $materialId)
            ->groupBy('mes')
            ->orderBy('mes', 'asc')
            ->get();

        // Si no hay datos suficientes, retornamos 0
        if ($historial->isEmpty()) {
            return 0;
        }

        // 2. Preparar datos para Python
        $datosJson = json_encode($historial->toArray());
        $scriptPath = base_path('ai_scripts/prediccion_demanda.py');

        // 3. Ejecutar proceso
        try {
            $process = new Process([$pythonExecutable, $scriptPath, $datosJson]);
            $process->run();

            if (!$process->isSuccessful()) {
                // Log::error($process->getErrorOutput()); // Útil para depurar
                return 0;
            }

            // 4. Retornar resultado limpio
            return (int) $process->getOutput();

        } catch (\Exception $e) {
            return 0;
        }
    }
}