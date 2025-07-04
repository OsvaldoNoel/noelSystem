<div>
    @include('livewire.tenant.config.bancos.modals')
    
    <div class="card">
        <div class="card-header bg-info bg-opacity-15 d-flex align-items-center">
            <a wire:click="addModal()" class="nav-link fs-3 btnPluss">
                <i class="fa-solid fa-square-plus pointer me-3"></i>
            </a>
            BANCOS
        </div>
        <div class="card-body"> 
            
            <div class="row row-cols-1 row-cols-md-2 g-3">

                @foreach ($datos as $dato)
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body {{ $dato['entidad_color'] }} bg-opacity-10 ">
                                <div wire:ignore class="d-flex align-items-center ">

                                    @if(  $dato['entidad_image']  )
                                        <img src="{{ asset('storage/img/config/entidades/'. $dato['entidad_image'] .'') }}" alt=""
                                        class="w-80px h-80px object-fit-cover rounded" />
                                    @endif 

                                    <div class="flex-fill ps-3">
                                        <h5 class="mb-1 text-info">
                                            {{ $dato['entidad_nombre'] }}
                                        </h5>
                                        <div class="small">Cta. Cte.: {{ $dato['cuenta'] }}</div>
                                        <div class="form-check form-switch mt-3">
                                            <input wire:click="statusFn({{ $dato['id'] }})" type="checkbox"
                                                class="form-check-input pointer" {{ $dato['status'] == 1 ? 'checked' : '' }}
                                                id="status{{ $componentName }}{{ $dato['id'] }}">
                                            <label class="form-check-label pointer" for="status{{ $componentName }}{{ $dato['id'] }}">
                                                {{ $dato['status'] == 1 ? 'Activo' : 'Inactivo' }}
                                            </label>
                                        </div>
                                    </div>

                                    <a href="#" data-bs-toggle="dropdown" class="text-white text-opacity-50"><i
                                            class="bi bi-three-dots-vertical fa-lg"></i></a>
                                    <div class="dropdown-menu dropdown-menu-end bg-black">
                                        <a wire:click="edit({{ $dato['id'] }})" class="dropdown-item text-theme"><i
                                                class="bi-pencil-square"></i> Edit</a>
                                        <a wire:click="destroy({{ $dato['id'] }})" class="dropdown-item text-theme"><i
                                                class="bi-trash3-fill"></i> Delete</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

</div> 
 
