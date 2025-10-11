<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden de Compra {{ $orden->codigo }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .head { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px; }
        .box { border: 1px solid #888; padding: 8px; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #888; padding: 6px; }
        th { background: #efefef; }
        .right { text-align: right; }
        .small { font-size: 11px; color: #555; }
    </style>
</head>
<body>
    <div class="head">
        <div>
            <h2 style="margin:0;">AKLEnergy</h2>
            <div class="small">Orden de Compra</div>
        </div>
        <div class="box">
            <div><strong>Código:</strong> {{ $orden->codigo }}</div>
            <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($orden->fecha_emision)->format('d/m/Y') }}</div>
            <div><strong>Estado:</strong> {{ strtoupper($orden->estado) }}</div>
        </div>
    </div>

    <div class="box">
        <div><strong>Pedido:</strong> {{ $orden->pedido->codigo ?? $orden->pedido_id }}</div>
        <div><strong>Obra:</strong> {{ $orden->pedido->obra->nombre ?? '-' }}</div>
        <div><strong>Proveedor:</strong> {{ $orden->proveedor->nombre ?? '—' }}</div>
        <div><strong>Emitido por:</strong> {{ $orden->user->name ?? '-' }}</div>
        <div><strong>Entrega Estimada:</strong> {{ optional($orden->fecha_entrega_estimada)->format('d/m/Y') ?? '—' }}</div>
        <div><strong>Moneda:</strong> {{ $orden->moneda }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:14%">ID Mat.</th>
                <th>Descripción</th>
                <th style="width:12%">Unidad</th>
                <th class="right" style="width:14%">Cantidad</th>
                <th class="right" style="width:16%">P. Unit.</th>
                <th class="right" style="width:16%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orden->detalles as $it)
                <tr>
                    <td>{{ $it->material_id }}</td>
                    <td>{{ $it->descripcion ?? ($it->material->nombre ?? ('ID '.$it->material_id)) }}</td>
                    <td>{{ $it->unidad ?? ($it->material->unidad ?? '—') }}</td>
                    <td class="right">{{ rtrim(rtrim(number_format($it->cantidad, 2, '.', ''), '0'), '.') }}</td>
                    <td class="right">{{ number_format($it->precio_unitario, 2) }}</td>
                    <td class="right">{{ number_format($it->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="5" class="right">Total</th>
                <th class="right">{{ number_format($orden->monto_total, 2) }} {{ $orden->moneda }}</th>
            </tr>
        </tfoot>
    </table>

    @if ($orden->observaciones)
        <div class="box" style="margin-top:10px;">
            <strong>Observaciones:</strong><br>
            {!! nl2br(e($orden->observaciones)) !!}
        </div>
    @endif

    <div style="margin-top:40px; display:flex; gap:40px;">
        <div style="flex:1; text-align:center;">
            ___________________________<br>
            <span class="small">Aprobado por Compras</span>
        </div>
        <div style="flex:1; text-align:center;">
            ___________________________<br>
            <span class="small">Conformidad de Obra</span>
        </div>
    </div>
</body>
</html>
