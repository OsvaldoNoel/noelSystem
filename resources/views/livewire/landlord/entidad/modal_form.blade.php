<form wire:submit="save">
    <input wire:model="image" type="file" style="display:none;" id="file" accept="image/png, image/jpeg, image/gif">
    <div class="row">
        <div class="col-sm-4 mt-3">
            <div class="mb-3 bg-white rounded position-relative" style="height: 150px; width: 150px;">
                @if ($image)
                    <a href = "#"> 
                        <img alt="avatar" src="{{ $image->temporaryUrl() }}"
                            class="position-absolute top-50 start-50 translate-middle rounded"
                            style="max-width: 100%; max-height: 100%" onclick="abrir()" />
                    </a>
                @else
                    @if ($imagePrev == null)
                        <a href = "#">
                            <img alt="avatar" src="{{ asset('storage/sin_imagen.png') }}" class="img-fluid rounded"
                                onclick="abrir()" />
                        </a>
                    @else
                        <a href = "#">
                            <img alt="avatar"
                                src="{{ asset('storage/img/config/entidades/' . $imagePrev) }}"
                                class="position-absolute top-50 start-50 translate-middle rounded"
                                style="max-width: 100%; max-height: 100%" onclick="abrir()" />
                        </a>
                    @endif
                @endif
            </div>

            

            <div class="d-flex flex-wrap border justify-content-center p-2"> 
                <div wire:click="renderizarColor('bg-primary')" class="w-30px h-30px rounded bg-primary m-2 pointer"></div>
                <div wire:click="renderizarColor('bg-teal')" class="w-30px h-30px rounded bg-teal m-2 pointer"></div>
                <div wire:click="renderizarColor('bg-danger')" class="w-30px h-30px rounded bg-danger m-2 pointer"></div>
                <div wire:click="renderizarColor('bg-info')" class="w-30px h-30px rounded bg-info m-2 pointer"></div>
                <div wire:click="renderizarColor('bg-yellow')" class="w-30px h-30px rounded bg-yellow m-2 pointer"></div>
                <div wire:click="renderizarColor('bg-purple')" class="w-30px h-30px rounded bg-purple m-2 pointer"></div>
            </div>

            @error('color')
                <span class="error text-danger">{{ $message }}</span>
            @enderror


        </div>

        <div class="form-group col-sm-8 mt-3">
            <label class="mb-0 text-muted" for="ent">Entidad</label>
            <div class="input-group">
                <select wire:model="entidad" class="form-select text-theme" id="ent">
                    <option class="opt" value="Banco">Banco</option>
                    <option class="opt" value="Financiera">Financiera</option>
                    <option class="opt" value="Cooperativa">Cooperativa</option>
                </select>
            </div>
            @error('entidad')
                <span class="error text-danger">{{ $message }}</span>
            @enderror

            <label class="mb-0 text-muted mt-3" for="name">Nombre</label>
            <div class="input-group">
                <input type="text"  style="display:none;" id="name2" autocomplete="off">
                <input wire:model="name" type="text" class="form-control text-theme" id="name"
                    placeholder="Banco, Finaciera o cooperativa" autocomplete="nop">
            </div>
            @error('name')
                <span class="error text-danger">{{ $message }}</span>
            @enderror 

            <label class="mb-0 text-muted mt-3" for="addres">Dirección de la entidad</label>
            <div class="input-group">
                <input wire:model="addres" type="text" class="form-control text-theme" id="addres"
                    placeholder="Nombre" autocomplete="nop">
            </div>
            @error('addres')
                <span class="error text-danger">{{ $message }}</span>
            @enderror

            <label class="mb-0 text-muted mt-3" for="phone">Telefono de la entidad</label>
            <div class="input-group">
                <input wire:model="phone" type="text" class="form-control text-theme" id="phone"
                    placeholder="Telefono" autocomplete="nop">
            </div>
            @error('phone')
                <span class="error text-danger">{{ $message }}</span>
            @enderror

            <div class="d-flex align-items-center mt-3">
                <div class="mb-0 text-muted">Color de enfasis</div>
                <div class="w-30px h-30px rounded {{ $color }} ms-3"></div>
            </div>

        </div>
    </div>

</form>

<script>
    //-------------abrir selector de archivos---------
    function abrir() {
        var file = document.getElementById("file").click();
    }
</script>

