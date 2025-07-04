<div wire:ignore.self class="modal fade" id="dataModal{{ $componentName }}" data-bs-backdrop="static" tabindex="-1"  role="dialog" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                @if ($selected_id == null)
                    <h5 class="modal-title text-success">Crear {{ $componentName }}</h5>
                @else
                    <h5 class="modal-title text-success">Actualizar {{ $componentName }}</h5>
                @endif
                <button wire:click.prevent="cancel()" type="button" class="btn-close text-info " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
				@include("livewire.tenant.stock.productos.modal_form")

            </div>
            <div class="modal-footer"> 
                <button type="button" wire:click.prevent="cancel()" class="btn btn-outline-info btn-rounded close-btn" data-bs-dismiss="modal">Cerrar</button>
                @if ($selected_id == null)
                    <button type="button" wire:click.prevent="store()" class="btn btn-outline-success btn-rounded" >Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" class="btn btn-outline-success btn-rounded" >Actualizar</button>
                @endif
            </div>
        </div>
    </div>
</div>



