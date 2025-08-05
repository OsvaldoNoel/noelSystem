<div wire:ignore.self class="modal fade" id="addModal{{ $componentName }}" data-bs-keyboard="false"
    data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-modal="true">
    <div class="modal-dialog modal-lg"> <!-- Cambiado a modal-lg para más espacio -->
        <div class="modal-content position-relative border-0">
            <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
                style="pointer-events: none;"></div>

            <div class="modal-header rounded-top-4">
                <h5 class="modal-title text-success">Que deseas crear..?</h5>
                <button type="button" class="btn-close text-info" wire:click.prevent="cancel()"
                    wire:click="$dispatch('closeModal')"></button>
            </div>
            <div class="modal-body bg-info-subtle">
                <form>
                    <!-- Sección Tenant -->
                    <div class="form-group ms-5">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tenant_type" id="new_company"
                                value="principal">
                            <label class="form-check-label" for="new_company">
                                Empresa Principal
                            </label>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="radio" name="tenant_type" id="existing_branch"
                                value="sucursal">
                            <label class="form-check-label" for="existing_branch">
                                Sucursal
                            </label>
                        </div>
                    </div>

                    <!-- Sección Tipo de Empresa (solo visible para empresas principales) -->
                    <div class="form-group mt-4 leader-select-container @if ($is_branch) d-none @endif">
                        <label class="mb-0 text-muted">Tipo de Empresa <span class="text-danger">*</span></label>
                        <div class="row">
                            @foreach ([1 => 'POS', 2 => 'Servicios', 3 => 'MicroVentas', 4 => 'Restaurante'] as $value => $label)
                                <div class="col-6 col-md-3">
                                    <input type="radio" class="btn-check" name="tenant_type_id"
                                        id="tenant_type_{{ $value }}" wire:model="tenant_type"
                                        value="{{ $value }}" autocomplete="off"
                                        @if ($is_branch) disabled @endif>
                                    <label class="btn btn-outline-primary w-100 mb-2"
                                        for="tenant_type_{{ $value }}">
                                        {{ $label }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('tenant_type')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <hr class="text-info mt-3">


                    <div class="row">

                        <div class="form-group col-md-6 mt-4 mb-5">
                            <label class="mb-0 text-muted" id="labelTitleSelect" for="nameAdd{{ $componentName }}">
                                @if ($is_branch)
                                    Nombre para la Sucursal
                                @else
                                    Nombre para la nueva Empresa
                                @endif
                            </label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-building"></i></span>
                                <input wire:model="name" type="text" class="form-control text-theme" data-next="ci"
                                    id="nameAdd{{ $componentName }}" placeholder="Asigne el nombre" autocomplete="nop">
                            </div>
                            @error('name')
                                <span class="error text-danger er">{{ $message }}</span>
                            @enderror
                        </div>

                        <div
                            class="form-group col-md-6 mb-3 mt-4 branch-select-container @if (!$is_branch) d-none @endif ">
                            <label class="mb-0 text-muted" for="selectEmpresasPrincipales">Empresa Principal</label>
                            <select wire:model="sucursal" class="form-select text-theme" id="selectEmpresasPrincipales">
                                <option value="">-- Seleccione una empresa principal --</option>
                                @foreach ($tenantsList as $item)
                                    <option value="{{ $item['id'] }}" @selected($item['id'] == $sucursal)>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sucursal')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div
                            class="form-group leader-select-container col-md-6 mb-3 mt-4 @if ($is_branch) d-none @endif ">
                            <label class="mb-0 text-muted" for="ci">Cédula de Identidad</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-id-card"></i></span>
                                <input wire:model="ci" type="number" class="form-control text-theme ci-input"
                                    id="ci" placeholder="Cédula" autocomplete="off">
                            </div>
                            @error('ci')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>


                    <div class="owner-select-container @if ($sucursal == null && $ci == null) d-none @endif ">

                        <!-- Sección Usuario Propietario -->
                        <h6 class="ms-5 mb-3 text-info">
                            Información del Propietario
                            @if ($is_branch)
                                ---- C.I. {{ $ci }}
                            @endif

                        </h6>

                        <hr class="text-info mt-3">


                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="mb-0 text-muted" for="name_user">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                                    <input wire:model="name_user" type="text" class="form-control text-theme"
                                        id="name_user" placeholder="Nombre" autocomplete="off" data-next="lastname">
                                </div>
                                @error('name_user')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="mb-0 text-muted" for="lastname">Apellido</label>
                                <div class="input-group">
                                    <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                                    <input wire:model="lastname" type="text" class="form-control text-theme"
                                        id="lastname" placeholder="Apellido" autocomplete="nop" data-next="email">
                                </div>
                                @error('lastname')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="mb-0 text-muted" for="email">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text text-theme"><i class="fas fa-envelope"></i></span>
                                    <input wire:model="email" type="email" class="form-control text-theme"
                                        id="email" placeholder="ejemplo@gmail.com" autocomplete="off"
                                        data-next="phone">
                                </div>
                                @error('email')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="mb-0 text-muted" for="phone">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text text-theme"><i class="fas fa-phone"></i></span>
                                    <input wire:model="phone" type="text" class="form-control text-theme"
                                        id="phone" placeholder="Teléfono" autocomplete="nop" data-next="barrio">
                                </div>
                                @error('phone')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6 mb-3">
                                <label class="mb-0 text-muted" for="barrio">Barrio</label>
                                <div class="input-group">
                                    <span class="input-group-text text-theme"><i
                                            class="fas fa-map-marker-alt"></i></span>
                                    <input wire:model="barrio" type="text" class="form-control text-theme"
                                        id="barrio" placeholder="Barrio" autocomplete="off" data-next="city">
                                </div>
                                @error('barrio')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label class="mb-0 text-muted" for="city">Ciudad</label>
                                <div class="input-group">
                                    <span class="input-group-text text-theme"><i class="fas fa-city"></i></span>
                                    <input wire:model="city" type="text" class="form-control text-theme"
                                        id="city" placeholder="Ciudad" autocomplete="nop" data-next="address">
                                </div>
                                @error('city')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="mb-0 text-muted" for="address">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text text-theme"><i class="fas fa-home"></i></span>
                                <input wire:model="address" type="text" class="form-control text-theme"
                                    id="address" placeholder="Dirección completa" autocomplete="nop"
                                    data-next="buttonAddTenant">
                            </div>
                            @error('address')
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
                    <button id="buttonAddTenant" type="button" wire:click="store"
                        class="btn btn-outline-success btn-rounded"
                        @if(($is_branch && !$sucursal) || (!$is_branch && (!$ci || strlen($ci) < 6 || !$profileFound))) disabled @endif >
                        @if ($is_branch)
                            Registrar Sucursal
                        @else
                            Registrar Empresa y Propietario
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- solo en el store se cargaran los datos del propietario del tenant, el edit se mantiene igual. 
La edicion de los datos de los usuarios se realizara de ser necesario ya una vez registrado el tenant
y desde un formulario para editar su respectivo perfil --}}



