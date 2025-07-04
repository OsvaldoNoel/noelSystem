<form wire:submit="save">
    <input type="text" class="d-none" id="name2" autocomplete="off">

    <div class="form-group">

        <label class="mb-0 text-muted" for="name">Nombre</label>
        <div class="input-group">
            <span class="input-group-text text-success"><label for="name"><Span
                        class="fa fa-edit pointer"></Span></label></span>
            <input wire:model="name" type="text" class="form-control text-theme" id="name"
                placeholder="Nombre" autocomplete="off">
        </div>
        @error('name')
            <span class="error text-danger">{{ $message }}</span>
        @enderror
        
    </div> 

</form> 
 
