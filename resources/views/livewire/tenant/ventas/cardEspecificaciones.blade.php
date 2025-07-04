<div class="card mb-4">
    <div class="card-header fw-bold">
        ESPECIFICACIONES 
    </div>
    <div class="card-body bg-info-subtle">
        <div>
            <livewire:tenant.clientes.selected-clientes>
        </div> 

        <div class="row mt-3">   
            <div class="col">
                <div class="form-group">
                    <label for="select-cliente" class="mb-0 text-muted">Lista de precio</label>
                    <div class="input-group">
                        <select class="form-select text-theme" id="select-cliente" autocomplete="off">
                            <option class="opt" value="1">Lista 1</option>
                            <option class="opt" value="2">Lista 2</option>
                            <option class="opt" value="3">Lista 3</option>
                        </select>
                    </div>
                </div>
            </div> 
            <div class="col">
                <div class="form-group">
                    <label for="select-cliente" class="mb-0 text-muted">Tipo de venta</label>
                    <div class="input-group">
                        <select class="form-select text-theme" id="select-cliente" autocomplete="off">
                            <option class="opt" value="1">Contado</option>
                            <option class="opt" value="2">Credito</option>
                        </select>
                    </div>
                </div>
            </div> 
        </div>

        <div class="row mt-3">
            <div class="col">
                <div class="form-group">
                    <label for="select-cliente" class="mb-0 text-muted">Tipo de pago</label>
                    <div class="input-group">
                        <select class="form-select text-theme" id="select-cliente" autocomplete="off">
                            <option class="opt" value="1">Efectivo</option>
                            <option class="opt" value="2">Transferncia</option>
                            <option class="opt" value="2">Tarj. Crédito</option>
                            <option class="opt" value="2">Tarj. Débito</option>
                            <option class="opt" value="2">Cheque al día</option>
                            <option class="opt" value="2">Cheque diferido</option>
                        </select>
                    </div>
                </div>
            </div> 
            
            <div class="col">
                <div class="form-group">
                    <div class="form-group">
                        <label class="mb-0 text-muted form-label" for="inputFecha">Fecha</label>
                        <input type="text" class="form-control" id="inputFecha">
                    </div>
                </div>
            </div> 
        </div> 
    </div>
</div>