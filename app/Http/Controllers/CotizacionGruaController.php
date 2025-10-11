<?php

namespace App\Http\Controllers;

use App\Models\CotizacionGrua;
use App\Models\CotizacionElectrico;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Notifications\CotizacionEstadoActualizado;

class CotizacionGruaController extends Controller
{
    public function index(Request $request)
    {
        $cotizaciones = CotizacionGrua::where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return view('DashboardCliente.gruas', compact('cotizaciones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tipo_grua'      => ['required', 'string', 'max:150'],
            'capacidad'      => ['required', 'numeric', 'min:1'],
            'dias_alquiler'  => ['required', 'integer', 'min:1'],
            'fecha_inicio'   => ['required', 'date', 'after_or_equal:today'],
            'ubicacion_obra' => ['required', 'string', 'max:255'],
            'observaciones'  => ['nullable', 'string', 'max:2000'],
            'incluir_operador'    => ['nullable', 'boolean'],
            'incluir_rigger'      => ['nullable', 'boolean'],
            'incluir_combustible' => ['nullable', 'boolean'],
        ]);

        $incluyeOperador    = $request->boolean('incluir_operador');
        $incluyeRigger      = $request->boolean('incluir_rigger');
        $incluyeCombustible = $request->boolean('incluir_combustible');

        // Estimación simple (ajusta a tu negocio)
        $costoBaseDia     = 1500;
        $extraOperador    = 150;
        $extraRigger      = 120;
        $extraCombustible = 200;

        $extras = ($incluyeOperador ? $extraOperador : 0)
                + ($incluyeRigger ? $extraRigger : 0)
                + ($incluyeCombustible ? $extraCombustible : 0);

        $precioTotal = ($costoBaseDia + $extras) * (int) $data['dias_alquiler'];

        CotizacionGrua::create([
            'user_id'             => $request->user()->id,
            'tipo_grua'           => $data['tipo_grua'],
            'capacidad'           => $data['capacidad'],
            'dias_alquiler'       => $data['dias_alquiler'],
            'fecha_inicio'        => $data['fecha_inicio'],
            'ubicacion_obra'      => $data['ubicacion_obra'],
            'incluye_operador'    => $incluyeOperador,
            'incluye_rigger'      => $incluyeRigger,
            'incluye_combustible' => $incluyeCombustible,
            'observaciones'       => $data['observaciones'] ?? null,
            'precio_total'        => $precioTotal,
            'estado'              => 'pendiente',
        ]);

        return redirect()->route('cliente.gruas')->with('ok', 'Solicitud de cotización registrada.');
    }

    public function estado(Request $request)
    {
        $userId = $request->user()->id;

        $cotizacionesGruas = CotizacionGrua::where('user_id', $userId)->latest()->get();
        $cotizacionesElectricas = CotizacionElectrico::where('user_id', $userId)->latest()->get();

        return view('DashboardCliente.estado', compact('cotizacionesGruas', 'cotizacionesElectricas'));
    }

    /**
     * Decide (aprobar/rechazar) una cotización SOLO si está "pendiente".
     * Sin FormRequest: validación inline + transacción + lockForUpdate + control de concurrencia.
     */
    public function decidir(Request $request)
    {
        // 1) Validación inline
        $data = $request->validate([
            'tipo'       => ['required','in:grua,electrico'],
            'id'         => ['required','integer'],
            'decision'   => ['required','in:aceptada,rechazada'],
            'motivo'     => ['required','string','min:5','max:2000'],
            'updated_at' => ['nullable','date'], // para concurrencia optimista
        ], [
            'tipo.in'      => 'Tipo inválido.',
            'decision.in'  => 'La decisión debe ser aceptada o rechazada.',
            'motivo.min'   => 'El motivo debe tener al menos 5 caracteres.',
        ]);

        $modelo = $data['tipo'] === 'grua'
            ? CotizacionGrua::query()
            : CotizacionElectrico::query();

        // 2) Transacción con lock pesimista para evitar condiciones de carrera
        return DB::transaction(function () use ($modelo, $data) {
            $cot = $modelo->lockForUpdate()->findOrFail((int) $data['id']);

            // 3) Concurrencia optimista: si updated_at cambió, avisar
            if (!empty($data['updated_at']) && $cot->updated_at) {
                if ($cot->updated_at->ne(Carbon::parse($data['updated_at']))) {
                    return back()->with('error', 'La cotización cambió mientras decidías. Recarga la página e inténtalo de nuevo.');
                }
            }

            // 4) Blindaje: solo decidir si está pendiente
            if ($cot->estado !== 'pendiente') {
                return back()->with('error', 'Esta cotización ya fue decidida (estado: ' . $cot->estado . ').');
            }

            // 5) Aplicar decisión
            $cot->estado          = $data['decision'];
            $cot->decision_motivo = $data['motivo'];
            $cot->decision_por    = Auth::id();
            $cot->decision_fecha  = now();
            $cot->save();

            // 6) Notificar al cliente (si hay relación)
            if (method_exists($cot, 'user') && $cot->user) {
                $cot->user->notify(new CotizacionEstadoActualizado(
                    tipo: ($cot instanceof CotizacionGrua) ? 'grua' : 'electrico',
                    cotizacionId: $cot->id,
                    estado: $data['decision'],
                    motivo: $data['motivo']
                ));
            }

            return back()->with('ok', 'Decisión registrada y cliente notificado.');
        });
    }
}
