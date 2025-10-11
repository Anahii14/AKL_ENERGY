@extends('layouts.adminObraPlantilla')

@section('title', 'Empresa - Dashboard Admin de Obra')

@section('content')

    {{-- Mensajes --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="m-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <style>
        .card-np {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06)
        }

        .btn-akl {
            background: #ff6a3d;
            color: #fff;
            font-weight: 700;
            border: none;
            border-radius: 10px
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

        .chip-row {
            display: flex;
            gap: 10px;
            align-items: center;
            padding: 10px;
            background: #fff4ee;
            border: 1px solid #fde5db;
            border-radius: 10px
        }

        .chip-row .remove {
            border: none;
            background: #fca5a5;
            color: #fff;
            border-radius: 8px;
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center
        }

        .chip-row .remove:hover {
            filter: brightness(.95)
        }

        .section-title {
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 8px
        }

        .subtle {
            color: #64748b;
            font-weight: 600;
            margin: 0 0 14px
        }

        .req {
            color: #ef4444
        }
    </style>

    <div class="container-xxl">
        <div class="card-np p-4 p-md-5">
            <h3 class="section-title">Crear Nuevo Pedido de Materiales</h3>
            <p class="subtle">Completa los campos y agrega los materiales necesarios para tu obra.</p>

            {{-- Cambia action a la ruta POST --}}
            <form method="POST" action="{{ route('pedidos.store') }}" id="form-pedido">
                @csrf

                {{-- Obra / Fecha requerida --}}
                <div class="row g-3">
                    <div class="col-lg-8">
                        <label class="form-label">Obra <span class="req">*</span></label>
                        <select name="obra_id" class="form-select" required>
                            <option value="" selected disabled>Selecciona una obra</option>
                            @foreach ($obras as $obra)
                                <option value="{{ $obra->id }}" @selected(old('obra_id') == $obra->id)>
                                    {{ $obra->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <label class="form-label">Fecha Requerida <span class="req">*</span></label>
                        <input type="date" name="fecha_requerida" class="form-control"
                            value="{{ old('fecha_requerida') }}" required>
                    </div>
                </div>

                {{-- Observaciones --}}
                <div class="mt-3">
                    <label class="form-label">Observaciones</label>
                    <textarea name="observaciones" rows="3" class="form-control" placeholder="Detalles adicionales del pedido...">{{ old('observaciones') }}</textarea>
                </div>

                {{-- Materiales --}}
                <div class="mt-4">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <label class="form-label m-0">Materiales <span class="req">*</span></label>
                        <button type="button" id="btn-add" class="btn btn-soft">
                            <i class="ti ti-plus"></i> Agregar Material
                        </button>
                    </div>

                    {{-- Selección rápida --}}
                    <div class="row g-2 mt-2">
                        <div class="col-lg-9">
                            <select id="material-select" class="form-select">
                                <option value="" selected disabled>Selecciona material</option>
                                @foreach ($materiales as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-2">
                            <input id="material-cantidad" type="number" class="form-control" placeholder="Cantidad"
                                min="1">
                        </div>
                        <div class="col-lg-1 d-grid">
                            <button type="button" class="btn btn-danger" id="btn-fast-remove" disabled>
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Items agregados --}}
                    <div id="items" class="mt-3 d-grid gap-2">
                        {{-- JS inserta .chip-row aquí --}}
                    </div>
                </div>

                {{-- CTA --}}
                <div class="mt-4">
                    <button type="submit" class="btn btn-akl w-100 py-3">Crear Pedido</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBtn = document.getElementById('btn-add');
            const selMat = document.getElementById('material-select');
            const qtyInp = document.getElementById('material-cantidad');
            const items = document.getElementById('items');
            const fastRemove = document.getElementById('btn-fast-remove');

            if (!addBtn || !selMat || !qtyInp || !items || !fastRemove) return; // por si el DOM no coincide

            function toggleFastRemove() {
                fastRemove.disabled = !(selMat.value || qtyInp.value);
            }
            selMat.addEventListener('change', toggleFastRemove);
            qtyInp.addEventListener('input', toggleFastRemove);

            addBtn.addEventListener('click', () => {
                const id = selMat.value;
                const text = selMat.options[selMat.selectedIndex]?.text || '';
                const qty = parseFloat(qtyInp.value);

                if (!id) {
                    selMat.focus();
                    return;
                }
                if (!qty || qty <= 0) {
                    qtyInp.focus();
                    return;
                }

                // Si el material ya existe, SUMA cantidades en la misma fila
                const exist = items.querySelector(`[data-id="${id}"]`);
                if (exist) {
                    const qtyEl = exist.querySelector('.qty');
                    const newVal = parseFloat(qtyEl.value || 0) + qty;
                    qtyEl.value = newVal;
                    exist.querySelector('.qty-hidden').value = newVal;
                    exist.querySelector('.qty-label').textContent = newVal;
                } else {
                    const idx = items.children.length;
                    const row = document.createElement('div');
                    row.className = 'chip-row';
                    row.dataset.id = id;
                    row.innerHTML = `
        <div>
          <strong>${text}</strong>
          <div style="width:40px;height:3px;border-radius:999px;background:#ff6a3d;margin:6px 0;"></div>
          <small class="text-muted">Cantidad: <b class="qty-label">${qty}</b></small>
        </div>
        <div class="ms-auto d-flex align-items-center gap-2">
          <input type="number" min="1" class="form-control qty" style="max-width:110px;border-radius:10px;" value="${qty}">
          <button type="button" class="remove" title="Quitar"><i class="ti ti-trash"></i></button>
        </div>

        <input type="hidden" name="materials[${idx}][material_id]" value="${id}">
        <input type="hidden" name="materials[${idx}][cantidad]"   class="qty-hidden" value="${qty}">
      `;

                    const qtyEl = row.querySelector('.qty');
                    const qtyHidden = row.querySelector('.qty-hidden');
                    const qtyLabel = row.querySelector('.qty-label');

                    qtyEl.addEventListener('input', () => {
                        const v = Math.max(1, parseFloat(qtyEl.value || 1));
                        qtyEl.value = v;
                        qtyHidden.value = v;
                        qtyLabel.textContent = v;
                    });

                    row.querySelector('.remove').addEventListener('click', () => {
                        row.remove();
                        // Reindexa los names
                        [...items.children].forEach((el, i) => {
                            el.querySelector('[name$="[material_id]"]').setAttribute('name',
                                `materials[${i}][material_id]`);
                            el.querySelector('[name$="[cantidad]"]').setAttribute('name',
                                `materials[${i}][cantidad]`);
                        });
                    });

                    items.appendChild(row);
                }

                // Limpia selección rápida
                selMat.value = '';
                qtyInp.value = '';
                toggleFastRemove();
            });

            fastRemove.addEventListener('click', () => {
                selMat.value = '';
                qtyInp.value = '';
                toggleFastRemove();
            });

            document.getElementById('form-pedido').addEventListener('submit', (e) => {
                if (items.children.length === 0) {
                    e.preventDefault();
                    alert('Agrega al menos un material al pedido.');
                }
            });
        });
    </script>
@endpush
