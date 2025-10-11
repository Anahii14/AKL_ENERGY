@extends('layouts.clientePlantilla')

@section('title', 'Electrico - Dashboard Cliente')

<style>
    /* ===== Identidad ===== */
    .brand-akl {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
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
        letter-spacing: .3px;
    }

    .akl-text {
        display: flex;
        flex-direction: column;
        line-height: 1.1;
    }

    .akl-text .title {
        color: #0f172a;
        font-weight: 800;
        font-size: 18px;
    }

    .akl-text .subtitle {
        color: #64748b;
        font-size: 12px;
        font-weight: 600;
    }

    /* ===== Container ===== */
    .wrap {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0 24px;
    }

    @media (max-width:600px) {
        .wrap {
            padding: 0 16px;
        }
    }

    /* ===== Tabs/Pills ===== */
    .tabbar {
        display: flex;
        gap: 16px;
        background: #eef3f8;
        border-radius: 8px;
        padding: 10px;
        overflow: auto;
    }

    .tabbar a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        color: #1f2937;
        text-decoration: none;
        font-weight: 700;
        opacity: .9;
        white-space: nowrap;
    }

    .tabbar a .ti {
        font-size: 18px;
    }

    .tabbar a:hover {
        background: #e6edf6;
    }

    .tabbar a.active {
        background: #fff;
        box-shadow: inset 0 0 0 2px #e5edf7;
        color: #0f172a;
    }

    /* ===== Cabecera de sección ===== */
    .section-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin: 18px 0 12px;
    }

    .sec-title {
        display: flex;
        flex-direction: column;
    }

    .sec-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sec-title-row .ti {
        color: #ff6a3d;
    }

    .sec-title h2 {
        margin: 0;
        font-weight: 900;
        color: #0f172a;
    }

    .sec-sub {
        color: #64748b;
        margin-top: 2px;
    }

    /* ===========================
       BOTÓN NARANJA (AKL STYLE)
       =========================== */
    .btn-orange {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 8px;
        border: 1px solid #ff6a3d !important;
        background: #ff6a3d !important;
        color: #fff !important;
        font-weight: 800;
        font-size: 15px;
        line-height: 1;
        box-shadow: 0 4px 10px rgba(255, 106, 61, .25);
        transition: all .2s ease-in-out;
        white-space: nowrap;
    }

    .btn-orange i {
        color: #fff !important;
        font-weight: 800;
        font-size: 16px;
    }

    .btn-orange:hover {
        background: #e85a2f !important;
        border-color: #e85a2f !important;
        filter: brightness(.98);
        transform: translateY(-1px);
        color: #fff !important;
    }

    .btn-orange:active {
        background: #d84f27 !important;
        border-color: #d84f27 !important;
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(255, 106, 61, .2);
        color: #fff !important;
    }

    .btn-orange:focus {
        color: #fff !important;
        box-shadow: 0 0 0 .2rem rgba(255, 106, 61, .3) !important;
    }

    @media (max-width:600px) {
        .btn-orange {
            padding: 9px 14px;
            font-size: 14px;
        }
    }

    /* ===== Empty State ===== */
    .card-empty {
        background: #fff;
        border: 1px solid #e6ebf0;
        border-radius: 10px;
        padding: 48px 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 180px;
    }

    .empty-inner {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }

    .empty-ico {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #f1f5f9;
        color: #94a3b8;
        font-size: 26px;
    }

    .empty-text {
        color: #64748b;
        font-weight: 700;
        text-align: center;
    }

    /* ===== Tarjetas listadas (inline styles opcionales) ===== */
    .s-name {
        margin: 0 0 6px;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
    }

    /* ===== Formularios / Modal ===== */
    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    .form-control,
    .form-select,
    .form-check-input,
    textarea.form-control {
        border-radius: 8px;
    }

    .form-control:focus,
    .form-select:focus,
    textarea.form-control:focus {
        border-color: #ff6a3d !important;
        box-shadow: 0 0 0 .2rem rgba(255, 106, 61, .15) !important;
    }

    .form-check-input:checked {
        background-color: #ff6a3d;
        border-color: #ff6a3d;
    }

    /* ===== Responsiveness ===== */
    @media (max-width:600px) {
        .sec-title h2 {
            font-size: 1.25rem;
        }
    }
</style>

