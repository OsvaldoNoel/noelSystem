<form wire:submit="save">
    <input type="text" class="d-none" id="name2" autocomplete="off">
    <div class="row">
        <div class="form-group col-sm-6">
            <label class="mb-0 text-muted" for="name">Nombre completo</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="name"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="name" type="text" class="form-control text-theme" id="name"
                    placeholder="Nombre" autocomplete="nop">
            </div>
            @error('name')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="col-sm-6">
            <div class="row row-min">
                <div class="form-group col-9">
                    <label class="mb-0 text-muted" for="ruc">RUC</label>
                    <div class="input-group">
                        <span class="input-group-text text-success"><label for="ruc"><Span
                                    class="fa fa-edit pointer"></Span></label></span>
                        <input wire:model="ruc" type="number" onkeydown="filtrarTeclas()" onkeyup="quitarCeroIzq()"
                            class="form-control text-end text-theme" id="ruc" placeholder="RUC"
                            autocomplete="nop">
                    </div>
                </div>

                <div class="form-group col-3">
                    <label class="mb-0 text-muted" for="dv">DV </label>
                    <div class="input-group">
                        <input wire:model="dv" type="number" onkeydown="filtrarTeclas()" onkeyup="calcularDv()"
                            class="form-control text-theme" id="dv" placeholder="DV" autocomplete="nop">
                    </div>

                </div>
            </div>
            @error('ruc')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
            @error('dv')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="phone">Telefono</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="phone"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="phone" type="text" class="form-control text-theme" id="phone"
                    placeholder="Telefono" autocomplete="nop">
            </div>
            @error('phone')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="email">e-mail</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="email"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="email" type="email" class="form-control text-theme" id="email"
                    placeholder="ejemplo@gmail.com" autocomplete="nop">
            </div>
            @error('email')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="barrio">Barrio</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="barrio"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="barrio" type="text" class="form-control text-theme" id="barrio"
                    placeholder="Barrio" autocomplete="nop">
            </div>
            @error('barrio')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="city">Ciudad</label>
            <div class="input-group">
                <span class="input-group-text text-success"><label for="city"><Span
                            class="fa fa-edit pointer"></Span></label></span>
                <input wire:model="city" type="text" class="form-control text-theme" id="city"
                    placeholder="Ciudad" autocomplete="nop">
            </div>
            @error('city')
                <span class="error text-danger">{{ $message }}</span>
            @enderror
        </div>
    </div>

    <div class="form-group mt-3">
        <label class="mb-0 text-muted" for="adress">Dirección</label>
        <div class="input-group">
            <span class="input-group-text text-success"><label for="adress"><Span
                        class="fa fa-edit pointer"></Span></label></span>
            <input wire:model="adress" type="text" class="form-control text-theme" id="adress"
                placeholder="Direccion" autocomplete="nop">
        </div>
        @error('adress')
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
