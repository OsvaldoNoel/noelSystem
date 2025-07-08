<div>
    <div class="card">
        <div class="card-header with-btn">
            <h3>Usuarios de la empresa</h3>
            <div class="card-header-btn">
                <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                        icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                        icon="material-symbols-light:fullscreen"></iconify-icon></a>
                <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                        icon="material-symbols-light:close-rounded"></iconify-icon></a>
            </div>
        </div>
        <div class="card-body">
            <div class="col">
                <ul class="nav nav-pills" id="pills-tab">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-listado-tab" data-bs-toggle="pill"
                            href="#pills-listado">Listado</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-tarjetas-tab" data-bs-toggle="pill"
                            href="#pills-tarjetas">Tarjetas</a>
                    </li>
                </ul>
            </div>
            <hr class="mb-3 opacity-3" />
            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade show active" id="pills-listado">

                    <livewire:tenant.config.users.list.user-list-controller />

                </div>

                <div class="tab-pane fade" id="pills-tarjetas">

                    Lugar para asignar Sucursales a los usuarios

                </div>

            </div>
        </div>
    </div>


</div>
