<form wire:submit="save">

    <div class="form-group  ">
        <label class="mb-0 text-muted" for="name">Nombre para la caja</label>
        <div class="input-group">
            <span class="input-group-text text-success"><label for="name"><Span
                        class="fa fa-edit pointer"></Span></label></span>
            <input wire:model="name" type="text" class="form-control text-theme" id="name" placeholder="Nombre"
                autocomplete="off">
        </div>
        @error('name')
            <span class="error text-danger">{{ $message }}</span>
        @enderror
    </div> 

    <div class="form-group mt-3 ">
        <label class="mb-0 text-muted" for="ubi">Ubicación de la caja</label>
        <div class="input-group">
            <span class="input-group-text text-success"><label for="ubi"><Span
                        class="fa fa-edit pointer"></Span></label></span>
            <input wire:model="ubi" type="text" class="form-control text-theme" id="ubi" placeholder="Ubicacón"
                autocomplete="off">
        </div>
        @error('ubi')
            <span class="error text-danger">{{ $message }}</span>
        @enderror
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