{{-- Modal para editar los tenants --}}

<div wire:ignore.self class="modal fade" id="editModal{{ $componentName }}" data-bs-keyboard="false"
    data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title text-success">
                    @if ($sucursal == null)
                        Actualizar Empresa
                    @else
                        Actualizar Sucursal
                    @endif
                </h5>
                <button type="button" class="btn-close text-info " wire:click.prevent="cancel()"
                    wire:click="$dispatch('closeModal')"></button>
            </div>
            <div class="modal-body">
                <form action="">

                    <div class="form-group">
                        <label class="mb-0 text-muted" for="nameEdit{{ $componentName }}">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text text-theme"><span class="fa fa-edit"></span></span>
                            <input wire:model.lazy="name" type="text" class="form-control text-theme"
                                id="nameEdit{{ $componentName }}" placeholder="Nombre del tenant"
                                autocomplete="off">
                        </div>
                        @error('name')
                            <span class="error text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div wire:loading wire:target="update()"class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple" style="width: 66%">
                        --Procesando.................</div>
                </div>
                <div wire:loading.remove wire:target="update()">
                    <button type="button" wire:click.prevent="cancel()"
                        class="btn btn-outline-info btn-rounded close-btn"
                        wire:click="$dispatch('closeModal')">Cerrar</button>
                    <button type="button" wire:click.prevent="update()"
                        class="btn btn-outline-success btn-rounded">Actualizar</button>
                </div>

                <div wire:loading wire:target="update()">

                    <button type="button" class="btn btn-info opacity-2 btn-rounded">Cerrar</button>
                    <button type="button" class="btn btn-success opacity-2 btn-rounded">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>

@script
    <script>
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                $wire.dispatch('closeModal');
            }
        });
    </script>
@endscript
