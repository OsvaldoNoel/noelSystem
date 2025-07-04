<div wire:ignore.self class="modal animated zoomInUp custo-zoomInUp" id="dataModal{{ $componentName }}" data-bs-backdrop="static" tabindex="-1"  role="dialog" aria-labelledby="dataModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
				@include("livewire.tenant.stock.marcas.modal_form")

            </div>
            <div class="modal-footer"> 
                <button type="button" wire:click.prevent="cancel()" wire:loading.attr="disabled" wire:target="image" class="btn btn-outline-info btn-rounded btn-sm close-btn" data-bs-dismiss="modal">Cerrar</button>
                @if ($selected_id == null)
                    <button type="button" wire:click.prevent="store()" class="btn btn-outline-success btn-rounded btn-sm close-btn">Guardar</button>
                @else
                    <button type="button" wire:click.prevent="update()" wire:loading.attr="disabled" wire:target="image" class="btn btn-outline-success btn-rounded btn-sm close-btn">Actualizar</button>
                @endif
            </div>
        </div>
    </div>
</div>
