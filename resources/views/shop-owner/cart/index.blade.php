@extends('layouts.shop-owner')

@php
$title = 'Shopping Cart';
$subtitle = 'Review your items';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.products.index');
@endphp

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #FAFAFA;
        -webkit-font-smoothing: antialiased;
        padding-bottom: 140px;
        /* Space for cart summary + bottom nav */
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #E4E4E7;
        border-radius: 20px;
    }

    /* Main content container - MOBILE ONLY */
    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
    }

    /* Cart summary - fixed at bottom */
    .cart-summary {
        position: fixed;
        bottom: 80px;
        /* Above bottom navigation */
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #E2E8F0;
        padding: 16px;
        box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1);
        z-index: 40;
    }

    /* Custom animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }

    /* Cart item hover effect */
    .cart-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Quantity buttons */
    .qty-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: white;
        border: 1px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #4F46E5;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .qty-btn:hover:not(:disabled) {
        background: #4F46E5;
        color: white;
        border-color: #4F46E5;
    }

    .qty-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Switch toggle */
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #E2E8F0;
        transition: .4s;
        border-radius: 24px;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked+.slider {
        background-color: #10B981;
    }

    input:checked+.slider:before {
        transform: translateX(20px);
    }

    /* Hide on larger screens */
    @media (min-width: 768px) {
        body::before {
            content: "Mobile Only View - Please view on mobile device";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999999;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            padding: 20px;
        }
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #E2E8F0;
        border-top-color: #64748B;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Empty state */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
        background: #F8FAFC;
        border-radius: 0.75rem;
        border: 2px dashed #E2E8F0;
    }
</style>

<main class="p-4 main-content">
    <!-- Cart Items -->
    <div id="cartItemsContainer" class="space-y-3">
        <!-- Cart items will be loaded here -->
        <div class="flex items-center justify-center py-12">
            <div class="loading-spinner"></div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyCart" class="empty-state hidden">
        <div class="flex flex-col items-center justify-center">
            <iconify-icon icon="lucide:shopping-cart" width="48" class="text-slate-400 mb-4"></iconify-icon>
            <h4 class="text-lg font-semibold text-slate-700 mb-2">Your Cart is Empty</h4>
            <p class="text-sm text-slate-500 mb-6 max-w-md">
                Add products from the shop to get started with your order
            </p>
            <button onclick="window.location.href='{{ route('shop-owner.products.index') }}'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                <iconify-icon icon="lucide:shopping-bag" width="16"></iconify-icon>
                Continue Shopping
            </button>
        </div>
    </div>

    <!-- Order Summary -->
    <div id="orderSummary" class="hidden bg-white rounded-xl border border-slate-200 mt-6">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">Order Summary</h3>
        </div>

        <div class="p-4 space-y-3">
            <div class="flex justify-between">
                <span class="text-sm text-slate-600">Subtotal</span>
                <span class="text-sm font-medium text-slate-900">₹<span id="cartSubtotal">0</span></span>
            </div>

            <div class="flex justify-between">
                <span class="text-sm text-slate-600">Delivery Charge</span>
                <span class="text-sm font-medium text-slate-900">₹<span id="deliveryCharge">50</span></span>
            </div>

            <!-- Wallet Option -->
            <div class="p-3 rounded-lg border border-slate-200 bg-slate-50 mt-3">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h4 class="text-sm font-medium text-slate-900">Use Wallet Balance</h4>
                        <p class="text-xs text-slate-500">Available: ₹<span id="walletBalance">0</span></p>
                    </div>
                    <label class="switch">
                        <input type="checkbox" id="useWallet" onchange="toggleWallet()">
                        <span class="slider"></span>
                    </label>
                </div>
                <p class="text-xs text-slate-500">Get up to 50% discount on order value</p>
            </div>

            <div id="walletDiscountRow" class="flex justify-between hidden">
                <span class="text-sm text-slate-600">Wallet Discount</span>
                <span class="text-sm font-medium text-emerald-600">-₹<span id="walletDiscount">0</span></span>
            </div>

            <div class="flex justify-between pt-3 border-t border-slate-100">
                <div>
                    <p class="text-sm font-semibold text-slate-900">Total Payable</p>
                    <p class="text-xs text-slate-500">Including all charges</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-bold text-emerald-600">₹<span id="cartTotal">0</span></p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Checkout Button - Fixed at Bottom -->
<div class="cart-summary" id="checkoutButton" style="display: none;">
    <button onclick="proceedToCheckout()"
        class="w-full px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
        <iconify-icon icon="lucide:shopping-cart" width="18"></iconify-icon>
        Proceed to Checkout
    </button>
