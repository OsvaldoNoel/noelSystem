<form>
    <input type="hidden" wire:model="selected_id">

    <div class="form-group">
        <label for="name"></label>
        <div class="input-group">
            <span class="input-group-text"><Span class="fa fa-edit"></Span></span>
            <input wire:model.lazy="name" type="text" class="form-control" id="name" placeholder="Nombre" autocomplete="off">
        </div>
        @error('name') <span class="error text-danger er">{{ $message }}</span> @enderror
    </div>

</form>
