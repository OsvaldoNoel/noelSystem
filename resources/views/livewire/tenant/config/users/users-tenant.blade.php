<div>
    <div class="card position-relative border-0">
        <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
            style="pointer-events: none;"></div>

        <div class="card-header with-btn bg-black">

            <ul class="nav nav-pills m-3" id="pills-tab">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-listado-tab" data-bs-toggle="pill" href="#pills-listado">Lista de
                        Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-roles-tab" data-bs-toggle="pill" href="#pills-roles">Definir Roles</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-asignRoles-tab" data-bs-toggle="pill" href="#pills-asignRoles">Asignar
                        Rol al usuario</a>
                </li>
            </ul>


            <div class="card-header-btn">
                <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                        icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                        icon="material-symbols-light:close-rounded"></iconify-icon></a>
            </div>
        </div>
        <div class="card-body bg-info-subtle">
            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade show active" id="pills-listado">

                    <livewire:tenant.config.users.list.user-list-controller />

                </div>

                <div class="tab-pane fade" id="pills-roles">

                    <livewire:tenant.config.users.roles.role-manager />

                </div>

                <div class="tab-pane fade" id="pills-asignRoles">

                    <livewire:tenant.config.users.roles.user-role-assignment />

                </div>

            </div>
        </div>
    </div>


</div>
