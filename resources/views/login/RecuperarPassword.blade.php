<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar Contraseña | AKL Energy Hub</title>

  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <style>
    :root{
      --bg1:#0d3a8a;
      --bg2:#ccbfc7;
      --card:#ffffff;
      --text:#0f172a;
      --muted:#64748b;
      --primary:#ff6a3d;
      --primary-dark:#f2572a;
      --input:#f8fafc;
      --ring:#3b82f6;
      --border:#e5e7eb;
      --shadow: 0 20px 40px rgba(2,6,23,.15);
      --radius: 14px;
    }
    *{box-sizing:border-box} html,body{height:100%}
    body{
      margin:0; font-family:Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial;
      color:var(--text);
      background: linear-gradient(180deg, var(--bg1), var(--bg2)) fixed;
      display:grid; place-items:center; padding:32px 16px;
    }
    .card{width:100%; max-width:520px; background:var(--card); border-radius:var(--radius); box-shadow:var(--shadow); padding:32px}
    .logo{height:64px;width:64px;border-radius:16px;display:grid;place-items:center;margin-inline:auto;background:linear-gradient(135deg,#ffb347,#ff6a3d);color:#fff}
    .logo svg{width:34px;height:34px}
    h1{text-align:center;margin:16px 0 6px;font-size:26px;line-height:1.2;font-weight:700}
    .subtitle{color:var(--muted);text-align:center;margin:0 0 24px}
    .group{margin-bottom:16px}
    label{display:block;margin-bottom:8px;font-size:14px;font-weight:600}
    .input{width:100%;padding:12px 14px;border:1px solid var(--border);border-radius:10px;background:var(--input);outline:none;font-size:14px;transition:.15s}
    .input:focus{border-color:var(--ring); box-shadow:0 0 0 3px rgba(59,130,246,.15); background:#fff}
    .btn{width:100%;border:0;border-radius:10px;padding:12px 14px;font-weight:700;font-size:15px;cursor:pointer;background:var(--primary);color:#fff;transition:.15s}
    .btn:hover{background:var(--primary-dark)}
    .back{display:inline-flex;align-items:center;gap:8px;color:#0ea5e9;text-decoration:none;font-weight:500;margin-top:12px}
    .back:hover{text-decoration:underline}
    .alert{background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius:10px;padding:10px 12px;font-size:14px;margin-bottom:14px}
    @media (max-width:480px){.card{padding:22px}}
  </style>
</head>
<body>

  <main class="card">
    <!-- Logo -->
    <div class="logo" aria-hidden="true">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M13 3L4 14h6l-1 7 9-11h-6l1-7z" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>

    <h1>Recuperar Contraseña</h1>
    <p class="subtitle">Ingresa tu correo electrónico</p>

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

    {{-- Mensaje de estado (por ejemplo, “te enviamos un correo…”) --}}
    @if (session('status'))
      <div class="alert" style="background:#ecfdf5;color:#065f46;border-color:#a7f3d0;">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('auth.password.email') }}" novalidate>
      @csrf

      <div class="group">
        <label for="email">Correo Electrónico</label>
        <input id="email" name="email" type="email" class="input" placeholder="tu@email.com"
               value="{{ old('email') }}" required autocomplete="email" autofocus>
      </div>

      <button type="submit" class="btn">Continuar</button>
    </form>

    <div style="text-align:center">
      <a class="back" href="{{ route('auth.login.form') }}">
        <span>←</span> Volver al inicio de sesión
      </a>
    </div>
  </main>

</body>
</html>
