<div>
    @include('livewire.tenant.finanzas.aportes.modals')

    <div class="row">

        <div class="col">
            <div class="d-grid gap-2">
                <button wire:click="aporte()" type="button" class="btn btn-outline-success mb-2">Aporte</button>
            </div> 

        </div>

        <div class="col">
            <div class="d-grid gap-2">
                <button wire:click="devolucion()" type="button" class="btn btn-outline-pink mb-2">Retiros</button>
            </div>
        </div> 

        

    </div>
</div>
