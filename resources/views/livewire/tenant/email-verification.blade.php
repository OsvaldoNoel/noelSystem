<div>
    <div class="card d-flex position-relative bg-info-subtle rounded-4 border-0">
        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-1 border-primary"
            style="pointer-events: none; z-index: 1;"></div>

        <div
            class="card-header bg-black text-white rounded-top-4 h-50px align-items-center d-flex justify-content-center">
            <h4 class="mb-0">
                <i class="fas fa-envelope me-2"></i> Verificación de Email Requerida
            </h4>
        </div>

        <div class="card-body">
            <div class="alert alert-info text-center rounded-3 mb-4">
                <i class="fas fa-info-circle me-2"></i>
                Hemos enviado un enlace de verificación a <strong>{{ auth()->user()->email }}</strong>.
                Por favor verifica tu email para acceder al sistema.
            </div>

            <div class="text-center">
                <button wire:click="resendVerification" class="btn btn-primary me-2">
                    <i class="fas fa-paper-plane me-2"></i> Reenviar Email
                </button>

                <a href="{{ route('logout') }}" class="btn btn-outline-danger"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
