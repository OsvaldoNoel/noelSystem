<div>
    <div class="card position-relative border-0">
        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
            style="pointer-events: none;"></div>

        <div class="card-header with-btn bg-black">
            <ul class="nav nav-pills m-3" id="pills-tab">
                <li class="nav-item">
                    <a class="nav-link @if($activeTab === 'pills-listado') active @endif" 
                       id="pills-listado-tab" 
                       wire:click="changeTab('pills-listado')"
                       data-bs-toggle="pill" 
                       href="#pills-listado">Lista de Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($activeTab === 'pills-roles') active @endif" 
                       id="pills-roles-tab"
                       wire:click="changeTab('pills-roles')"
                       data-bs-toggle="pill" 
                       href="#pills-roles">Definir Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if($activeTab === 'pills-asignRoles') active @endif" 
                       id="pills-asignRoles-tab"
                       wire:click="changeTab('pills-asignRoles')"
                       data-bs-toggle="pill" 
                       href="#pills-asignRoles">Asignar Rol al usuario</a>
                </li>
                
                @if($hasBranches)
                <li class="nav-item">
                    <a class="nav-link @if($activeTab === 'pills-asignSucursal') active @endif" 
                       id="pills-asignSucursal-tab"
                       wire:click="changeTab('pills-asignSucursal')"
                       data-bs-toggle="pill" 
                       href="#pills-asignSucursal">Asignar Sucursal</a>
                </li>
                @endif
                
            </ul>

            <div class="card-header-btn">
                <a href="#" data-toggle="card-collapse" class="btn">
                    <iconify-icon icon="material-symbols-light:stat-minus-1"></iconify-icon>
                </a>
                <a href="#" data-toggle="card-remove" class="btn">
                    <iconify-icon icon="material-symbols-light:close-rounded"></iconify-icon>
                </a>
            </div>
        </div>
        
        <div class="card-body bg-info-subtle">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade @if($activeTab === 'pills-listado') show active @endif" id="pills-listado">
                    <livewire:tenant.config.users.list.user-list-controller />
                </div>

                <div class="tab-pane fade @if($activeTab === 'pills-roles') show active @endif" id="pills-roles">
                    <livewire:tenant.config.users.roles.role-manager />
                </div>

                <div class="tab-pane fade @if($activeTab === 'pills-asignRoles') show active @endif" id="pills-asignRoles">
                    <livewire:tenant.config.users.roles.user-role-assignment />
                </div>

                @if($hasBranches)
                <div class="tab-pane fade @if($activeTab === 'pills-asignSucursal') show active @endif" id="pills-asignSucursal">
                    <livewire:tenant.config.users.sucursal.user-branch-assignment />
                </div>
                @endif

            </div>
        </div>
    </div>
</div>