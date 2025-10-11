{{-- resources/views/empresa/guias/index.blade.php --}}
@extends('layouts.adminObraPlantilla')

@section('title', 'Guías de Salida')

@section('content')
    <style>
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto
        }

        .h-title {
            display: flex;
            gap: 10px;
            align-items: center;
            font-weight: 800;
            color: #0f172a
        }

        .h-sub {
            color: #64748b;
            font-weight: 600;
            margin: 4px 0 18px
        }

        .card-np {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(2, 6, 23, .06)
        }

        .guide {
            position: relative;
            padding: 20px;
            border: 1px solid #eef2f7;
            border-radius: 12px;
            background: #fff
        }

        .guide+.guide {
            margin-top: 16px
        }

        .guide:hover {
            box-shadow: 0 6px 20px rgba(2, 6, 23, .05)
        }

        .guide-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a
        }

        .meta {
            margin-top: 6px
        }

        .meta small {
            display: block;
            color: #64748b;
            font-weight: 600
        }

        .ok-line {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 8px;
            font-weight: 700
        }

        .ok-line i {
            font-size: 16px
        }

        .ok-line a {
            color: #16a34a;
            text-decoration: none;
            border-bottom: 1px dotted #16a34a
        }

        .ok-line a:hover {
            opacity: .9
        }
    </style>

    <div class="container-xxl page-wrap">
        <div class="card-np p-4 p-md-5">

            <h3 class="h-title">
                <i class="ti ti-truck-delivery"></i> Guías de Salida
            </h3>
            <p class="h-sub">Revisa las guías de salida de tus pedidos</p>

            {{-- Guía ejemplo 1 --}}
            <div class="guide">
                <div class="guide-name">Guía G-82567565</div>
                <div class="meta">
                    <small><strong>Obra:</strong> Chupaca</small>
                </div>
                <div class="meta">
                    <small><strong>Fecha de emisión:</strong> 10/2/2025</small>
                </div>
            </div>

            {{-- Guía ejemplo 2 --}}
            <div class="guide">
                <div class="guide-name">Guía G-82567566</div>
                <div class="meta">
                    <small><strong>Obra:</strong> Junín</small>
                </div>
                <div class="meta">
                    <small><strong>Fecha de emisión:</strong> 12/2/2025</small>
                </div>
                <div class="ok-line">
                    <i class="ti ti-check" style="color:#16a34a"></i>
                    <a href="#">Ver detalles de la guía</a>
                </div>
            </div>

        </div>
    </div>
@endsection
