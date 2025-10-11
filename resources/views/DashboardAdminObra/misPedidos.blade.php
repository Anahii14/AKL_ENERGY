@extends('layouts.adminObraPlantilla')

@section('title', 'Mis Pedidos')

@section('content')
    <style>
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto
        }

        .h-title {
            display: flex;
            gap: 10px;
            align-items: center;
            font-weight: 800;
            color: #0f172a
        }

        .h-sub {
            color: #64748b;
            font-weight: 600;
            margin: 4px 0 18px
        }

        .card-np {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06)
        }

        .order {
            position: relative;
            padding: 22px;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            background: #fff
        }

        .order+.order {
            margin-top: 18px
        }

        .order:hover {
            box-shadow: 0 6px 20px rgba(2, 6, 23, .05)
        }

        .order-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 6px
        }

        .order-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a
        }

        .order-sub {
            font-size: 13px;
            color: #64748b;
            font-weight: 600;
            margin-top: 2px
        }

        .badge {
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
            color: #334155;
            white-space: nowrap
        }

        .badge--blue {
            background: #e8f0ff;
            border-color: #c7dbff;
            color: #0b3ea2;
            font-weight: 800
        }

        .badge--orange {
            background: #fff1e8;
            border-color: #ffd8c2;
            color: #b45309;
            font-weight: 800
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
            margin-top: 10px
        }

        .meta small {
            display: block;
            color: #64748b;
            font-weight: 600
        }

        .meta strong {
            color: #0f172a
        }

        .block {
            margin-top: 14px
        }

        .block-label {
            font-size: 13px;
            color: #0f172a;
            font-weight: 700;
            margin-bottom: 6px
        }

        .input-like {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #f7fbff;
            border: 1px solid #e8eef6;
            border-radius: 10px;
            padding: 10px 12px;
            color: #0f172a;
            font-weight: 600
        }

        .input-like+.input-like {
            margin-top: 8px
        }

        .input-like span.qty {
            color: #64748b;
            font-weight: 700
        }

        .btn-soft {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #0f172a;
            border-radius: 10px;
            padding: 8px 12px;
            font-weight: 700
        }

        .btn-soft:hover {
            background: #f8fafc
        }

        @media (max-width:576px) {
            .order-head {
                flex-direction: column;
                align-items: flex-start
            }

            .badge {
                margin-top: 6px
            }
        }
    </style>

    <div class="container-xxl page-wrap">
        <div class="card-np p-4 p-md-5">
            <h3 class="h-title">
                <i class="ti ti-shopping-cart"></i> Mis Pedidos
            </h3>
            <p class="h-sub">Revisa el estado y detalle de tus pedidos</p>

            @forelse ($pedidos as $p)
                @php
                    // Clase visual de la "pill" según estado
                    $badgeMap = [
                        'en_proceso_de_compra' => 'badge--blue',
                        'solicitado' => 'badge--blue',
                        'aprobado' => 'badge--blue',
                        'atendido' => 'badge--blue',
                        'entregado' => 'badge--orange',
                        'rechazado' => 'badge--orange',
                        'cancelado' => 'badge--orange',
                        'borrador' => '',
                    ];
                    $badgeClass = $badgeMap[$p->estado] ?? '';
                    $estadoTexto = strtoupper($p->estado); // EN_PROCESO_DE_COMPRA, ENTREGADO, etc.
                @endphp

                <div class="order">
                    <div class="order-head">
                        <div>
                            <div class="order-title">Pedido #{{ $p->codigo }}</div>
                            <div class="order-sub">
                                {{ $p->obra_id }} - {{ $p->obra->nombre ?? '—' }}
                            </div>
                        </div>
                        <span class="badge {{ $badgeClass }}">{{ $estadoTexto }}</span>
                    </div>

                    <div class="meta">
                        <div><small><strong>Fecha solicitada:</strong>
                                {{ optional($p->fecha_requerida)->format('d/m/Y') }}</small></div>
                        <div><small><strong>Fecha creación:</strong> {{ $p->created_at->format('d/m/Y') }}</small></div>
                    </div>

                    <div class="block">
                        <div class="block-label">Materiales:</div>

                        @forelse ($p->detalles as $d)
                            <div class="input-like">
                                <span>
                                    {{-- "3 - cable" --}}
                                    {{ rtrim(rtrim(number_format($d->cantidad, 2, '.', ''), '0'), '.') }}
                                    - {{ $d->material->nombre ?? 'Material' }}
                                </span>
                                <span class="qty">
                                    {{-- "0/15 m" (a falta de entregas, ponemos 0/total unidad) --}}
                                    0/{{ rtrim(rtrim(number_format($d->cantidad, 2, '.', ''), '0'), '.') }}
                                    {{ $d->material->unidad ?? '' }}
                                </span>
                            </div>
                        @empty
                            <div class="input-like">
                                <span>Sin materiales</span>
                                <span class="qty">—</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            @empty
                <div class="text-center py-4">
                    No tienes pedidos aún.
                </div>
            @endforelse

            {{-- Paginación --}}
            <div class="mt-3">
                {{ $pedidos->links() }}
            </div>

        </div>
    </div>
@endsection
