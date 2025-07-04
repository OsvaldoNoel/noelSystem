<div wire:ignore.self class="modal fade" id="addModal{{ $componentName }}" data-bs-keyboard="false"
    data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">Crear {{ $componentName }}</h5>
                <button type="button" class="btn-close text-info" wire:click.prevent="cancel()" wire:click="$dispatch('closeModal')"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-3">
                        <label class="mb-0 text-muted">Tipo de Tenant</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tenant_type" id="new_company"
                                value="principal" checked>
                            <label class="form-check-label" for="new_company">
                                Empresa Principal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="tenant_type" id="existing_branch"
                                value="sucursal">
                            <label class="form-check-label" for="existing_branch">
                                Sucursal
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-3 branch-select-container {{ $is_branch ? '' : 'd-none' }}">
                        <label class="mb-0 text-muted" for="selectEmpresasPrincipales">Empresa Principal</label>
                        <select wire:model="sucursal" class="form-select text-theme" id="selectEmpresasPrincipales" placeholser="Seleccionar empresa principal">
                            <option value="">-- Seleccione una empresa principal --</option>
                            @foreach ($tenantsList as $item)
                                <option value="{{ $item['id'] }}">{{ $item['name'] }}</option>
                            @endforeach
                        </select>
                        @error('sucursal')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div> 

                    <div class="form-group">
                        <label class="mb-0 text-muted" for="name">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text text-theme"><span class="fa fa-edit"></span></span>
                            <input wire:model="name" type="text" class="form-control text-theme" id="name"
                                placeholder="Nombre del tenant" autocomplete="off">
                        </div>
                        @error('name')
                            <span class="error text-danger er">{{ $message }}</span>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div wire:loading wire:target="store" class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple" style="width: 66%">
                        --Procesando.................</div>
                </div>

                <div wire:loading.remove wire:target="store">
                    <button type="button" wire:click="$dispatch('closeModal')" wire:click.prevent="cancel"  class="btn btn-outline-info btn-rounded">
                        Cerrar
                    </button>
                    <button type="button" wire:click="store" class="btn btn-outline-success btn-rounded">
                        Guardar
                    </button>
                </div>

                <div wire:loading wire:target="update()">
                    <button type="button" class="btn btn-info opacity-2 btn-rounded">Cerrar</button>
                    <button type="button" class="btn btn-success opacity-2 btn-rounded">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div wire:ignore.self class="modal fade" id="editModal{{ $componentName }}" data-bs-keyboard="false"
    data-bs-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-modal="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="modalTitle" class="modal-title text-success">Actualizar {{ $componentName }}</h5>
                <button type="button" class="btn-close text-info " wire:click.prevent="cancel()"
                    wire:click="$dispatch('closeModal')"></button>
            </div>
            <div class="modal-body">
                <form action="">

                    <div class="form-group">
                        <label class="mb-0 text-muted" for="name">Nombre</label>
                        <div class="input-group">
                            <span class="input-group-text text-theme"><span class="fa fa-edit"></span></span>
                            <input wire:model.lazy="name" type="text" class="form-control text-theme"
                                id="name" placeholder="Nombre del tenant" autocomplete="off">
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
