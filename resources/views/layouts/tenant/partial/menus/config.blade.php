<div class="menu-item has-sub {{ Request::is('*/config/*') ? 'active expand' : '' }}">
    <a href="#" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="ph:gear-duotone"></iconify-icon>
        </span>
        <span class="menu-text">CONFIG</span>
        <span class="menu-caret"><b class="caret"></b></span>
    </a>
    <div class="menu-submenu">
        <div class="menu-item {{ Request::routeIs('usersTenant') ? 'active' : '' }}">
            <a href="{{ route('usersTenant') }}" class="menu-link">
                <span class="menu-text">Usuarios</span>
            </a>
        </div>
        <div class="menu-item {{ Request::routeIs('tesoreriaConfig') ? 'active' : '' }}">
            <a href="{{ route('tesoreriaConfig') }}" class="menu-link">
                <span class="menu-text">Tesoreria</span>
            </a>
        </div> 
         
    </div>
</div>