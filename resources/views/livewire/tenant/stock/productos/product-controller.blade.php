<div>
    <!-- BEGIN container -->
    <div class="container">
        @include('livewire.tenant.stock.productos.modals')

        <!-- BEGIN #vendesores -->
        <div id="productos">
            <div class="card">
                <div class="card-header with-btn">
					PRODUCTOS
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
                    @include('livewire.tenant.stock.productos.tableProducts')
                </div>
            </div>
        </div>
        <!-- END #productos -->
    </div>
</div>
<!-- END container --> 

<script>
    function filtrarTeclas() {
        var tecla = event.key;
        if (['.', 'e', ',', '-', '+'].includes(tecla))
            event.preventDefault()
    } 
</script>
