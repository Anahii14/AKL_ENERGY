{{-- resources/views/almacen/pedidos.blade.php --}}
@extends('layouts.almaceneroPlantilla')

@section('title', 'Pedidos - Almacén')

@section('content')
    <style>
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto
        }

        .tabs-wrap {
            background: #eef5fb;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 6px;
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap
        }

        .tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            font-weight: 800;
            color: #0f172a;
            background: transparent;
            border: 1px solid transparent;
            border-radius: 10px;
            text-decoration: none
        }

        .tab small {
            opacity: .7;
            font-weight: 800
        }

        .tab .ico {
            font-size: 16px;
            display: inline-flex
        }

        .tab.is-active {
            background: #fff;
            border-color: #e5e7eb;
            box-shadow: 0 1px 0 rgba(10, 10, 10, .02)
        }

        .card-np {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06);
            padding: 1rem
        }

        .empty-box {
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-weight: 700
        }

        .pedido-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(2, 6, 23, .06);
            padding: 18px 20px;
            margin-bottom: 14px
        }

        .pedido-head {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .pedido-icon {
            font-size: 20px
        }

        .pedido-title {
            font-weight: 900;
            color: #0f172a
        }

        .pedido-subhead {
            color: #64748b;
            font-weight: 800;
            font-size: 13px;
            margin-top: 2px
        }

        .badge-state {
            font-weight: 900;
            font-size: 12px;
            padding: 6px 10px;
            border-radius: 999px;
            border: 1px solid #cbd5e1
        }

        .badge--pendiente {
            background: #e7f1ff;
            color: #1d4ed8;
            border-color: #bfdbfe
        }

        .badge--en_proceso {
            background: #fff7ed;
            color: #b45309;
            border-color: #fed7aa
        }

        .badge--entregado {
            background: #ecfdf5;
            color: #065f46;
            border-color: #bbf7d0
        }

        .badge--cancelado {
            background: #fef2f2;
            color: #991b1b;
            border-color: #fecaca
        }

        .pedido-sub {
            color: #64748b;
            font-weight: 700;
            font-size: 13px;
            margin-top: 12px
        }

        .material-row {
            background: #f1fdf5;
            border: 1px solid #dcfce7;
            border-radius: 10px;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 8px
        }

        .material-name,
        .material-qty {
            font-weight: 800;
            color: #0f172a
        }

        .material-stock {
            font-weight: 900;
            font-size: 12px;
            color: #16a34a
        }

        .pedido-actions {
            display: flex;
            gap: 10px;
            margin-top: 14px;
            flex-wrap: wrap
        }

        .btn-akl-orange {
            background: linear-gradient(135deg, #ff8a3d, #ff6a3d);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 900;
            padding: 9px 14px
        }

        .btn-akl-red {
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 900;
            padding: 9px 14px
        }

        .btn-akl-soft {
            background: #eef2ff;
            color: #3730a3;
            border: 1px solid #c7d2fe;
            border-radius: 10px;
            font-weight: 900;
            padding: 9px 14px
        }

        /* Selector píldora de estado */
        .estado-form {
            position: relative
        }

        .state-select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #b45309;
            padding: 6px 34px 6px 12px;
            border-radius: 999px;
            font-weight: 900;
            font-size: 12px;
            cursor: pointer;
        }

        .estado-form::after {
            content: "▾";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 12px;
            color: #b45309;
            pointer-events: none;
        }

        .state-select:disabled {
            opacity: .6;
            cursor: not-allowed
        }

        @media (max-width:576px) {
            .tabs-wrap {
                gap: 6px
            }

            .tab {
                padding: 7px 10px
            }
        }
    </style>

    <div class="container-xxl page-wrap">
        {{-- Tabs --}}
        <div class="tabs-wrap mb-3">
            <a href="{{ route('almacen.pedidos', ['tab' => 'pendientes']) }}"
                class="tab {{ ($tab ?? 'pendientes') === 'pendientes' ? 'is-active' : '' }}">
                <span class="ico">🆕</span> Pendientes <small>({{ $counts['pendientes'] ?? 0 }})</small>
            </a>

            <a href="{{ route('almacen.pedidos', ['tab' => 'proceso']) }}"
                class="tab {{ ($tab ?? '') === 'proceso' ? 'is-active' : '' }}">
                <span class="ico ti ti-settings"></span> En Proceso <small>({{ $counts['proceso'] ?? 0 }})</small>
            </a>

            <a href="{{ route('almacen.pedidos', ['tab' => 'terminados']) }}"
                class="tab {{ ($tab ?? '') === 'terminados' ? 'is-active' : '' }}">
                <span class="ico ti ti-badge-check"></span> Terminados <small>({{ $counts['terminados'] ?? 0 }})</small>
            </a>

            <a href="{{ route('almacen.pedidos', ['tab' => 'todos']) }}"
                class="tab {{ ($tab ?? '') === 'todos' ? 'is-active' : '' }}" style="margin-left:auto">
                Todos <small>({{ $counts['todos'] ?? 0 }})</small>
            </a>
        </div>

        {{-- Listado --}}
        <div class="card-np p-3">
            @if (($coleccion ?? collect())->isEmpty())
                <div class="empty-box">No hay pedidos en esta categoría</div>
            @else
                @foreach ($coleccion as $pedido)
                    @php
                        $estado = strtolower($pedido->estado ?? 'solicitado');

                        $badgeClass = match ($estado) {
                            'solicitado' => 'badge-state badge--pendiente',
                            'aprobado', 'en_proceso_de_compra' => 'badge-state badge--en_proceso',
                            'entregado', 'atendido' => 'badge-state badge--entregado',
                            'rechazado', 'cancelado' => 'badge-state badge--cancelado',
                            default => 'badge-state',
                        };

                        $badgeLabel = match ($estado) {
                            'solicitado' => 'PENDIENTE',
                            'aprobado', 'en_proceso_de_compra' => 'EN PROCESO',
                            'entregado' => 'ENTREGADO',
                            'atendido' => 'ATENDIDO',
                            'rechazado' => 'RECHAZADO',
                            'cancelado' => 'CANCELADO',
                            default => strtoupper($pedido->estado ?? ''),
                        };

                        $estadosEnProceso = ['aprobado', 'en_proceso_de_compra'];

                        $obraId = $pedido->obra->id ?? null;
                        $obraNombre = $pedido->obra->nombre ?? '-';
                        $codigoVis = $pedido->codigo ?? 'PED-' . str_pad($pedido->id, 6, '0', STR_PAD_LEFT);
                    @endphp

                    <div class="pedido-card">
                        <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
                            <div class="pedido-head">
                                <span class="pedido-icon">📦</span>
                                <div>
                                    <div class="pedido-title">Pedido #{{ $codigoVis }}</div>
                                    <div class="pedido-subhead">
                                        {{ $obraId ?? '—' }} - {{ strtolower($obraNombre) }}
                                    </div>
                                </div>
                            </div>

                            {{-- Badge o selector de estado --}}
                            @if (in_array($estado, $estadosEnProceso))
                                <form class="estado-form" method="POST"
                                    action="{{ route('pedidos.update_estado', $pedido) }}">
                                    @csrf @method('PATCH')
                                    <select name="estado" class="state-select" onchange="this.form.submit()">
                                        {{-- Permitir volver a Pendiente (solicitado) --}}
                                        <option value="solicitado" {{ $estado === 'solicitado' ? 'selected' : '' }}>
                                            Pendiente</option>
                                        <option value="aprobado" {{ $estado === 'aprobado' ? 'selected' : '' }}>Aprobado
                                        </option>
                                        <option value="en_proceso_de_compra"
                                            {{ $estado === 'en_proceso_de_compra' ? 'selected' : '' }}>En Proceso de Compra
                                        </option>
                                        <option value="atendido" {{ $estado === 'atendido' ? 'selected' : '' }}>Atendido
                                        </option>
                                        <option value="entregado" {{ $estado === 'entregado' ? 'selected' : '' }}>Entregado
                                        </option>
                                    </select>
                                </form>
                            @else
                                <span class="{{ $badgeClass }}">{{ $badgeLabel }}</span>
                            @endif
                        </div>

                        <div class="pedido-sub">
                            Fecha requerida:
                            <strong>{{ \Carbon\Carbon::parse($pedido->fecha_requerida)->format('d/m/Y') }}</strong>
                        </div>

                        <div style="margin-top:10px;font-weight:900;color:#0f172a;">Materiales solicitados:</div>
                        @foreach ($pedido->detalles as $d)
                            @php
                                $material = $d->material ?? null;
                                $nombreMat = $material->nombre ?? 'ID ' . $d->material_id;
                                $unidad = $material->unidad ?? 'und';
                                $stockDisp = $material->stock_actual ?? null;
                            @endphp
                            <div class="material-row">
                                <div class="material-name">{{ $d->material_id }} - {{ strtolower($nombreMat) }}</div>
                                <div style="display:flex;align-items:center;gap:14px;">
                                    <div class="material-qty">
                                        {{ rtrim(rtrim(number_format($d->cantidad, 2, '.', ''), '0'), '.') }}
                                        {{ $unidad }}
                                    </div>
                                    @if (!is_null($stockDisp))
                                        <div class="material-stock">(Stock:
                                            {{ rtrim(rtrim(number_format($stockDisp, 2, '.', ''), '0'), '.') }})</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="pedido-actions">
                            {{-- PENDIENTE → Marcar como leído --}}
                            @if ($estado === 'solicitado')
                                <form method="POST" action="{{ route('pedidos.visto', $pedido) }}">
                                    @csrf
                                    <button type="submit" class="btn-akl-orange">
                                        <i class="ti ti-clipboard-check me-1"></i> Marcar como Leído
                                    </button>
                                </form>
                            @endif

                            {{-- EN PROCESO → Procesar Pedido (genera guía) --}}
                            @if (in_array($estado, $estadosEnProceso))
                                <form method="POST" action="{{ route('pedidos.procesar', $pedido) }}">
                                    @csrf
                                    <button type="submit" class="btn-akl-orange">
                                        <i class="ti ti-clipboard-text me-1"></i> Procesar Pedido
                                    </button>
                                </form>
                            @endif

                            {{-- ORDEN DE COMPRA --}}
                            @php $tieneOC = method_exists($pedido, 'ordenCompra') && $pedido->ordenCompra; @endphp
                            @if ($estado === 'en_proceso_de_compra' && !$tieneOC)
                                <form method="POST" action="{{ route('pedidos.generar_oc', $pedido) }}">
                                    @csrf
                                    <button type="submit" class="btn-akl-soft">
                                        <i class="ti ti-shopping-cart me-1"></i> Generar Orden de Compra
                                    </button>
                                </form>
                            @elseif ($tieneOC)
                                <span class="badge-state badge--entregado"
                                    style="display:inline-flex;align-items:center;gap:6px">
                                    <i class="ti ti-badge-check"></i> OC: {{ $pedido->ordenCompra->codigo }}
                                </span>
                                <a href="{{ route('oc.pdf', $pedido->ordenCompra) }}" class="btn-akl-soft">
                                    <i class="ti ti-printer me-1"></i> Imprimir OC
                                </a>
                            @endif

                            {{-- Cancelar --}}
                            @if (Route::has('pedidos.cancel') &&
                                    !in_array($estado, ['cancelado', 'rechazado', 'entregado', 'atendido']) &&
                                    !in_array($estado, $estadosEnProceso) &&
                                    $estado !== 'solicitado')
                                <form method="POST" action="{{ route('pedidos.cancel', $pedido) }}"
                                    onsubmit="return confirm('¿Cancelar este pedido?');">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-akl-red">Cancelar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const faltantes = @json(session('faltantes'));
            const pedidoId = @json(session('pedido_id'));

            if (faltantes && pedidoId) {
                let lista = '<ul style="text-align:left;margin-top:10px">';
                faltantes.forEach(f => {
                    lista +=
                        `<li><strong>${f.material}</strong>: requerido ${f.req}, disponible ${f.disp}</li>`;
                });
                lista += '</ul>';

                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    html: `
                        No hay materiales suficientes para atender el pedido.<br>
                        ${lista}
                        <div style="margin-top:8px">¿Deseas generar una <strong>Orden de Compra</strong> ahora?</div>
                    `,
                    showCancelButton: true,
                    focusConfirm: false,
                    confirmButtonText: 'Generar Orden de Compra',
                    cancelButtonText: 'Cancelar'
                }).then((res) => {
                    if (res.isConfirmed) {
                        document.getElementById('formGenerarOC')?.submit();
                    }
                });
            }
        });
    </script>

    {{-- Formulario oculto para disparar la generación de OC desde el SweetAlert --}}
    @if (session('pedido_id'))
        <form id="formGenerarOC" method="POST"
            action="{{ route('pedidos.generar_oc', ['pedido' => session('pedido_id')]) }}" style="display:none">
            @csrf
        </form>
    @endif
@endpush
