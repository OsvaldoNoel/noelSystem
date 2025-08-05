<div wire:ignore.self class="modal fade" id="addModal{{ $componentName }}" data-bs-keyboard="false"
    data-bs-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content position-relative border-0">
            <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
                style="pointer-events: none;"></div>

            <div class="modal-header rounded-top-4">
                <h5 id="modalTitle" class="modal-title text-success">Crear {{ $componentName }}</h5>
                <button type="button" class="btn-close text-info" wire:click.prevent="cancel()"
                    wire:click="$dispatch('closeModal')"></button>
            </div>
            <div class="modal-body bg-info-subtle">
                <!-- Formulario de creación -->
                <form>
                    <input type="text" class="d-none" id="name2_add" autocomplete="off">

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="ci_add">Ced. de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-id-card"></i></span>
                                <input wire:model="ci" type="number" onkeydown="filtrarTeclas()"
                                    onkeyup="quitarCeroIzq()" class="form-control text-theme ci-input" id="ci_add"
                                    placeholder="Ced. de Identidad" autocomplete="nop" data-next="email_add">
                            </div>
                            @error('ci')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                            @error('username')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="email_add">e-mail</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-envelope"></i></span>
                                <input wire:model="email" type="email" class="form-control text-theme bg-secondary-subtle" id="email_add"
                                    placeholder="ejemplo@gmail.com" autocomplete="nop" disabled data-next="name_add">
                            </div>
                            @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="name_add">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                                <input wire:model="name" type="text" class="form-control text-theme bg-secondary-subtle" id="name_add"
                                    placeholder="Nombre" autocomplete="nop" disabled data-next="lastname_add">
                            </div>
                            @error('name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="lastname_add">Apellido</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                                <input wire:model="lastname" type="text" class="form-control text-theme bg-secondary-subtle"
                                    id="lastname_add" placeholder="Apellido" autocomplete="nop" disabled
                                    data-next="phone_add">
                            </div>
                            @error('lastname')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="phone_add">Telefono</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-phone"></i></span>
                                <input wire:model="phone" type="text" class="form-control text-theme bg-secondary-subtle" id="phone_add"
                                    placeholder="Telefono" autocomplete="nop" disabled data-next="barrio_add">
                            </div>
                            @error('phone')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="barrio_add">Barrio</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-map-marker-alt"></i></span>
                                <input wire:model="barrio" type="text" class="form-control text-theme bg-secondary-subtle"
                                    id="barrio_add" placeholder="Barrio" autocomplete="nop" disabled
                                    data-next="address_add">
                            </div>
                            @error('barrio')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="address_add">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-home"></i></span>
                                <input wire:model="address" type="text" class="form-control text-theme bg-secondary-subtle"
                                    id="address_add" placeholder="Direccion" autocomplete="nop" disabled
                                    data-next="city_add">
                            </div>
                            @error('address')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="city_add">Ciudad</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-city"></i></span>
                                <input wire:model="city" type="text" class="form-control text-theme bg-secondary-subtle"
                                    id="city_add" placeholder="Ciudad" autocomplete="nop" disabled
                                    data-next="btnAddUser">
                            </div>
                            @error('city')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer rounded-bottom-4">
                <div wire:loading wire:target="store" class="progress w-100">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple"
                        style="width: 100%">
                        Procesando registro...
                    </div>
                </div>

                <div class="mt-3 mb-3" wire:loading.remove wire:target="store">
                    <button type="button" wire:click="$dispatch('closeModal')" wire:click.prevent="cancel"
                        class="btn btn-outline-info btn-rounded">
                        Cancelar
                    </button>
                    <button id="btnAddUser" type="button" wire:click="store"
                        class="btn btn-outline-success btn-rounded" disabled>
                        Registrar Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<div wire:ignore.self class="modal fade" id="editModal{{ $componentName }}" data-bs-keyboard="false"
    data-bs-backdrop="static" tabindex="-1" role="dialog" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content position-relative border-0">
            <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
                style="pointer-events: none;"></div>

            <div class="modal-header rounded-top-4">
                <h5 class="modal-title text-success">Actualizar {{ $componentName }}</h5>
                <button wire:click.prevent="cancel()" wire:click="$dispatch('closeModal')" type="button"
                    class="btn-close text-info"></button>
            </div>
            <div class="modal-body bg-info-subtle">
                <!-- Formulario de edición -->
                <form>
                    <input type="text" class="d-none" id="name2_edit" autocomplete="off">

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="ci_edit">Ced. de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-id-card"></i></span>
                                <input wire:model="ci" type="number" onkeydown="filtrarTeclas()"
                                    onkeyup="quitarCeroIzq()" class="form-control text-theme ci-input" id="ci_edit"
                                    placeholder="Ced. de Identidad" autocomplete="nop" disabled>
                            </div>
                            @error('ci')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="email_edit">e-mail</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-envelope"></i></span>
                                <input wire:model="email" type="email" class="form-control text-theme"
                                    id="email_edit" placeholder="ejemplo@gmail.com" autocomplete="nop"
                                    data-next="name_edit">
                            </div>
                            @error('email')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="name_edit">Nombre</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                                <input wire:model="name" type="text" class="form-control text-theme"
                                    id="name_edit" placeholder="Nombre" autocomplete="nop"
                                    data-next="lastname_edit">
                            </div>
                            @error('name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="lastname_edit">Apellido</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                                <input wire:model="lastname" type="text" class="form-control text-theme"
                                    id="lastname_edit" placeholder="Apellido" autocomplete="nop"
                                    data-next="phone_edit">
                            </div>
                            @error('lastname')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="phone_edit">Telefono</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-phone"></i></span>
                                <input wire:model="phone" type="text" class="form-control text-theme"
                                    id="phone_edit" placeholder="Telefono" autocomplete="nop"
                                    data-next="barrio_edit">
                            </div>
                            @error('phone')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="barrio_edit">Barrio</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-map-marker-alt"></i></span>
                                <input wire:model="barrio" type="text" class="form-control text-theme"
                                    id="barrio_edit" placeholder="Barrio" autocomplete="nop"
                                    data-next="address_edit">
                            </div>
                            @error('barrio')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="address_edit">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-home"></i></span>
                                <input wire:model="address" type="text" class="form-control text-theme"
                                    id="address_edit" placeholder="Direccion" autocomplete="nop"
                                    data-next="city_edit">
                            </div>
                            @error('address')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label class="mb-0 text-muted" for="city_edit">Ciudad</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-city"></i></span>
                                <input wire:model="city" type="text" class="form-control text-theme"
                                    id="city_edit" placeholder="Ciudad" autocomplete="nop"
                                    data-next="btnUpdateUser">
                            </div>
                            @error('city')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer rounded-bottom-4">
                <div wire:loading wire:target="update" class="progress w-100">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple"
                        style="width: 100%">
                        Procesando actualización...
                    </div>
                </div>

                <div class="mt-3 mb-3" wire:loading.remove wire:target="update">
                    <button type="button" wire:click="$dispatch('closeModal')" wire:click.prevent="cancel"
                        class="btn btn-outline-info btn-rounded">
                        Cancelar
                    </button>
                    <button id="btnUpdateUser" type="button" wire:click="update"
                        class="btn btn-outline-success btn-rounded">
                        Actualizar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filtrarTeclas() {
        var tecla = event.key;
        if (['.', 'e', ',', '-', '+'].includes(tecla))
            event.preventDefault()
    }

    function quitarCeroIzq() {
        let input = event.target;
        if (input.value === 0) {
            input.value = '';
        }
        // También limpiamos si comienza con cero después de otros caracteres
        input.value = input.value.replace(/^0+/, '');
    }
</script>