</div>
@endsection

@section('scripts')
<script>
    let cart = [];
    let walletBalance = 0;
    let useWallet = false;

    // Dummy data - In real app, this would come from Laravel controller
    const dummyData = {
        shopOwner: {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            walletBalance: 12500,
            totalOrders: 47,
            pendingOrders: 8,
            deliveredOrders: 39
        }
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadCartPage();
    });

    function loadCartPage() {
        // Load cart from localStorage
        loadCart();

        // Load wallet balance
        loadWalletBalance();

        // Update display
        updateCartDisplay();
    }

    function loadCart() {
        const savedCart = localStorage.getItem('shop_cart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
        }
    }

    function loadWalletBalance() {
        // Try to get from localStorage first, then dummy data
        const savedShopOwner = localStorage.getItem('shop_owner');
        if (savedShopOwner) {
            const shopOwner = JSON.parse(savedShopOwner);
            walletBalance = shopOwner.walletBalance || 0;
        } else {
            walletBalance = dummyData.shopOwner.walletBalance || 0;
        }
        
        document.getElementById('walletBalance').textContent = walletBalance.toLocaleString();
    }

    function updateCartDisplay() {
        const container = document.getElementById('cartItemsContainer');
        const emptyCart = document.getElementById('emptyCart');
        const orderSummary = document.getElementById('orderSummary');
        const checkoutButton = document.getElementById('checkoutButton');

        if (cart.length === 0) {
            emptyCart.classList.remove('hidden');
            orderSummary.classList.add('hidden');
            checkoutButton.style.display = 'none';
            container.innerHTML = '';
            return;
        }

        emptyCart.classList.add('hidden');
        orderSummary.classList.remove('hidden');
        checkoutButton.style.display = 'block';

        // Render cart items
        container.innerHTML = '';

        cart.forEach((item, index) => {
            const cartItem = document.createElement('div');
            cartItem.className = 'cart-item bg-white rounded-xl border border-slate-200 p-4 transition-all animate-slide-up';
            cartItem.style.animationDelay = `${index * 0.05}s`;

            cartItem.innerHTML = `
                    <div class="flex items-start gap-4">
                        <!-- Product Image -->
                        <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center border border-indigo-200 overflow-hidden flex-shrink-0">
                            <iconify-icon icon="lucide:package" width="24" class="text-indigo-600"></iconify-icon>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-2">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 truncate">${item.name}</h4>
                                    <p class="text-xs text-slate-500">${item.brand || ''} • ${item.unit}</p>
                                </div>
                                <button onclick="removeItem(${item.id})" 
                                        class="p-1.5 rounded-lg hover:bg-red-50 transition-colors">
                                    <iconify-icon icon="lucide:x" width="14" class="text-red-500"></iconify-icon>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button onclick="updateQuantity(${item.id}, -1)" 
                                            class="qty-btn minus">
                                        <iconify-icon icon="lucide:minus" width="12"></iconify-icon>
                                    </button>
                                    <span class="text-sm font-semibold text-slate-900 w-8 text-center">${item.quantity}</span>
                                    <button onclick="updateQuantity(${item.id}, 1)" 
                                            class="qty-btn plus">
                                        <iconify-icon icon="lucide:plus" width="12"></iconify-icon>
                                    </button>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-sm font-medium text-slate-900">₹${item.price} each</p>
                                    <p class="text-sm font-bold text-emerald-600">₹${item.total}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            container.appendChild(cartItem);
        });

        // Calculate totals
        calculateTotals();
    }

    function updateQuantity(productId, change) {
        const itemIndex = cart.findIndex(item => item.id === productId);
        if (itemIndex !== -1) {
            cart[itemIndex].quantity += change;

            if (cart[itemIndex].quantity <= 0) {
                cart.splice(itemIndex, 1);
            } else {
                cart[itemIndex].total = cart[itemIndex].quantity * cart[itemIndex].price;
            }

            saveCart();
            updateCartDisplay();
            
            // Show toast notification
            showToast({
                text: `Quantity updated for ${cart[itemIndex].name}`,
                type: 'success'
            });
        }
    }

    function removeItem(productId) {
        const item = cart.find(item => item.id === productId);
        if (item && confirm(`Are you sure you want to remove ${item.name} from your cart?`)) {
            cart = cart.filter(item => item.id !== productId);
            saveCart();
            updateCartDisplay();
            
            showToast({
                text: `${item.name} removed from cart`,
                type: 'info'
            });
        }
    }

    function saveCart() {
        localStorage.setItem('shop_cart', JSON.stringify(cart));
        updateCartCountInNav();
    }

    function calculateTotals() {
        const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
        const delivery = 50;
        const walletDiscount = useWallet ? Math.min(walletBalance, subtotal * 0.5) : 0;
        const total = Math.max(0, subtotal + delivery - walletDiscount);

        document.getElementById('cartSubtotal').textContent = subtotal.toLocaleString();
        document.getElementById('walletDiscount').textContent = walletDiscount.toLocaleString();
        document.getElementById('cartTotal').textContent = total.toLocaleString();

        // Show/hide wallet discount row
        const walletDiscountRow = document.getElementById('walletDiscountRow');
        if (walletDiscount > 0) {
            walletDiscountRow.classList.remove('hidden');
        } else {
            walletDiscountRow.classList.add('hidden');
        }
    }

    function toggleWallet() {
        useWallet = document.getElementById('useWallet').checked;
        calculateTotals();
        
        showToast({
            text: useWallet ? 'Wallet discount applied' : 'Wallet discount removed',
            type: 'info'
        });
    }

    function proceedToCheckout() {
        if (cart.length === 0) {
            showToast({
                text: 'Your cart is empty',
                type: 'info'
            });
            return;
        }

        // Calculate totals
        const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
        const delivery = 50;
        const walletDiscount = useWallet ? Math.min(walletBalance, subtotal * 0.5) : 0;
        const total = Math.max(0, subtotal + delivery - walletDiscount);

        // Prepare checkout details
        const checkoutDetails = {
            items: cart,
            subtotal: subtotal,
            delivery: delivery,
            walletDiscount: walletDiscount,
            total: total,
            useWallet: useWallet,
            timestamp: new Date().toISOString()
        };

        // Save to localStorage for checkout page
        localStorage.setItem('checkout_details', JSON.stringify(checkoutDetails));
        
        // Redirect to checkout
        window.location.href = '{{ route('shop-owner.checkout.index') }}';
    }

    function clearCart() {
        if (cart.length === 0) {
            showToast({
                text: 'Your cart is already empty',
                type: 'info'
            });
            return;
        }

        if (confirm('Are you sure you want to clear your cart? All items will be removed.')) {
            cart = [];
            saveCart();
            updateCartDisplay();
            
            showToast({
                text: 'Cart cleared successfully',
                type: 'success'
            });
        }
    }

    function updateCartCountInNav() {
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        // Update cart badge in navigation if exists
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            if (totalItems > 0) {
                cartBadge.textContent = totalItems > 9 ? '9+' : totalItems;
                cartBadge.classList.remove('hidden');
            } else {
                cartBadge.classList.add('hidden');
            }
        }
    }

    // Toast notification function
    function showToast({ text, type = 'info' }) {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-6 right-6 z-50 px-4 py-3 rounded-lg shadow-lg transition-all transform translate-x-full opacity-0 ${
            type === 'success' ? 'bg-emerald-500 text-white' : 
            type === 'error' ? 'bg-rose-500 text-white' : 
            'bg-indigo-500 text-white'
        }`;
        toast.textContent = text;
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 10);
        
        // Animate out after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+Q to clear cart
        if (e.ctrlKey && e.key === 'q') {
            e.preventDefault();
            clearCart();
        }
        // Ctrl+C to proceed to checkout
        if (e.ctrlKey && e.key === 'c') {
            e.preventDefault();
            proceedToCheckout();
        }
        // Ctrl+Backspace to reload cart
        if (e.ctrlKey && e.key === 'Backspace') {
            e.preventDefault();
            loadCartPage();
            showToast({
                text: 'Cart refreshed',
                type: 'info'
            });
        }
    });

    // Add clear cart button in header (optional)
    window.addEventListener('load', function() {
        // If there are items in cart, add a clear button
        if (cart.length > 0) {
            const header = document.querySelector('header');
            if (header) {
                const clearBtn = document.createElement('button');
                clearBtn.className = 'text-xs text-rose-600 hover:text-rose-700 font-medium ml-2';
                clearBtn.innerHTML = '<iconify-icon icon="lucide:trash-2" width="14"></iconify-icon>';
                clearBtn.title = 'Clear Cart (Ctrl+Q)';
                clearBtn.onclick = clearCart;
                
                // Find a place to insert the button
                const headerRight = header.querySelector('.flex.items-center.gap-2');
                if (headerRight) {
                    headerRight.appendChild(clearBtn);
                }
            }
        }
        
        updateCartCountInNav();
    });
</script>
@endsection