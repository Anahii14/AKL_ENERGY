{{-- resources/views/empresa/pedidos/historial.blade.php --}}
@extends('layouts.adminObraPlantilla')

@section('title', 'Historial de Pedidos')

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
            padding: 20px;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            background: #fff
        }

        .order+.order {
            margin-top: 16px
        }

        .order:hover {
            box-shadow: 0 6px 20px rgba(2, 6, 23, .05)
        }

        .order-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            text-transform: lowercase
        }

        .meta {
            margin-top: 6px
        }

        .meta small {
            display: block;
            color: #64748b;
            font-weight: 600
        }

        .status-pill {
            position: absolute;
            right: 16px;
            top: 16px;
            font-size: 12px;
            padding: 6px 12px;
            border-radius: 999px;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
            color: #334155
        }

        .status-entregado {
            background: #eefdf3;
            border-color: #c6f6d5;
            color: #166534
        }

        .status-en_proceso_de_compra {
            background: #f3f4f6;
            border-color: #e5e7eb;
            color: #334155
        }

        .ok-line {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            font-weight: 700
        }

        .ok-line i {
            font-size: 16px
        }

        .ok-line a {
            color: #16a34a;
            text-decoration: none;
            border-bottom: 1px dotted #16a34a
        }

        .ok-line a:hover {
            opacity: .9
        }

        @media (max-width:576px) {
            .status-pill {
                position: static;
                display: inline-block;
                margin-top: 10px
            }
        }
    </style>

    <div class="container-xxl page-wrap">
        <div class="card-np p-4 p-md-5">

            <h3 class="h-title">
                <i class="ti ti-history"></i> Historial de Pedidos
            </h3>
            <p class="h-sub">Revisa el estado de todos tus pedidos</p>

            @forelse ($pedidos as $p)
                @php
                    $statusClass = 'status-' . $p->estado; 
                    $estadoTexto = $p->estado; 
                @endphp

                <div class="order">
                    <span class="status-pill {{ $statusClass }}">{{ $estadoTexto }}</span>

                    <div class="order-name">{{ $p->obra->nombre ?? '—' }}</div>

                    <div class="meta">
                        <small>Solicitado: {{ $p->created_at->format('d/m/Y') }}</small>
                    </div>
                    <div class="meta">
                        <small><strong>Fecha solicitada:</strong>
                            {{ optional($p->fecha_requerida)->format('d/m/Y') }}</small>
                    </div>

                    @if ($p->estado === 'entregado')
                        <div class="ok-line">
                            <i class="ti ti-check" style="color:#16a34a"></i>
                            <a href="{{ route('admin.guias') }}">Guía de salida generada</a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-4">No hay pedidos en tu historial.</div>
            @endforelse

            <div class="mt-3">
                {{ $pedidos->links() }}
            </div>

        </div>
    </div>
@endsection
