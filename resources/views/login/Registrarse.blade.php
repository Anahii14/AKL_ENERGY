<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear Cuenta de Cliente | AKL Energy Hub</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg1: #2764d7;
            --bg2: #a9c3ff;
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #ff6a3d;
            --primary-dark: #f2572a;
            --input: #f8fafc;
            --border: #e5e7eb;
            --ring: #3b82f6;
            --shadow: 0 30px 50px rgba(2, 6, 23, .18);
            --radius: 16px;
            --panel: #f3f6fb;
        }

        * {
            box-sizing: border-box
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, Segoe UI, Roboto, Helvetica, Arial, sans-serif;
            color: var(--text);
            background: radial-gradient(1200px 600px at 50% -200px, rgba(255, 255, 255, .8), transparent),
                linear-gradient(180deg, var(--bg1), var(--bg2)) fixed;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .card {
            width: 100%;
            max-width: 680px;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 28px 26px;
            border: 1px solid rgba(255, 255, 255, .3);
        }

        .logo {
            width: 68px;
            height: 68px;
            border-radius: 18px;
            margin: 0 auto 10px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, #ffcf7a, #ff6a3d);
            box-shadow: 0 10px 24px rgba(255, 106, 61, .35);
        }

        .logo svg {
            width: 36px;
            height: 36px
        }

        h1 {
            margin: 8px 0 6px;
            text-align: center;
            font-size: 30px;
            font-weight: 800;
            background: linear-gradient(90deg, #ff8a4c, #ff6a3d);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }

        .subtitle {
            margin: 0 0 20px;
            text-align: center;
            color: var(--muted)
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
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700
        }

        .input,
        .select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--input);
            outline: none;
            font-size: 14px;
            transition: .15s;
        }

        .input:focus,
        .select:focus {
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
            background: #fff
        }

        .panel {
            background: var(--panel);
            border: 1px solid #e6ecf7;
            border-radius: 14px;
            padding: 14px;
        }

        .panel-title {
            font-weight: 800;
            color: #334155;
            margin: 0 0 10px;
            font-size: 14px
        }

        .btn {
            width: 100%;
            border: 0;
            border-radius: 12px;
            padding: 13px 14px;
            font-weight: 800;
            font-size: 15px;
            background: var(--primary);
            color: #fff;
            cursor: pointer;
            transition: .15s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:hover {
            background: var(--primary-dark)
        }

        .links {
            text-align: center;
            margin-top: 14px;
            font-size: 14px
        }

        .links a {
            color: #0ea5e9;
            text-decoration: none
        }

        .links a:hover {
            text-decoration: underline
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
</head>

<body>
    <main class="card">
        <div class="logo">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1>Crear Cuenta de Cliente</h1>
        <p class="subtitle">Completa tus datos para registrarte como cliente</p>

        {{-- errores de validación --}}
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

        <form method="POST" action="{{ route('auth.register') }}">
            @csrf
            {{-- rol fijo = Cliente --}}
            <input type="hidden" name="role" value="Cliente">

            <div class="grid">
                <div>
                    <label for="name">Nombre Completo *</label>
                    <input id="name" name="name" type="text" class="input" placeholder="Ingrese nombre y apellido"
                        value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="email">Correo Electrónico *</label>
                    <input id="email" name="email" type="email" class="input"
                        placeholder="Ingrese su correo" value="{{ old('email') }}" required>
                </div>

                <div class="full">
                    <label for="phone">Teléfono</label>
                    <input id="phone" name="phone" type="text" class="input" placeholder="Ingrese su teléfono"
                        value="{{ old('phone') }}">
                </div>

                <div>
                    <label for="password">Contraseña *</label>
                    <input id="password" name="password" type="password" class="input" placeholder="••••••••"
                        required>
                </div>

                <div>
                    <label for="password_confirmation">Confirmar Contraseña *</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="input"
                        placeholder="Repite tu contraseña" required>
                </div>

                {{-- Panel de seguridad (opcional pero recomendado, coincide con tu controlador actual) --}}
                <div class="full panel">
                    <div class="panel-title">Pregunta de Seguridad (para recuperar contraseña)</div>
                    <div class="grid">
                        <div class="full">
                            <label for="security_question">Pregunta *</label>
                            <select id="security_question" name="security_question" class="select" required>
                                <option value="" disabled {{ old('security_question') ? '' : 'selected' }}>
                                    Selecciona una pregunta</option>
                                <option value="¿Cuál es tu ciudad natal?"
                                    {{ old('security_question') == '¿Cuál es tu ciudad natal?' ? 'selected' : '' }}>
                                    ¿Cuál es tu ciudad natal?
                                </option>
                                <option value="¿Cuál es el nombre de tu primera mascota?"
                                    {{ old('security_question') == '¿Cuál es el nombre de tu primera mascota?' ? 'selected' : '' }}>
                                    ¿Cuál es el nombre de tu primera mascota?
                                </option>
                                <option value="¿Cuál es tu comida favorita?"
                                    {{ old('security_question') == '¿Cuál es tu comida favorita?' ? 'selected' : '' }}>
                                    ¿Cuál es tu comida favorita?
                                </option>
                                <option value="¿Cuál es el nombre de tu mejor amigo de infancia?"
                                    {{ old('security_question') == '¿Cuál es el nombre de tu mejor amigo de infancia?' ? 'selected' : '' }}>
                                    ¿Cuál es el nombre de tu mejor amigo de infancia?
                                </option>
                            </select>
                        </div>

                        <div class="full">
                            <label for="security_answer">Respuesta *</label>
                            <input id="security_answer" name="security_answer" type="text" class="input"
                                placeholder="Escribe tu respuesta" value="{{ old('security_answer') }}" required>
                        </div>
                    </div>
                </div>

                <div class="full" style="margin-top:6px">
                    <button type="submit" class="btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        Crear Cuenta de Cliente
                    </button>
                </div>
            </div>
        </form>

        <div class="links">
            ¿Ya tienes cuenta? <a href="{{ route('auth.login.form') }}">Inicia sesión</a>
        </div>
    </main>
</body>

</html>
