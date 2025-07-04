<div> 
    <div id="listWidget" class="mb-5">
        <h4>Lista de Cajas y Bancos</h4>
        <p>
            Aqui debe crear sus <code>BANCOS</code> con los cuales va trabajar su empresa.
            Es recomendable que tenga un cuenta exclusiva para este fin, de tan manera a tener un control preciso
            y no mexclar con gastos o movimientos personales.
        </p>
        <p>
            También debe crear sus <code>CAJAS</code>, desde las cuales estarán operando los cajeros
            habilidatos con los clientes y proveedores.
        </p>
        <div class="row">
            <div class="col-xl-6">
                <livewire:tenant.config.cajas.cajas-controller />

                
            </div>
            <div class="col-xl-6">
                <livewire:tenant.config.bancos.bancos-controller />
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-xl-6">
                {{-- <livewire:tenant.finanzas.aportantes.aportantes-controller /> --}}
                
            </div>
            <div class="col-xl-6">
                 
            </div>
        </div>
    </div> 
</div>
