<form wire:submit="save">
    <input type="text" class="d-none" id="name2" autocomplete="off">

    <div class="row"> 
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="ci">Ced. de Identidad</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-id-card"></i></span>
                <input wire:model="ci" type="number" onkeydown="filtrarTeclas()" onkeyup="quitarCeroIzq()"
                    class="form-control text-theme ci-input" id="ci" placeholder="Ced. de Identidad"
                    autocomplete="nop">
            </div>
            @error('ci')<span class="error text-danger">{{ $message }}</span>@enderror
            @error('username')<span class="error text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="email">e-mail</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-envelope"></i></span>
                <input wire:model="email" type="email" class="form-control text-theme" id="email"
                    placeholder="ejemplo@gmail.com" autocomplete="nop" disabled>
            </div>
            @error('email')<span class="error text-danger">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="row">
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="name">Nombre</label>
            <div class="input-group">
                <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                <input wire:model="name" type="text" class="form-control text-theme" id="name"
                    placeholder="Nombre" autocomplete="nop" disabled>
            </div>
            @error('name')<span class="error text-danger">{{ $message }}</span>@enderror
        </div>

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="lastname">Apellido</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-user"></i></span>
                <input wire:model="lastname" type="text" class="form-control text-theme" id="lastname"
                    placeholder="Apellido" autocomplete="nop" disabled>
            </div>
            @error('lastname')<span class="error text-danger">{{ $message }}</span>@enderror
        </div> 
    </div>

    <div class="row">  
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="phone">Telefono</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-phone"></i></span>
                <input wire:model="phone" type="text" class="form-control text-theme" id="phone"
                    placeholder="Telefono" autocomplete="nop" disabled>
            </div>
            @error('phone')<span class="error text-danger">{{ $message }}</span>@enderror
        </div> 

        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="barrio">Barrio</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-map-marker-alt"></i></span>
                <input wire:model="barrio" type="text" class="form-control text-theme" id="barrio"
                    placeholder="Barrio" autocomplete="nop" disabled>
            </div>
            @error('barrio')<span class="error text-danger">{{ $message }}</span>@enderror
        </div>
    </div>

    <div class="row"> 
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="address">Dirección</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-home"></i></span>
                <input wire:model="address" type="text" class="form-control text-theme" id="address"
                    placeholder="Direccion" autocomplete="nop" disabled>
            </div>
            @error('address')<span class="error text-danger">{{ $message }}</span>@enderror
        </div>
        <div class="form-group col-sm-6 mt-3">
            <label class="mb-0 text-muted" for="city">Ciudad</label>
            <div class="input-group"> 
                <span class="input-group-text text-theme"><i class="fas fa-city"></i></span>
                <input wire:model="city" type="text" class="form-control text-theme" id="city"
                    placeholder="Ciudad" autocomplete="nop" disabled>
            </div>
            @error('city')<span class="error text-danger">{{ $message }}</span>@enderror
        </div>
    </div>
</form>