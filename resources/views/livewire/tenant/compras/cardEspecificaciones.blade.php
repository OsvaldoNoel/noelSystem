<div class="card-body bg-info-subtle rounded-3">
    <div class="border border-info-subtle rounded-4 mb-4">
        <div class="m-3">
            <div class="form-group mb-3">
                <label class="mb-0 text-muted form-label" for="inputFecha">Fecha del
                    Comprobante</label>
                <input type="text" class="form-control text-theme @error('fechaFactura') is-invalid @enderror"
                    id="inputFecha" autocomplete="off" readonly
                    value="{{ $header == null ? null : $header['fecha_Fac'] }}" placeholder="Seleccione la fecha">
                @error('fechaFactura')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group mb-3">
                <label for="tipoVenta" class="mb-0 text-muted">Condición de Compra</label>
                <div class="input-group">
                    <select class="form-select text-theme" id="condCompra" autocomplete="off"
                        wire:change="$dispatch('setHeader')">
                        <option class="opt" value="1"
                            {{ $header == null ? 'selected' : ($header['cond_Compra'] == 1 ? 'selected' : '') }}>
                            Contado</option>
                        <option class="opt" value="2"
                            {{ $header == null ? '' : ($header['cond_Compra'] == 2 ? 'selected' : '') }}>
                            Credito</option>
                    </select>
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="tipoComprobante" class="mb-0 text-muted">Comprobante</label>
                <div class="input-group">
                    <select class="form-select text-theme" id="tipoComprobante" autocomplete="off"
                        wire:change="$dispatch('changeTipo')">
                        <option class="opt" value="1"
                            {{ $header == null ? 'selected' : ($header['tipo_Comprobante'] == 1 ? 'selected' : '') }}>
                            Factura Legal</option>
                        <option class="opt" value="2"
                            {{ $header == null ? '' : ($header['tipo_Comprobante'] == 2 ? 'selected' : '') }}>
                            Recibo Común</option>
                        <option class="opt" value="3"
                            {{ $header == null ? '' : ($header['tipo_Comprobante'] == 3 ? 'selected' : '') }}>
                            Otros Doc.</option>
                    </select>
                </div>
            </div>

            <div class="{{ $header == null ? '' : ($header['tipo_Comprobante'] == 1 ? '' : 'd-none') }}">
                <div class="form-group mb-3">
                    <label class="mb-0 text-muted form-label" for="inputNroFac">
                        Nro de Factura
                    </label>
                    <input type="text" class="form-control text-theme @error('numeroFactura') is-invalid @enderror"
                        id="inputNroFac" autocomplete="off"
                        wire:blur="$dispatch('validarInput', {source: 'blur', id: 'inputNroFac'})"
                        wire:keydown.enter.prevent="$dispatch('validarInput', {source: 'enter', id: 'inputNroFac'})"
                        placeholder="___-___-_______" value="{{ $header == null ? null : $header['nro_Fac'] }}">
                    @error('numeroFactura')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label class="mb-0 text-muted form-label" for="inputTimbrado">
                        Timbrado
                    </label>
                    <input type="text" class="form-control text-theme @error('timbrado') is-invalid @enderror"
                        id="inputTimbrado" autocomplete="off"
                        wire:blur="$dispatch('validarInput', {source: 'blur', id: 'inputTimbrado'})"
                        wire:keydown.enter.prevent="$dispatch('validarInput', {source: 'enter', id: 'inputTimbrado'})"
                        placeholder="________" value="{{ $header == null ? null : $header['nro_Tim'] }}">
                    @error('timbrado')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <div class="{{ $header == null ? 'd-none' : ($header['tipo_Comprobante'] == 1 ? 'd-none' : '') }}">
                <div class="form-group mb-3">
                    <label class="mb-0 text-muted form-label" for="inputOtros">
                        Nro comprobante
                    </label>
                    <input type="text" class="form-control text-theme @error('numeroOtros') is-invalid @enderror"
                        id="inputOtros" autocomplete="off"
                        wire:blur="$dispatch('validarInput', {source: 'blur', id: 'inputOtros'})"
                        wire:keydown.enter.prevent="$dispatch('validarInput', {source: 'enter', id: 'inputOtros'})"
                        placeholder="Opcional" value="{{ $header == null ? null : $header['nro_Otros'] }}">
                    @error('numeroOtros')
                        <div class="text-danger">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="">
        <a wire:click.prevent="store" wire:loading.attr='disabled'
            class="btn btn-info mx-auto d-flex justify-content-center align-items-center rounded-2 fw-bold">
            <iconify-icon icon="material-symbols-light:check"
                class="fs-20px me-1 ms-n2 my-n2 d-inline-flex"></iconify-icon>
            <span wire:loading.remove wire:target='store'> Registrar Compra</span>
            <span wire:loading wire:target='store'> Registrando..</span>
        </a>
    </div>

</div>
