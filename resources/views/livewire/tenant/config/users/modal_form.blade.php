<form> 
    
    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="ci" type="text" class="form-control text-theme" id="ci" placeholder="Cedula de Identidad" autocomplete="no_autocompletar">
        </div>
        @error('ci') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="name" type="text" class="form-control text-theme" id="name" placeholder="Nombres" autocomplete="no_autocompletar">
        </div>
        @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="lastname" type="text" class="form-control text-theme" id="lastname" placeholder="Apellidos" autocomplete="no_autocompletar">
        </div>
        @error('lastname') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="phone" type="text" class="form-control text-theme" id="phone" placeholder="Telefono" autocomplete="no_autocompletar">
        </div>
        @error('phone') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="email" type="email" class="form-control text-theme" id="email" placeholder="Correo electronico" autocomplete="no_autocompletar">
        </div>
        @error('email') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="addres" type="text" class="form-control text-theme" id="addres" placeholder="Dirección" autocomplete="no_autocompletar">
        </div>
        @error('addres') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="barrio" type="text" class="form-control text-theme" id="barrio" placeholder="Barrio" autocomplete="no_autocompletar">
        </div>
        @error('barrio') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

    <div class="form-group mb-4">
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="city" type="text" class="form-control text-theme" id="city" placeholder="Ciudad" autocomplete="no_autocompletar">
        </div>
        @error('city') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 

</form>
