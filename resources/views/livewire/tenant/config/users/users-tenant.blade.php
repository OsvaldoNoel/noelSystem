<div>
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN row -->
        <div class="row justify-content-center">
            <!-- BEGIN col-10 -->
            <div class="col-xl-12">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-10 -->
                    <div class="col-xl-10">

                        <h1 class="page-header">
                            Usuarios de la empresa
                        </h1>

                        <hr class="mb-4 opacity-3" />

                        @include('livewire.tenant.config.users.modals')

                        <!-- BEGIN #vendesores -->
                        <div id="vendedores" class="mb-5">
                            <h4>Vendedores</h4>
                            <div class="card">
                                <div class="card-header with-btn">
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
                                    <div class="row">
                                        <div class="col">
                                            <ul class="nav nav-pills" id="pills-tab">
                                                <li class="nav-item">
                                                    <a class="nav-link active" id="pills-listado-tab"
                                                        data-bs-toggle="pill" href="#pills-listado">Listado</a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link" id="pills-tarjetas-tab" data-bs-toggle="pill"
                                                        href="#pills-tarjetas">Tarjetas</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col text-end">
                                            <a wire:click="addUser()" class="nav-link fs-3 btnPluss">
                                                <i class="fa-solid fa-square-plus"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="mb-3 opacity-3" />
                                    <div class="tab-content" id="pills-tabContent">

                                        <div class="tab-pane fade show active" id="pills-listado">

                                            @include('livewire.tenant.config.users.tableUsers')

                                        </div>

                                        <div class="tab-pane fade" id="pills-tarjetas">

                                            Lugar para las tarjetas

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END #vendedores -->


                    </div>
                    <!-- END col-10 -->
                    <!-- BEGIN col-2 -->
                    <div class="col-xl-2">
                        <!-- BEGIN #sidebar-bootstrap -->
                        <div wire:ignore>
                            <nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
                                <nav class="nav">
                                    <a class="nav-link" href="#vendedores" data-toggle="scroll-to">Vendedores</a>
                                </nav>
                            </nav>
                        </div>
                        <!-- END #sidebar-bootstrap -->
                    </div>
                    <!-- END col-2 -->
                </div>
                <!-- END row -->
            </div>
            <!-- END col-10 -->
        </div>
        <!-- END row -->
    </div>
    <!-- END container -->
</div>

<script>
    document.addEventListener('livewire:init', () => {
        $(document).ready((function() {
            new bootstrap.ScrollSpy(document.body, {
                target: "#sidebar-bootstrap",
                offset: 200
            })
        }));
    });
</script>
