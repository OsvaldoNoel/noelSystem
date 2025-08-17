<div class="menu-item has-sub {{ Request::is('*/ventas/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="bx:layer"></iconify-icon>
        </span>
        <span class="menu-text">VENTAS</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu"> 
        <div class="menu-item {{ Request::routeIs('app.carrito') ? 'active' : '' }}">
            <a href="{{ route('app.carrito') }}" class="menu-link">
                <span class="menu-text">Carrito</span>
            </a>
        </div>
         
    </div>
</div>