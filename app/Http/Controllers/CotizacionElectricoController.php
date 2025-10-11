<?php

namespace App\Http\Controllers;

use App\Models\CotizacionElectrico;
use App\Models\CotizacionGrua;
use Illuminate\Http\Request;

class CotizacionElectricoController extends Controller
{
    
    public function index(Request $request)
    {
        $cotizaciones = CotizacionElectrico::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('DashboardCliente.electrico', [
            'cotizaciones' => $cotizaciones,
            'countElectricas' => $cotizaciones->count(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_servicio'       => ['required', 'string', 'max:150'],
            'area_m2'             => ['required', 'numeric', 'min:0'],
            'descripcion_trabajo' => ['required', 'string', 'max:10000'],
            'voltaje_requerido'   => ['nullable', 'string', 'max:100'],
            'duracion_dias'       => ['nullable', 'integer', 'min:1', 'max:3650'],
            'ubicacion_obra'      => ['required', 'string', 'max:255'],
            'fecha_inicio'        => ['required', 'date'],
            'incluir_materiales'  => ['nullable', 'boolean'],
            'observaciones'       => ['nullable', 'string', 'max:2000'],
        ]);

        $data['user_id'] = $request->user()->id;
        $data['incluir_materiales'] = (bool)($data['incluir_materiales'] ?? false);

        CotizacionElectrico::create($data);

        return back()->with('ok', 'Tu cotización eléctrica fue enviada correctamente.');
    }

    public function almacenIndex()
    {
        $cotizacionesGruas = CotizacionGrua::with('user:id,name')->latest()->get();
        $cotizacionesElectricas = CotizacionElectrico::with('user:id,name')->latest()->get();

        return view('DashboardAlmacen.cotizaciones', [
            'cotizacionesGruas'      => $cotizacionesGruas,
            'cotizacionesElectricas' => $cotizacionesElectricas,
            'countGruas'             => $cotizacionesGruas->count(),
            'countElectricas'        => $cotizacionesElectricas->count(),
        ]);
    }
}
