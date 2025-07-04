<form>
    <div class="col-xl-12">
        <!-- BEGIN row -->
        <div class="row g-2">

            <div class="col-lg-6">
                <!-- BEGIN card -->
                <div class="card h-100 mx-1">
                    <div class="row ">
                        <div class="col-6">
                            <div class="mb-3 bg-black rounded position-relative" style="height: 180px;">
                                @if ($image)
                                    <a href = "#">
                                        <img alt="avatar" src="{{ $image->temporaryUrl() }}"
                                            class="position-absolute top-50 start-50 translate-middle rounded"
                                            style="max-width: 100%; max-height: 100%" onclick="abrir()" />
                                    </a>
                                @else
                                    @if ($imagePrev == null)
                                        <a href = "#">
                                            <img alt="avatar" src="{{ asset('storage/sin_imagen.png') }}"
                                                class="img-fluid rounded" onclick="abrir()" />
                                        </a>
                                    @else
                                        <a href = "#">
                                            <img alt="avatar"
                                                src="{{ asset('storage/img/' . Auth::user()->tenant_id . '/' . 'productos/' . $imagePrev) }}"
                                                class="position-absolute top-50 start-50 translate-middle rounded"
                                                style="max-width: 100%; max-height: 100%" onclick="abrir()" />
                                        </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-6">

                            <div class="input-group mb-3">
                                <span class="input-group-text" style="width: 80px" id="stockMin">stock min</span>
                                <input wire:model="stockMin" wire:keydown="convertInteger" type="text"
                                    x-mask:dynamic="$money($input, ',', '.')" onkeydown="filtrarTeclas()"
                                    class="form-control text-end text-theme" placeholder="0" id="stockMin"
                                    autocomplete="off">
                            </div>

                            <div class="input-group mb-3">
                                <span class="input-group-text" style="width: 80px" id="stockMax">stock max</span>
                                <input wire:model="stockMax" wire:keydown="convertInteger" type="text"
                                    x-mask:dynamic="$money($input, ',', '.')" onkeydown="filtrarTeclas()"
                                    class="form-control text-end text-theme" placeholder="0" id="stockMax"
                                    autocomplete="off">
                            </div>

                            <input wire:model="image" type="file" class="invisible" id="file"
                                accept="image/png, image/jpeg, image/gif">


                            <div class="input-group mb-3">
                                <span class="input-group-text" style="width: 80px" name="redondeo">Redondeo</span>
                                <input wire:model.live="redondeo" wire:keydown="convertInteger" id="redondeo"
                                    type="text" x-mask:dynamic="$money($input, ',', '.')" onkeydown="filtrarTeclas()"
                                    class="form-control text-end text-theme" autocomplete="off">
                            </div>

                            <div class="form-check form-switch">
                                <input wire:model="vto" wire:click="vtoSwith()" type="checkbox" class="form-check-input"
                                    {{ $vto == 1 ? 'checked' : '' }} id="vto">
                                <label class="form-check-label" for="vto">Incluir vencimiento</label>
                            </div>
                            {{ $vto }}
                        </div>
                        @error('image')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                        @error('stockMin')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                        @error('stockMax')
                            <span class="error text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <h4 class="text-red">COSTO PROMEDIO = <span class="text-red">{{ $costoPromedio }}</span>
                            </h4>
                            <hr>
                            <div class="col-4">
                                <h5 class="card-title text-theme">Utilidad</h5>

                                <div class="card">
                                    <div class="form-check mt-2">
                                        <input wire:model.live = "tipoUtilidad" class="form-check-input" value="1"
                                            type="radio" name="tipoUtilidad" id="flexRadio1"
                                            {{ $tipoUtilidad == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexRadio1">
                                            Baja
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input wire:model.live = "tipoUtilidad" class="form-check-input" value="2"
                                            type="radio" name="tipoUtilidad" id="flexRadio2"
                                            {{ $tipoUtilidad == 2 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexRadio2">
                                            Media
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input wire:model.live = "tipoUtilidad" class="form-check-input"
                                            value="3" type="radio" name="tipoUtilidad" id="flexRadio3"
                                            {{ $tipoUtilidad == 3 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexRadio3">
                                            Alta
                                        </label>
                                    </div>

                                    <div class="form-check">
                                        <input wire:model.live = "tipoUtilidad" class="form-check-input"
                                            value="4" type="radio" name="tipoUtilidad" id="flexRadio4"
                                            {{ $tipoUtilidad == 4 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="flexRadio4">
                                            Especial
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-8">

                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="width: 125px" name="priceList1">Lista 1
                                        {{ $tipoUtilidad == 4 || $costoPromedio == 0 ? '' : '= ' . $porcent_lista1 . '%' }}</span>
                                    <input wire:model.live="priceList1" wire:keydown="convertInteger" id="priceList1"
                                        type="text" x-mask:dynamic="$money($input, ',', '.')"
                                        onkeydown="filtrarTeclas()" {{ $tipoUtilidad != 4 ? 'disabled' : '' }}
                                        class="form-control text-end {{ $tipoUtilidad == 4 ? 'text-theme' : '' }}"
                                        placeholder="0" autocomplete="off">
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text" style="width: 125px" name="priceList2">Lista 2
                                        {{ $tipoUtilidad == 4 || $costoPromedio == 0 ? '' : '= ' . $porcent_lista2 . '%' }}</span>
                                    <input wire:model.live="priceList2" wire:keydown="convertInteger" id="priceList2"
                                        type="text" x-mask:dynamic="$money($input, ',', '.')"
                                        onkeydown="filtrarTeclas()" {{ $tipoUtilidad != 4 ? 'disabled' : '' }}
                                        class="form-control text-end {{ $tipoUtilidad == 4 ? 'text-theme' : '' }}"
                                        placeholder="0" autocomplete="off">
                                </div>

                                <div class="input-group">
                                    <span class="input-group-text" style="width: 125px" name="priceList3">Lista 3
                                        {{ $tipoUtilidad == 4 || $costoPromedio == 0 ? '' : '= ' . $porcent_lista3 . '%' }}</span>
                                    <input wire:model.live="priceList3" wire:keydown="convertInteger" id="priceList3"
                                        type="text" x-mask:dynamic="$money($input, ',', '.')"
                                        onkeydown="filtrarTeclas()" {{ $tipoUtilidad != 4 ? 'disabled' : '' }}
                                        class="form-control text-end {{ $tipoUtilidad == 4 ? 'text-theme' : '' }}"
                                        placeholder="0" autocomplete="off">
                                </div>
                            </div>
                            @error('priceList1')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                            @error('priceList2')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                            @error('priceList3')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <!-- END card -->
            </div>



            <div class="col-lg-6">

                <!-- BEGIN card -->
                <div class="card h-100 mx-1">
                    <!-- BEGIN card-body -->
                    <div class="card-body">
                        <div class="form-group">
                            <label class="mb-0 text-muted" for="name">Artículo </label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><label for="name"><Span
                                            class="fa fa-edit pointer"></Span></label></span>
                                <input wire:model.lazy="name" type="text" class="form-control text-theme"
                                    id="name" placeholder="Nombre" autocomplete="off">
                            </div>
                            @error('name')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group mt-3">
                            <label class="mb-0 text-muted" for="code">Código</label>
                            <div class="input-group">
                                <span class="input-group-text text-success"><label for="code"><Span
                                            class="fa fa-edit pointer"></Span></label></span>
                                <input wire:model.lazy="code" type="text" class="form-control text-theme"
                                    id="code" placeholder="Código" autocomplete="off">
                                <span class="input-group-text text-info pointer" data-bs-toggle="tooltip"
                                    data-bs-placement="top" title="Generar Código"><label for="code"><Span
                                            wire:click="generateCode()"
                                            class="fa fa-barcode pointer"></Span></label></span>
                            </div>
                            @error('code')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <livewire:tenant.stock.presentations.selected-presentations>
                                @error('presentation_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        <div class="mt-3">
                            <livewire:tenant.stock.marcas.selected-marcas>
                                @error('marca_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        <div class="mt-3">
                            <livewire:tenant.stock.categorias.selected-categorias>
                                @error('categoria_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                        <div class="mt-3">
                            <livewire:tenant.stock.subcategorias.selected-subcategorias>
                                @error('subcategoria_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                        </div>

                    </div>
                    <!-- END card-body -->
                </div>
                <!-- END card -->
            </div>

        </div>
        <!-- END row -->
    </div>


</form>


<script>
    //-------------abrir selector de archivos---------
    function abrir() {
        var file = document.getElementById("file").click();
    }
</script>
