{{-- resources/views/DashboardAlmacen/gestion.blade.php --}}
@extends('layouts.almaceneroPlantilla')

@section('title', 'Gestión de Recursos - Almacén')

@section('content')
    <style>
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto
        }

        .card-np {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06)
        }

        .h-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            color: #0f172a
        }

        .title-ico {
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: #fff7f3;
            border: 1px solid #ffdccb;
            color: #ff6a3d;
            display: grid;
            place-items: center;
            font-size: 14px;
            line-height: 0
        }

        .acc {
            display: grid;
            gap: 14px
        }

        .acc-item {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff
        }

        .acc-head {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding: 14px 16px;
            font-weight: 800;
            color: #0f172a;
            border-radius: 12px
        }

        .acc-head .chev {
            margin-left: auto;
            color: #334155;
            transition: transform .15s ease
        }

        .acc-item.is-open .chev {
            transform: rotate(180deg)
        }

        .acc-body {
            display: none;
            padding: 18px;
            border-top: 1px solid #eef2f7
        }

        .acc-item.is-open .acc-body {
            display: block
        }

        .block-head {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px
        }

        .block-head h4 {
            margin: 0;
            font-weight: 800;
            color: #0f172a
        }

        .block-ico {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: grid;
            place-items: center;
            color: #0f172a;
            font-size: 18px;
            line-height: 0
        }

        .btn-new {
            margin-left: auto;
            background: #ff6a3d;
            color: #fff;
            border: 0;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 800;
            display: inline-flex;
            gap: 8px;
            align-items: center
        }

        .btn-new:hover {
            filter: brightness(.95);
            color: #fff
        }

        .m-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 14px;
            background: #fff
        }

        .m-item+.m-item {
            margin-top: 12px
        }

        .m-title {
            font-weight: 800;
            color: #0f172a
        }

        .m-meta {
            color: #64748b;
            font-weight: 600
        }

        .btn-edit {
            margin-left: auto;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            color: #0f172a
        }

        .btn-edit:hover {
            background: #f8fafc
        }

        .soft {
            color: #64748b;
            font-weight: 600
        }

        /* Tabla */
        .tbl {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0
        }

        .tbl thead th {
            font-size: 13px;
            color: #64748b;
            font-weight: 800;
            padding: 12px 14px;
            border-bottom: 1px solid #e5e7eb
        }

        .tbl tbody td {
            padding: 14px;
            border-top: 1px solid #eef2f7;
            vertical-align: middle
        }

        .tbl tbody tr:hover {
            background: #f9fafb
        }

        .row-actions {
            display: flex;
            gap: 8px;
            justify-content: flex-end
        }

        .btn-icon {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            width: 36px;
            height: 36px;
            display: grid;
            place-items: center;
            color: #0f172a
        }

        .btn-icon:hover {
            background: #f8fafc
        }

        .table-flat {
            border-radius: 0 !important
        }

        .tbl,
        .tbl thead,
        .tbl tbody,
        .tbl tr,
        .tbl th,
        .tbl td {
            border-radius: 0 !important
        }

        /* Modal */
        .modal-akl .modal-content {
            border-radius: 12px
        }

        .modal-akl .modal-header {
            border-bottom: 0;
            padding-bottom: 0
        }

        .modal-akl .modal-title {
            font-weight: 800;
            color: #0f172a
        }

        .modal-akl .subtitle {
            color: #64748b;
            margin-top: 4px
        }

        .modal-akl .form-label {
            font-weight: 600;
            color: #0f172a
        }

        .modal-akl .form-control:focus {
            border-color: #ff6a3d;
            box-shadow: 0 0 0 .25rem rgba(255, 106, 61, .15)
        }

        .btn-akl {
            background: #ff6a3d;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 10px;
            padding: 9px 14px
        }

        .btn-akl:hover {
            filter: brightness(.95);
            color: #fff
        }

        .btn-soft {
            background: #fff;
            border: 1px solid #e5e7eb;
            color: #0f172a;
            border-radius: 10px
        }

        .btn-soft:hover {
            background: #f8fafc
        }

        .invalid-feedback {
            display: block
        }

        /* feedback animaciones */
        .tbl tr.flash-new {
            animation: rowFlash 1.2s ease-out
        }

        .tbl tr.flash-update {
            animation: rowFlash 1.2s ease-out
        }

        @keyframes rowFlash {
            0% {
                background: #fff3ec
            }

            100% {
                background: transparent
            }
        }

        .tbl tr.fade-out {
            animation: fadeRow .25s ease-in forwards
        }

        @keyframes fadeRow {
            to {
                opacity: 0;
                height: 0;
                transform: scaleY(0)
            }
        }

        /* Acciones en la tarjeta de material (editar / eliminar) */
        .m-actions {
            margin-left: auto;
            display: flex;
            gap: 8px;
        }

        /* La que ya tenías, pero sin empujar cuando vaya dentro de .m-actions */
        .m-item .btn-edit {
            margin-left: 0;
        }

        /* Botón eliminar con estilo suave */
        .btn-del {
            background: #fff;
            border: 1px solid #fee2e2;
            /* rojo muy suave */
            border-radius: 10px;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;

        }
    </style>

    <div class="container-xxl page-wrap">
        <div class="card-np p-4 p-md-5">
            <h3 class="h-title mb-3">
                <span class="title-ico"><i class="ti ti-settings"></i></span>
                Gestión de Recursos
            </h3>

            <div class="acc">

                {{-- Inventario de Materiales (demo) --}}
                <div class="acc-item is-open" id="acc-materiales">
                    <div class="acc-head">
                        <span class="title-ico" style="border-color:#ffdccb;background:#fff7f3;color:#ff6a3d"><i
                                class="ti ti-box"></i></span>
                        Inventario de Materiales
                        <i class="ti ti-chevron-down chev"></i>
                    </div>

                    <div class="acc-body">
                        <div class="block-head">
                            <span class="block-ico"><i class="ti ti-cube"></i></span>
                            <h4>Inventario de Materiales</h4>
                            <button type="button" class="btn-new" data-bs-toggle="modal" data-bs-target="#modalMaterial">
                                <i class="ti ti-plus"></i> Nuevo Material
                            </button>
                        </div>

                        <!-- LISTA donde se agregan las tarjetas nuevas -->
                        <div id="lista-materiales">
                            @forelse ($materiales as $m)
                                <div class="m-item" data-id="{{ $m->id }}" data-nombre="{{ e($m->nombre) }}"
                                    data-codigo="{{ e($m->codigo) }}" data-descripcion="{{ e($m->descripcion) }}"
                                    data-unidad="{{ e($m->unidad) }}" data-stock_actual="{{ $m->stock_actual }}"
                                    data-stock_minimo="{{ $m->stock_minimo }}" data-precio="{{ $m->precio_unitario }}"
                                    data-proveedor_id="{{ $m->proveedor_id }}">
                                    <div>
                                        <div class="m-title">
                                            <span class="td-codigo">{{ $m->codigo }}</span> -
                                            <span class="td-nombre">{{ $m->nombre }}</span>
                                        </div>
                                        <div class="m-meta">
                                            Stock: <span class="td-stock">
                                                {{ rtrim(rtrim(number_format($m->stock_actual, 3, '.', ''), '0'), '.') }}
                                            </span>
                                            <span class="td-unidad">{{ $m->unidad }}</span> |
                                            Precio: S/
                                            <span
                                                class="td-precio">{{ number_format($m->precio_unitario, 2, '.', '') }}</span>
                                        </div>
                                    </div>
                                    <div class="m-actions">
                                        <button type="button" class="btn-edit btn-edit-mat" data-id="{{ $m->id }}"
                                            title="Editar">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <button type="button" class="btn-del btn-del-mat" data-id="{{ $m->id }}"
                                            title="Eliminar">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="soft empty-materials" style="padding:14px">Sin materiales registrados.</div>
                            @endforelse
                        </div>


                    </div>
                </div>

                {{-- Proveedores --}}
                <div class="acc-item" id="acc-proveedores">
                    <div class="acc-head">
                        <span class="title-ico"><i class="ti ti-building-store"></i></span>
                        Proveedores
                        <i class="ti ti-chevron-down chev"></i>
                    </div>
                    <div class="acc-body">
                        <div class="block-head">
                            <span class="block-ico"><i class="ti ti-building-store"></i></span>
                            <div>
                                <h4 class="m-0">Gestión de Proveedores</h4>
                                <div class="soft" style="margin-top:2px">Administra la información de los proveedores
                                </div>
                            </div>
                            <button type="button" class="btn-new" style="margin-left:auto" data-bs-toggle="modal"
                                data-bs-target="#modalProveedor">
                                <i class="ti ti-plus"></i> Nuevo Proveedor
                            </button>
                        </div>

                        <div class="card-np table-flat" style="padding:0; overflow:hidden">
                            <table class="tbl" id="tabla-proveedores" data-base="{{ url('/almacen/proveedores') }}">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>RUC</th>
                                        <th>Contacto</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Dirección</th>
                                        <th style="width:110px; text-align:right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($proveedores as $p)
                                        <tr data-id="{{ $p->id }}">
                                            <td class="td-nombre" style="font-weight:800">{{ $p->nombre }}</td>
                                            <td class="td-ruc">{{ $p->ruc }}</td>
                                            <td class="td-contacto">{{ $p->contacto }}</td>
                                            <td class="td-telefono">{{ $p->telefono }}</td>
                                            <td class="td-email">{{ $p->email }}</td>
                                            <td class="td-direccion">{{ $p->direccion }}</td>
                                            <td>
                                                <div class="row-actions">
                                                    <button class="btn-icon btn-edit-prov" data-id="{{ $p->id }}"
                                                        title="Editar"><i class="ti ti-edit"></i></button>
                                                    <button class="btn-icon btn-del-prov" data-id="{{ $p->id }}"
                                                        title="Eliminar"><i class="ti ti-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="empty">
                                            <td colspan="7" class="soft" style="padding:18px">Sin proveedores
                                                registrados.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Obras --}}
                <div class="acc-item" id="acc-obras">
                    <div class="acc-head">
                        <span class="title-ico"><i class="ti ti-building"></i></span>
                        Obras
                        <i class="ti ti-chevron-down chev"></i>
                    </div>

                    <div class="acc-body">
                        <div class="block-head">
                            <span class="block-ico"><i class="ti ti-building"></i></span>
                            <div>
                                <h4 class="m-0">Gestión de Obras</h4>
                                <div class="soft" style="margin-top:2px">Control de obras, ubicaciones y responsables
                                </div>
                            </div>
                            <button type="button" class="btn-new" style="margin-left:auto" data-bs-toggle="modal"
                                data-bs-target="#modalObra">
                                <i class="ti ti-plus"></i> Nueva Obra
                            </button>
                        </div>

                        <div class="card-np table-flat" style="padding:0; overflow:hidden">
                            <table class="tbl" id="tabla-obras" data-base="{{ url('/almacen/obras') }}">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Nombre</th>
                                        <th>Ubicación</th>
                                        <th>Admin. Obra</th>
                                        <th>Fechas</th>
                                        <th style="width:90px; text-align:right">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($obras as $o)
                                        <tr data-id="{{ $o->id }}" data-inicio="{{ $o->fecha_inicio }}"
                                            data-fin="{{ $o->fecha_fin_estimada }}">
                                            <td class="td-codigo" style="font-weight:800">{{ $o->codigo }}</td>
                                            <td class="td-nombre">{{ $o->nombre }}</td>
                                            <td class="td-direccion"><i class="ti ti-map-pin"></i> {{ $o->direccion }}
                                            </td>
                                            <td class="soft">Sin asignar</td>
                                            <td class="td-fechas">
                                                <div><i class="ti ti-calendar"></i>
                                                    Inicio:
                                                    @if ($o->fecha_inicio)
                                                        {{ \Carbon\Carbon::parse($o->fecha_inicio)->format('m/d/Y') }}
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                                <div class="soft">
                                                    Fin:
                                                    @if ($o->fecha_fin_estimada)
                                                        {{ \Carbon\Carbon::parse($o->fecha_fin_estimada)->format('m/d/Y') }}
                                                    @else
                                                        —
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div class="row-actions">
                                                    <button class="btn-icon btn-edit-obra" data-id="{{ $o->id }}"
                                                        title="Editar"><i class="ti ti-edit"></i></button>
                                                    <button class="btn-icon btn-del-obra" data-id="{{ $o->id }}"
                                                        title="Eliminar"><i class="ti ti-trash"></i></button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="empty">
                                            <td colspan="6" class="soft" style="padding:18px">Sin obras registradas.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    {{-- Toggle acordeón --}}
    <script>
        document.querySelectorAll('.acc-head').forEach(h => {
            h.addEventListener('click', () => {
                h.parentElement.classList.toggle('is-open');
            });
        });
    </script>

    {{-- Modal Proveedor (un solo formulario, sin duplicados) --}}
    <div class="modal fade modal-akl" id="modalProveedor" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Nuevo Proveedor</h5>
                        <div class="subtitle">Ingresa los datos del nuevo proveedor</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="formProveedor" action="{{ route('proveedores.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="id" id="prov_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" required>
                                <div class="invalid-feedback" data-err="nombre"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">RUC *</label>
                                <input type="text" name="ruc" class="form-control" required inputmode="numeric"
                                    {{-- teclado numérico en móviles --}} pattern="\d{11}" {{-- exactamente 11 dígitos --}} minlength="11"
                                    maxlength="11" autocomplete="off"
                                    oninput="
    this.value = this.value.replace(/\D/g,'').slice(0,11);            // filtra no-dígitos + corta a 11
    if (this.value.length === 11) { this.setCustomValidity(''); }
    else { this.setCustomValidity('El RUC debe tener exactamente 11 dígitos numéricos.'); }
  "
                                    oninvalid="this.setCustomValidity('El RUC debe tener exactamente 11 dígitos numéricos.')" />
                                <div class="invalid-feedback" data-err="ruc"></div>
                                <small class="text-muted">Ingresa 11 dígitos (solo números).</small>

                                <div class="invalid-feedback" data-err="ruc"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contacto</label>
                                <input type="text" name="contacto" class="form-control">
                                <div class="invalid-feedback" data-err="contacto"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" name="telefono" class="form-control">
                                <div class="invalid-feedback" data-err="telefono"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control">
                                <div class="invalid-feedback" data-err="email"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control">
                                <div class="invalid-feedback" data-err="direccion"></div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-akl" id="btnGuardarProveedor">Crear</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal: Nueva Obra -->
    <div class="modal fade modal-akl" id="modalObra" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Nueva Obra</h5>
                        <div class="subtitle">Ingresa los datos de la nueva obra</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <form id="formObra" action="{{ route('obras.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="id" id="obra_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la Obra *</label>
                                <input type="text" name="nombre" class="form-control" required>
                                <div class="invalid-feedback" data-err="nombre"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Código *</label>
                                <input type="text" name="codigo" class="form-control" required>
                                <div class="invalid-feedback" data-err="codigo"></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Dirección</label>
                                <input type="text" name="direccion" class="form-control">
                                <div class="invalid-feedback" data-err="direccion"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control">
                                <div class="invalid-feedback" data-err="fecha_inicio"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Fin Estimada</label>
                                <input type="date" name="fecha_fin_estimada" class="form-control">
                                <div class="invalid-feedback" data-err="fecha_fin_estimada"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-akl" id="btnGuardarObra">Crear Obra</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-akl" id="modalMaterial" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title">Nuevo Material</h5>
                        <div class="subtitle">Ingresa los datos del nuevo material</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body">
                    <form id="formMaterial" action="{{ route('materiales.store') }}" method="POST" novalidate>
                        @csrf
                        <input type="hidden" name="id" id="mat_id">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre" class="form-control" required>
                                <div class="invalid-feedback" data-err="nombre"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Código *</label>
                                <input type="text" name="codigo" class="form-control" required>
                                <div class="invalid-feedback" data-err="codigo"></div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Descripción</label>
                                <input type="text" name="descripcion" class="form-control">
                                <div class="invalid-feedback" data-err="descripcion"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Unidad de Medida *</label>
                                <select name="unidad" class="form-select" required>
                                    <option value="kg">Kilogramo (kg)</option>
                                    <option value="m">Metro (m)</option>
                                    <option value="m²">Metro² (m²)</option>
                                    <option value="m³">Metro³ (m³)</option>
                                    <option value="L">Litro (L)</option>
                                    <option value="bolsa">Bolsa</option>
                                    <option value="caja">Caja</option>
                                </select>
                                <div class="invalid-feedback" data-err="unidad"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stock Actual *</label>
                                <input type="number" name="stock_actual" min="0" class="form-control"
                                    value="0" required>
                                <div class="invalid-feedback" data-err="stock_actual"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Stock Mínimo *</label>
                                <input type="number" name="stock_minimo" min="0" class="form-control"
                                    value="10" required>
                                <div class="invalid-feedback" data-err="stock_minimo"></div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Precio Unitario *</label>
                                <input type="number" name="precio_unitario" step="0.01" min="0"
                                    class="form-control" value="0" required>
                                <div class="invalid-feedback" data-err="precio_unitario"></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Proveedor</label>
                                <select name="proveedor_id" class="form-select">
                                    <option value="">Selecciona proveedor</option>
                                    @foreach ($proveedores as $prov)
                                        <option value="{{ $prov->id }}">{{ $prov->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" data-err="proveedor_id"></div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-soft" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-akl" id="btnGuardarMaterial">Crear Material</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const form = document.getElementById('formProveedor');
            const btnSave = document.getElementById('btnGuardarProveedor');
            const modalEl = document.getElementById('modalProveedor');
            const table = document.getElementById('tabla-proveedores');
            const tbody = table.querySelector('tbody');
            const baseUrl = table.dataset.base || '/almacen/proveedores';
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

            let mode = 'create'; // 'create' | 'update'
            let currentId = null;

            function clearErrors() {
                form.querySelectorAll('.form-control').forEach(i => i.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            }

            function setErrors(errors) {
                Object.entries(errors).forEach(([field, msgs]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    const fb = form.querySelector(`.invalid-feedback[data-err="${field}"]`);
                    if (input) input.classList.add('is-invalid');
                    if (fb) fb.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
                });
            }

            function rowFromId(id) {
                return tbody.querySelector(`tr[data-id="${id}"]`);
            }

            // Abrir modal en modo crear
            document.querySelector('[data-bs-target="#modalProveedor"]')?.addEventListener('click', () => {
                mode = 'create';
                currentId = null;
                form.reset();
                clearErrors();
                form.action = "{{ route('proveedores.store') }}";
                modalEl.querySelector('.modal-title').textContent = 'Nuevo Proveedor';
                btnSave.textContent = 'Crear';
            });

            // Delegación: Editar / Eliminar
            tbody.addEventListener('click', async (ev) => {
                const editBtn = ev.target.closest('.btn-edit-prov');
                const delBtn = ev.target.closest('.btn-del-prov');

                if (editBtn) {
                    const id = editBtn.dataset.id || editBtn.closest('tr')?.dataset.id;
                    const tr = rowFromId(id);
                    if (!tr) return;
                    mode = 'update';
                    currentId = id;
                    clearErrors();
                    form.nombre.value = tr.querySelector('.td-nombre')?.textContent.trim() || '';
                    form.ruc.value = tr.querySelector('.td-ruc')?.textContent.trim() || '';
                    form.contacto.value = tr.querySelector('.td-contacto')?.textContent.trim() || '';
                    form.telefono.value = tr.querySelector('.td-telefono')?.textContent.trim() || '';
                    form.email.value = tr.querySelector('.td-email')?.textContent.trim() || '';
                    form.direccion.value = tr.querySelector('.td-direccion')?.textContent.trim() || '';
                    document.getElementById('prov_id').value = id;

                    form.action = `${baseUrl}/${id}`;
                    modalEl.querySelector('.modal-title').textContent = 'Editar Proveedor';
                    btnSave.textContent = 'Guardar';
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                    return;
                }

                if (delBtn) {
                    const id = delBtn.dataset.id || delBtn.closest('tr')?.dataset.id;
                    if (!id) return;
                    if (!confirm('¿Eliminar este proveedor?')) return;

                    try {
                        const res = await fetch(`${baseUrl}/${id}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        });
                        if (!res.ok) {
                            alert('No se pudo eliminar.');
                            return;
                        }

                        const tr = rowFromId(id);
                        if (tr) {
                            tr.classList.add('fade-out');
                            setTimeout(() => {
                                tr.remove();
                                if (!tbody.children.length) {
                                    const empty = document.createElement('tr');
                                    empty.className = 'empty';
                                    empty.innerHTML =
                                        `<td colspan="7" class="soft" style="padding:18px">Sin proveedores registrados.</td>`;
                                    tbody.appendChild(empty);
                                }
                            }, 250);
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Error inesperado al eliminar.');
                    }
                }
            });

            // Guardar (crear/editar)
            btnSave.addEventListener('click', async () => {
                clearErrors();
                btnSave.disabled = true;

                try {
                    const fd = new FormData(form);
                    const headers = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    };
                    let url = form.action;
                    let method = 'POST';
                    if (mode === 'update') {
                        fd.append('_method', 'PUT');
                    }

                    const res = await fetch(url, {
                        method,
                        headers,
                        body: fd
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        setErrors(data.errors || {});
                        btnSave.disabled = false;
                        return;
                    }
                    if (!res.ok) {
                        console.error('HTTP', res.status);
                        alert('No se pudo guardar.');
                        btnSave.disabled = false;
                        return;
                    }

                    const {
                        proveedor: p
                    } = await res.json();

                    if (mode === 'create') {
                        const tr = document.createElement('tr');
                        tr.dataset.id = p.id;
                        tr.classList.add('flash-new');
                        tr.innerHTML = `
          <td class="td-nombre"   style="font-weight:800">${p.nombre ?? ''}</td>
          <td class="td-ruc">${p.ruc ?? ''}</td>
          <td class="td-contacto">${p.contacto ?? ''}</td>
          <td class="td-telefono">${p.telefono ?? ''}</td>
          <td class="td-email">${p.email ?? ''}</td>
          <td class="td-direccion">${p.direccion ?? ''}</td>
          <td>
            <div class="row-actions">
              <button class="btn-icon btn-edit-prov" data-id="${p.id}" title="Editar"><i class="ti ti-edit"></i></button>
              <button class="btn-icon btn-del-prov"  data-id="${p.id}" title="Eliminar"><i class="ti ti-trash"></i></button>
            </div>
          </td>`;
                        const empty = tbody.querySelector('.empty');
                        if (empty) empty.remove();
                        tbody.insertBefore(tr, tbody.firstChild);
                        setTimeout(() => tr.classList.remove('flash-new'), 1200);
                    } else {
                        const tr = rowFromId(p.id);
                        if (tr) {
                            tr.querySelector('.td-nombre').textContent = p.nombre ?? '';
                            tr.querySelector('.td-ruc').textContent = p.ruc ?? '';
                            tr.querySelector('.td-contacto').textContent = p.contacto ?? '';
                            tr.querySelector('.td-telefono').textContent = p.telefono ?? '';
                            tr.querySelector('.td-email').textContent = p.email ?? '';
                            tr.querySelector('.td-direccion').textContent = p.direccion ?? '';
                            tr.classList.add('flash-update');
                            setTimeout(() => tr.classList.remove('flash-update'), 1200);
                        }
                    }

                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                    form.reset();
                    btnSave.disabled = false;

                } catch (e) {
                    console.error(e);
                    alert('Error inesperado.');
                    btnSave.disabled = false;
                }
            });
        })();
    </script>

    <script>
        (function() {
            const form = document.getElementById('formObra');
            const btnSave = document.getElementById('btnGuardarObra');
            const modalEl = document.getElementById('modalObra');
            const table = document.getElementById('tabla-obras');
            const tbody = table.querySelector('tbody');
            const baseUrl = table.dataset.base || '/almacen/obras';
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;

            let mode = 'create';
            let currentId = null;

            const fmt = (d) => {
                if (!d) return '—';
                // Normaliza yyyy-mm-dd a fecha local
                const t = new Date(d + 'T00:00:00');
                if (isNaN(t)) return '—';
                return t.toLocaleDateString(); // mm/dd/yyyy en muchos navegadores
            };

            const clearErrors = () => {
                form.querySelectorAll('.form-control').forEach(i => i.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            };
            const setErrors = (errors) => {
                Object.entries(errors).forEach(([field, msgs]) => {
                    const input = form.querySelector(`[name="${field}"]`);
                    const fb = form.querySelector(`.invalid-feedback[data-err="${field}"]`);
                    if (input) input.classList.add('is-invalid');
                    if (fb) fb.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
                });
            };
            const rowFromId = id => tbody.querySelector(`tr[data-id="${id}"]`);

            // Abrir modal crear
            document.querySelector('[data-bs-target="#modalObra"]')?.addEventListener('click', () => {
                mode = 'create';
                currentId = null;
                form.reset();
                clearErrors();
                form.action = "{{ route('obras.store') }}";
                modalEl.querySelector('.modal-title').textContent = 'Nueva Obra';
                modalEl.querySelector('.subtitle').textContent = 'Ingresa los datos de la nueva obra';
                btnSave.textContent = 'Crear Obra';
            });

            // Delegación: editar / eliminar
            tbody.addEventListener('click', async (ev) => {
                const editBtn = ev.target.closest('.btn-edit-obra');
                const delBtn = ev.target.closest('.btn-del-obra');

                if (editBtn) {
                    const id = editBtn.dataset.id || editBtn.closest('tr')?.dataset.id;
                    const tr = rowFromId(id);
                    if (!tr) return;

                    mode = 'update';
                    currentId = id;
                    clearErrors();
                    form.nombre.value = tr.querySelector('.td-nombre')?.textContent.trim() || '';
                    form.codigo.value = tr.querySelector('.td-codigo')?.innerText.trim() || '';
                    form.direccion.value = tr.querySelector('.td-direccion')?.textContent.replace(/^.*?\s/,
                        '').trim() || '';
                    form.fecha_inicio.value = tr.dataset.inicio || '';
                    form.fecha_fin_estimada.value = tr.dataset.fin || '';
                    form.action = `${baseUrl}/${id}`;

                    modalEl.querySelector('.modal-title').textContent = 'Editar Obra';
                    modalEl.querySelector('.subtitle').textContent = 'Actualiza los datos de la obra';
                    btnSave.textContent = 'Guardar Cambios';
                    bootstrap.Modal.getOrCreateInstance(modalEl).show();
                    return;
                }

                if (delBtn) {
                    const id = delBtn.dataset.id || delBtn.closest('tr')?.dataset.id;
                    if (!id) return;
                    if (!confirm('¿Eliminar esta obra?')) return;

                    try {
                        const res = await fetch(`${baseUrl}/${id}`, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        });
                        if (!res.ok) {
                            alert('No se pudo eliminar.');
                            return;
                        }

                        const tr = rowFromId(id);
                        if (tr) {
                            tr.classList.add('fade-out');
                            setTimeout(() => tr.remove(), 250);
                        }
                        if (!tbody.children.length) {
                            const empty = document.createElement('tr');
                            empty.className = 'empty';
                            empty.innerHTML =
                                `<td colspan="6" class="soft" style="padding:18px">Sin obras registradas.</td>`;
                            tbody.appendChild(empty);
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Error inesperado al eliminar.');
                    }
                }
            });

            // Guardar (crear/editar)
            btnSave.addEventListener('click', async () => {
                clearErrors();
                btnSave.disabled = true;
                try {
                    const fd = new FormData(form);
                    const headers = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    };
                    let method = 'POST';
                    if (mode === 'update') {
                        fd.append('_method', 'PUT');
                    }

                    const res = await fetch(form.action, {
                        method,
                        headers,
                        body: fd
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        setErrors(data.errors || {});
                        btnSave.disabled = false;
                        return;
                    }
                    if (!res.ok) {
                        console.error('HTTP', res.status);
                        alert('No se pudo guardar.');
                        btnSave.disabled = false;
                        return;
                    }

                    const {
                        obra: o
                    } = await res.json();

                    if (mode === 'create') {
                        const tr = document.createElement('tr');
                        tr.dataset.id = o.id;
                        tr.dataset.inicio = o.fecha_inicio || '';
                        tr.dataset.fin = o.fecha_fin_estimada || '';
                        tr.classList.add('flash-new');
                        tr.innerHTML = `
          <td class="td-codigo"><span class="chip">${o.codigo ?? ''}</span></td>
          <td class="td-nombre" style="font-weight:800">${o.nombre ?? ''}</td>
          <td class="td-direccion"><i class="ti ti-map-pin"></i> ${o.direccion ?? ''}</td>
          <td class="soft">Sin asignar</td>
          <td class="td-fechas">
            <div><i class="ti ti-calendar"></i> Inicio: ${fmt(o.fecha_inicio)}</div>
            <div class="soft">Fin: ${fmt(o.fecha_fin_estimada)}</div>
          </td>
          <td>
            <div class="row-actions">
              <button class="btn-icon btn-edit-obra" data-id="${o.id}" title="Editar"><i class="ti ti-edit"></i></button>
              <button class="btn-icon btn-del-obra"  data-id="${o.id}" title="Eliminar"><i class="ti ti-trash"></i></button>
            </div>
          </td>`;
                        const empty = tbody.querySelector('.empty');
                        if (empty) empty.remove();
                        tbody.insertBefore(tr, tbody.firstChild);
                        setTimeout(() => tr.classList.remove('flash-new'), 1200);
                    } else {
                        const tr = rowFromId(o.id);
                        if (tr) {
                            tr.dataset.inicio = o.fecha_inicio || '';
                            tr.dataset.fin = o.fecha_fin_estimada || '';
                            tr.querySelector('.td-codigo').innerHTML =
                                `<span class="chip">${o.codigo ?? ''}</span>`;
                            tr.querySelector('.td-nombre').textContent = o.nombre ?? '';
                            tr.querySelector('.td-direccion').innerHTML =
                                `<i class="ti ti-map-pin"></i> ${o.direccion ?? ''}`;
                            tr.querySelector('.td-fechas').innerHTML = `
            <div><i class="ti ti-calendar"></i> Inicio: ${fmt(o.fecha_inicio)}</div>
            <div class="soft">Fin: ${fmt(o.fecha_fin_estimada)}</div>`;
                            tr.classList.add('flash-update');
                            setTimeout(() => tr.classList.remove('flash-update'), 1200);
                        }
                    }

                    bootstrap.Modal.getOrCreateInstance(modalEl).hide();
                    form.reset();
                    btnSave.disabled = false;

                } catch (e) {
                    console.error(e);
                    alert('Error inesperado.');
                    btnSave.disabled = false;
                }
            });
        })();
    </script>

    <script>
        (function() {
            const list = document.getElementById('lista-materiales');
            const form = document.getElementById('formMaterial');
            const btn = document.getElementById('btnGuardarMaterial');
            const modal = document.getElementById('modalMaterial');
            const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
            let mode = 'create',
                currentId = null;

            // Utilidad: render exacto de la tarjeta que pediste
            function renderCard(m) {
                const price = Number(m.precio_unitario || 0).toFixed(2);
                const card = document.createElement('div');
                card.className = 'm-item flash-new';
                card.dataset.id = m.id;
                card.dataset.nombre = m.nombre || '';
                card.dataset.codigo = m.codigo || '';
                card.dataset.descripcion = m.descripcion || '';
                card.dataset.unidad = m.unidad || '';
                card.dataset.stock_actual = m.stock_actual ?? 0;
                card.dataset.stock_minimo = m.stock_minimo ?? 0;
                card.dataset.precio = m.precio_unitario ?? 0;
                card.dataset.proveedor_id = m.proveedor_id ?? '';

                card.innerHTML = `
      <div>
        <div class="m-title">${m.codigo ?? ''} - ${m.nombre ?? ''}</div>
        <div class="m-meta">Stock: ${m.stock_actual ?? 0} ${m.unidad ?? ''} | Precio: S/ ${price}</div>
      </div>
      <div class="m-actions">
        <button type="button" class="btn-edit btn-edit-mat" title="Editar"><i class="ti ti-edit"></i></button>
        <button type="button" class="btn-del  btn-del-mat"  title="Eliminar"><i class="ti ti-trash"></i></button>
      </div>`;
                return card;
            }

            function clearErrors() {
                form.querySelectorAll('.form-control, .form-select').forEach(i => i.classList.remove('is-invalid'));
                form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            }

            function setErrors(errors) {
                Object.entries(errors).forEach(([f, msgs]) => {
                    const inp = form.querySelector(`[name="${f}"]`);
                    const fb = form.querySelector(`.invalid-feedback[data-err="${f}"]`);
                    if (inp) inp.classList.add('is-invalid');
                    if (fb) fb.textContent = Array.isArray(msgs) ? msgs[0] : msgs;
                });
            }

            // Abrir modal (crear)
            document.querySelector('[data-bs-target="#modalMaterial"]')?.addEventListener('click', () => {
                mode = 'create';
                currentId = null;
                form.reset();
                clearErrors();
                form.action = "{{ route('materiales.store') }}";
                modal.querySelector('.modal-title').textContent = 'Nuevo Material';
                modal.querySelector('.subtitle').textContent = 'Ingresa los datos del nuevo material';
                btn.textContent = 'Crear Material';
            });

            // Delegación: editar / eliminar sobre la lista
            list.addEventListener('click', async (ev) => {
                const edit = ev.target.closest('.btn-edit-mat');
                const del = ev.target.closest('.btn-del-mat');

                if (edit) {
                    const card = edit.closest('.m-item');
                    mode = 'update';
                    currentId = card.dataset.id;
                    clearErrors();

                    form.nombre.value = card.dataset.nombre || '';
                    form.codigo.value = card.dataset.codigo || '';
                    form.descripcion.value = card.dataset.descripcion || '';
                    form.unidad.value = card.dataset.unidad || 'm';
                    form.stock_actual.value = card.dataset.stock_actual || 0;
                    form.stock_minimo.value = card.dataset.stock_minimo || 0;
                    form.precio_unitario.value = card.dataset.precio || 0;
                    form.proveedor_id.value = card.dataset.proveedor_id || '';

                    form.action = "{{ url('/almacen/materiales') }}/" + currentId;
                    modal.querySelector('.modal-title').textContent = 'Editar Material';
                    modal.querySelector('.subtitle').textContent = 'Actualiza los datos del material';
                    btn.textContent = 'Guardar Cambios';
                    bootstrap.Modal.getOrCreateInstance(modal).show();
                    return;
                }

                if (del) {
                    const card = del.closest('.m-item');
                    const id = card.dataset.id;
                    if (!id) {
                        card.remove();
                        return;
                    } // demo
                    if (!confirm('¿Eliminar este material?')) return;

                    try {
                        const res = await fetch("{{ url('/almacen/materiales') }}/" + id, {
                            method: 'POST',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                _method: 'DELETE'
                            })
                        });
                        if (!res.ok) {
                            alert('No se pudo eliminar.');
                            return;
                        }
                        card.classList.add('fade-out');
                        setTimeout(() => card.remove(), 250);
                    } catch (e) {
                        console.error(e);
                        alert('Error inesperado al eliminar.');
                    }
                }
            });

            // Guardar (crear/editar)
            btn.addEventListener('click', async () => {
                clearErrors();
                btn.disabled = true;
                try {
                    const fd = new FormData(form);
                    const headers = {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    };
                    let method = 'POST';
                    if (mode === 'update') {
                        fd.append('_method', 'PUT');
                    }

                    const res = await fetch(form.action, {
                        method,
                        headers,
                        body: fd
                    });

                    if (res.status === 422) {
                        const data = await res.json();
                        setErrors(data.errors || {});
                        btn.disabled = false;
                        return;
                    }
                    if (!res.ok) {
                        console.error('HTTP', res.status);
                        alert('No se pudo guardar.');
                        btn.disabled = false;
                        return;
                    }

                    const {
                        material: m
                    } = await res.json();

                    if (mode === 'create') {
                        // si hay tarjetas "demo", puedes eliminar una para que no duplique visualmente
                        list.querySelectorAll('.m-item[data-demo="1"]').forEach(d => d.remove());
                        const card = renderCard(m);
                        list.insertBefore(card, list.firstChild);
                        setTimeout(() => card.classList.remove('flash-new'), 1200);
                    } else {
                        const card = list.querySelector(`.m-item[data-id="${m.id}"]`);
                        if (card) {
                            // actualiza datasets y texto visible
                            card.dataset.nombre = m.nombre || '';
                            card.dataset.codigo = m.codigo || '';
                            card.dataset.descripcion = m.descripcion || '';
                            card.dataset.unidad = m.unidad || '';
                            card.dataset.stock_actual = m.stock_actual ?? 0;
                            card.dataset.stock_minimo = m.stock_minimo ?? 0;
                            card.dataset.precio = m.precio_unitario ?? 0;
                            card.dataset.proveedor_id = m.proveedor_id ?? '';

                            card.querySelector('.m-title').textContent =
                                `${m.codigo ?? ''} - ${m.nombre ?? ''}`;

                            card.querySelector('.m-meta').textContent =
                                `Stock: ${m.stock_actual ?? 0} ${m.unidad ?? ''} | Precio: S/ ${Number(m.precio_unitario||0).toFixed(2)}`;

                            card.classList.add('flash-new');
                            setTimeout(() => card.classList.remove('flash-new'), 1200);
                        }
                    }

                    bootstrap.Modal.getOrCreateInstance(modal).hide();
                    form.reset();
                    btn.disabled = false;
                } catch (e) {
                    console.error(e);
                    alert('Error inesperado.');
                    btn.disabled = false;
                }
            });

        })();
    </script>

@endsection
