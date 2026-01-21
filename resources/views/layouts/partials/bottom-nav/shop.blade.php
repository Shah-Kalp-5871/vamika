<div class="bottom-nav">
    <a href="{{ route('shop-owner.dashboard') }}" class="nav-item">
        <span class="nav-icon">
            <iconify-icon icon="mdi:home-outline"></iconify-icon>
        </span>
        <span>Home</span>
    </a>

    <a href="{{ route('shop-owner.products.index') }}" class="nav-item">
        <span class="nav-icon">
            <iconify-icon icon="mdi:package-variant-closed"></iconify-icon>
        </span>
        <span>Products</span>
    </a>

    <a href="{{ route('shop-owner.cart.index') }}" class="nav-item">
        <span class="nav-icon">
            <iconify-icon icon="mdi:cart-outline"></iconify-icon>
        </span>
        <span>Cart</span>
    </a>

    <a href="{{ route('shop-owner.orders.index') }}" class="nav-item">
        <span class="nav-icon">
            <iconify-icon icon="mdi:clipboard-list-outline"></iconify-icon>
        </span>
        <span>Orders</span>
    </a>

    <a href="{{ route('shop-owner.profile.index') }}" class="nav-item">
        <span class="nav-icon">
            <iconify-icon icon="mdi:account-outline"></iconify-icon>
        </span>
        <span>Profile</span>
    </a>
</div>