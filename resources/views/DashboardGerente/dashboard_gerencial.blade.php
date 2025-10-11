{{-- resources/views/DashboardGerente/dashboard_gerencial.blade.php --}}
@extends('layouts.gerentePlantilla')

@section('title', 'Dashboard Gerencial')

@section('content')
    <style>
        :root {
            --card: #fff;
            --ink: #0f172a;
            --muted: #64748b;
            --line: #e5e7eb;
            --chip: #eef5fb;
            --accent: #ff6a3d;
            --brand: #0a7abf;
            --brand2: #25a6d9;
            --shadow: 0 12px 28px rgba(2, 6, 23, .10);
            --radius: 14px;
        }

        .page-wrap {
            max-width: 1200px;
            margin: 0 auto
        }

        .panel-filtros {
            background: linear-gradient(180deg, rgba(2, 6, 23, .02), transparent), var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 16px 18px;
            margin-bottom: 18px
        }

        .panel-title {
            font-weight: 900;
            color: var(--ink);
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 10px
        }

        .panel-title i {
            color: var(--brand)
        }

        .f-grid {
            display: grid;
            grid-template-columns: 1.1fr .9fr .9fr;
            gap: 12px
        }

        .f-control {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .f-control label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 700
        }

        .f-control select,
        .f-control input {
            background: #f8fafc;
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
            font-weight: 600;
            color: var(--ink)
        }

        /* KPIs */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 12px
        }

        .kpi {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 16px 18px
        }

        .kpi .label {
            font-weight: 800;
            color: var(--muted);
            margin-bottom: 6px
        }

        .kpi .value {
            font-size: 28px;
            line-height: 1.1;
            font-weight: 900;
            color: var(--ink)
        }

        .kpi .value--accent {
            color: var(--accent)
        }

        .kpi .sub {
            margin-top: 6px;
            color: var(--muted);
            font-weight: 700;
            font-size: 12px
        }

        .kpi .chips {
            margin-top: 8px;
            display: flex;
            gap: 8px;
            flex-wrap: wrap
        }

        .chip {
            background: var(--chip);
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 800;
            color: var(--ink)
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            border-bottom: 1px solid var(--line);
            margin: 14px 0
        }

        .tab {
            padding: 10px 14px;
            font-weight: 800;
            border-radius: 10px 10px 0 0;
            color: var(--ink);
            cursor: pointer
        }

        .tab.active {
            background: linear-gradient(135deg, var(--brand), var(--brand2));
            color: #fff
        }

        .tab-pane {
            display: none
        }

        .tab-pane.active {
            display: block
        }

        /* Cards contenedoras */
        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow)
        }

        .card-head {
            padding: 16px 18px;
            border-bottom: 1px solid var(--line);
            font-weight: 900;
            color: var(--ink);
            display: flex;
            gap: 8px;
            align-items: center
        }

        .card-body {
            padding: 14px 18px
        }

        .nodata {
            padding: 28px;
            color: var(--muted);
            font-weight: 800;
            text-align: center
        }

        @media (max-width: 1100px) {
            .kpi-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .f-grid {
                grid-template-columns: 1fr 1fr
            }
        }

        @media (max-width: 640px) {
            .kpi-grid {
                grid-template-columns: 1fr
            }

            .f-grid {
                grid-template-columns: 1fr
            }
        }
    </style>

    @php
        // Valores seguros por defecto
        $kpi = $kpi ?? [
            'cot_total' => 0,
            'pedidos_total' => 0,
            'oc_count' => 0,
            'oc_total' => 0,
            'obras_activas' => 0,
            'pedidos_breakdown' => ['EN PROCESO' => 0, 'LISTO' => 0, 'EN_PROCESO_DE_COMPRA' => 0],
        ];
        $pedidosPorEstado = $pedidosPorEstado ?? ['EN PROCESO' => 0, 'LISTO' => 0, 'EN_PROCESO_DE_COMPRA' => 0];
        $pedidosPorObra = $pedidosPorObra ?? []; // [['obra'=>'Chupaca','cantidad'=>2], ...]
        $gastos = $gastos ?? ['oc_total' => 0, 'cot_gruas_total' => 0];

        // Usa la variable que envía el controlador
        $obraId = $obraId ?? null;
        $fechaIni = $fechaIni ?? null;
        $fechaFin = $fechaFin ?? null;
    @endphp

    <div class="container-xxl page-wrap">

        {{-- Filtros --}}
        <div class="panel-filtros">
            <div class="panel-title">
                <i class="ti ti-adjustments-alt"></i> Filtros de Análisis
            </div>
            <form method="GET" action="{{ route('gerente.dashboard_gerencial') }}">
                <div class="f-grid">
                    <div class="f-control">
                        <label>Obra</label>
                        <select name="obra_id">
                            <option value="">Todas las obras</option>
                            @foreach ($obras ?? [] as $o)
                                <option value="{{ $o->id }}"
                                    {{ (string) $obraId === (string) $o->id ? 'selected' : '' }}>
                                    {{ $o->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="f-control">
                        <label>Fecha Inicio</label>
                        <input type="date" name="fecha_ini"
                            value="{{ $fechaIni ? \Illuminate\Support\Carbon::parse($fechaIni)->format('Y-m-d') : '' }}">
                    </div>
                    <div class="f-control">
                        <label>Fecha Fin</label>
                        <input type="date" name="fecha_fin"
                            value="{{ $fechaFin ? \Illuminate\Support\Carbon::parse($fechaFin)->format('Y-m-d') : '' }}">
                    </div>
                </div>
                <div style="margin-top:12px;display:flex;gap:10px">
                    <button class="btn btn-primary"
                        style="background:linear-gradient(135deg,var(--brand),var(--brand2));border:none;font-weight:800">Aplicar</button>
                    <a href="{{ route('gerente.dashboard_gerencial') }}" class="btn btn-light"
                        style="font-weight:800">Limpiar</a>
                </div>
            </form>
        </div>

        {{-- KPIs --}}
        <div class="kpi-grid">
            <div class="kpi">
                <div class="label"><i class="ti ti-currency-sol"></i> Total Cotizaciones</div>
                <div class="value value--accent">S/ {{ number_format($kpi['cot_total'], 2) }}</div>
                <div class="sub">Grúas: S/ {{ number_format($gastos['cot_gruas_total'] ?? 0, 2) }}</div>
            </div>

            <div class="kpi">
                <div class="label"><i class="ti ti-filter"></i> Pedidos Filtrados</div>
                <div class="value">{{ $kpi['pedidos_total'] }}</div>
                <div class="chips">
                    <span class="chip">en: {{ $kpi['pedidos_breakdown']['EN PROCESO'] ?? 0 }}</span>
                    <span class="chip">list: {{ $kpi['pedidos_breakdown']['LISTO'] ?? 0 }}</span>
                    <span class="chip">enP: {{ $kpi['pedidos_breakdown']['EN_PROCESO_DE_COMPRA'] ?? 0 }}</span>
                </div>
            </div>

            <div class="kpi">
                <div class="label"><i class="ti ti-shopping-cart"></i> Órdenes de Compra</div>
                <div class="value">{{ $kpi['oc_count'] }}</div>
                <div class="sub">Total: S/ {{ number_format($kpi['oc_total'], 2) }}</div>
            </div>

            <div class="kpi">
                <div class="label"><i class="ti ti-building-skyscraper"></i> Obras Activas</div>
                <div class="value">{{ $kpi['obras_activas'] }}</div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="tabs" id="tabs">
            <div class="tab active" data-tab="t-graficos"><i class="ti ti-chart-bar"></i> Gráficos</div>
            <div class="tab" data-tab="t-pedidos"><i class="ti ti-clipboard-list"></i> Pedidos</div>
            <div class="tab" data-tab="t-cotizaciones"><i class="ti ti-file-invoice"></i> Cotizaciones</div>
            <div class="tab" data-tab="t-ordenes"><i class="ti ti-shopping-cart"></i> Órdenes</div>
        </div>

        {{-- PANE: Gráficos --}}
        <div class="tab-pane active" id="t-graficos">
            <div class="row g-3">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-head"><i class="ti ti-box"></i> Pedidos por Estado</div>
                        <div class="card-body">
                            <canvas id="chartEstados" height="220"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-head"><i class="ti ti-building"></i> Pedidos por Obra</div>
                        <div class="card-body">
                            @php $totalObras = collect($pedidosPorObra)->sum('cantidad'); @endphp
                            @if ($totalObras > 0)
                                <canvas id="chartObras" height="220"></canvas>
                            @else
                                <div class="nodata">No hay datos para mostrar</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-head"><i class="ti ti-currency-dollar"></i> Distribución de Gastos</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-8">
                                    <canvas id="chartGastos" height="280"></canvas>
                                </div>
                                <div class="col-lg-4 d-flex align-items-center">
                                    <ul style="list-style:none;margin:0;padding:0;font-weight:800;color:var(--ink)">
                                        <li style="margin-bottom:8px">
                                            <span
                                                style="display:inline-block;width:12px;height:12px;background:#3b82f6;border-radius:2px;margin-right:8px"></span>
                                            Órdenes de Compra: <strong>S/
                                                {{ number_format($gastos['oc_total'] ?? 0, 2) }}</strong>
                                        </li>
                                        <li>
                                            <span
                                                style="display:inline-block;width:12px;height:12px;background:#10b981;border-radius:2px;margin-right:8px"></span>
                                            Cotizaciones Grúas: <strong>S/
                                                {{ number_format($gastos['cot_gruas_total'] ?? 0, 2) }}</strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div> {{-- /row --}}
        </div>

        {{-- PANE: Pedidos (placeholder) --}}
        {{-- PANE: Pedidos --}}
        <div class="tab-pane" id="t-pedidos">
            <style>
                .order-list {
                    margin-top: 8px
                }

                .order-item {
                    background: #eff5fb;
                    /* tono como el de tu imagen */
                    border-radius: 12px;
                    padding: 14px 16px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    color: var(--ink);
                    font-weight: 800
                }

                .order-item+.order-item {
                    margin-top: 12px
                }

                .order-left {
                    display: flex;
                    flex-direction: column
                }

                .order-title {
                    font-weight: 900
                }

                .order-sub {
                    font-size: 12px;
                    color: #6b7280;
                    font-weight: 800
                }

                .badge {
                    display: inline-flex;
                    align-items: center;
                    gap: 6px;
                    border-radius: 999px;
                    padding: 6px 10px;
                    font-size: 12px;
                    font-weight: 900;
                    border: 1px solid #dbeafe;
                    background: #eef2ff;
                    color: #1e293b;
                }

                .badge i {
                    opacity: .8
                }

                /* Colores por estado */
                .b-en_proceso {
                    background: #fff7ed;
                    border-color: #fed7aa;
                    color: #9a3412
                }

                .b-listo {
                    background: #eef2ff;
                    border-color: #c7d2fe;
                    color: #3730a3
                }

                .b-entregado {
                    background: #ecfeff;
                    border-color: #a5f3fc;
                    color: #065f46
                }

                .b-en_proceso_de_compra {
                    background: #eff6ff;
                    border-color: #bfdbfe;
                    color: #1d4ed8
                }
            </style>

            <div class="card">
                <div class="card-head"><i class="ti ti-clipboard-list"></i> Todos los Pedidos de Materiales</div>
                <div class="card-body">
                    @if (($pedidos->total() ?? 0) === 0)
                        <div class="nodata">No hay pedidos que coincidan con los filtros.</div>
                    @else
                        <div class="order-list">
                            @foreach ($pedidos as $p)
                                @php
                                    $obraNombre = optional($p->obra)->nombre ?? 'Sin obra';
                                    $fechaReq =
                                        optional($p->fecha_requerida)->format('m/d/Y') ??
                                        optional($p->created_at)->format('m/d/Y');
                                    $estado = strtolower($p->estado ?? '');
                                    // normaliza clases (coincidir con tu imagen)
                                    $map = [
                                        'en_proceso' => 'b-en_proceso',
                                        'listo' => 'b-listo',
                                        'entregado' => 'b-entregado',
                                        'en_proceso_de_compra' => 'b-en_proceso_de_compra',
                                    ];
                                    $badgeClass = $map[$estado] ?? 'badge';
                                @endphp

                                <div class="order-item">
                                    <div class="order-left">
                                        <div class="order-title">{{ $obraNombre }}</div>
                                        <div class="order-sub">Fecha solicitada: {{ $fechaReq }}</div>
                                    </div>
                                    <span class="badge {{ $badgeClass }}">
                                        <i class="ti ti-bolt"></i>
                                        {{ $p->estado }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginación --}}
                        <div class="mt-3">
                            {{ $pedidos->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- PANE: Cotizaciones --}}
        <div class="tab-pane" id="t-cotizaciones">
            <style>
                .cot-grid {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 16px
                }

                @media (max-width: 900px) {
                    .cot-grid {
                        grid-template-columns: 1fr
                    }
                }

                .cot-card {
                    background: var(--card);
                    border: 1px solid var(--line);
                    border-radius: var(--radius);
                    box-shadow: var(--shadow)
                }

                .cot-head {
                    padding: 16px 18px;
                    border-bottom: 1px solid var(--line);
                    font-weight: 900;
                    color: var(--ink);
                    display: flex;
                    gap: 8px;
                    align-items: center
                }

                .cot-body {
                    padding: 14px 18px
                }

                .cot-item {
                    background: #eef3f8;
                    border-radius: 12px;
                    padding: 14px 16px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between
                }

                .cot-item+.cot-item {
                    margin-top: 10px
                }

                .cot-title {
                    font-weight: 900
                }

                .cot-sub {
                    font-size: 12px;
                    color: #6b7280;
                    font-weight: 800;
                    margin-top: 2px
                }

                .cot-amount {
                    margin-top: 8px;
                    color: #ef4444;
                    font-weight: 900
                }

                .chip {
                    display: inline-flex;
                    align-items: center;
                    border-radius: 999px;
                    padding: 6px 10px;
                    font-size: 12px;
                    font-weight: 900
                }

                .chip-pendiente {
                    background: #e0ecff;
                    color: #1d4ed8
                }

                .chip-aprobado {
                    background: #e7f9ef;
                    color: #065f46
                }

                .chip-rechazado {
                    background: #ffe4e6;
                    color: #9f1239
                }

                .nodata {
                    text-align: center;
                    color: #64748b;
                    font-weight: 800;
                    padding: 18px 0
                }
            </style>

            <div class="cot-grid">
                {{-- Columna: Cotizaciones de Grúas --}}
                <div class="cot-card">
                    <div class="cot-head"><i class="ti ti-file-invoice"></i> Cotizaciones de Grúas</div>
                    <div class="cot-body">
                        @if (($cotGruas->count() ?? 0) === 0)
                            <div class="nodata">No hay cotizaciones que coincidan</div>
                        @else
                            @foreach ($cotGruas as $c)
                                @php
                                    $estado = strtolower($c->estado ?? 'pendiente');
                                    $chipClass = match ($estado) {
                                        'aprobado' => 'chip-aprobado',
                                        'rechazado' => 'chip-rechazado',
                                        default => 'chip-pendiente',
                                    };
                                @endphp
                                <div class="cot-item">
                                    <div>
                                        <div class="cot-title">{{ $c->tipo_grua ?? 'Grúa' }}</div>
                                        <div class="cot-sub">
                                            {{ (int) ($c->capacidad ?? 0) }} ton
                                            – {{ (int) ($c->dias_alquiler ?? 0) }} días
                                        </div>
                                        <div class="cot-amount">S/ {{ number_format((float) ($c->precio_total ?? 0), 2) }}
                                        </div>
                                    </div>
                                    <span class="chip {{ $chipClass }}">{{ $estado }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- Columna: Cotizaciones Eléctricas --}}
                <div class="cot-card">
                    <div class="cot-head"><i class="ti ti-file-invoice"></i> Cotizaciones Eléctricas</div>
                    <div class="cot-body">
                        @if (($cotElectricas->count() ?? 0) === 0)
                            <div class="nodata">No hay cotizaciones que coincidan</div>
                        @else
                            @foreach ($cotElectricas as $e)
                                @php
                                    $estado = strtolower($e->estado ?? 'pendiente');
                                    $chipClass = match ($estado) {
                                        'aprobado' => 'chip-aprobado',
                                        'rechazado' => 'chip-rechazado',
                                        default => 'chip-pendiente',
                                    };
                                @endphp
                                <div class="cot-item">
                                    <div>
                                        <div class="cot-title">{{ $e->tipo_servicio ?? 'Servicio eléctrico' }}</div>
                                        <div class="cot-sub">
                                            {{ (float) ($e->area_m2 ?? 0) }} m² – {{ (int) ($e->duracion_dias ?? 0) }}
                                            días
                                        </div>
                                        {{-- Si más adelante agregas monto en eléctricos, muéstralo aquí como en grúas --}}
                                    </div>
                                    <span class="chip {{ $chipClass }}">{{ $estado }}</span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>


        {{-- PANE: Órdenes --}}
        <div class="tab-pane" id="t-ordenes">
            <style>
                .oc-list {
                    margin-top: 8px
                }

                .oc-item {
                    background: #eef3f8;
                    border-radius: 12px;
                    padding: 14px 16px;
                    display: flex;
                    align-items: center;
                    justify-content: space-between
                }

                .oc-item+.oc-item {
                    margin-top: 10px
                }

                .oc-left {
                    display: flex;
                    flex-direction: column
                }

                .oc-title {
                    font-weight: 900
                }

                .oc-sub {
                    font-size: 12px;
                    color: #6b7280;
                    font-weight: 800;
                    margin-top: 2px
                }

                .oc-right {
                    display: flex;
                    flex-direction: column;
                    align-items: flex-end;
                    gap: 8px
                }

                .oc-amount {
                    color: #ff6a3d;
                    font-weight: 900
                }

                .chip {
                    display: inline-flex;
                    align-items: center;
                    border-radius: 999px;
                    padding: 6px 10px;
                    font-size: 12px;
                    font-weight: 900
                }

                .chip-pendiente {
                    background: #e0ecff;
                    color: #1d4ed8
                }

                .chip-aprobado {
                    background: #e7f9ef;
                    color: #065f46
                }

                .chip-anulada,
                .chip-rechazado {
                    background: #ffe4e6;
                    color: #9f1239
                }

                .chip-recibido {
                    background: #dcfce7;
                    color: #166534
                }

                .oc-item a {
                    text-decoration: none;
                    color: inherit
                }
            </style>

            <div class="card">
                <div class="card-head"><i class="ti ti-shopping-cart"></i> Órdenes de Compra a Proveedores</div>
                <div class="card-body">
                    @if (($ordenes->total() ?? 0) === 0)
                        <div class="nodata">No hay órdenes que coincidan</div>
                    @else
                        <div class="oc-list">
                            @foreach ($ordenes as $oc)
                                @php
                                    $proveedor = optional($oc->proveedor)->nombre ?? '—';
                                    $fecha = optional($oc->fecha_emision)->format('m/d/Y') ?? '';
                                    $estadoStr = strtolower($oc->estado ?? 'pendiente');
                                    $map = [
                                        'pendiente' => 'chip-pendiente',
                                        'aprobado' => 'chip-aprobado',
                                        'anulada' => 'chip-anulada',
                                        'rechazado' => 'chip-rechazado',
                                        'recibido' => 'chip-recibido',
                                    ];
                                    $chipClass = $map[$estadoStr] ?? 'chip-pendiente';
                                @endphp

                                <div class="oc-item">
                                    <div class="oc-left">
                                        <div class="oc-title">
                                            {{ $oc->codigo ?? 'OC-' . str_pad($oc->id, 6, '0', STR_PAD_LEFT) }}</div>
                                        <div class="oc-sub">Proveedor: {{ $proveedor }}</div>
                                        <div class="oc-sub">Fecha: {{ $fecha }}</div>
                                    </div>
                                    <div class="oc-right">
                                        <div class="oc-amount">S/ {{ number_format((float) ($oc->monto_total ?? 0), 2) }}
                                        </div>
                                        <span class="chip {{ $chipClass }}">{{ $estadoStr }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Paginación --}}
                        <div class="mt-3">
                            {{ $ordenes->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div> {{-- /page-wrap --}}
@endsection

@push('scripts')
    {{-- Chart.js CDN (quita si ya lo cargas en tu layout) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Tabs simples
        document.querySelectorAll('#tabs .tab').forEach(t => {
            t.addEventListener('click', () => {
                document.querySelectorAll('#tabs .tab').forEach(x => x.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(x => x.classList.remove('active'));
                t.classList.add('active');
                document.getElementById(t.dataset.tab).classList.add('active');
            });
        });

        // Data desde backend
        const pedidosEstadosData = @json(array_values($pedidosPorEstado));
        const pedidosEstadosLabels = @json(array_keys($pedidosPorEstado));

        const pedidosObra = @json($pedidosPorObra);
        const obrasLabels = pedidosObra.map(o => o.obra);
        const obrasCant = pedidosObra.map(o => o.cantidad);

        const gastosData = [
            {{ (float) ($gastos['oc_total'] ?? 0) }},
            {{ (float) ($gastos['cot_gruas_total'] ?? 0) }},
        ];

        // Chart: Pedidos por Estado (Barras)
        const ctxE = document.getElementById('chartEstados').getContext('2d');
        new Chart(ctxE, {
            type: 'bar',
            data: {
                labels: pedidosEstadosLabels,
                datasets: [{
                    label: 'cantidad',
                    data: pedidosEstadosData,
                    backgroundColor: '#ff6a3d'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true
                    }
                }
            }
        });

        // Chart: Pedidos por Obra (Barras horizontales si hay data)
        @if (collect($pedidosPorObra)->sum('cantidad') > 0)
            const ctxO = document.getElementById('chartObras').getContext('2d');
            new Chart(ctxO, {
                type: 'bar',
                data: {
                    labels: obrasLabels,
                    datasets: [{
                        label: 'cantidad',
                        data: obrasCant,
                        backgroundColor: '#0ea5e9'
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    scales: {
                        x: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        @endif

        // Chart: Distribución de Gastos (Pie)
        const ctxG = document.getElementById('chartGastos').getContext('2d');
        new Chart(ctxG, {
            type: 'pie',
            data: {
                labels: ['Órdenes de Compra', 'Cotizaciones Grúas'],
                datasets: [{
                    data: gastosData,
                    backgroundColor: ['#3b82f6', '#10b981']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endpush
