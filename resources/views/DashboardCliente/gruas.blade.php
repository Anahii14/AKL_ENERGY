@extends('layouts.clientePlantilla')

@section('title', 'Gruas - Dashboard Cliente')

<style>
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

    .q-wrap {
        max-width: 1120px;
        margin: 0 auto;
        padding: 0 24px;
    }

    @media (max-width:600px) {
        .q-wrap {
            padding: 0 16px;
        }
    }

    .q-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 6px 0 16px;
        gap: 16px;
    }

    .q-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0;
        font-weight: 800;
        color: #0f172a;
    }

    .q-title i {
        color: #ff6a3d;
    }

    .q-sub {
        margin: 2px 0 0;
        color: #64748b;
        font-weight: 500;
    }

    /* ===========================
       BOTÓN NARANJA ACTUALIZADO
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
        box-shadow: 0 4px 10px rgba(255, 106, 61, 0.25);
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
    }

    .btn-orange i {
        color: #fff !important;
        font-weight: bold;
        font-size: 16px;
    }

    .btn-orange:hover {
        background: #e85a2f !important;
        border-color: #e85a2f !important;
        filter: brightness(0.98);
        transform: translateY(-1px);
        color: #fff !important;
    }

    .btn-orange:active {
        background: #d84f27 !important;
        border-color: #d84f27 !important;
        transform: translateY(0);
        box-shadow: 0 2px 6px rgba(255, 106, 61, 0.2);
        color: #fff !important;
    }

    .btn-orange:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 106, 61, 0.3) !important;
        color: #fff !important;
    }

    @media (max-width:600px) {
        .btn-orange {
            padding: 9px 14px;
            font-size: 14px;
        }
    }

    /* =========================== */

    .q-card {
        background: #fff;
        border: 1px solid #e6ebf0;
        border-radius: 10px;
        padding: 18px 22px;
        margin-bottom: 14px;
        box-shadow: 0 2px 0 rgba(2, 6, 23, .02);
    }

    .q-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 10px;
    }

    .q-name {
        margin: 0 0 4px;
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
    }

    .q-meta {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-weight: 600;
    }

    .q-meta i {
        font-size: 16px;
    }

    .q-badge {
        background: #f4c84a;
        color: #3b2f09;
        border-radius: 999px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 800;
    }

    .q-body {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 12px;
        gap: 16px;
    }

    .q-left {
        max-width: 60%;
    }

    .q-left small {
        display: block;
        color: #64748b;
        font-weight: 700;
        margin-bottom: 2px;
    }

    .q-left b {
        color: #0f172a;
    }

    .q-tags {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .q-tag {
        background: #eef2f6;
        border: 1px solid #e2e8f0;
        color: #0f172a;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 10px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .q-right {
        text-align: center;
        min-width: 280px;
    }

    .q-right small {
        color: #64748b;
        font-weight: 700;
    }

    .q-price {
        color: #ff6a3d;
        font-weight: 900;
        font-size: 22px;
        margin-top: 4px;
    }

    @media (max-width:1200px) {
        .q-right {
            min-width: 240px;
        }
    }

    @media (max-width:900px) {
        .q-body {
            flex-direction: column;
            align-items: flex-start;
        }

        .q-left {
            max-width: 100%;
        }

        .q-right {
            text-align: left;
            min-width: auto;
        }
    }

    @media (max-width:600px) {
        .q-name {
            font-size: 18px;
        }

        .q-price {
            font-size: 20px;
        }

        .q-badge {
            padding: 5px 10px;
        }

        .q-meta {
            font-size: .95rem;
        }
    }

    /* Look & feel del formulario como la maqueta */
    .modal-content {
        border-radius: 12px;
    }

    .modal-header {
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
    }

    /* Inputs suaves + borde al foco en naranja AKL */
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

    /* Checkboxes en naranja */
    .form-check-input:checked {
        background-color: #ff6a3d;
        border-color: #ff6a3d;
    }

    /* Etiquetas/separadores como en la maqueta */
    .form-label {
        color: #0f172a;
    }

    .text-muted {
        color: #64748b !important;
    }
</style>


@section('content')
    <div class="q-wrap">

        <div class="q-head">
            <div>
                <h2 class="q-title"><i class="ti ti-truck"></i> Cotizaciones de Grúas</h2>
                <div class="q-sub">Solicita alquiler de grúas para tus proyectos</div>
            </div>
            <button type="button" class="btn btn-orange" data-bs-toggle="modal" data-bs-target="#modalNuevaCotizacion">
                <i class="ti ti-plus"></i> Nueva Cotización
            </button>

        </div>

        {{-- Mensajes flash / validación opcional arriba de la lista --}}
        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @forelse($cotizaciones as $c)
            <article class="q-card">
                <div class="q-top">
                    <div>
                        <h3 class="q-name">{{ $c->tipo_grua }} - {{ $c->capacidad }}t</h3>
                        <div class="q-meta">
                            <i class="ti ti-calendar"></i>
                            <span>{{ $c->fecha_inicio?->format('m/d/Y') }} • {{ $c->dias_alquiler }} días</span>
                        </div>
                    </div>
                    <span class="q-badge">{{ $c->estado }}</span>
                </div>

                <div class="q-body">
                    <div class="q-left">
                        <small>Ubicación:</small>
                        <b>{{ $c->ubicacion_obra }}</b>

                        <div class="q-tags">
                            @if ($c->incluye_operador)
                                <span class="q-tag"><i class="ti ti-check"></i> Operador</span>
                            @endif
                            @if ($c->incluye_rigger)
                                <span class="q-tag"><i class="ti ti-check"></i> Rigger</span>
                            @endif
                            @if ($c->incluye_combustible)
                                <span class="q-tag"><i class="ti ti-check"></i> Combustible</span>
                            @endif
                        </div>
                    </div>

                    <div class="q-right">
                        <small>Precio Total:</small>
                        <div class="q-price">S/ {{ number_format($c->precio_total ?? 0, 2) }}</div>
                    </div>
                </div>
            </article>
        @empty
            <article class="q-card">
                <div class="text-muted">Aún no tienes cotizaciones. Crea la primera con <b>Nueva Cotización</b>.</div>
            </article>
        @endforelse


    </div>

    <!-- Modal: Nueva Solicitud de Cotización -->
    <div class="modal fade" id="modalNuevaCotizacion" tabindex="-1" aria-labelledby="modalNuevaCotizacionLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header" style="background:#ff6a3d;">
                    <h5 class="modal-title text-white fw-bold" id="modalNuevaCotizacionLabel">
                        <i class="ti ti-crane me-2"></i>Nueva Solicitud de Cotización
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Cerrar"></button>
                </div>

                <form id="formNuevaCotizacion" action="{{ route('cotizaciones.gruas.store') }}" method="POST"
                    class="needs-validation" novalidate>
                    @csrf

                    <div class="modal-body">
                        <p class="text-muted mb-3">Completa los datos para tu requerimiento</p>

                        <div class="row g-3">
                            <!-- Tipo de Grúa -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipo de Grúa <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="tipo_grua" class="form-control"
                                    placeholder="Ej: Grúa Torre, Grúa Móvil" required minlength="3" maxlength="150"
                                    oninvalid="this.setCustomValidity('Indica el tipo de grúa (mín. 3 caracteres)')"
                                    oninput="this.setCustomValidity('')">
                                <div class="invalid-feedback">Este campo es obligatorio y debe tener al menos 3 caracteres.
                                </div>
                            </div>

                            <!-- Capacidad (toneladas) -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Capacidad (toneladas) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.1" min="1" name="capacidad" class="form-control"
                                    placeholder="Ej: 50" required
                                    oninvalid="this.setCustomValidity('Ingresa una capacidad válida (número ≥ 1)')"
                                    oninput="this.setCustomValidity('')">
                                <div class="invalid-feedback">La capacidad debe ser un número mayor o igual a 1.</div>
                            </div>

                            <!-- Días de Alquiler -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Días de Alquiler <span
                                        class="text-danger">*</span></label>
                                <input type="number" min="1" name="dias_alquiler" class="form-control"
                                    placeholder="Ej: 30" required
                                    oninvalid="this.setCustomValidity('Ingresa la cantidad de días (número ≥ 1)')"
                                    oninput="this.setCustomValidity('')">
                                <div class="invalid-feedback">Debe ser un número de días mayor o igual a 1.</div>
                            </div>

                            <!-- Fecha de Inicio -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Fecha de Inicio <span
                                        class="text-danger">*</span></label>
                                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required
                                    oninvalid="this.setCustomValidity('Selecciona una fecha de inicio válida')"
                                    oninput="this.setCustomValidity('')">
                                <div class="invalid-feedback">Selecciona una fecha de inicio.</div>
                            </div>

                            <!-- Ubicación -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Ubicación de Obra <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="ubicacion_obra" class="form-control"
                                    placeholder="Dirección de la obra" required minlength="3" maxlength="255"
                                    oninvalid="this.setCustomValidity('Indica la dirección o zona de la obra (mín. 3 caracteres)')"
                                    oninput="this.setCustomValidity('')">
                                <div class="invalid-feedback">Indica la dirección o referencia de la obra.</div>
                            </div>

                            <!-- Servicios Adicionales -->
                            <div class="col-12">
                                <label class="form-label fw-semibold d-block mb-2">Servicios Adicionales</label>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="srvOperador"
                                        name="incluir_operador" value="1" checked>
                                    <label class="form-check-label" for="srvOperador">Incluir Operador (+S/
                                        150/día)</label>
                                </div>

                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="srvRigger" name="incluir_rigger"
                                        value="1">
                                    <label class="form-check-label" for="srvRigger">Incluir Rigger (+S/ 120/día)</label>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="srvCombustible"
                                        name="incluir_combustible" value="1">
                                    <label class="form-check-label" for="srvCombustible">Incluir Combustible (+S/
                                        200/día)</label>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Observaciones</label>
                                <textarea name="observaciones" rows="3" class="form-control" placeholder="Detalles adicionales..."
                                    maxlength="2000"></textarea>
                                <div class="invalid-feedback">Revisa el texto de observaciones.</div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-orange">
                            <i class="ti ti-send me-1"></i> Enviar Solicitud
                        </button>
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validación Bootstrap
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Fecha mínima = hoy
        (function() {
            const inputFecha = document.getElementById('fecha_inicio');
            if (inputFecha) {
                const hoy = new Date();
                const yyyy = hoy.getFullYear();
                const mm = String(hoy.getMonth() + 1).padStart(2, '0');
                const dd = String(hoy.getDate()).padStart(2, '0');
                inputFecha.min = `${yyyy}-${mm}-${dd}`;
            }
        })();

        // Reset limpio al cerrar (y operador marcado)
        document.getElementById('modalNuevaCotizacion')
            .addEventListener('hidden.bs.modal', function() {
                const f = document.getElementById('formNuevaCotizacion');
                f.reset();
                f.classList.remove('was-validated');
                document.getElementById('srvOperador').checked = true;
                // re-aplicar min date por si el reset lo borra en algunos navegadores
                const inputFecha = document.getElementById('fecha_inicio');
                if (inputFecha && !inputFecha.min) {
                    const hoy = new Date();
                    const yyyy = hoy.getFullYear();
                    const mm = String(hoy.getMonth() + 1).padStart(2, '0');
                    const dd = String(hoy.getDate()).padStart(2, '0');
                    inputFecha.min = `${yyyy}-${mm}-${dd}`;
                }
            });
    </script>

@endsection
