{{-- resources/views/DashboardAlmacen/cotizaciones.blade.php --}}
@extends('layouts.almaceneroPlantilla')

@section('title', 'Cotizaciones - Almacén')

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

        .q-card {
            background: #fff;
            border: 1px solid #f5c6cb;
            border-radius: 12px
        }

        .q-card .q-body {
            padding: 18px 20px
        }

        .q-title {
            font-weight: 800;
            margin: 0 0 4px
        }

        .q-meta {
            font-size: .85rem;
            color: #334155
        }

        .q-label {
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 4px
        }

        .q-value {
            margin-bottom: 16px
        }

        .q-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .q-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            margin-top: 8px
        }

        .btn-approve {
            background: #22c55e;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 14px;
            font-weight: 700
        }

        .btn-reject {
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 14px;
            font-weight: 700
        }

        .btn-approve .ti,
        .btn-reject .ti {
            margin-right: 6px
        }

        @media (max-width:576px) {
            .q-grid {
                grid-template-columns: 1fr
            }

            .q-actions {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="container-xxl page-wrap">

        {{-- Pestañas --}}
        <div class="tabs-wrap mb-3">
            <a href="#tab-gruas" class="tab is-active" data-tab="gruas">
                <span class="ico ti ti-truck"></span> Grúas <small>({{ $countGruas }})</small>
            </a>
            <a href="#tab-electricas" class="tab" data-tab="electricas">
                <span class="ico ti ti-bolt"></span> Eléctricas <small>({{ $countElectricas }})</small>
            </a>
        </div>

        <div id="tab-content">

            {{-- ===== GRÚAS ===== --}}
            <div id="tab-gruas" class="tab-pane" style="display:block;">
                @if ($cotizacionesGruas->isEmpty())
                    <div class="q-card">
                        <div class="q-body text-center text-muted">No hay cotizaciones de grúas.</div>
                    </div>
                @else
                    @foreach ($cotizacionesGruas as $c)
                        <article class="q-card mb-3">
                            <div class="q-body">
                                {{-- Encabezado --}}
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="q-title">{{ $c->tipo_grua ?? 'Grúa' }}</h5>
                                        <div class="q-meta">
                                            Cliente: {{ optional($c->user)->name ?? '—' }}<br>
                                            {{ optional($c->created_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    @php
                                        $estadoGrua = $c->estado ?? 'pendiente';
                                        $badge =
                                            [
                                                'pendiente' => 'bg-secondary',
                                                'enviada' => 'bg-info',
                                                'aceptada' => 'bg-success',
                                                'rechazada' => 'bg-danger',
                                            ][$estadoGrua] ?? 'bg-secondary';
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ ucfirst($estadoGrua) }}</span>
                                </div>

                                {{-- Cuerpo en dos columnas --}}
                                <div class="q-grid mt-3">
                                    <div>
                                        <div class="q-label">Capacidad:</div>
                                        <div class="q-value">{{ $c->capacidad }} toneladas</div>

                                        <div class="q-label">Ubicación:</div>
                                        <div class="q-value">{{ $c->ubicacion_obra }}</div>
                                    </div>
                                    <div>
                                        <div class="q-label">Días de alquiler:</div>
                                        <div class="q-value">{{ $c->dias_alquiler }}</div>

                                        <div class="q-label">Fecha requerida:</div>
                                        <div class="q-value">{{ \Carbon\Carbon::parse($c->fecha_inicio)->format('m/d/Y') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Motivo y fecha si ya fue decidida --}}
                                @if (!empty($c->decision_motivo))
                                    <div class="mt-2" style="font-size:.9rem;color:#475569">
                                        <strong>Motivo:</strong> {{ $c->decision_motivo }}<br>
                                        <strong>Decidido:</strong> {{ optional($c->decision_fecha)->format('d/m/Y H:i') }}
                                        por #{{ $c->decision_por }}
                                    </div>
                                @endif

                                {{-- Acciones (solo si está pendiente) --}}
                                @php $decididaGrua = in_array($estadoGrua, ['aceptada','rechazada']); @endphp
                                <div class="q-actions">
                                    @if (!$decididaGrua)
                                        <button type="button" class="btn-approve open-decision" data-tipo="grua"
                                            data-id="{{ $c->id }}" data-decision="aceptada"
                                            data-updated="{{ optional($c->updated_at)->toIso8601String() }}"
                                            data-titulo="Aprobar cotización de grúa #{{ $c->id }}">
                                            <i class="ti ti-checks"></i> Aprobar
                                        </button>
                                        <button type="button" class="btn-reject open-decision" data-tipo="grua"
                                            data-id="{{ $c->id }}" data-decision="rechazada"
                                            data-updated="{{ optional($c->updated_at)->toIso8601String() }}"
                                            data-titulo="Rechazar cotización de grúa #{{ $c->id }}">
                                            <i class="ti ti-circle-x"></i> Rechazar
                                        </button>
                                    @else
                                        <button class="btn-approve" disabled>
                                            <i class="ti ti-lock"></i> Decisión registrada
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>

            {{-- ===== ELÉCTRICAS ===== --}}
            <div id="tab-electricas" class="tab-pane" style="display:none;">
                @if ($cotizacionesElectricas->isEmpty())
                    <div class="q-card">
                        <div class="q-body text-center text-muted">No hay cotizaciones eléctricas.</div>
                    </div>
                @else
                    @foreach ($cotizacionesElectricas as $e)
                        <article class="q-card mb-3">
                            <div class="q-body">
                                {{-- Encabezado --}}
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="q-title">{{ $e->tipo_servicio ?? 'Servicio eléctrico' }}</h5>
                                        <div class="q-meta">
                                            Cliente: {{ optional($e->user)->name ?? '—' }}<br>
                                            {{ optional($e->created_at)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                    @php
                                        $estadoElec = $e->estado ?? 'pendiente';
                                        $badgeElec =
                                            $estadoElec === 'aceptada'
                                                ? 'bg-success'
                                                : ($estadoElec === 'rechazada'
                                                    ? 'bg-danger'
                                                    : 'bg-secondary');
                                    @endphp
                                    <span class="badge {{ $badgeElec }}">{{ ucfirst($estadoElec) }}</span>
                                </div>

                                {{-- Cuerpo en dos columnas --}}
                                <div class="q-grid mt-3">
                                    <div>
                                        <div class="q-label">Área:</div>
                                        <div class="q-value">{{ number_format((float) $e->area_m2, 0) }} m²</div>

                                        <div class="q-label">Ubicación:</div>
                                        <div class="q-value">{{ $e->ubicacion_obra }}</div>
                                    </div>
                                    <div>
                                        <div class="q-label">Duración (días):</div>
                                        <div class="q-value">{{ $e->duracion_dias ?? '—' }}</div>

                                        <div class="q-label">Fecha inicio:</div>
                                        <div class="q-value">{{ \Carbon\Carbon::parse($e->fecha_inicio)->format('m/d/Y') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Motivo y fecha si ya fue decidida --}}
                                @if (!empty($e->decision_motivo))
                                    <div class="mt-2" style="font-size:.9rem;color:#475569">
                                        <strong>Motivo:</strong> {{ $e->decision_motivo }}<br>
                                        <strong>Decidido:</strong> {{ optional($e->decision_fecha)->format('d/m/Y H:i') }}
                                        por #{{ $e->decision_por }}
                                    </div>
                                @endif

                                {{-- Acciones (solo si está pendiente) --}}
                                @php $decididaElec = in_array($estadoElec, ['aceptada','rechazada']); @endphp
                                <div class="q-actions">
                                    @if (!$decididaElec)
                                        <button type="button" class="btn-approve open-decision" data-tipo="electrico"
                                            data-id="{{ $e->id }}" data-decision="aceptada"
                                            data-updated="{{ optional($e->updated_at)->toIso8601String() }}"
                                            data-titulo="Aprobar cotización eléctrica #{{ $e->id }}">
                                            <i class="ti ti-checks"></i> Aprobar
                                        </button>
                                        <button type="button" class="btn-reject open-decision" data-tipo="electrico"
                                            data-id="{{ $e->id }}" data-decision="rechazada"
                                            data-updated="{{ optional($e->updated_at)->toIso8601String() }}"
                                            data-titulo="Rechazar cotización eléctrica #{{ $e->id }}">
                                            <i class="ti ti-circle-x"></i> Rechazar
                                        </button>
                                    @else
                                        <button class="btn-approve" disabled>
                                            <i class="ti ti-lock"></i> Decisión registrada
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                @endif
            </div>

        </div>
    </div>

    {{-- Modal global para decidir --}}
    <div class="modal fade" id="modalDecision" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('almacen.cotizaciones.decidir') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="decisionTitle">Decidir cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="tipo" id="decisionTipo">
                    <input type="hidden" name="id" id="decisionId">
                    <input type="hidden" name="decision" id="decisionValor">
                    {{-- Concurrencia optimista --}}
                    <input type="hidden" name="updated_at" id="decisionUpdatedAt">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Motivo</label>
                        <textarea name="motivo" id="decisionMotivo" class="form-control" rows="4" required minlength="5"
                            maxlength="2000" placeholder="Explica brevemente la razón de la decisión. Esto será visible para el cliente."></textarea>
                        <small class="text-muted">Se notificará al cliente con este motivo.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-light" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit" id="btnDecisionSubmit">Confirmar decisión</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        // Tabs
        document.querySelectorAll('.tabs-wrap .tab').forEach(tab => {
            tab.addEventListener('click', e => {
                e.preventDefault();
                document.querySelectorAll('.tabs-wrap .tab').forEach(t => t.classList.remove('is-active'));
                tab.classList.add('is-active');
                const target = tab.getAttribute('href');
                document.querySelectorAll('.tab-pane').forEach(p => p.style.display = 'none');
                document.querySelector(target).style.display = 'block';
            });
        });

        // Modal: preparar y abrir
        const decisionModalEl = document.getElementById('modalDecision');
        let decisionModal;
        document.addEventListener('DOMContentLoaded', () => {
            decisionModal = new bootstrap.Modal(decisionModalEl);
        });

        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.open-decision');
            if (!btn) return;

            document.getElementById('decisionTitle').textContent = btn.dataset.titulo || 'Decidir cotización';
            document.getElementById('decisionTipo').value = btn.dataset.tipo || '';
            document.getElementById('decisionId').value = btn.dataset.id || '';
            document.getElementById('decisionValor').value = btn.dataset.decision || '';
            document.getElementById('decisionMotivo').value = '';
            // Concurrencia optimista
            document.getElementById('decisionUpdatedAt').value = btn.dataset.updated || '';

            decisionModal.show();
        });

        // Anti doble submit (pulido UX)
        const formDecision = document.querySelector('#modalDecision form');
        const btnSubmit = document.getElementById('btnDecisionSubmit');
        formDecision?.addEventListener('submit', () => {
            btnSubmit.disabled = true;
            btnSubmit.textContent = 'Enviando...';
        });
    </script>
@endsection
