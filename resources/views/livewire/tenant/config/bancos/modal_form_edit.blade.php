<form wire:submit="save"> 

    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label for="select-entidad_edit" class="mb-0 text-muted">Entidad</label>

            <div wire:ignore>
                <div class="input-group">
                    <span class="input-group-text text-success"><Span class="fa fa-edit"></Span></span>
                    <select class="form-select text-theme" id="select-entidad_edit" placeholder="Seleccione una opción"
                        autocomplete="off">
                    </select>
                </div>
            </div>

            @error('entidad_id')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
            
        </div> 

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="tipoCta">Tipo de cuenta</label>
            <div class="input-group">
                <select wire:model="tipoCta" class="form-select text-theme" id="tipoCta" autocomplete="off">
                    <option selected class="opt" value="Cuenta Corriente">Cuenta Corriente</option>
                    <option class="opt" value="Caja de Ahorro">Caja de Ahorro</option>
                </select>
            </div>
            @error('tipoCta')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>


    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="titular">Titular de la Cuenta</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="titular"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="titular" type="text" class="form-control text-theme" id="titular"
                    placeholder="Nombre y Apellido" autocomplete="off">
            </div>

            @error('titular')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="cuenta">Nro. de Cuenta</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="cuenta"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="cuenta" type="text" onkeypress="return valideKey(event);"
                    class="form-control text-theme" id="cuenta" placeholder="Ingrese el numero" autocomplete="off">
            </div>
            @error('cuenta')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-3">

            <label class="mb-0 text-muted" for="moneda">Moneda</label>
            <div class="input-group">
                <select wire:model="moneda" class="form-select text-theme" id="moneda" autocomplete="off">
                    <option selected class="opt" value="Guaranies">Guaranies</option>
                    <option class="opt" value="Dolares">Dolares</option>
                </select>
            </div>
            @error('moneda')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="sobregiro">Linea de Sobregiro</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="sobregiro"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="sobregiro" type="text" onkeyup="format('sobregiro')"
                    x-mask:dynamic="$money($input, ',', '.')" class="form-control text-theme" id="sobregiro"
                    placeholder="Monto asigando" autocomplete="off">
            </div>
            @error('sobregiro')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="oficial">Oficial asignado</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="oficial"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="oficial" type="text" class="form-control text-theme" id="oficial"
                    placeholder="Nombre" autocomplete="off">
            </div>
            @error('oficial')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="phone">Telefono del oficial</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="phone"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="phone" type="text" class="form-control text-theme" id="phone"
                    placeholder="Telefono" autocomplete="off">
            </div>
            @error('phone')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

</form>

<script>
    function filtrarTeclas() {
        var tecla = event.key;
        if (['.', 'e', ',', '-', '+'].includes(tecla))
            event.preventDefault()
    }

    function filtrarTeclasCta() {
        var tecla = event.key;
        if (['.', 'e', ',', '+'].includes(tecla))
            event.preventDefault()
    }

    function format(id) {
        let el = document.querySelector(`[id="${id}"]`);
        let stringQty = (el.value).split(".");
        let newQty = "";

        if (el.value != "") {
            for (i = 0; i < stringQty.length; i++) {
                newQty = parseInt(newQty + stringQty[i]);
            };
            el.value = new Intl.NumberFormat("de-DE").format(newQty);
        } else {
            el.value = null
        }
    }

    function valideKey(evt) {
        // code is the decimal ASCII representation of the pressed key.
        var code = (evt.which) ? evt.which : evt.keyCode;

        if (code == 8 || code == 45) { // backspace or negative.
            return true;
        } else if (code >= 48 && code <= 57) { // is a number.
            return true;
        } else { // other keys.
            return false;
        }
    }
</script>

@script
    <script>
        $wire.$on('valorData', ($id) => {
            let options = JSON.parse($wire.options) 
            let newName = new TomSelect("#select-entidad_edit", {
                create: false,
                items: $id[0],
                options: options,
                valueField: 'id',
                labelField: 'nombre',
                searchField: 'nombre',
                hideSelected: true,
                sortField: {
                    field: "nombre",
                    direction: "asc",
                },
                itemClass: 'text-theme',
                onChange: function() {
                    let id = newName.items[0];
                    if (id != null) {
                        Livewire.dispatch('selectedEntidad', {
                            id
                        });
                    } else {
                        id = null;
                        Livewire.dispatch('selectedEntidad', {
                            id
                        });
                    }
                },

            });

            //hacemos el scholl del body en la tabla
            $('.ts-dropdown-content').niceScroll({
                cursorwidth: 3,
                cursoropacitymin: 0.6,
                cursorcolor: 'rgb(0 0 0 / 50%)',
                cursorborder: 'none',
                cursorborderradius: 4,
                autohidemode: 'leave'
            });

            $wire.$on('destroyTomselec', ($id) => {
                newName.destroy();
            })
        })
    </script>
@endscript
