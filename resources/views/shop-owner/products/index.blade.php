@extends('layouts.shop-owner')

@php
$title = 'Products';
$subtitle = 'Browse & order items';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.dashboard');
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
        height: 3px;
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

    /* Product card hover effect */
    .product-card {
        transition: all 0.2s ease;
    }

    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Selected product style */
    .product-card.selected {
        border-color: #4F46E5;
        background-color: #F5F3FF;
    }

    /* Quantity buttons */
    .qty-btn {
        width: 32px;
        height: 32px;
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

    /* Filter chip styling */
    .filter-chip {
        padding: 6px 14px;
        border-radius: 8px;
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 12px;
        font-weight: 500;
        color: #475569;
        white-space: nowrap;
    }

    .filter-chip:hover {
        background-color: #F1F5F9;
        border-color: #CBD5E1;
    }

    .filter-chip.active {
        background-color: #4F46E5;
        color: white;
        border-color: #4F46E5;
    }

    /* Category chip styling */
    .category-chip {
        padding: 8px 16px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 500;
        white-space: nowrap;
        transition: all 0.2s;
    }

    .category-chip:not(.active) {
        background-color: #F1F5F9;
        color: #475569;
    }

    .category-chip.active {
        background-color: #4F46E5;
        color: white;
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
</style>

<!-- Main Content -->
<div class="main-content">
    <main class="p-6">
        <!-- Product Count -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-sm font-semibold text-slate-900">Product Catalog</h3>
                <p id="productCount" class="text-xs text-slate-500">Loading...</p>
            </div>
        </div>

        <!-- Product Division Filter -->
        <div class="mb-4">
            <h4 class="text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">
                Product Division
            </h4>
            <div class="flex gap-2 overflow-x-auto pb-2">
                <div onclick="filterByDivision('all')" class="filter-chip active">All</div>
                <div onclick="filterByDivision('food')" class="filter-chip">Food</div>
                <div onclick="filterByDivision('beverages')" class="filter-chip">Beverages</div>
                <div onclick="filterByDivision('personal-care')" class="filter-chip">Personal Care</div>
                <div onclick="filterByDivision('home-care')" class="filter-chip">Home Care</div>
            </div>
        </div>

        <!-- Product Brand Filter -->
        <div class="mb-4">
            <h4 class="text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">
                Product Brand
            </h4>
            <div class="flex gap-2 overflow-x-auto pb-2">
                <div onclick="filterByBrand('all')" class="filter-chip active">All</div>
                <div onclick="filterByBrand('ashirwad')" class="filter-chip">Ashirwad</div>
                <div onclick="filterByBrand('tata')" class="filter-chip">Tata</div>
                <div onclick="filterByBrand('amul')" class="filter-chip">Amul</div>
                <div onclick="filterByBrand('coca-cola')" class="filter-chip">Coca-Cola</div>
                <div onclick="filterByBrand('lays')" class="filter-chip">Lays</div>
                <div onclick="filterByBrand('parle')" class="filter-chip">Parle</div>
            </div>
        </div>

        <!-- Product Sub-Brand Filter -->
        <div class="mb-4">
            <h4 class="text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">
                Product Sub-Brand
            </h4>
            <div class="flex gap-2 overflow-x-auto pb-2">
                <div onclick="filterBySubBrand('all')" class="filter-chip active">All</div>
                <div onclick="filterBySubBrand('select')" class="filter-chip">Select</div>
                <div onclick="filterBySubBrand('premium')" class="filter-chip">Premium</div>
                <div onclick="filterBySubBrand('regular')" class="filter-chip">Regular</div>
                <div onclick="filterBySubBrand('gold')" class="filter-chip">Gold</div>
                <div onclick="filterBySubBrand('fresh')" class="filter-chip">Fresh</div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="relative mb-4">
            <iconify-icon icon="lucide:search" width="18"
                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input type="text" id="productSearch" placeholder="Search products by name or category..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white
                      focus:outline-none focus:ring-2 focus:ring-indigo-100
                      focus:border-indigo-300 text-sm placeholder-slate-400 transition-all"
                onkeyup="searchProducts()">
        </div>

        <!-- Category Chips -->
        <div class="flex gap-2 overflow-x-auto pb-2 mb-4">
            <button onclick="filterByCategory('all')" class="category-chip active">
                All Products
            </button>
            <button onclick="filterByCategory('staples')" class="category-chip">Staples</button>
            <button onclick="filterByCategory('beverages')" class="category-chip">Beverages</button>
            <button onclick="filterByCategory('snacks')" class="category-chip">Snacks</button>
            <button onclick="filterByCategory('dairy')" class="category-chip">Dairy</button>
            <button onclick="filterByCategory('personal-care')" class="category-chip">Personal Care</button>
        </div>

        <!-- Product List -->
        <div id="productList" class="grid grid-cols-1 gap-4">
            <!-- Products will be loaded here -->
            <div class="flex items-center justify-center py-12 col-span-2">
                <div class="loading-spinner"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden flex flex-col items-center justify-center py-16 text-slate-400">
            <iconify-icon icon="lucide:package-search" width="48" class="mb-4 opacity-50"></iconify-icon>
            <p class="text-sm font-medium">No products found</p>
            <p class="text-xs mt-1">Try a different search or filter</p>
        </div>
    </main>
</div>

<!-- Cart Summary - Fixed at Bottom -->
<div class="cart-summary">
    <div class="flex items-center justify-between mb-3">
        <div>
            <p class="text-xs text-slate-500">Cart Total</p>
            <p class="text-xl font-semibold text-emerald-600">₹<span id="cartTotal">0</span></p>
        </div>
        <div class="text-right">
            <p class="text-xs text-slate-500">Items</p>
            <p class="text-lg font-medium text-slate-900"><span id="cartCount">0</span> items</p>
        </div>
    </div>
    <button id="addtocartBtn" disabled onclick="goToCart()"
        class="w-full py-3 px-4 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
        <iconify-icon icon="lucide:shopping-cart" width="18"></iconify-icon>
        View Cart & Checkout
    </button>
</div>
@endsection

@section('scripts')
<script>
    const cart = {};
    let currentCategory = 'all';
    let currentSearch = '';
    let currentDivision = 'all';
    let currentBrand = 'all';
    let currentSubBrand = 'all';

    // Updated Product Data with new fields
    const productData = {
        products: [
            {
                id: 1,
                name: "Aata (Wheat Flour)",
                brand: "Ashirwad",
                division: "food",
                subBrand: "select",
                category: "staples",
                unit: "5kg",
                price: 250,
                stock: 45,
                mrp: 275,
                image: null
            },
            {
                id: 2,
                name: "Basmati Rice",
                brand: "India Gate",
                division: "food",
                subBrand: "premium",
                category: "staples",
                unit: "5kg",
                price: 450,
                stock: 28,
                mrp: 480,
                image: null
            },
            {
                id: 3,
                name: "Toor Dal",
                brand: "Tata Sampann",
                division: "food",
                subBrand: "regular",
                category: "staples",
                unit: "1kg",
                price: 180,
                stock: 62,
                mrp: 195,
                image: null
            },
            {
                id: 4,
                name: "Cooking Oil",
                brand: "Fortune",
                division: "food",
                subBrand: "regular",
                category: "staples",
                unit: "1L",
                price: 190,
                stock: 34,
                mrp: 210,
                image: null
            },
            {
                id: 5,
                name: "Cola",
                brand: "Coca-Cola",
                division: "beverages",
                subBrand: "regular",
                category: "beverages",
                unit: "750ml",
                price: 40,
                stock: 120,
                mrp: 45,
                image: null
            },
            {
                id: 6,
                name: "Potato Chips",
                brand: "Lays",
                division: "food",
                subBrand: "regular",
                category: "snacks",
                unit: "50g",
                price: 20,
                stock: 85,
                mrp: 25,
                image: null
            },
            {
                id: 7,
                name: "Milk",
                brand: "Amul",
                division: "food",
                subBrand: "fresh",
                category: "dairy",
                unit: "500ml",
                price: 30,
                stock: 96,
                mrp: 32,
                image: null
            },
            {
                id: 8,
                name: "Biscuits",
                brand: "Parle-G",
                division: "food",
                subBrand: "regular",
                category: "snacks",
                unit: "100g",
                price: 10,
                stock: 150,
                mrp: 12,
                image: null
            },
            {
                id: 9,
                name: "Sugar",
                brand: "Madhur",
                division: "food",
                subBrand: "regular",
                category: "staples",
                unit: "1kg",
                price: 45,
                stock: 72,
                mrp: 48,
                image: null
            },
            {
                id: 10,
                name: "Toothpaste",
                brand: "Colgate",
                division: "personal-care",
                subBrand: "fresh",
                category: "personal-care",
                unit: "100g",
                price: 55,
                stock: 58,
                mrp: 60,
                image: null
            },
            {
                id: 11,
                name: "Detergent Powder",
                brand: "Surf Excel",
                division: "home-care",
                subBrand: "select",
                category: "home-care",
                unit: "1kg",
                price: 120,
                stock: 42,
                mrp: 135,
                image: null
            },
            {
                id: 12,
                name: "Tea Powder",
                brand: "Taj Mahal",
                division: "food",
                subBrand: "premium",
                category: "staples",
                unit: "250g",
                price: 120,
                stock: 38,
                mrp: 130,
                image: null
            }
        ]
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadCartFromStorage();
        loadProducts();
        updateCartSummary();
    });

    function loadProducts() {
        const productList = document.getElementById('productList');
        const emptyState = document.getElementById('emptyState');
        const productCount = document.getElementById('productCount');

        // Filter products based on all criteria
        const filteredProducts = productData.products.filter(product => {
            const matchesCategory = currentCategory === 'all' || product.category === currentCategory;
            const matchesSearch = !currentSearch ||
                product.name.toLowerCase().includes(currentSearch) ||
                product.brand.toLowerCase().includes(currentSearch) ||
                product.category.toLowerCase().includes(currentSearch);
            const matchesDivision = currentDivision === 'all' || product.division === currentDivision;
            const matchesBrand = currentBrand === 'all' || product.brand.toLowerCase().includes(currentBrand);
            const matchesSubBrand = currentSubBrand === 'all' || product.subBrand === currentSubBrand;

            return matchesCategory && matchesSearch && matchesDivision && matchesBrand && matchesSubBrand;
        });

        // Update product count
        productCount.textContent = `${filteredProducts.length} products found`;

        // Update empty state
        if (filteredProducts.length === 0) {
            productList.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        productList.classList.remove('hidden');
        emptyState.classList.add('hidden');

        // Clear and load products
        productList.innerHTML = '';

        filteredProducts.forEach((product, index) => {
            cart[product.id] = cart[product.id] || 0;

            const productCard = document.createElement('div');
            productCard.className = `product-card bg-white rounded-xl border border-slate-200 shadow-sm transition-all overflow-hidden p-4 animate-slide-up ${cart[product.id] > 0 ? 'selected' : ''}`;
            productCard.style.animationDelay = `${index * 0.05}s`;

            productCard.innerHTML = `
                    <div class="flex items-start gap-4">
                        <!-- Product Image -->
                        <div class="h-20 w-20 rounded-lg bg-gradient-to-br from-indigo-50 to-indigo-100 flex items-center justify-center border border-indigo-200 overflow-hidden flex-shrink-0">
                            ${product.image ?
                    `<img src="${product.image}" alt="${product.name}" class="h-full w-full object-cover">` :
                    `<iconify-icon icon="lucide:package" width="32" class="text-indigo-600"></iconify-icon>`
                }
                        </div>
                        
                        <!-- Product Details -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between mb-1">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 truncate">${product.name}</h4>
                                    <p class="text-xs text-slate-500">${product.brand} • ${product.unit}</p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100">
                                    ${product.stock} in stock
                                </span>
                            </div>
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold text-slate-900">₹${product.price}</span>
                                <span class="text-sm text-slate-400 line-through">₹${product.mrp}</span>
                                <span class="text-[10px] px-1 py-0.5 rounded bg-red-50 text-red-600 font-medium">
                                    Save ₹${product.mrp - product.price}
                                </span>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-[10px] px-2 py-1 rounded bg-slate-100 text-slate-600 font-medium">
                                    ${product.category}
                                </span>
                                
                                <!-- Quantity Controls -->
                                <div class="flex items-center gap-2">
                                    <button onclick="decreaseQty(${product.id})" 
                                            class="qty-btn minus"
                                            ${cart[product.id] === 0 ? 'disabled' : ''}>
                                        <iconify-icon icon="lucide:minus" width="14"></iconify-icon>
                                    </button>
                                    <span class="text-sm font-semibold text-slate-900 w-8 text-center" id="qty-${product.id}">
                                        ${cart[product.id]}
                                    </span>
                                    <button onclick="increaseQty(${product.id})" 
                                            class="qty-btn plus">
                                        <iconify-icon icon="lucide:plus" width="14"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

            productList.appendChild(productCard);
        });
    }

    function increaseQty(productId) {
        cart[productId] = (cart[productId] || 0) + 1;
        updateQtyDisplay(productId);
        updateCartSummary();
    }

    function decreaseQty(productId) {
        if (cart[productId] > 0) {
            cart[productId]--;
            updateQtyDisplay(productId);
            updateCartSummary();
        }
    }

    function updateQtyDisplay(productId) {
        const qtySpan = document.getElementById(`qty-${productId}`);
        if (qtySpan) {
            qtySpan.textContent = cart[productId];
            const productCard = qtySpan.closest('.product-card');
            const minusBtn = productCard.querySelector('.qty-btn.minus');

            // Update selected state
            if (cart[productId] > 0) {
                productCard.classList.add('selected');
                minusBtn.disabled = false;
            } else {
                productCard.classList.remove('selected');
                minusBtn.disabled = true;
            }
        }
    }

    function updateCartSummary() {
        let totalItems = 0;
        let totalAmount = 0;

        Object.keys(cart).forEach(productId => {
            const product = productData.products.find(p => p.id == productId);
            if (product && cart[productId] > 0) {
                totalItems += cart[productId];
                totalAmount += cart[productId] * product.price;
            }
        });

        // Update display
        document.getElementById('cartCount').textContent = totalItems;
        document.getElementById('cartTotal').textContent = totalAmount.toLocaleString();

        // Enable/disable checkout button
        const checkoutBtn = document.getElementById('addtocartBtn');
        checkoutBtn.disabled = totalItems === 0;

        // Save cart to localStorage
        const cartItems = [];
        Object.keys(cart).forEach(productId => {
            if (cart[productId] > 0) {
                const product = productData.products.find(p => p.id == productId);
                cartItems.push({
                    ...product,
                    quantity: cart[productId],
                    total: cart[productId] * product.price
                });
            }
        });
        localStorage.setItem('shop_cart', JSON.stringify(cartItems));
    }

    function searchProducts() {
        const searchInput = document.getElementById('productSearch');
        currentSearch = searchInput.value.toLowerCase();
        loadProducts();
    }

    function filterByCategory(category) {
        currentCategory = category;

        // Update active chip
        const chips = document.querySelectorAll('.category-chip');
        chips.forEach(chip => {
            chip.classList.remove('active', 'bg-indigo-600', 'text-white');
            chip.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
        });

        // Activate the clicked chip
        const activeChip = event.currentTarget;
        activeChip.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
        activeChip.classList.add('active', 'bg-indigo-600', 'text-white');

        loadProducts();
    }

    function filterByDivision(division) {
        currentDivision = division;
        updateFilterChips('division', division);
        loadProducts();
    }

    function filterByBrand(brand) {
        currentBrand = brand;
        updateFilterChips('brand', brand);
        loadProducts();
    }

    function filterBySubBrand(subBrand) {
        currentSubBrand = subBrand;
        updateFilterChips('subBrand', subBrand);
        loadProducts();
    }

    function updateFilterChips(filterType, value) {
        // Get all filter chips of this type
        const filterChips = document.querySelectorAll(`[onclick*="filterBy${filterType.charAt(0).toUpperCase() + filterType.slice(1)}('"]`);

        // Reset all chips of this type
        filterChips.forEach(chip => {
            chip.classList.remove('active');
            chip.classList.add('bg-slate-100', 'text-slate-600');
        });

        // Activate the clicked chip
        const activeChip = event.currentTarget;
        activeChip.classList.remove('bg-slate-100', 'text-slate-600');
        activeChip.classList.add('active');
    }

    function goToCart() {
        if (Object.values(cart).some(qty => qty > 0)) {
            window.location.href = '{{ route('shop-owner.cart.index') }}';
        }
    }

    function loadCartFromStorage() {
        const savedCart = localStorage.getItem('shop_cart');
        if (savedCart) {
            const cartItems = JSON.parse(savedCart);
            cartItems.forEach(item => {
                cart[item.id] = item.quantity;
            });
        }
    }

    // Add keyboard shortcuts for quantity control
    document.addEventListener('keydown', function (e) {
        // For demo: Space to refresh, Ctrl+Enter to clear cart
        if (e.code === 'Space' && e.target.tagName !== 'INPUT') {
            e.preventDefault();
            loadProducts();
        } else if (e.ctrlKey && e.code === 'Enter') {
            // Clear cart on Ctrl+Enter
            Object.keys(cart).forEach(key => {
                cart[key] = 0;
            });
            updateCartSummary();
            loadProducts();
        }
    });

    // Initialize filter chips active state
    window.onload = function () {
        // Set all 'All' filter chips as active
        document.querySelectorAll('.filter-chip').forEach(chip => {
            if (chip.textContent === 'All') {
                chip.classList.remove('bg-slate-100', 'text-slate-600');
                chip.classList.add('active');
            }
        });
    };
</script>
@endsection