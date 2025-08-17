<div>
    <div class="card-body position-relative" style="z-index: 2;">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-md-8 col-lg-6">
                <div class="card d-flex position-relative bg-info-subtle rounded-4 border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-1 border-primary"
                        style="pointer-events: none; z-index: 1;"></div>
                    <div
                        class="card-header bg-black text-white rounded-top-4 h-50px align-items-center d-flex justify-content-center">
                        <h4 class="mb-0">
                            <i class="fas fa-key me-2"></i> Cambio de contraseña requerido
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info text-center rounded-3 mb-4" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            Es tu primer acceso. Por seguridad, debes establecer una nueva contraseña para continuar.
                        </div>

                        <form wire:submit.prevent="updatePassword">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nueva contraseña</label>
                                    <input type="password" class="form-control" wire:model="password" required>
                                    @error('password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Confirmar contraseña</label>
                                    <input type="password" class="form-control" wire:model="password_confirmation"
                                        required>
                                </div>
                            </div>

                            <div class="form-text small mb-4">
                                <strong>Requisitos de la contraseña:</strong>
                                <ul class="mb-0 ps-3">
                                    <li>Mínimo 8 caracteres</li>
                                    <li>1 letra mayúscula (A-Z)</li>
                                    <li>1 letra minúscula (a-z)</li>
                                    <li>3 números no consecutivos</li>
                                </ul>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <a href="{{ route('logout') }}" class="btn btn-outline-danger w-100"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2"></i> Salir
                                    </a>
                                </div>
                                <div class="col-6">
                                    <button type="submit" class="btn btn-outline-primary w-100">
                                        <i class="fas fa-save me-2"></i> Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</div>
