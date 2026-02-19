<div class="bottom-nav shopkeeper-nav">
    <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <span class="nav-icon">
            <iconify-icon icon="mdi:view-dashboard-outline"></iconify-icon>
        </span>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <span class="nav-icon">
            <iconify-icon icon="mdi:account-group-outline"></iconify-icon>
        </span>
        <span>Users</span>
    </a>

    <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
        <span class="nav-icon">
            <iconify-icon icon="mdi:package-variant-closed"></iconify-icon>
        </span>
        <span>Products</span>
    </a>
    
    <a href="{{ route('admin.reports.visit') }}" class="nav-item {{ request()->routeIs('admin.reports.visit') ? 'active' : '' }}">
        <span class="nav-icon">
            <iconify-icon icon="lucide:map-pin-check"></iconify-icon>
        </span>
        <span>Visits</span>
    </a>

    <a href="{{ route('admin.bits.index') }}" class="nav-item {{ request()->routeIs('admin.bits.*') ? 'active' : '' }}">
        <span class="nav-icon">
            <iconify-icon icon="mdi:map-marker-path"></iconify-icon>
        </span>
        <span>Bits</span>
    </a>

    <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
        <span class="nav-icon">
            <iconify-icon icon="mdi:cash-multiple"></iconify-icon>
        </span>
        <span>Orders</span>
    </a>
</div>