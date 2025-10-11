<?php

namespace App\Http\Controllers;

use App\Models\OrdenCompra;
use App\Models\DetalleOrdenCompra;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class OrdenCompraController extends Controller
{
    public function generarDesdePedido(Pedido $pedido, Request $request)
    {
        $pedido->load(['detalles.material','obra']);

        // Calcular faltantes
        $faltantes = [];
        foreach ($pedido->detalles as $d) {
            $mat  = $d->material;
            $req  = (float)$d->cantidad;
            $disp = (float)($mat->stock_actual ?? 0);
            $falt = max($req - $disp, 0);

            if ($falt > 0) {
                $faltantes[] = [
                    'material_id'     => $d->material_id,
                    'descripcion'     => $mat?->nombre ?? ("ID {$d->material_id}"),
                    'unidad'          => $mat?->unidad,
                    'cantidad'        => $falt,
                    'precio_unitario' => (float)($mat->precio_unitario ?? 0),
                ];
            }
        }

        if (empty($faltantes)) {
            return back()->with('info', 'No hay faltantes. No se generó Orden de Compra.');
        }

        $proveedorId = $request->integer('proveedor_id') ?: null;
        $fechaEst    = $request->date('fecha_entrega_estimada') ?: Carbon::now()->addDays(5);

        $oc = DB::transaction(function () use ($pedido, $faltantes, $proveedorId, $fechaEst) {
            $codigo = 'OC-' . random_int(10000000, 99999999);

            $oc = OrdenCompra::create([
                'codigo'                 => $codigo,
                'proveedor_id'           => $proveedorId,
                'pedido_id'              => $pedido->id,
                'user_id'                => auth()->id(),
                'fecha_emision'          => Carbon::now()->toDateString(),
                'fecha_entrega_estimada' => $fechaEst->toDateString(),
                'monto_total'            => 0,
                'estado'                 => 'pendiente',
                'moneda'                 => 'PEN',
                'observaciones'          => null,
            ]);

            foreach ($faltantes as $f) {
                DetalleOrdenCompra::create([
                    'orden_compra_id' => $oc->id,
                    'material_id'     => $f['material_id'],
                    'descripcion'     => $f['descripcion'],
                    'unidad'          => $f['unidad'],
                    'cantidad'        => $f['cantidad'],
                    'precio_unitario' => $f['precio_unitario'],
                    'subtotal'        => $f['cantidad'] * $f['precio_unitario'],
                ]);
            }

            // Estado y traza en el pedido
            $pedido->update([
                'estado'        => 'en_proceso_de_compra',
                'observaciones' => trim(($pedido->observaciones ?? '') . "\n[ALM] OC generada: {$oc->codigo}"),
            ]);

            $oc->refresh();
            $oc->recalcularTotal();

            return $oc;
        });

        // === Generar y descargar PDF inmediatamente ===
        $oc->load(['proveedor','pedido.obra','user','detalles.material']);
        $pdf = Pdf::loadView('pdf.orden_compra', ['orden' => $oc]);

        return $pdf->download('OC-' . $oc->codigo . '.pdf');
    }

    /** PDF individual desde link (para el botón Imprimir en el listado) */
    public function pdf(OrdenCompra $orden)
    {
        $orden->load(['proveedor','pedido.obra','user','detalles.material']);
        $pdf = Pdf::loadView('pdf.orden_compra', compact('orden'));
        return $pdf->download('OC-' . $orden->codigo . '.pdf');
    }
}
