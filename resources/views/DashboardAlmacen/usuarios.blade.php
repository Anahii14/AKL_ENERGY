@extends('layouts.almaceneroPlantilla')
@section('title', 'Gestión de Usuarios')

@section('content')
    <style>
        :root {
            --card: #fff;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #ff6a3d;
            --primary-dark: #f2572a;
            --input: #f8fafc;
            --border: #e5e7eb;
            --ring: #3b82f6;
            --shadow: 0 20px 40px rgba(2, 6, 23, .12)
        }

        .page {
            max-width: 1100px;
            margin: 0 auto
        }

        .title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 800;
            margin: 8px 0
        }

        .sub {
            color: var(--muted);
            margin: 0 0 14px
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow);
            padding: 18px
        }

        .note {
            background: #fff7ed;
            border: 1px solid #fed7aa;
            color: #9a3412;
            border-radius: 10px;
            padding: 10px 12px;
            font-weight: 600;
            margin-bottom: 12px
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px
        }

        .full {
            grid-column: 1 / -1
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 700
        }

        .input,
        .select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--input);
            outline: none;
            font-size: 14px;
            transition: .15s
        }

        .input:focus,
        .select:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
            background: #fff
        }

        .help {
            font-size: 12px;
            color: var(--muted);
            margin-top: 6px
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 10px;
            padding: 12px 14px;
            font-weight: 800;
            font-size: 15px;
            background: var(--primary);
            color: #fff;
            cursor: pointer;
            transition: .15s
        }

        .btn:hover {
            background: var(--primary-dark)
        }

        .errors {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 12px
        }

        @media (max-width:576px) {
            .grid {
                grid-template-columns: 1fr
            }
        }
    </style>

    <div class="page">
        <h2 class="title"><i class="ti ti-users-cog"></i> Gestión de Usuarios del Sistema</h2>
        <p class="sub">Crea cuentas para administradores de obra, encargados de almacén y gerentes generales</p>

        @if ($errors->any())
            <div class="errors">
                <strong>Revisa los campos:</strong>
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('ok'))
            <div class="alert alert-success">{{ session('ok') }}</div>
        @endif

        <div class="card">
            <div class="note">
                <i class="ti ti-shield-lock"></i> Creación segura mediante backend: las cuentas se crean en el servidor.
            </div>

            <form method="POST" action="{{ route('almacen.usuarios.store') }}">
                @csrf
                <div class="grid">
                    <div class="full">
                        <label for="name">Nombre Completo *</label>
                        <input id="name" name="name" type="text" class="input" placeholder="Juan Pérez"
                            value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label for="email">Correo Electrónico *</label>
                        <input id="email" name="email" type="email" class="input" placeholder="usuario@empresa.com"
                            value="{{ old('email') }}" required>
                        <div class="help">Será el usuario para iniciar sesión.</div>
                    </div>

                    <div>
                        <label for="phone">Teléfono</label>
                        <input id="phone" name="phone" type="text" class="input" placeholder="Opcional"
                            value="{{ old('phone') }}">
                    </div>

                    <div>
                        <label for="password">Contraseña *</label>
                        <input id="password" name="password" type="password" class="input"
                            placeholder="Mínimo 8 caracteres" required>
                        <div class="help">Usa al menos 8 caracteres.</div>
                    </div>

                    <div>
                        <label for="role">Rol del Usuario *</label>
                        <select id="role" name="role" class="select" required>
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Selecciona un rol</option>
                            <option value="Administrador de Obra"
                                {{ old('role') == 'Administrador de Obra' ? 'selected' : '' }}>Administrador de Obra</option>
                            <option value="Encargado de Almacén"
                                {{ old('role') == 'Encargado de Almacén' ? 'selected' : '' }}>Encargado de Almacén</option>
                            <option value="Gerente General" {{ old('role') == 'Gerente General' ? 'selected' : '' }}>Gerente
                                General</option>
                        </select>
                        <div class="help">Controla el acceso a módulos.</div>
                    </div>

                    <div class="full" style="margin-top:6px">
                        <button type="submit" class="btn"><i class="ti ti-user-plus"></i> Crear Usuario del
                            Sistema</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
