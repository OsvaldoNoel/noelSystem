<div>
    <livewire:tenant.clientes.add-cliente>
    <div id="carrito">
        <div class="row gx-4">

            <div class="col-xl-8">
                <div class="card mb-4">
                    <div class="card-body bg-info-subtle"> 
                        
                        <livewire:tenant.stock.productos.selected-product>

                        @include('livewire.tenant.ventas.tableVentas')

                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                
                @include('livewire.tenant.ventas.cardEspecificaciones')

                @include('livewire.tenant.ventas.cardResumen')
                 
            </div>
        </div>
    </div>
</div>
 