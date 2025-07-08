<!-- BEGIN #header -->
<div id="header" class="app-header">
    <!-- BEGIN desktop-toggler -->
    <div class="desktop-toggler">
        <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-collapsed"
            data-dismiss-class="app-sidebar-toggled" data-toggle-target=".app">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END desktop-toggler -->

    <!-- BEGIN mobile-toggler -->
    <div class="mobile-toggler">
        <button type="button" class="menu-toggler" data-toggle-class="app-sidebar-mobile-toggled"
            data-toggle-target=".app">
            <span class="bar"></span>
            <span class="bar"></span>
        </button>
    </div>
    <!-- END mobile-toggler -->

    <!-- BEGIN brand -->
    <div class="brand">
        <a href="#" class="brand-logo w-100 d-flex align-items-center">
            <iconify-icon icon="lets-icons:time-progress-duotone" class="fs-24px me-2 text-theme"></iconify-icon>
            @if (auth()->check())
                <div class="d-flex flex-column text-center">
                    <span class="brand-text fs-11px text-theme">
                        {{ session('tenant') ? strtoupper(session('tenant')) : 'SYSTEM ADMIN' }}
                    </span>
                    <span class="brand-text fw-500 fs-10px">
                        {{ session('sucursal') ? session('sucursal') : '' }}
                    </span>
                </div>
            @endif
        </a>
    </div>
    <!-- END brand -->

    <!-- BEGIN menu -->
    <div class="menu">
        <div class="menu-item dropdown d-lg-flex d-none me-3">
            <div class="menu-search-inline">
                <iconify-icon icon="ph:magnifying-glass-duotone" class="menu-icon"></iconify-icon>
                <input class="form-control" placeholder="Buscar..." value="" name="keywords" />
            </div>
        </div>
        <div class="menu-item dropdown">
            <a href="#" data-toggle="theme-panel-expand" class="menu-link menu-link-icon">
                <iconify-icon icon="ph:gear-duotone" class="menu-icon"></iconify-icon>
            </a>
            <div class="dropdown-menu dropdown-menu-end fade">
                <h6 class="dropdown-header">Settings</h6>
                <a class="dropdown-item" href="#">General Settings</a>
                <a class="dropdown-item" href="#">System Preferences</a>
                <a class="dropdown-item" href="#">Security Settings</a>
                <a class="dropdown-item" href="#">Application Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#">About</a>
                <a class="dropdown-item" href="#">Feedback</a>
            </div>
        </div>
        <div class="menu-item dropdown dropdown-mobile-full">
            <a href="#" data-bs-toggle="dropdown" data-bs-display="static"
                class="menu-link d-flex align-items-center">
                <div class="menu-img online me-sm-2 ms-lg-0 ms-n2">
                    <img src="{{ asset('assets/img/user/profile.jpg') }}" alt="Profile" class="" />
                </div>
                <div class="menu-text d-sm-block d-none">
                    <span class="d-block"><span>{{ Auth::user()->name }}</span></span>
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end me-lg-3 fs-10px fade">
                <h6 class="dropdown-header">USER OPTIONS</h6>
                <a class="dropdown-item" href="profile.html">VIEW PROFILE</a>
                <a class="dropdown-item" href="settings.html">ACCOUNT SETTINGS</a>
                <a class="dropdown-item" href="calendar.html">CALENDER SETTINGS</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="helper.html">HELP & SUPPORT</a>

                <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); 
					document.getElementById('logout-form').submit();">LOG OUT
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>


            </div>
        </div>
    </div>
    <!-- END menu -->

    <!-- BEGIN menu-search-float -->
    <form class="menu-search-float" method="POST" name="header_search_form">
        <div class="menu-search-container">
            <div class="menu-search-icon"><i class="bi bi-search"></i></div>
            <div class="menu-search-input">
                <input type="text" class="form-control" placeholder="Search something..." />
            </div>
            <div class="menu-search-icon">
                <a href="#" data-toggle-class="app-header-menu-search-toggled" data-toggle-target=".app"><i
                        class="bi bi-x-lg"></i></a>
            </div>
        </div>
    </form>
    <!-- END menu-search-float -->
</div>
<!-- END #header -->
