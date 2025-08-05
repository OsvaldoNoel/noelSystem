<div>
    <div class="row">
        <div class="col-xl-6">
            <!-- BEGIN #entidad -->
            <div id="entidad">
                <div class="card">
                    <div class="card-header with-btn">
                        ENTIDADES FINACIERAS, BANCOS Y COOPERATIVAS
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
                        {{-- <livewire:landlord.entidad.entidad-controller /> --}}
                    </div>
                </div>
            </div>
            <!-- END #entidad -->
        </div>

        <div class="col-xl-6">

            <!-- BEGIN #Tenants -->
            <div id="Tenants">
                <div class="card h-100 position-relative border-0">
                    <div class="position-absolute top-0 start-0 w-100 h-100 rounded-4 border border-3 border-info"
                        style="pointer-events: none;"></div>

                    <div class="card-header rounded-top-4 with-btn">
                        <strong class="m-2">EMPRESAS Y SUCURSALES</strong>
                        <div class="card-header-btn">
                            <a href="#" data-toggle="card-collapse" class="btn"><iconify-icon
                                    icon="material-symbols-light:stat-minus-1"></iconify-icon></a>
                            <a href="#" data-toggle="card-expand" class="btn"><iconify-icon
                                    icon="material-symbols-light:fullscreen"></iconify-icon></a>
                            <a href="#" data-toggle="card-remove" class="btn"><iconify-icon
                                    icon="material-symbols-light:close-rounded"></iconify-icon></a>
                        </div>
                    </div>
                    <div class="card-body rounded-bottom-4">
                        <livewire:landlord.tenants.tenant-controller />
                    </div>
                </div>
            </div>
            <!-- END #Tenants -->

        </div>
    </div>
</div>
