<!-- BEGIN #sidebar -->
<div id="sidebar" class="app-sidebar">
    <!-- BEGIN scrollbar -->
    <div class="app-sidebar-content mt-4" data-scrollbar="true" data-height="100%">
 
		
        <div class="rounded-2 border border-1 border-purple bg-info-subtle m-2">

            @if (auth()->check())
                <div class="d-flex flex-column text-center mt-3 mb-3">
                    <span class="brand-text fs-15px text-purple">
                        {{ session('tenant') ? strtoupper(session('tenant')) : 'SYSTEM ADMIN' }}
                    </span>
                    <span class="brand-text fw-500 fs-12px">
                        {{ session('sucursal') ? session('sucursal') : '' }}
                    </span>
                </div>
            @endif
        </div>

        <!-- BEGIN menu -->
        <div class="menu">

            <div class="mt-3"></div>
            @include('layouts.tenant.partial.menus.dashboard')

            <div class="menu-header">APP</div>
            @include('layouts.tenant.partial.menus.ventas')
            @include('layouts.tenant.partial.menus.stock')
            @include('layouts.tenant.partial.menus.clientes')
            @include('layouts.tenant.partial.menus.proveedores')
            @include('layouts.tenant.partial.menus.finanzas')

            <div class="menu-header">ADMIN</div>
            @include('layouts.tenant.partial.menus.config')
            @include('layouts.tenant.partial.menus.reportes')

        </div>
        <!-- END menu -->
        <div class="mt-auto p-15px w-100">
            <a href="#" class="btn d-block btn-secondary btn-sm py-6px w-100">
                DOCUMENTATION
            </a>
        </div>
    </div>
    <!-- END scrollbar -->
</div>
<!-- END #sidebar -->

<!-- BEGIN mobile-sidebar-backdrop -->
<button class="app-sidebar-mobile-backdrop" data-toggle-target=".app"
    data-toggle-class="app-sidebar-mobile-toggled"></button>
<!-- END mobile-sidebar-backdrop -->
