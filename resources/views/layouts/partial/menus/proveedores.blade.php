<div class="menu-item has-sub {{ Request::is('*/proveedores/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="bx:layer"></iconify-icon>
        </span>
        <span class="menu-text">PROVEEDORES</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu">
        <div class="menu-item {{ Request::routeIs('app.proveedores') ? 'active' : '' }}">
            <a href="{{ route('app.proveedores') }}" class="menu-link">
                <span class="menu-text">Lista de proveedores</span>
            </a>
        </div>
         
    </div>
</div>