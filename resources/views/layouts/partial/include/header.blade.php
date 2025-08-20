<!-- BEGIN #header -->
<div id="header" class="app-header d-flex align-items-center justify-content-between pe-3 h-50px">

    <!-- Sección Izquierda: Toggle y Logo -->
    <div class="header-left d-flex align-items-center">
        @if ($showNormalUI)
            <!-- Toggle Sidebar -->
            <div class="desktop-toggler me-3">
                <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed"
                    data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
                    <span class="bar"></span>
                    <span class="bar"></span>
                </button>
            </div>
        @endif

        <!-- Logo y Nombre del Sistema -->
        <div class="brand">
            <a href="#" class="brand-logo d-flex align-items-center text-decoration-none">
                <iconify-icon icon="lets-icons:time-progress-duotone" class="fs-24px me-2 text-theme"></iconify-icon>
                <strong class="text-theme">NOEL SYSTEM</strong>
            </a>
        </div>
    </div>

    @if ($showNormalUI)
        <!-- Sección Central: Accesos Rápidos -->
        <div class="header-center d-flex justify-content-center">
            @if ($user->hasRole('Propietario') || $user->hasRole('Admin'))
                <a href="{{ route('app.carrito') }}" class="btn btn-icon mx-2" title="Ventas">
                    <i class="fas fa-cash-register fa-lg text-success"></i>
                </a>
                <a href="{{ route('app.compras') }}" class="btn btn-icon mx-2" title="Compras">
                    <i class="fas fa-shopping-cart fa-lg text-primary"></i>
                </a>
                <a href="{{ route('app.reporteCompras') }}" class="btn btn-icon mx-2" title="Reportes">
                    <i class="fas fa-chart-bar fa-lg text-info"></i>
                </a>
            @endif

            @if ($user->hasRole('Propietario') || $user->hasRole('Admin'))
                <a href="{{ route('app.configTenant') }}" class="btn btn-icon mx-2" title="Configuración">
                    <i class="fas fa-cogs fa-lg text-warning"></i>
                </a>
            @endif
        </div>
    @endif

    <!-- Sección Derecha: Perfil de Usuario -->
    <div class="header-right">
        <div class="menu">
            <div class="menu-item dropdown dropdown-mobile-full">
                <a href="#" data-bs-toggle="dropdown" data-bs-display="static"
                    class="menu-link d-flex align-items-center">
                    <div class="online me-sm-2 ms-lg-0 ms-n2">
                        <img src="{{ $profilePhotoUrl }}" alt="{{ $user->profile->name }}" class="rounded-circle"
                            style="width: 40px; height: 40px; object-fit: cover; border: 1px solid var(--bs-primary)">
                    </div>
                    <div class="menu-text d-sm-block d-none text-center">
                        <span class="d-block">{{ $user->profile->name }}</span>
                        <small class="d-block text-info fs-10px">
                            {{ $this->getUserRoleText() }}
                        </small>
                    </div>
                </a>
                @if ($showNormalUI)
                    <div
                        class="dropdown-menu dropdown-menu-end me-lg-3 fs-10px fade rounded-2 border border-1 border-primary">
                        <h6 class="dropdown-header text-theme text-center">CONFIGURACIONES</h6>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('app.profile') }}">
                            <i class="fas fa-user-circle me-2"></i> PERFIL DEL USUARIO
                        </a>
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-cog me-2"></i> ACCOUNT SETTINGS
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-info text-center fs-5" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i> SALIR
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- END #header -->
