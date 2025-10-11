@extends('layouts.adminObraPlantilla')

@section('title', 'Empresa - Dashboard Admin de Obra')

@section('content')
    <div class="pc-content">

        <section class="hero-card">
            <span class="chip">Sobre Nosotros</span>
            <div class="hero-head">
                <div class="soft-badge">
                    <i class="ti ti-building" style="font-size:26px;"></i>
                </div>
                <div>
                    <h1 class="hero-title">Constructora AKL</h1>
                    <div class="hero-ruc">RUC: 20123456789</div>
                    <p class="hero-sub">Empresa líder en construcción y alquiler de equipos</p>
                </div>
            </div>
        </section>

        <section class="info-grid">
            <div class="info-card">
                <div class="info-ico"><i class="ti ti-phone" style="font-size:20px;"></i></div>
                <div class="info-text">
                    <small>Teléfono</small>
                    <b>+51 999 888 777</b>
                </div>
            </div>

            <div class="info-card">
                <div class="info-ico"><i class="ti ti-mail" style="font-size:20px;"></i></div>
                <div class="info-text">
                    <small>Email</small>
                    <b>contacto@constructoraabc.com</b>
                </div>
            </div>

            <div class="info-card">
                <div class="info-ico"><i class="ti ti-map-pin" style="font-size:20px;"></i></div>
                <div class="info-text">
                    <small>Ubicación</small>
                    <b>Av. Principal 123, Lima, Perú</b>
                </div>
            </div>
        </section>

        <section class="two-col">
            <article class="section-card">
                <div class="section-title">
                    <div class="ico"><i class="ti ti-target" style="font-size:20px;"></i></div>
                    <h3>Nuestra Misión</h3>
                </div>
                <div class="section-body">
                    Proporcionar soluciones integrales de construcción con los más altos estándares de calidad.
                </div>
            </article>

            <article class="section-card">
                <div class="section-title">
                    <div class="ico"><i class="ti ti-eye" style="font-size:20px;"></i></div>
                    <h3>Nuestra Visión</h3>
                </div>
                <div class="section-body">
                    Ser la empresa constructora más confiable del país.
                </div>
            </article>
        </section>

        <section class="why-card">
            <div class="why-head">
                <div class="ico"><i class="ti ti-gift" style="font-size:20px;"></i></div>
                <h3>¿Por qué elegirnos?</h3>
            </div>

            <div class="why-grid">
                <div class="why-item">
                    <h4>Experiencia Comprobada</h4>
                    <div class="why-underline"></div>
                    <p>Años de trayectoria en el sector de construcción e instalaciones industriales.</p>
                </div>

                <div class="why-item">
                    <h4>Equipo Profesional</h4>
                    <div class="why-underline"></div>
                    <p>Personal altamente calificado y comprometido con la excelencia.</p>
                </div>

                <div class="why-item">
                    <h4>Soluciones Integrales</h4>
                    <div class="why-underline"></div>
                    <p>Desde la planificación hasta la ejecución, cubrimos todas tus necesidades.</p>
                </div>
            </div>
        </section>

    </div>
@endsection
