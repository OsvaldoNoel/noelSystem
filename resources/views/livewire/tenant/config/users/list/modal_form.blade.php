<form wire:submit="save">
    <input type="text" class="d-none" id="name2" autocomplete="off">
    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="name">Nombre</label>
            <div class="input-group">
                <input wire:model="name" type="text" class="form-control text-theme" id="name"
                    placeholder="Nombre" autocomplete="nop">
            </div>
            @error('name')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="lastname">Apellido</label>
            <div class="input-group"> 
                <input wire:model="lastname" type="text" class="form-control text-theme" id="lastname"
                    placeholder="Direccion" autocomplete="nop">
            </div>
            @error('lastname')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div> 
    </div>

    <div class="row"> 
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="ci">Ced. de Identidad</label>
            <div class="input-group"> 
                <input wire:model="ci" type="number" onkeydown="filtrarTeclas()" onkeyup="quitarCeroIzq()"
                    class="form-control text-theme ci-input" id="ci" placeholder="Ced. de Identidad"
                    autocomplete="nop">
            </div>

            @error('ci')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="phone">Telefono</label>
            <div class="input-group"> 
                <input wire:model="phone" type="text" class="form-control text-theme" id="phone"
                    placeholder="Telefono" autocomplete="nop">
            </div>
            @error('phone')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div> 
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="email">e-mail</label>
            <div class="input-group"> 
                <input wire:model="email" type="email" class="form-control text-theme" id="email"
                    placeholder="ejemplo@gmail.com" autocomplete="nop">
            </div>
            @error('email')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="barrio">Barrio</label>
            <div class="input-group"> 
                <input wire:model="barrio" type="text" class="form-control text-theme" id="barrio"
                    placeholder="Barrio" autocomplete="nop">
            </div>
            @error('barrio')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div> 
    </div>

    <div class="row"> 
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="address">Dirección</label>
            <div class="input-group"> 
                <input wire:model="address" type="text" class="form-control text-theme" id="address"
                    placeholder="Direccion" autocomplete="nop">
            </div>
            @error('address')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="city">Ciudad</label>
            <div class="input-group"> 
                <input wire:model="city" type="text" class="form-control text-theme" id="city"
                    placeholder="Ciudad" autocomplete="nop">
            </div>
            @error('city')
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

    function calcularDv() {
        let x = $("#dv").val();
        if (x > 9)
            x = Number.parseInt(x / 10);
        $("#dv").val(x);

        if (x === "0" + event.key)
            $("#dv").val(0);
    }

    function quitarCeroIzq() {
        let x = $("#ruc").val();
        if (x == 0)
            $("#ruc").val(null)
        else
            $("#ruc").val(x * 100 / 100)
    }
</script>
