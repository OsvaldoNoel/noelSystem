<div class="menu-item has-sub {{ Request::is('*/reportes/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="bx:layer"></iconify-icon>
        </span>
        <span class="menu-text">REPORTES</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu"> 
        <div class="menu-item {{ Request::routeIs('reporteCompras') ? 'active' : '' }}">
            <a href="{{ route('reporteCompras') }}" class="menu-link">
                <span class="menu-text">Compras</span>
            </a>
        </div>
         
    </div>
</div>