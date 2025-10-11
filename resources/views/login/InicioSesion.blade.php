<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | AKL Energy Hub</title>

    <!-- Fuente -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-top: #4f86ff;
            /* azul vivo arriba */
            --bg-bottom: #c9e1ff;
            /* celeste suave abajo */
            --card: #ffffff;
            --text: #0f172a;
            --muted: #64748b;
            --primary: #ff6a3d;
            /* naranja botón */
            --primary-dark: #f2572a;
            --ring: #3b82f6;
            --border: #e5e7eb;
            --input: #f8fafc;
            --shadow: 0 24px 60px rgba(15, 23, 42, .18);
            --radius: 18px;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, "Helvetica Neue", Arial;
            color: var(--text);
            background: radial-gradient(1200px 600px at 50% -200px, rgba(255, 255, 255, .35), transparent 70%),
                linear-gradient(180deg, var(--bg-top), var(--bg-bottom)) fixed;
            display: grid;
            place-items: center;
            padding: 32px 16px;
        }

        .card {
            width: 100%;
            max-width: 520px;
            background: var(--card);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            padding: 36px 32px 28px;
        }

        /* Logo cuadrado con rayo */
        .logo {
            width: 68px;
            height: 68px;
            border-radius: 16px;
            margin: 6px auto 10px;
            display: grid;
            place-items: center;
            color: #fff;
            background: linear-gradient(135deg, #ffb347, var(--primary));
            filter: drop-shadow(0 8px 20px rgba(255, 106, 61, .35));
        }

        .logo svg {
            width: 34px;
            height: 34px
        }

        h1 {
            margin: 10px 0 6px;
            text-align: center;
            font-weight: 800;
            font-size: 28px;
            letter-spacing: .2px;
            color: var(--text);
        }

        /* “AKL Energy Hub” naranja como la imagen */
        .brand-accent {
            color: var(--primary)
        }

        .subtitle {
            margin: 0 0 22px;
            text-align: center;
            color: var(--muted);
            font-size: 14px;
            font-weight: 500;
        }

        .group {
            margin-bottom: 16px
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        .input {
            width: 100%;
            height: 44px;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--input);
            outline: none;
            font-size: 14px;
            transition: .15s border-color, .15s box-shadow, .15s background;
        }

        .input::placeholder {
            color: #94a3b8
        }

        .input:focus {
            background: #fff;
            border-color: var(--ring);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .15);
        }

        .btn {
            width: 100%;
            height: 46px;
            border: 0;
            border-radius: 10px;
            background: var(--primary);
            color: #fff;
            font-weight: 800;
            font-size: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: .15s background, .15s transform, .15s box-shadow;
            box-shadow: 0 10px 22px rgba(255, 106, 61, .25);
        }

        .btn:hover {
            background: var(--primary-dark)
        }

        .btn:active {
            transform: translateY(1px)
        }

        .links {
            text-align: center;
            margin-top: 12px;
            font-size: 14px;
        }

        .links a {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 600;
        }

        .links a:hover {
            text-decoration: underline
        }

        .divider {
            height: 1px;
            background: var(--border);
            margin: 20px 0 0;
        }

        /* Errores */
        .alert {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 14px;
            margin-bottom: 14px;
        }

        @media (max-width:480px) {
            .card {
                padding: 26px 22px 20px
            }

            h1 {
                font-size: 24px
            }
        }
    </style>
</head>

<body>
    <main class="card">
        <!-- Logo -->
        <div class="logo" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>

        <h1><span class="brand-accent">AKL Energy Hub</span></h1>
        <p class="subtitle">Ingresa a tu cuenta</p>

        {{-- Errores de validación --}}
        @if ($errors->any())
            <div class="alert">
                <ul style="margin:0;padding-left:18px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Mensaje flash --}}
        @if (session('status'))
            <div class="alert">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('auth.login') }}" novalidate>
            @csrf

            <div class="group">
                <label for="email">Correo Electrónico</label>
                <input id="email" name="email" type="email" class="input" placeholder="Ingresa tu correo"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>
            </div>

            <div class="group">
                <label for="password">Contraseña</label>
                <input id="password" name="password" type="password" class="input" placeholder="••••••••" required
                    autocomplete="current-password">
            </div>

            <button type="submit" class="btn">
                <!-- Icono rayo dentro del botón -->
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Iniciar Sesión
            </button>
        </form>

        <div class="links">
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
        </div>

        <div class="links">
            ¿No tienes cuenta?
            <a href="{{ route('auth.register.form') }}">Regístrate</a>
        </div>

        <div class="divider"></div>
    </main>
</body>

</html>
