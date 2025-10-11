<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Guía de Salida {{ $guia->codigo }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .head {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .box {
            border: 1px solid #888;
            padding: 8px;
            border-radius: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #888;
            padding: 6px;
        }

        th {
            background: #efefef;
        }

        .right {
            text-align: right;
        }

        .small {
            font-size: 11px;
            color: #555;
        }
    </style>
</head>

<body>
    <div class="head">
        <div>
            <h2 style="margin:0;">AKLEnergy</h2>
            <div class="small">Guía de Salida</div>
        </div>
        <div class="box">
            <div><strong>Código:</strong> {{ $guia->codigo }}</div>
            <div><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($guia->fecha_emision)->format('d/m/Y') }}</div>
            <div><strong>Estado:</strong> {{ strtoupper($guia->estado) }}</div>
        </div>
    </div>

    <div class="box">
        <div><strong>Obra:</strong> {{ $guia->obra->nombre ?? '-' }}</div>
        <div><strong>Pedido:</strong> {{ $guia->pedido->codigo ?? $guia->pedido_id }}</div>
        <div><strong>Emitido por:</strong> {{ $guia->user->name ?? '-' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width:14%">ID Mat.</th>
                <th>Descripción</th>
                <th style="width:12%">Unidad</th>
                <th class="right" style="width:14%">Cantidad</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($guia->detalles as $it)
                <tr>
                    <td>{{ $it->material_id }}</td>
                    <td>{{ $it->descripcion }}</td>
                    <td>{{ $it->unidad }}</td>
                    <td class="right">{{ rtrim(rtrim(number_format($it->cantidad, 2, '.', ''), '0'), '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($guia->observaciones)
        <div class="box" style="margin-top:10px;">
            <strong>Observaciones:</strong><br>
            {!! nl2br(e($guia->observaciones)) !!}
        </div>
    @endif

    <div style="margin-top:40px; display:flex; gap:40px;">
        <div style="flex:1; text-align:center;">
            ___________________________<br>
            <span class="small">Entregado por Almacén</span>
        </div>
        <div style="flex:1; text-align:center;">
            ___________________________<br>
            <span class="small">Recibido por Obra</span>
        </div>
    </div>
</body>

</html>
