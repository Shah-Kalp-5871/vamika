<div class="bottom-nav">
    <a href="{{ route('salesperson.dashboard') }}" class="nav-item">
        <iconify-icon icon="lucide:layout-dashboard" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Dashboard</span>
    </a>
    <!-- <a href="{{ route('salesperson.shops.index') }}" class="nav-item">
        <iconify-icon icon="lucide:store" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Shops</span>
    </a> -->
    <a href="{{ route('salesperson.shops.select') }}" class="nav-item">
        <iconify-icon icon="lucide:shopping-bag" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Order</span>
    </a>

    <a href="{{ route('salesperson.visits.index') }}" class="nav-item">
        <iconify-icon icon="lucide:map-pin-check" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Visits</span>
    </a>

    <a href="{{ route('salesperson.products.index') }}" class="nav-item">
        <iconify-icon icon="lucide:package" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Products</span>
    </a>
    <a href="{{ route('salesperson.profile.index') }}" class="nav-item">
        <iconify-icon icon="lucide:user" width="20" class="mb-1 nav-icon"></iconify-icon>
        <span>Profile</span>
    </a>
</div>