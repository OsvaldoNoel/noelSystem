<div class="menu-item has-sub {{ Request::is('*/finanzas/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="bx:layer"></iconify-icon>
        </span>
        <span class="menu-text">FINANZAS</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu">
        <div class="menu-item {{ Request::routeIs('finanzas') ? 'active' : '' }}">
            <a href="{{ route('finanzas') }}" class="menu-link">
                <span class="menu-text">Vista General</span>
            </a>
        </div> 

        <div class="menu-item {{ Request::routeIs('cajaTesoreria') ? 'active' : '' }}">
            <a href="{{ route('cajaTesoreria') }}" class="menu-link">
                <span class="menu-text">Caja Tesoreria</span>
            </a>
        </div> 

        <div class="menu-item {{ Request::routeIs('bancos') ? 'active' : '' }}">
            <a href="{{ route('bancos') }}" class="menu-link">
                <span class="menu-text">Entidades Financieras</span>
            </a>
        </div> 

        <div class="menu-item {{ Request::routeIs('aportes') ? 'active' : '' }}">
            <a href="{{ route('aportes') }}" class="menu-link">
                <span class="menu-text">Aportes</span>
            </a>
        </div> 
         
    </div>
</div>