@section('content')
    <div class="wrap">

        {{-- Flash messages --}}
        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <b>Corrige los siguientes campos:</b>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="section-head">
            <div class="sec-title">
                <div class="sec-title-row">
                    <i class="ti ti-bolt"></i>
                    <h2>Instalaciones Eléctricas</h2>
                </div>
                <div class="sec-sub">
                    @php $count = $countElectricas ?? ($cotizaciones->count() ?? 0); @endphp
                    Tienes <b>{{ $count }}</b> cotización(es)
                </div>
            </div>

            <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#modalNuevaCotizacion">
                <i class="ti ti-plus"></i> Nueva Cotización
            </button>
        </div>

        {{-- Listado / Empty state --}}
        @if (($cotizaciones ?? collect())->isEmpty())
            <div class="card-empty">
                <div class="empty-inner">
                    <div class="empty-ico"><i class="ti ti-bolt"></i></div>
                    <div class="empty-text">No tienes cotizaciones eléctricas registradas</div>
                </div>
            </div>
        @else
            @foreach ($cotizaciones as $c)
                <div class="s-card"
                    style="background:#fff;border:1px solid #e6ebf0;border-radius:10px;padding:18px 22px;margin-top:14px">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="s-name" style="margin:0 0 6px;font-size:18px;font-weight:800;color:#0f172a">
                                {{ $c->tipo_servicio }}
                            </h3>
                            <div class="s-date" style="color:#64748b;font-weight:600">
                                {{ optional($c->created_at)->format('d/m/Y') }}
                            </div>
                        </div>
                        <span class="s-badge"
                            style="background:#fff4c2;color:#6b5500;border-radius:999px;padding:6px 12px;font-size:12px;font-weight:800">
                            {{ $c->estado }}
                        </span>
                    </div>
                    <div class="s-body" style="margin-top:12px">
                        <div class="s-meta" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;max-width:480px">
                            <div><small>Área (m²):</small> <b>{{ number_format($c->area_m2, 2) }}</b></div>
                            <div><small>Voltaje:</small> <b>{{ $c->voltaje_requerido ?? '—' }}</b></div>
                            <div><small>Duración (días):</small> <b>{{ $c->duracion_dias ?? '—' }}</b></div>
                            <div><small>Ubicación:</small> <b>{{ $c->ubicacion_obra }}</b></div>
                            <div><small>Inicio:</small>
                                <b>{{ \Carbon\Carbon::parse($c->fecha_inicio)->format('d/m/Y') }}</b></div>
                            <div><small>Incluye materiales:</small> <b>{{ $c->incluir_materiales ? 'Sí' : 'No' }}</b></div>
                        </div>
                        <div style="margin-top:10px;color:#334155">
                            <small style="display:block;color:#64748b;font-weight:700;margin-bottom:2px">Descripción</small>
                            <div>{{ $c->descripcion_trabajo }}</div>
                        </div>
                        @if ($c->observaciones)
                            <div style="margin-top:10px;color:#334155">
                                <small
                                    style="display:block;color:#64748b;font-weight:700;margin-bottom:2px">Observaciones</small>
                                <div>{{ $c->observaciones }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif

    </div>

    {{-- Modal: Nueva Cotización --}}
    <div class="modal fade" id="modalNuevaCotizacion" tabindex="-1" aria-labelledby="modalNuevaCotizacionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header bg-warning-subtle">
                    <h5 class="modal-title" id="modalNuevaCotizacionLabel">
                        <i class="ti ti-bolt"></i> Nueva Cotización Eléctrica
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <form method="POST" action="{{ route('cotizaciones.electrico.store') }}">
                    @csrf
                    <div class="modal-body">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Servicio *</label>
                                <input type="text" name="tipo_servicio" class="form-control" required
                                    placeholder="Instalación, mantenimiento, etc.">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Área (m²) *</label>
                                <input type="number" step="0.01" min="0" name="area_m2" class="form-control"
                                    required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Voltaje Requerido</label>
                                <input type="text" name="voltaje_requerido" class="form-control"
                                    placeholder="220V, 440V...">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Descripción del Trabajo *</label>
                                <textarea name="descripcion_trabajo" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Duración (días)</label>
                                <input type="number" min="1" name="duracion_dias" class="form-control">
                            </div>

                            <div class="col-md-5">
                                <label class="form-label fw-bold">Ubicación de Obra *</label>
                                <input type="text" name="ubicacion_obra" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Fecha de Inicio *</label>
                                <input type="date" name="fecha_inicio" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="incluyeMat"
                                        name="incluir_materiales" checked>
                                    <label class="form-check-label" for="incluyeMat">
                                        Incluir materiales
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Observaciones</label>
                                <textarea name="observaciones" class="form-control" rows="2" placeholder="Notas adicionales"></textarea>
                            </div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-orange">Enviar solicitud</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
