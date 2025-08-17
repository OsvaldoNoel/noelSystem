<div class="card position-relative border-0">
    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
        style="pointer-events: none;"></div>

    <div class="card-header with-btn bg-black">
        <h4 class="ms-4 mt-3 text-white">Mi Perfil</h4>
    </div>

    <div class="card-body bg-info-subtle">
        <div class="row">
            <!-- Columna Izquierda - Foto -->
            <div class="col-md-4">
                <div class="text-center mb-4">
                    <!-- Foto de Perfil -->
                    <div class="position-relative d-inline-block" x-data="{ triggerFileInput() { this.$refs.fileInput.click() } }">
                        <!-- Imagen de perfil con transición -->
                        <img src="{{ $photoUrl }}" class="rounded-circle shadow transition-opacity duration-300"
                            width="200" height="200" alt="Foto de perfil"
                            onerror="this.src='{{ $defaultPhotoUrl }}'" wire:loading.class="opacity-50"
                            wire:target="photo,savePhoto">

                        <!-- Spinner para ambas acciones -->
                        <div wire:loading wire:target="photo,savePhoto"
                            class="position-absolute top-50 start-50 translate-middle">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Cargando...</span>
                            </div>
                        </div>

                        <!-- Botón para cambiar foto -->
                        <button class="btn btn-sm btn-outline-info position-absolute bottom-0 end-0 rounded-circle"
                            x-on:click="triggerFileInput">
                            <i class="fas fa-camera"></i>
                        </button>

                        <!-- Input oculto para subir foto -->
                        <input type="file" wire:model="photo" x-ref="fileInput" class="d-none" accept="image/*">
                    </div>

                    <!-- Sección para mostrar la vista previa y botones de acción -->
                    <div x-show="$wire.photo" x-transition class="mt-3">
                        <div class="d-flex flex-column">
                            <small class="text-muted">Formatos: JPG, PNG (Max 2MB)</small>

                            <!-- Mensaje de error debajo del texto formatos -->
                            @error('photo')
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @enderror

                            <div class="mt-2">
                                <button wire:click="savePhoto" wire:target="photo" wire:loading.attr="disabled"
                                    class="btn btn-sm btn-outline-success" @error('photo') disabled @enderror>
                                    <i class="fas fa-save me-1"></i> Guardar Foto
                                </button>
                                <button wire:click="$set('photo', null)" class="btn btn-sm btn-outline-primary ms-1">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha - Formularios -->
            <div class="col-md-8">
                <!-- Formulario de Datos Personales -->
                <div class="card d-flex position-relative border-0 mb-4">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-3 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-header rounded-top-3 bg-black text-white">
                        <h5 class="mb-0 ms-5">Datos Personales</h5>
                    </div>
                    <div class="card-body rounded-bottom-3">
                        <form wire:submit.prevent="saveProfile">
                            <div class="row">
                                <!-- Columna 1 -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" wire:model="name" class="form-control">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Columna 2 -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Apellido</label>
                                    <input type="text" wire:model="lastname" class="form-control">
                                    @error('lastname')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Columna 3 -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Teléfono</label>
                                    <input type="text" wire:model="phone" class="form-control">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Columna 1 - Segunda fila -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="email" class="form-control">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Columna 2 - Segunda fila -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Barrio</label>
                                    <input type="text" wire:model="barrio" class="form-control">
                                    @error('barrio')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Columna 3 - Segunda fila -->
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Ciudad</label>
                                    <input type="text" wire:model="city" class="form-control">
                                    @error('city')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Dirección - Ocupa todo el ancho -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" wire:model="address" class="form-control">
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-outline-info rounded">
                                    <i class="fas fa-save me-1"></i> Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Formulario para Cambiar Contraseña -->
                <div class="card d-flex position-relative border-0 mb-3">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-3 border border-3 border-black"
                        style="pointer-events: none;"></div>

                    <div class="card-header rounded-top-3 bg-black text-white">
                        <h5 class="mb-0">Cambiar Contraseña</h5>
                    </div>
                    <div class="card-body rounded-bottom-3">
                        <form wire:submit.prevent="updatePassword">
                            <div class="row">
                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Contraseña Actual</label>
                                    <input type="password" wire:model="current_password" class="form-control">
                                    @error('current_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="password" wire:model="password" class="form-control">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col-lg-4 col-md-6 mb-3">
                                    <label class="form-label">Confirmar Contraseña</label>
                                    <input type="password" wire:model="password_confirmation" class="form-control">
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button type="submit" class="btn btn-outline-info rounded">
                                    <i class="fas fa-key me-1"></i> Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
