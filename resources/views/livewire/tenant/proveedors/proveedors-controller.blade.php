<div>
    <!-- BEGIN container -->
    {{-- <div class="container"> --}}
    @include('livewire.tenant.proveedors.modals')

    <!-- BEGIN #vendesores -->
    <div id="proveedors">
        <div class="card">
            <div class="card-header with-btn">
                PROVEEDORES
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
                @include('livewire.tenant.proveedors.tableProveedors')
            </div>
        </div>
    </div>
    <!-- END #proveedors -->
</div>

{{-- </div> --}}
<!-- END container -->