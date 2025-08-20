<div>

    <div class="card d-flex position-relative bg-info-subtle rounded-4 border-0" style="min-height: 80vh;">
        <!-- Borde decorativo -->
        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-1 border-primary"
            style="pointer-events: none; z-index: 1;"></div>

        <!-- Badge "en construcción" en la parte superior -->
        <div class="card-body text-center align-content-center position-relative">
            <div class="alert alert-success mx-5 fs-2 rounded-2">
                <i class="fas fa-hammer me-2"></i>
                Sitio en construcción
            </div>

        </div>
        <!-- Icono de fondo (home) -->
        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
            style="z-index: 2;">
            <i class="fa-solid fa-house text-primary" style="opacity: 0.15; font-size: min(25vw, 25rem);"></i>
        </div>

        <!-- Contenido principal superpuesto -->
        <div class="card-body d-flex flex-column justify-content-center align-items-center position-relative"
            style="z-index: 3;">

            <div class="mt-4">
                <button wire:click="sendTestEmail" wire:loading.attr="disabled" class="btn btn-outline-primary">
                    <span wire:loading.remove>Enviar correo de prueba</span>
                    <span wire:loading>
                        <i class="fas fa-spinner fa-spin"></i> Enviando...
                    </span>
                </button>

                <p class="mt-2 text-muted small">
                    Se enviará a: {{ Auth::user()->email }}
                </p>
            </div>


            <div class="text-center" style="transform: translateY(20%);">
                <!-- Grupo de iconos superpuestos al home -->
                <div class="position-relative mb-4">
                    <i class="fas fa-user-shield text-secodary" style="font-size: 9rem; position: relative;"></i>
                    <i class="fas fa-cogs text-secodary" style="font-size: 6rem; position: relative;"></i>
                </div>
            </div>

        </div>
    </div>


</div>
