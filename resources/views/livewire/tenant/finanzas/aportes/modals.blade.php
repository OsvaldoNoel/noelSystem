<div wire:ignore.self class="modal modal-sm fade" id="modalaporte" data-bs-keyboard="false" data-bs-backdrop="static"
    tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">

                @if ($operacion == 0)
                    <h5 id="modalTitle" class="modal-title text-success">Asentar {{ $componentName }}</h5>
                @else
                    <h5 class="modal-title text-success">Retiro de {{ $componentName }}</h5>
                @endif

                <button wire:click.prevent="cancel()" type="button" class="btn-close text-info "
                    data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                @include('livewire.tenant.finanzas.aportes.modal_form')

            </div>
            <div class="modal-footer">
                {{-- @if ($operacion == null) --}}
                <div wire:loading wire:target="store()" class="progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple" style="width: 66%">
                        --Procesando.................</div>
                </div>

                <div wire:loading.remove wire:target="store()">
                    <button type="button" wire:click.prevent="cancel()"
                        class="btn btn-outline-info btn-rounded close-btn" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" wire:click.prevent="store()" class="btn btn-outline-success btn-rounded"
                        id="btnUpdated">Guardar</button>
                </div>

                <div wire:loading wire:target="store()">
                    <button type="button" class="btn btn-info opacity-2 btn-rounded">Cerrar</button>
                    <button type="button" class="btn btn-success opacity-2 btn-rounded"
                        id="btnUpdatedOFF">Guardar</button>
                </div>
                {{-- @else
                    <div wire:loading wire:target="update()"class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-purple"
                            style="width: 66%">
                            --Procesando.................</div>
                    </div>
                    <div wire:loading.remove wire:target="update()">
                        <button type="button" wire:click.prevent="cancel()"
                            class="btn btn-outline-info btn-rounded close-btn" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" wire:click.prevent="update()"
                            class="btn btn-outline-success btn-rounded">Actualizar</button>
                    </div>

                    <div wire:loading wire:target="update()">
                        <button type="button" class="btn btn-info opacity-2 btn-rounded">Cerrar</button>
                        <button type="button" class="btn btn-success opacity-2 btn-rounded">Actualizar</button>
                    </div>
                @endif --}}


            </div>
        </div>
    </div>
</div>



@script
    <script>
        $wire.on('showModal', () => {
            $('#modalaporte').modal('show');

            document.getElementById("destino1").checked = true;

            // if ($wire.operacion == 0) {
            //     document.getElementById("modalaporte").classList.remove('modal-lg');
            //     document.getElementById("modalaporte").classList.add('modal-sm');
            // } else {
            //     document.getElementById("modalaporte").classList.remove('modal-sm');
            //     document.getElementById("modalaporte").classList.add('modal-lg');
            // }

            $wire.dispatch('selectedCaja');
        });

        $wire.on('closeModal', () => {
            $('#modalaporte').modal('hide');
            $wire.cancel();
        });
    </script>
@endscript
