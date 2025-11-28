@extends('layouts.almaceneroPlantilla')

@section('title', 'Guías / Órdenes - Almacén')

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

        .spacer {
            margin-left: auto
        }

        .card-np {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06)
        }

        .order-item {
            position: relative;
            padding: 18px 20px;
            border-radius: 12px
        }

        .order-item+.order-item {
            border-top: 1px solid #eef2f7
        }

        .order-head {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            justify-content: space-between
        }

        .order-title {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0
        }

        .order-sub {
            color: #64748b;
            font-weight: 700;
            margin: 4px 0 0
        }

        .order-meta {
            margin-top: 10px;
            color: #0f172a
        }

        .order-meta b {
            font-weight: 800
        }

        .btn-print {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #0f172a;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 800;
            text-decoration: none
        }

        .btn-print:hover {
            background: #f8fafc
        }

        .btn-print .ti {
            font-size: 18px
        }

        .tab-pane {
            display: none
        }

        .tab-pane.active {
            display: block
        }

        .empty-box {
            padding: 30px;
            text-align: center;
            color: #64748b;
            font-weight: 700
        }

        @media (max-width:576px) {
            .tab {
                padding: 7px 10px
            }

            .order-head {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px
            }
        }
    </style>

    <div class="container-xxl page-wrap">
        {{-- Tabs --}}
        <div class="tabs-wrap mb-3">
            <a href="#guias" class="tab is-active" data-tab="guias">
                <span class="ico ti ti-file-export"></span>
                Guías de Salida
                <small>({{ $guiasCount ?? ($guias->total() ?? $guias->count()) }})</small>
            </a>
            <a href="#ordenes" class="tab" data-tab="ordenes">
                <span class="ico ti ti-shopping-cart"></span>
                Órdenes de Compra
                <small>({{ $ordenesCount ?? (isset($ordenes) ? (method_exists($ordenes, 'total') ? $ordenes->total() : $ordenes->count()) : 0) }})</small>
            </a>
            <span class="spacer"></span>
        </div>

        {{-- Contenido: Guías --}}
        <div class="card-np tab-pane active" id="guias">
            <div class="order-item"
                style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eef2f7;">
                <div>
                    <h3 class="order-title" style="margin:0;">Guías de Salida</h3>
                    <p class="order-sub" style="margin:4px 0 0;">Genera una nueva guía desde un pedido elegible</p>
                </div>
                <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#modalNuevaGuia"
                    style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;font-weight:800;border:none;border-radius:10px;background:#ff6a3d;color:#fff;">
                    <i class="ti ti-plus" style="font-size:18px;"></i> Nueva Guía de Salida
                </button>
            </div>

            @forelse($guias as $g)
                <div class="order-item">
                    <div class="order-head">
                        <div>
                            <h3 class="order-title">Guía {{ $g->codigo }}</h3>
                            <p class="order-sub">Obra: {{ strtolower($g->obra->nombre ?? '-') }}</p>
                        </div>
                        <a href="{{ route('guia.pdf', $g) }}" class="btn-print">
                            <i class="ti ti-printer"></i> Imprimir
                        </a>
                    </div>
                    <div class="order-meta">
                        <span><b>Fecha:</b> {{ \Carbon\Carbon::parse($g->fecha_emision)->format('d/m/Y') }}</span>
                    </div>
                </div>
            @empty
                <div class="empty-box">No hay guías de salida registradas</div>
            @endforelse

            @if (isset($guias) && method_exists($guias, 'links'))
                <div class="p-3">{{ $guias->links() }}</div>
            @endif
        </div>

        {{-- Contenido: Órdenes de Compra --}}
        <div class="card-np tab-pane" id="ordenes">
            <div class="order-item"
                style="display:flex;justify-content:space-between;align-items:center;border-bottom:1px solid #eef2f7;">
                <div>
                    <h3 class="order-title" style="margin:0;">Órdenes de Compra</h3>
                    <p class="order-sub" style="margin:4px 0 0;">Genera una nueva orden desde un pedido aprobado o con
                        faltantes</p>
                </div>
                <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#modalNuevaOC"
                    style="display:inline-flex;align-items:center;gap:8px;padding:8px 12px;font-weight:800;border:none;border-radius:10px;background:#0d3a8a;color:#fff;">
                    <i class="ti ti-plus" style="font-size:18px;"></i> Nueva Orden de Compra
                </button>
            </div>

            @forelse($ordenes as $oc)
                <div class="order-item">
                    <div class="order-head">
                        <div>
                            <h3 class="order-title">Orden {{ $oc->codigo }}</h3>
                            <p class="order-sub">
                                Obra: {{ strtolower($oc->pedido->obra->nombre ?? '-') }}
                                @if ($oc->proveedor)
                                    · Proveedor: {{ $oc->proveedor->nombre }}
                                @endif
                            </p>
                        </div>
                        <a href="{{ route('oc.pdf', $oc) }}" class="btn-print">
                            <i class="ti ti-printer"></i> Imprimir
                        </a>
                    </div>

                    <div class="order-meta">
                        <span><b>Fecha:</b> {{ \Carbon\Carbon::parse($oc->fecha_emision)->format('d/m/Y') }}</span>

                        @php
                            // 1) total persistido (si viene en la consulta)
                            $mt = is_numeric($oc->monto_total ?? null) ? (float) $oc->monto_total : 0.0;

                            // 2) si en el query usaste ->withSum('detalles','subtotal')
                            $sumProp = isset($oc->detalles_sum_subtotal) ? (float) $oc->detalles_sum_subtotal : null;

                            // 3) si la relación ya está cargada
                            $sumRel = $oc->relationLoaded('detalles') ? (float) $oc->detalles->sum('subtotal') : null;

                            // 4) último recurso: sumar directo en BD
                            $sumDb = (float) $oc->detalles()->sum('subtotal');

                            // primer valor > 0 disponible
                            $totalOc = 0.0;
                            foreach ([$mt, $sumProp, $sumRel, $sumDb] as $cand) {
                                if (is_numeric($cand) && (float) $cand > 0) {
                                    $totalOc = (float) $cand;
                                    break;
                                }
                            }
                        @endphp

                        @if ($totalOc > 0)
                            <span style="margin-left:12px">
                                <b>Total:</b> {{ number_format($totalOc, 2) }} {{ $oc->moneda ?? 'PEN' }}
                            </span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="empty-box">No hay órdenes de compra registradas</div>
            @endforelse

            @if (isset($ordenes) && method_exists($ordenes, 'links'))
                <div class="p-3">{{ $ordenes->links() }}</div>
            @endif
        </div>
    </div>

    <!-- Modal: Nueva Guía -->
    <div class="modal fade" id="modalNuevaGuia" tabindex="-1" aria-labelledby="modalNuevaGuiaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:14px;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title" id="modalNuevaGuiaLabel" style="font-weight:800;color:#0f172a;">
                        <i class="ti ti-file-export me-2"></i> Registrar Guía de Salida
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form action="{{ route('guias.store') }}" method="POST" id="formNuevaGuia">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="pedido_id" class="form-label" style="font-weight:700;color:#0f172a;">Selecciona el
                                Pedido</label>
                            <select name="pedido_id" id="pedido_id" class="form-select" required>
                                <option value="">— Elegir pedido —</option>
                                @forelse(($pedidosElegibles ?? $pedidosParaOC ?? []) as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->codigo ?? 'PED-' . $p->id }}
                                        — Obra: {{ $p->obra->nombre ?? '-' }}
                                        — Estado: {{ $p->estado }}
                                        — {{ optional($p->created_at)->format('d/m/Y') }}
                                    </option>
                                @empty
                                    <option value="" disabled>No hay pedidos elegibles</option>
                                @endforelse
                            </select>
                            <small class="text-muted">Solo aparecen pedidos en estado <b>aprobado</b> o
                                <b>en_proceso_de_compra</b>.</small>
                        </div>

                        <div class="alert alert-info" role="alert" style="margin-top:10px;">
                            Al confirmar, se validará el stock. Si falta, se sugerirá generar una OC; si todo está OK, se
                            creará la Guía (estado <b>borrador</b>) y se descargará el PDF.
                        </div>
                    </div>

                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background:#0d3a8a;border-color:#0d3a8a;">
                            <i class="ti ti-check me-1"></i> Generar Guía
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Nueva Orden de Compra -->
    <div class="modal fade" id="modalNuevaOC" tabindex="-1" aria-labelledby="modalNuevaOCLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border-radius:14px;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title" id="modalNuevaOCLabel" style="font-weight:800;color:#0f172a;">
                        <i class="ti ti-shopping-cart me-2"></i> Registrar Orden de Compra
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                {{-- El action se setea dinámicamente con el pedido elegido --}}
                <form method="POST" id="formNuevaOC">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="oc_pedido_id" class="form-label"
                                style="font-weight:700;color:#0f172a;">Selecciona el Pedido</label>
                            <select id="oc_pedido_id" class="form-select" required>
                                <option value="">— Elegir pedido —</option>
                                @forelse($pedidosParaOC ?? [] as $p)
                                    <option value="{{ $p->id }}">
                                        {{ $p->codigo ?? 'PED-' . $p->id }}
                                        — Obra: {{ $p->obra->nombre ?? '-' }}
                                        — Estado: {{ $p->estado }}
                                        — {{ optional($p->created_at)->format('d/m/Y') }}
                                    </option>
                                @empty
                                    <option value="" disabled>No hay pedidos aptos</option>
                                @endforelse
                            </select>
                            <small class="text-muted">Recomendado: pedidos en <b>en_proceso_de_compra</b> o
                                <b>aprobado</b>.</small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="oc_proveedor_id" class="form-label"
                                    style="font-weight:700;color:#0f172a;">Proveedor (opcional)</label>
                                <select name="proveedor_id" id="oc_proveedor_id" class="form-select">
                                    <option value="">— Sin asignar —</option>
                                    @foreach ($proveedores ?? [] as $prov)
                                        <option value="{{ $prov->id }}">
                                            {{ $prov->nombre }}{{ $prov->ruc ? ' — RUC: ' . $prov->ruc : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="oc_fecha_entrega" class="form-label"
                                    style="font-weight:700;color:#0f172a;">Fecha entrega estimada</label>
                                <input type="date" name="fecha_entrega_estimada" id="oc_fecha_entrega"
                                    class="form-control" required>
                                <small class="text-muted">Por defecto: hoy + 5 días.</small>
                            </div>
                        </div>

                        <div class="alert alert-info mt-3" role="alert">
                            Se calcularán los <b>faltantes</b> del pedido. Si no hay faltantes, no se generará la OC.
                        </div>
                    </div>

                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" style="background:#0d3a8a;border-color:#0d3a8a;">
                            <i class="ti ti-check me-1"></i> Generar OC
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Aviso: faltó stock al generar Guía (sugerir OC) --}}
    @if (session('warning') && session('faltantes') && session('pedido_id'))
        <div class="alert alert-warning d-flex flex-column gap-2" role="alert">
            <div><strong>{{ session('warning') }}</strong></div>
            <div>Materiales con stock insuficiente:</div>
            <ul class="mb-0">
                @foreach (session('faltantes') as $f)
                    <li>{{ $f['material'] ?? ($f['descripcion'] ?? 'Material') }} — requerido:
                        {{ $f['req'] ?? $f['cantidad'] }} — disponible: {{ $f['disp'] ?? '?' }}</li>
                @endforeach
            </ul>
            <form action="{{ route('pedidos.generar_oc', session('pedido_id')) }}" method="POST" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-dark">
                    <i class="ti ti-file-dollar me-1"></i> Generar Orden de Compra para faltantes
                </button>
            </form>
        </div>
    @endif

    <script>
        // Tabs
        document.querySelectorAll('.tab').forEach(t => {
            t.addEventListener('click', function(e) {
                e.preventDefault();
                const tab = this.dataset.tab;
                document.querySelectorAll('.tab').forEach(x => x.classList.remove('is-active'));
                this.classList.add('is-active');
                document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
                document.getElementById(tab).classList.add('active');
            });
        });

        // Modal Guía: focus
        const modalNuevaGuia = document.getElementById('modalNuevaGuia');
        if (modalNuevaGuia) {
            modalNuevaGuia.addEventListener('shown.bs.modal', () => {
                const sel = document.getElementById('pedido_id');
                if (sel) sel.focus();
            });
        }

        // Modal OC: focus y fecha por defecto
        const modalNuevaOC = document.getElementById('modalNuevaOC');
        if (modalNuevaOC) {
            modalNuevaOC.addEventListener('shown.bs.modal', () => {
                const sel = document.getElementById('oc_pedido_id');
                if (sel) sel.focus();

                const f = document.getElementById('oc_fecha_entrega');
                if (f && !f.value) {
                    const dt = new Date();
                    dt.setDate(dt.getDate() + 5);
                    f.value = dt.toISOString().slice(0, 10);
                }
            });
        }

        // Construir action dinámico para /almacen/pedidos/{pedido}/generar-oc
        const formOC = document.getElementById('formNuevaOC');
        if (formOC) {
            formOC.addEventListener('submit', (e) => {
                const pedidoId = document.getElementById('oc_pedido_id')?.value;
                if (!pedidoId) {
                    e.preventDefault();
                    alert('Selecciona un pedido.');
                    return;
                }
                const action = "{{ url('/almacen/pedidos') }}/" + encodeURIComponent(pedidoId) + "/generar-oc";
                formOC.setAttribute('action', action);
            });
        }
    </script>

    {{-- Script para Alerta de IA (Anomalías - Idea 2) --}}
    @if(session('ia_anomalias'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '⚠️ ALERTA DE SEGURIDAD (IA)',
                html: `
                    <p>El sistema ha detectado un comportamiento inusual en las cantidades:</p>
                    <ul style="text-align:left; color:#d33; font-weight:bold; list-style:none;">
                        @foreach(session('ia_anomalias') as $a)
                            <li>🚫 {{ $a['material'] }}: {{ $a['cantidad'] }} unidades <br><small>({{ $a['mensaje'] }})</small></li>
                        @endforeach
                    </ul>
                    <p>¿Estás seguro de que quieres autorizar esta salida?</p>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, Autorizar Riesgo',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reenviamos el formulario automáticamente agregando el campo "ignorar_ia"
                    let form = document.createElement('form');
                    form.action = "{{ route('guias.store') }}"; 
                    form.method = 'POST';
                    
                    // Token CSRF necesario para Laravel
                    let csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    form.appendChild(csrfInput);

                    // ID del pedido original
                    let idInput = document.createElement('input');
                    idInput.type = 'hidden';
                    idInput.name = 'pedido_id';
                    idInput.value = "{{ session('pedido_id') }}";
                    form.appendChild(idInput);

                    // Campo mágico para saltar la IA esta vez
                    let ignoreInput = document.createElement('input');
                    ignoreInput.type = 'hidden';
                    ignoreInput.name = 'ignorar_ia';
                    ignoreInput.value = "1";
                    form.appendChild(ignoreInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    </script>
    @endif

@endsection