<form> 
    
    <div class="form-group">
        <label class="mb-0 text-muted" for="name">Nombre de la Subcategoria</label>
        <div class="input-group">
            <span class="input-group-text text-theme"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="name" type="text" class="form-control text-theme" id="name" placeholder="Nombre" autocomplete="off">
        </div>
        @error('name') <span class="error text-danger er">{{ $message }}</span> @enderror
    </div> 

    <div class="col mt-3">  
        <livewire:tenant.stock.categorias.selected-categorias>
        @error('categoria_id') <span class="error text-danger">{{ $message }}</span> @enderror
    </div>

</form>
