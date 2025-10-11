{{-- resources/views/DashboardCliente/estado.blade.php --}}
@extends('layouts.clientePlantilla')

@section('title', 'Estado - Dashboard Cliente')

@section('content')
    <style>
        .brand-akl {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none
        }

        .akl-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: #ff6a3d;
            color: #fff;
            font-weight: 800;
            font-size: 14px;
            letter-spacing: .3px
        }

        .akl-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1
        }

        .akl-text .title {
            color: #0f172a;
            font-weight: 800;
            font-size: 18px
        }

        .akl-text .subtitle {
            color: #64748b;
            font-size: 12px;
            font-weight: 600
        }

        .s-wrap {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 24px
        }

        @media (max-width:600px) {
            .s-wrap {
                padding: 0 16px
            }
        }

        .s-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 8px 0 16px;
            gap: 16px
        }

        .s-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
            font-weight: 800;
            color: #0f172a
        }

        .s-title i {
            color: #ff6a3d
        }

        .s-sub {
            margin-top: 2px;
            color: #64748b;
            font-weight: 500
        }

        .s-tabs {
            background: #f3f6fb;
            border: 1px solid #e6ebf0;
            border-radius: 10px;
            padding: 6px;
            display: flex;
            gap: 8px;
            align-items: center
        }

        .s-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid transparent;
            background: transparent;
            color: #0f172a;
            font-weight: 700;
            cursor: pointer
        }

        .s-tab i {
            font-size: 16px
        }

        .s-tab.active {
            background: #fff;
            border-color: #e6ebf0;
            box-shadow: 0 1px 0 rgba(2, 6, 23, .02)
        }

        .s-tab .count {
            opacity: .7;
            font-weight: 800
        }

        .s-panel {
            margin-top: 12px
        }

        .s-card {
            background: #fff;
            border: 1px solid #e6ebf0;
            border-radius: 10px;
            padding: 18px 22px;
            margin-top: 14px;
            box-shadow: 0 2px 0 rgba(2, 6, 23, .02)
        }

        .s-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px
        }

        .s-name {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 800;
            color: #0f172a
        }

        .s-date {
            color: #64748b;
            font-weight: 600
        }

        .s-badge {
            border-radius: 999px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 800;
            text-transform: capitalize
        }

        .s-badge.pendiente {
            background: #fff4c2;
            color: #6b5500
        }

        .s-badge.enviada {
            background: #c7f9cc;
            color: #064e3b
        }

        .s-badge.aceptada {
            background: #d1fae5;
            color: #065f46
        }

        .s-badge.rechazada {
            background: #fee2e2;
            color: #7f1d1d
        }

        .s-body {
            margin-top: 12px
        }

        .s-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            max-width: 460px
        }

        .s-meta small {
            display: block;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 2px
        }

        .s-meta b {
            color: #0f172a
        }

        .s-price {
            margin-top: 14px;
            color: #ff6a3d;
            font-weight: 900;
            font-size: 22px
        }

        .s-empty {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #64748b;
            background: #fff;
            border: 1px dashed #e2e8f0;
            border-radius: 10px;
            padding: 18px 22px;
            margin-top: 14px
        }

        .s-empty i {
            color: #94a3b8
        }

        /* Bloque de resultado (motivo + fecha) */
        .s-result {
            margin-top: 10px;
            background: #f8fafc;
            border: 1px solid #dbeafe;
            border-radius: 10px;
            padding: 14px 18px
        }

        .s-result .title {
            font-weight: 800;
            color: #0f172a
        }

        .s-result .body {
            color: #334155
        }
    </style>

    <div class="s-wrap">

        <div class="s-head">
            <div>
                <h2 class="s-title"><i class="ti ti-file-text"></i> Estado de Cotizaciones</h2>
                <div class="s-sub">Revisa el estado de todas tus solicitudes</div>
            </div>
        </div>

        @php
            $countGruas = $cotizacionesGruas->count() ?? 0;
            $countElectricas = $cotizacionesElectricas->count() ?? 0;
        @endphp

        <!-- Tabs -->
        <div class="s-tabs" role="tablist" aria-label="Tipos de cotización">
            <button class="s-tab active" type="button" data-target="tab-gruas" aria-controls="tab-gruas"
                aria-selected="true">
                <i class="ti ti-truck"></i><span>Grúas</span> <span class="count">({{ $countGruas }})</span>
            </button>
            <button class="s-tab" type="button" data-target="tab-electricas" aria-controls="tab-electricas"
                aria-selected="false">
                <i class="ti ti-bolt"></i><span>Eléctricas</span> <span class="count">({{ $countElectricas }})</span>
            </button>
        </div>

        <!-- PANEL: GRÚAS -->
        <section id="tab-gruas" class="s-panel" aria-labelledby="btn-gruas">
            @if ($cotizacionesGruas->isEmpty())
                <div class="s-empty">
                    <i class="ti ti-info-circle"></i>
                    <span>No tienes cotizaciones de grúas por ahora.</span>
                </div>
            @else
                @foreach ($cotizacionesGruas as $g)
                    <article class="s-card js-card">
                        <div class="s-top">
                            <div>
                                <h3 class="s-name">{{ $g->tipo_grua }}</h3>
                                <div class="s-date">{{ \Carbon\Carbon::parse($g->fecha_inicio)->format('d/m/Y') }}</div>
                            </div>
                            <span class="s-badge {{ $g->estado }}">{{ $g->estado }}</span>
                        </div>

                        <div class="s-body">
                            <div class="s-meta">
                                <div><small>Capacidad:</small><b>{{ $g->capacidad }} t</b></div>
                                <div><small>Días Alquiler:</small><b>{{ $g->dias_alquiler }}</b></div>
                                <div><small>Ubicación:</small><b>{{ $g->ubicacion_obra }}</b></div>
                                <div><small>Incluye Operador:</small><b>{{ $g->incluye_operador ? 'Sí' : 'No' }}</b></div>
                                <div><small>Incluye Rigger:</small><b>{{ $g->incluye_rigger ? 'Sí' : 'No' }}</b></div>
                                <div><small>Incluye Combustible:</small><b>{{ $g->incluye_combustible ? 'Sí' : 'No' }}</b>
                                </div>
                            </div>

                            @if ($g->precio_total)
                                <div class="s-price">S/ {{ number_format($g->precio_total, 2) }}</div>
                            @endif
                        </div>

                        {{-- Mensaje de resultado para el cliente (grúas) --}}
                        @if ($g->estado !== 'pendiente' && !empty($g->decision_motivo))
                            <div class="s-result">
                                <div class="title">
                                    Resultado: <span style="text-transform:capitalize">{{ $g->estado }}</span>
                                </div>
                                <div class="body">
                                    Motivo: {{ $g->decision_motivo }}
                                </div>
                                <small class="text-muted">
                                    Fecha: {{ optional($g->decision_fecha)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @endif
                    </article>
                @endforeach
            @endif
        </section>

        <!-- PANEL: ELÉCTRICAS -->
        <section id="tab-electricas" class="s-panel" hidden aria-labelledby="btn-electricas">
            @if ($cotizacionesElectricas->isEmpty())
                <div class="s-empty">
                    <i class="ti ti-info-circle"></i>
                    <span>No tienes cotizaciones eléctricas por ahora.</span>
                </div>
            @else
                @foreach ($cotizacionesElectricas as $e)
                    <article class="s-card js-card">
                        <div class="s-top">
                            <div>
                                <h3 class="s-name">{{ $e->tipo_servicio }}</h3>
                                <div class="s-date">{{ \Carbon\Carbon::parse($e->fecha_inicio)->format('d/m/Y') }}</div>
                            </div>
                            <span class="s-badge {{ $e->estado }}">{{ $e->estado }}</span>
                        </div>

                        <div class="s-body">
                            <div class="s-meta">
                                <div><small>Área (m²):</small><b>{{ number_format($e->area_m2, 2) }}</b></div>
                                <div><small>Duración:</small><b>{{ $e->duracion_dias ?? '—' }} días</b></div>
                                <div><small>Ubicación:</small><b>{{ $e->ubicacion_obra }}</b></div>
                                <div><small>Voltaje:</small><b>{{ $e->voltaje_requerido ?? '—' }}</b></div>
                                <div><small>Incluye Materiales:</small><b>{{ $e->incluir_materiales ? 'Sí' : 'No' }}</b>
                                </div>
                            </div>
                            <div style="margin-top:10px">
                                <small style="display:block;color:#64748b;font-weight:700">Descripción</small>
                                <div>{{ $e->descripcion_trabajo }}</div>
                            </div>
                        </div>

                        {{-- Mensaje de resultado para el cliente (eléctricas) --}}
                        @if ($e->estado !== 'pendiente' && !empty($e->decision_motivo))
                            <div class="s-result">
                                <div class="title">
                                    Resultado: <span style="text-transform:capitalize">{{ $e->estado }}</span>
                                </div>
                                <div class="body">
                                    Motivo: {{ $e->decision_motivo }}
                                </div>
                                <small class="text-muted">
                                    Fecha: {{ optional($e->decision_fecha)->format('d/m/Y H:i') }}
                                </small>
                            </div>
                        @endif
                    </article>
                @endforeach
            @endif
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.s-tab');
            const panels = document.querySelectorAll('.s-panel');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    panels.forEach(p => p.hidden = true);
                    const targetId = tab.dataset.target;
                    const target = document.getElementById(targetId);
                    if (target) target.hidden = false;
                });
            });
        });
    </script>
@endsection
