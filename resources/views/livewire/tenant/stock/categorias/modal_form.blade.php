<form>  
    <div class="form-group">
        <label for="name"></label>
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="name" type="text" class="form-control text-theme" id="name" placeholder="Nombre" autocomplete="off">
        </div>
        @error('name') <span class="error text-danger">{{ $message }}</span> @enderror
    </div> 
</form>
