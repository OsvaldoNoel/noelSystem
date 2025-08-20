<div class="menu-item {{ Request::routeIs('app.home') ? 'active' : '' }}">
    <a href="{{ route('app.home') }}" class="menu-link">
        <span class="menu-icon">
            <iconify-icon icon="ph:rocket-duotone"></iconify-icon>
        </span>
        <span class="menu-text">DASHBOARD</span>
    </a>
</div>