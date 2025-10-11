{{-- resources/views/layouts/cliente.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', 'Dashboard Almacenero')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Mantis Bootstrap 5 Admin Template">
    <meta name="keywords" content="Mantis, Bootstrap 5, Admin Dashboard">
    <meta name="author" content="CodedThemes">
    <link rel="icon" href="{{ url('assets/images/logoAKL-512.png') }}" type="image/png">


    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap"
        id="main-font-link">
    <link rel="stylesheet" href="{{ url('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ url('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ url('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ url('assets/fonts/material.css') }}">
    <link rel="stylesheet" href="{{ url('assets/css/style.css') }}" id="main-style-link">
    <link rel="stylesheet" href="{{ url('assets/css/style-preset.css') }}">

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

        .hero-card {
            background: #fff4ee;
            border: 1px solid #fde5db;
            border-radius: 14px;
            padding: 24px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06);
        }

        .chip {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            background: #1e40af;
            color: #e2e8f0;
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .hero-head {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .soft-badge {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: grid;
            place-items: center;
            background: #ffd7c8;
            color: #ff6a3d;
        }

        .hero-title {
            margin: 0;
            font-size: 42px;
            line-height: 1.1;
            color: #0f172a;
            font-weight: 800;
        }

        .hero-ruc {
            color: #64748b;
            margin: 8px 0 8px;
            font-weight: 600;
        }

        .hero-sub {
            color: #334155;
            margin: 0;
        }

        /* Contact cards */
        .info-grid {
            margin-top: 16px;
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(12, 1fr);
        }

        .info-card {
            grid-column: span 4 / span 4;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 12px rgba(2, 6, 23, .03);
        }

        .info-ico {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #fff3ee;
            color: #ff6a3d;
            display: grid;
            place-items: center;
            flex: 0 0 auto;
        }

        .info-text small {
            display: block;
            color: #64748b;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .info-text b {
            color: #0f172a;
        }

        /* Misión / Visión */
        .two-col {
            margin-top: 16px;
            display: grid;
            gap: 16px;
            grid-template-columns: repeat(12, 1fr);
        }

        .section-card {
            grid-column: span 6 / span 6;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 4px 12px rgba(2, 6, 23, .03);
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0 0 8px;
        }

        .section-title .ico {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #ffd7c8;
            color: #ff6a3d;
            display: grid;
            place-items: center;
        }

        .section-title h3 {
            margin: 0;
            font-size: 18px;
            color: #0f172a;
            font-weight: 800;
        }

        .section-body {
            color: #334155;
        }

        .why-card {
            margin-top: 16px;
            background: #fff4ee;
            border: 1px solid #fde5db;
            border-radius: 12px;
            padding: 18px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06);
        }

        .why-head {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 8px;
        }

        .why-head .ico {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: #ffd7c8;
            color: #ff6a3d;
            display: grid;
            place-items: center;
        }

        .why-head h3 {
            margin: 0;
            font-size: 22px;
            color: #0f172a;
            font-weight: 800;
        }

        .why-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(12, 1fr);
            margin-top: 10px;
        }

        .why-item {
            grid-column: span 4 / span 4;
        }

        .why-item h4 {
            margin: 0 0 6px;
            font-size: 16px;
            color: #0f172a;
            font-weight: 800;
        }

        .why-underline {
            width: 60px;
            height: 4px;
            border-radius: 999px;
            background: #ff6a3d;
            margin: 8px 0;
        }

        .why-item p {
            margin: 0;
            color: #334155;
        }

        @media (max-width:1024px) {
            .info-card {
                grid-column: span 6 / span 6;
            }

            .section-card {
                grid-column: span 12 / span 12;
            }

            .why-item {
                grid-column: span 6 / span 6;
            }

            .hero-title {
                font-size: 34px;
            }
        }

        @media (max-width:640px) {

            .info-card,
            .why-item {
                grid-column: span 12 / span 12;
            }

            .hero-title {
                font-size: 28px;
            }
        }

        .pc-caption:first-child {
            display: flex !important;
        }
    </style>

    @stack('styles')
</head>

<body data-pc-preset="preset-1" data-pc-direction="ltr" data-pc-theme="light">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>

    <nav class="pc-sidebar">
        <div class="navbar-wrapper">
            <div class="m-header" style="background:#fff; padding:12px;">
                <a href="{{ route('almacen.empresa') }}" class="b-brand brand-akl">
                    <span class="akl-badge">AKL</span>
                    <span class="akl-text">
                        <span class="title">AKL Energy Hub</span>
                        <span class="subtitle">Sistema de Gestión Operacional</span>
                    </span>
                </a>
            </div>


            <div class="navbar-content">
                <ul class="pc-navbar">

                    <li class="pc-item pc-caption">

                        <span class="pc-mtext" style="font-weight:800; font-size:15px; color:#0f172a;">
                            Dashboard Almacenero
                        </span>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('almacen.empresa') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-building"></i></span>
                            <span class="pc-mtext">Empresa</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('almacen.pedidos') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-shopping-cart"></i></span>
                            <span class="pc-mtext">Pedidos</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('almacen.cotizaciones') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-file-invoice"></i></span>
                            <span class="pc-mtext">Cotizaciones</span>
                        </a>
                    </li>

                    <li class="pc-item">
                        <a href="{{ route('almacen.usuarios') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-users"></i></span>
                            <span class="pc-mtext">Usuarios</span>
                        </a>
                    </li>


                    <li class="pc-item">
                        <a href="{{ route('almacen.guias_orden') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-clipboard"></i></span>
                            <span class="pc-mtext">Guías/Órdenes</span>
                        </a>
                    </li>


                    <li class="pc-item">
                        <a href="{{ route('almacen.gestion') }}" class="pc-link">
                            <span class="pc-micon"><i class="ti ti-settings"></i></span>
                            <span class="pc-mtext">Gestion</span>
                        </a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    <header class="pc-header">
        <div class="header-wrapper">
            <!-- [Mobile Media Block] start -->
            <div class="me-auto pc-mob-drp">
                <ul class="list-unstyled">
                    <li class="pc-h-item pc-sidebar-collapse">
                        <a href="#" class="pc-head-link ms-0" id="sidebar-hide">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="pc-h-item pc-sidebar-popup">
                        <a href="#" class="pc-head-link ms-0" id="mobile-collapse">
                            <i class="ti ti-menu-2"></i>
                        </a>
                    </li>
                    <li class="dropdown pc-h-item d-inline-flex d-md-none">
                        <a class="pc-head-link dropdown-toggle arrow-none m-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-search"></i>
                        </a>
                        <div class="dropdown-menu pc-h-dropdown drp-search">
                            <form class="px-3">
                                <div class="form-group mb-0 d-flex align-items-center">
                                    <i data-feather="search"></i>
                                    <input type="search" class="form-control border-0 shadow-none"
                                        placeholder="Search here. . .">
                                </div>
                            </form>
                        </div>
                    </li>
                    <li class="pc-h-item d-none d-md-inline-flex">
                        <form class="header-search">
                            <i data-feather="search" class="icon-search"></i>
                            <input type="search" class="form-control" placeholder="Search here. . .">
                        </form>
                    </li>
                </ul>
            </div>
            <!-- [Mobile Media Block end] -->
            <div class="ms-auto">
                <ul class="list-unstyled">

                    <li class="dropdown pc-h-item">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <i class="ti ti-mail"></i>
                        </a>

                    </li>

                    <li class="dropdown pc-h-item header-user-profile">
                        <a class="pc-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                            href="#" role="button" aria-haspopup="false" data-bs-auto-close="outside"
                            aria-expanded="false">
                            <i class="ti ti-user" style="font-size:18px; margin-right:6px;"></i>
                            <span><b>{{ Auth::user()->name }}</b> ({{ Auth::user()->role ?? 'Cliente' }})</span>
                        </a>

                        <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex mb-1 align-items-center">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="user-avtar wid-35 d-flex align-items-center justify-content-center rounded-circle bg-light">
                                            <i class="ti ti-user" style="font-size:20px; color:#64748b;"></i>
                                        </div>
                                    </div>

                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ Auth::user()->name }}</h6>
                                        <span>{{ Auth::user()->role ?? 'Cliente' }}</span>
                                    </div>

                                    {{-- Botón salir (POST logout) --}}
                                    <form method="POST" action="{{ route('logout') }}" class="ms-auto">
                                        @csrf
                                        <button type="submit" class="pc-head-link bg-transparent border-0 p-0"
                                            title="Salir">
                                            <i class="ti ti-power text-danger"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <ul class="nav drp-tabs nav-fill nav-tabs" id="mydrpTab" role="tablist">

                            </ul>

                            <div class="tab-content" id="mysrpTabContent">
                                {{-- PERFIL --}}
                                <div class="tab-pane fade show active" id="drp-tab-1" role="tabpanel"
                                    aria-labelledby="drp-t1" tabindex="0">
                                    <a href="#!" class="dropdown-item"><i
                                            class="ti ti-edit-circle"></i><span>Editar perfil</span></a>
                                    <a href="#!" class="dropdown-item"><i class="ti ti-user"></i><span>Ver
                                            perfil</span></a>
                                    <a href="#!" class="dropdown-item"><i
                                            class="ti ti-clipboard-list"></i><span>Datos personales</span></a>
                                    <a href="#!" class="dropdown-item"><i
                                            class="ti ti-wallet"></i><span>Facturación</span></a>
                                </div>

                                {{-- CONFIGURACIÓN --}}
                                <div class="tab-pane fade" id="drp-tab-2" role="tabpanel" aria-labelledby="drp-t2"
                                    tabindex="0">
                                    <a href="#!" class="dropdown-item"><i
                                            class="ti ti-help"></i><span>Soporte</span></a>
                                    <a href="#!" class="dropdown-item"><i class="ti ti-lock"></i><span>Centro de
                                            privacidad</span></a>
                                    <a href="#!" class="dropdown-item"><i
                                            class="ti ti-messages"></i><span>Comentarios</span></a>
                                    <a href="#!" class="dropdown-item"><i
                                            class="ti ti-list"></i><span>Historial</span></a>
                                </div>
                            </div>
                        </div>
                    </li>

                </ul>
            </div>

        </div>
    </header>

    {{-- AQUÍ SE INYECTA EL CONTENIDO DE CADA MÓDULO --}}
    <div class="pc-container">
        <div class="pc-content">
            @yield('content')
        </div>
    </div>

    <script src="{{ url('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ url('assets/js/pages/dashboard-default.js') }}"></script>
    <script src="{{ url('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ url('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ url('assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ url('assets/js/pcoded.js') }}"></script>
    <script src="{{ url('assets/js/plugins/feather.min.js') }}"></script>

    <script>
        layout_change('light');
    </script>
    <script>
        change_box_container('false');
    </script>
    <script>
        layout_rtl_change('false');
    </script>
    <script>
        preset_change("preset-1");
    </script>
    <script>
        font_change("Public-Sans");
    </script>

    @stack('scripts')
</body>

</html>
