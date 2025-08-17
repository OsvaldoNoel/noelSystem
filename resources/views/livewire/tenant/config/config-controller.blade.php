<div class="card position-relative border-0">
    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-1 border-info"
        style="pointer-events: none;"></div>

    <div class="card-header bg-black">
        <div class="text-center mb-3 mt-4">
            <h1 class="display-5 fw-bold">Panel de Configuración</h1>
            <p class="lead text-muted">
                Administra todas las configuraciones de tu aplicación desde este panel centralizado.
            </p>
        </div>
    </div>

    <div class="card-body bg-info-subtle">
        <div class="row g-4 p-5">
            @foreach ($configOptions as $option)
                <div class="col-md-6 col-lg-4">
                    <a href="{{ route($option['route']) }}"
                        class="config-item card text-decoration-none h-100 border-2 rounded shadow-sm hover-shadow transition-all hover-change-success bg-info-subtle position-relative overflow-hidden">
                        <!-- Icono como marca de agua -->
                        <div class="m-5">
                            <div class="position-absolute top-50 translate-middle-y opacity-25 text-end w-100 pe-40px"
                                style="font-size: 6rem;">
                                <i class="{{ $option['icon'] }}"
                                    style="color: var(--bs-{{ $option['hover_color'] }});"></i>
                            </div>
                        </div>

                        <!-- Contenido de la tarjeta -->
                        <div class="card text-info position-relative" style="z-index: 1;">
                            <h3 class="h4 card-title fw-bold mb-3 ms-4">
                                {{ $option['title'] }}
                            </h3>

                            <p class="card-text text-white mb-4 ms-4">
                                {{ $option['description'] }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Transiciones y efectos hover - Solo para items de configuración */
        .config-item {
            transition: all 0.3s ease;
        }

        .config-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Efecto de cambio a success en hover */
        .config-item:hover {
            border-color: var(--bs-success) !important;
            background-color: var(--bs-success-bg-subtle) !important;
        }

        .config-item:hover .card-body {
            color: var(--bs-success) !important;
        }

        .config-item:hover .card-title {
            color: var(--bs-purple) !important;
        }

        .config-item:hover .card-text {
            color: var(--bs-info-text-emphasis) !important;
        }

        /* Efecto de borde animado - Solo para items de configuración */
        .config-item:hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--bs-success);
            animation: borderGrow 0.3s ease-out forwards;
            z-index: 1;
        }

        @keyframes borderGrow {
            0% {
                width: 0;
            }

            100% {
                width: 100%;
            }
        }
    </style>
@endpush
