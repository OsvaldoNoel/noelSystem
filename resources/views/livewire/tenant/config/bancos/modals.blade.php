<div wire:ignore.self class="modal fade modal-lg" id="addModalBanco" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1"
    role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"> 
                <h5 id="modalTitle" class="modal-title text-success">Crear {{ $componentName }}</h5>  
                <button wire:click.prevent="cancel()" type="button" class="btn-close text-info "
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('livewire.tenant.config.bancos.modal_form_add')
            </div>
            <div class="modal-footer">  
                <div wire:loading wire:target="store()" class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple" style="width: 66%">
                        --Procesando.................</div>
                </div>

                <div wire:loading.remove>
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-outline-info btn-rounded close-btn" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-outline-success btn-rounded" id="btnUpdated">Guardar</button>
                </div>

                <div wire:loading>
                    <button type="button" class="btn btn-info opacity-2 btn-rounded">Cerrar</button> 
                    <button type="button" class="btn btn-success opacity-2 btn-rounded" id="btnUpdatedOFF">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<div wire:ignore.self class="modal fade modal-lg" id="editModalBanco" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1"
    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-success">Actualizar {{ $componentName }}</h5>
                <button wire:click.prevent="cancel()" type="button" class="btn-close text-info "
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('livewire.tenant.config.bancos.modal_form_edit')
            </div>
            <div class="modal-footer">
                <div wire:loading wire:target="update()"class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple" style="width: 66%">
                        --Procesando.................</div>
                </div>
                <div wire:loading.remove>
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-outline-info btn-rounded close-btn" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click.prevent="update()" class="btn btn-outline-success btn-rounded">Actualizar</button>
                </div>

                <div wire:loading>
                    <button type="button" class="btn btn-info opacity-2 btn-rounded">Cerrar</button>
                    <button type="button" class="btn btn-success opacity-2 btn-rounded">Actualizar</button>
                </div>
            </div>
        </div>
    </div>
</div>


@script
    <script>
        $wire.on('showAddModal', () => {
            $('#addModalBanco').modal('show');  
            $wire.actualizarEntidad([null]); 
        });
        $wire.on('showEditModal', ($id) => { 
            $('#editModalBanco').modal('show');  
            $wire.actualizarEntidad($id); 
        });
        $wire.on('closeModal', () => {
            $('#addModalBanco').modal('hide');
            $('#editModalBanco').modal('hide');
            $wire.cancel(); 
        });
    </script>
@endscript

