<div class="menu-item has-sub {{ Request::is('*/clientes/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="bx:layer"></iconify-icon>
        </span>
        <span class="menu-text">CLIENTES</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu">
        <div class="menu-item {{ Request::routeIs('app.clientes') ? 'active' : '' }}">
            <a href="{{ route('app.clientes') }}" class="menu-link">
                <span class="menu-text">Lista de clientes</span>
            </a>
        </div>
         
    </div>
</div>