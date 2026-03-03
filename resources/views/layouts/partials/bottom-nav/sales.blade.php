<div class="bottom-nav">
    <a href="{{ route('salesperson.dashboard') }}" class="nav-item {{ request()->routeIs('salesperson.dashboard') ? 'active' : '' }}">
        <iconify-icon icon="lucide:layout-dashboard" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Dashboard</span>
    </a>
    <a href="{{ route('salesperson.shops.index') }}" class="nav-item {{ request()->routeIs('salesperson.shops.*') ? 'active' : '' }}">
        <iconify-icon icon="lucide:store" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Shops</span>
    </a>

    <a href="{{ route('salesperson.visits.index') }}" class="nav-item {{ request()->routeIs('salesperson.visits.*') ? 'active' : '' }}">
        <iconify-icon icon="lucide:map-pin-check" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Visits</span>
    </a>

    <a href="{{ route('salesperson.products.index') }}" class="nav-item {{ request()->routeIs('salesperson.products.index') ? 'active' : '' }}">
        <iconify-icon icon="lucide:package" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Products</span>
    </a>
    <a href="{{ route('salesperson.profile.index') }}" class="nav-item {{ request()->routeIs('salesperson.profile.index') ? 'active' : '' }}">
        <iconify-icon icon="lucide:user" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Profile</span>
    </a>
</div>