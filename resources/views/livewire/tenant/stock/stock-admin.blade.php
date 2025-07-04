<div>
    <!-- BEGIN container -->
    <div class="container">
        <!-- BEGIN row -->
        <div class="row justify-content-center">
            <!-- BEGIN col-10 -->
            <div class="col-xl-10">
                <!-- BEGIN row -->
                <div class="row">
                    <!-- BEGIN col-9 -->
                    <div class="col-xl-9">
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">PRODUCTOS</a></li>
                            <li class="breadcrumb-item"><a href="#">PROVEEDORES</a></li>
                            <li class="breadcrumb-item"><a href="#">CLIENTES</a></li>
                        </ul>

                        <hr class="mb-4 opacity-3" />

                        <!-- BEGIN #itemMarcas -->
                        <div id="itemMarcas" class="mb-5">
                            <h4>Marcas</h4>
                            <p>
                                Las Marcas son utilizadas en el formulario de compra de sus <span
                                    class="text-theme">Productos</span>,
                                serán útiles ademas para filtrar las busquedas y mejorar la productividad en las ventas.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-xl-8 col-lg-6 col-md-8 col-sm-10">
                                    <div class="card">
                                        <div class="card-header with-btn">
                                            <div class="card-header-btn">
                                                <a href="#" data-toggle="card-collapse"
                                                    class="btn"><iconify-icon
                                                        icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                                                <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                                                        icon="material-symbols-light:fullscreen"></iconify-icon></a>
                                                <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                                                        icon="material-symbols-light:close-rounded"></iconify-icon></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <livewire:tenant.stock.marcas.marca-controller />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END #itemMarcas -->

                        <hr class="mb-4 opacity-3" />

                        <!-- BEGIN #itemPresentation -->
                        <div id="itemPresentation" class="mb-5">
                            <h4>Presentataciones</h4>
                            <p>
                                Se refieren al empaque en que son comprados ó seran vendidos los <span
                                    class="text-theme">Productos</span>,
                                estos como ejemplos pueden ser: estuches, cajas, bolsas, individual, balde 10 lt, potes
                                200cc, etc.
                                Son importantes para evitar que estas descripciones sean registradas dos o mas veces de
                                acuerdo a la interpretación
                                del funcionario, y asi mantener la coherencia en en los registros.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-xl-8 col-lg-6 col-md-8 col-sm-10">
                                    <div class="card">
                                        <div class="card-header with-btn">
                                            <div class="card-header-btn">
                                                <a href="#" data-toggle="card-collapse"
                                                    class="btn"><iconify-icon
                                                        icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                                                <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                                                        icon="material-symbols-light:fullscreen"></iconify-icon></a>
                                                <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                                                        icon="material-symbols-light:close-rounded"></iconify-icon></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <livewire:tenant.stock.presentations.presentation-controller />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END #itemPresentation -->

                        <hr class="mb-4 opacity-3" />

                        <!-- BEGIN #itemCategorias -->
                        <div id="itemCategorias" class="mb-5">
                            <h4>Categorias</h4>
                            <p>
                                Designe las categorias para sus <span class="text-theme">Productos</span> de manera
                                global, esto lo ayudara a la hora de generar reportes,
                                ademas de tener un control preciso de las ganancias y utilidades de cada rubro. Pude
                                <span class="text-theme">desactivar</span> una categoria
                                y la misma ya no podrá ser seleccionada en la carga de un producto nuevo.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-xl-8 col-lg-6 col-md-8 col-sm-10">
                                    <div class="card">
                                        <div class="card-header with-btn">
                                            <div class="card-header-btn">
                                                <a href="#" data-toggle="card-collapse"
                                                    class="btn"><iconify-icon
                                                        icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                                                <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                                                        icon="material-symbols-light:fullscreen"></iconify-icon></a>
                                                <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                                                        icon="material-symbols-light:close-rounded"></iconify-icon></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <livewire:tenant.stock.categorias.categoria-controller />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END #itemCategorias -->

                        <hr class="mb-4 opacity-3" />

                        <!-- BEGIN #itemSubcategorias -->
                        <div id="itemSubcategorias" class="mb-5">
                            <h4>Subcategorias</h4>
                            <p>
                                Designe las Subcategorias para sus <span class="text-theme">Productos</span> de manera
                                global, esto lo ayudara a la hora de generar reportes,
                                ademas de tener un control preciso de las ganancias y utilidades de cada rubro. Pude
                                <span class="text-theme">desactivar</span> una categoria
                                y la misma ya no podrá ser seleccionada en la carga de un producto nuevo.
                            </p>
                            <p>
                                Como ejemplo, una Categoria de "CARTERAS" puede tener varias
                                subcategorias: "Cuero", "Escolares", "Deportivos", "De mano", "Hombres", "Mujeres", etc.
                            </p>
                            <div class="row justify-content-center">
                                <div class="col-xl-8 col-lg-7 col-md-10 col-sm-10">
                                    <div class="card">
                                        <div class="card-header with-btn">
                                            <div class="card-header-btn">
                                                <a href="#" data-toggle="card-collapse"
                                                    class="btn"><iconify-icon
                                                        icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                                                <a href="#" data-toggle="card-expand"
                                                    class="btn"><iconify-icon
                                                        icon="material-symbols-light:fullscreen"></iconify-icon></a>
                                                <a href="#" data-toggle="card-remove"
                                                    class="btn"><iconify-icon
                                                        icon="material-symbols-light:close-rounded"></iconify-icon></a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <livewire:tenant.stock.subcategorias.subcategoria-controller />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END #itemSubcategorias -->


                    </div>
                    <!-- END col-9-->
                    <!-- BEGIN col-3 -->
                    <div class="col-xl-3">
                        <!-- BEGIN #sidebar-bootstrap -->
                        <nav id="sidebar-bootstrap" class="navbar navbar-sticky d-none d-xl-block">
                            <nav class="nav">
                                <a class="nav-link" href="#itemMarcas" data-toggle="scroll-to">Marcas</a>
                                <a class="nav-link" href="#itemPresentation"
                                    data-toggle="scroll-to">Presentaciones</a>
                                <a class="nav-link" href="#itemCategorias" data-toggle="scroll-to">Categorias</a>
                                <a class="nav-link" href="#itemSubcategorias"
                                    data-toggle="scroll-to">Subcategorias</a>
                            </nav>
                        </nav>
                        <!-- END #sidebar-bootstrap -->
                    </div>
                    <!-- END col-3 -->
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

