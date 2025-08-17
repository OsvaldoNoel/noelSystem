<div class="menu-item has-sub {{ Request::is('*/stock/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="bx:layer"></iconify-icon>
        </span>
        <span class="menu-text">STOCK</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu">  
        <div class="menu-item {{ Request::routeIs('app.compras') ? 'active' : '' }}">
            <a href="{{ route('app.compras') }}" class="menu-link">
                <span class="menu-text">Compras</span>
            </a>
        </div>
        <div class="menu-item {{ Request::routeIs('app.productos') ? 'active' : '' }}">
            <a href="{{ route('app.productos') }}" class="menu-link">
                <span class="menu-text">Productos</span>
            </a>
        </div>
        <div class="menu-item {{ Request::routeIs('app.stockAdmin') ? 'active' : '' }}">
            <a href="{{ route('app.stockAdmin') }}" class="menu-link">
                <span class="menu-text">Admin</span>
            </a>
        </div>
        
    </div>
</div>