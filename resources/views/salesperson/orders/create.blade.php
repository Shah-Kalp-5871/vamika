@extends('layouts.salesperson')

@section('content')
<style>
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .product-card.selected {
        border-color: #4F46E5;
        background-color: #F5F3FF;
    }

    .filter-chip {
        padding: 8px 16px;
        border-radius: 8px;
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 14px;
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
</style>

<div class="max-w-2xl mx-auto min-h-screen bg-white">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100 p-4">
        <div class="flex items-center gap-3">
            <div class="flex-1 min-w-0">
                <h1 class="text-sm font-semibold text-slate-900 truncate">
                    Add Products
                </h1>
                <div class="flex items-center gap-1 text-xs text-slate-500 truncate">
                    <span>Shop:</span>
                    <span id="currentShopName" class="font-medium text-indigo-600 truncate">
                        Loading...
                    </span>
                </div>
            </div>
            <button onclick="viewCart()"
                class="relative p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors">
                <iconify-icon icon="lucide:shopping-cart" width="18" class="text-slate-600"></iconify-icon>
                <span id="cartCount" class="absolute -top-1 -right-1 h-5 w-5 rounded-full bg-indigo-600
                           text-white text-xs font-semibold flex items-center justify-center">
                    0
                </span>
            </button>
        </div>
        
        <!-- Search Bar -->
        <div class="relative mt-4">
            <iconify-icon icon="lucide:search" width="18"
                class="absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
            <input type="text" id="productSearch" placeholder="Search products by name or brand..."
                class="w-full pl-12 pr-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm placeholder-slate-400 transition-all"
                onkeyup="searchProducts()">
        </div>
    </header>

    <main class="p-4">
        <!-- Three Filter Sections -->
        <div class="space-y-4">
            <!-- Product Division Filter -->
            <div>
                <h4 class="text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">PRODUCT DIVISION</h4>
                <div class="flex gap-2 overflow-x-auto pb-2 whitespace-nowrap">
                    <div onclick="filterByDivision('all')" class="filter-chip active">All</div>
                    <div onclick="filterByDivision('food')" class="filter-chip">Food</div>
                    <div onclick="filterByDivision('beverages')" class="filter-chip">Beverages</div>
                    <div onclick="filterByDivision('personal-care')" class="filter-chip">Personal Care</div>
                    <div onclick="filterByDivision('home-care')" class="filter-chip">Home Care</div>
                </div>
            </div>

            <!-- Product Brand Filter -->
            <div>
                <h4 class="text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">PRODUCT BRAND</h4>
                <div class="flex gap-2 overflow-x-auto pb-2 whitespace-nowrap">
                    <div onclick="filterByBrand('all')" class="filter-chip active">All</div>
                    <div onclick="filterByBrand('ashirwad')" class="filter-chip">Ashirwad</div>
                    <div onclick="filterByBrand('tata')" class="filter-chip">Tata</div>
                    <div onclick="filterByBrand('amul')" class="filter-chip">Amul</div>
                    <div onclick="filterByBrand('coca-cola')" class="filter-chip">Coca-Cola</div>
                    <div onclick="filterByBrand('lays')" class="filter-chip">Lays</div>
                    <div onclick="filterByBrand('parle')" class="filter-chip">Parle</div>
                    <div onclick="filterByBrand('colgate')" class="filter-chip">Colgate</div>
                    <div onclick="filterByBrand('surf-excel')" class="filter-chip">Surf Excel</div>
                </div>
            </div>

            <!-- Product Sub-Brand Filter -->
            <div>
                <h4 class="text-xs font-medium text-slate-500 mb-2 uppercase tracking-wide">PRODUCT SUB-BRAND</h4>
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <div onclick="filterBySubBrand('all')" class="filter-chip active">All</div>
                    <div onclick="filterBySubBrand('select')" class="filter-chip">Select</div>
                    <div onclick="filterBySubBrand('premium')" class="filter-chip">Premium</div>
                    <div onclick="filterBySubBrand('regular')" class="filter-chip">Regular</div>
                    <div onclick="filterBySubBrand('gold')" class="filter-chip">Gold</div>
                    <div onclick="filterBySubBrand('fresh')" class="filter-chip">Fresh</div>
                    <div onclick="filterBySubBrand('classic')" class="filter-chip">Classic</div>
                </div>
            </div>
        </div>

        <!-- Product Counter -->
        <div class="flex items-center justify-between my-4">
            <h3 class="text-sm font-semibold text-slate-900">Available Products</h3>
            <span id="productCount" class="text-xs text-slate-500">0 products</span>
        </div>

        <!-- Product List -->
        <div class="flex gap-3 pb-36">
            <!-- Image Scroll Rail -->
            <div id="imageRail"
                class="w-20 shrink-0 h-[calc(100vh-220px)] overflow-y-auto
                        space-y-3 pr-1 sticky top-24">
            </div>

            <div id="productList"
                class="space-y-4 mb-4 flex-1 min-w-0">
                <!-- Products will be loaded here -->
                <div class="p-8 text-center">
                    <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                        role="status" aria-label="loading"></div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Cart Summary (Fixed at bottom) -->
<div
    class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 sm:bottom-0 sm:relative sm:border-t-0 sm:border-t sm:rounded-b-2xl">
    <div class="max-w-2xl mx-auto">
        <div class="flex items-center justify-between mb-3">
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:shopping-cart" width="18" class="text-slate-400"></iconify-icon>
                <span class="text-sm font-medium text-slate-700">Order Summary</span>
            </div>
            <span id="totalItems" class="text-sm font-semibold text-slate-900">0 items</span>
        </div>

        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-xs text-slate-500">Total Amount</p>
                <h3 id="totalAmount" class="text-xl font-semibold text-slate-900">₹0</h3>
            </div>
            <button id="reviewBtn" onclick="proceedToReview()"
                class="px-6 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed shadow-md disabled:shadow-none"
                disabled>
                Review Order
            </button>
        </div>
    </div>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Create Order',
        'showBack' => true,
        'backUrl' => route('salesperson.shops.select'),
        'showBottomNav' => false
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Updated Dummy product data with new fields
    const dummyData = {
        products: [
            { id: 1, name: "Aata", brand: "Ashirwad", division: "food", subBrand: "select", category: "staples", unit: "5kg", price: 250, stock: true, image: "aata.jpg" },
            { id: 2, name: "Basmati Rice", brand: "India Gate", division: "food", subBrand: "premium", category: "staples", unit: "5kg", price: 450, stock: true, image: "rice.jpg" },
            { id: 3, name: "Toor Dal", brand: "Tata Sampann", division: "food", subBrand: "regular", category: "staples", unit: "1kg", price: 180, stock: true, image: "dal.jpg" },
            { id: 4, name: "Cooking Oil", brand: "Fortune", division: "food", subBrand: "regular", category: "staples", unit: "1L", price: 190, stock: true, image: "oil.jpg" },
            { id: 5, name: "Cola", brand: "Coca-Cola", division: "beverages", subBrand: "regular", category: "beverages", unit: "750ml", price: 40, stock: true, image: "cola.jpg" },
            { id: 6, name: "Potato Chips", brand: "Lays", division: "food", subBrand: "regular", category: "snacks", unit: "50g", price: 20, stock: false, image: "chips.jpg" },
            { id: 7, name: "Milk", brand: "Amul", division: "food", subBrand: "fresh", category: "dairy", unit: "500ml", price: 30, stock: true, image: "milk.jpg" },
            { id: 8, name: "Biscuits", brand: "Parle-G", division: "food", subBrand: "regular", category: "snacks", unit: "100g", price: 10, stock: true, image: "biscuits.jpg" },
            { id: 9, name: "Sugar", brand: "Madhur", division: "food", subBrand: "regular", category: "staples", unit: "1kg", price: 45, stock: true, image: "sugar.jpg" },
            { id: 10, name: "Salt", brand: "Tata", division: "food", subBrand: "regular", category: "staples", unit: "1kg", price: 20, stock: true, image: "salt.jpg" },
            { id: 11, name: "Tea Powder", brand: "Taj Mahal", division: "food", subBrand: "premium", category: "staples", unit: "250g", price: 120, stock: true, image: "tea.jpg" },
            { id: 12, name: "Coffee Powder", brand: "Nescafe", division: "food", subBrand: "classic", category: "staples", unit: "100g", price: 180, stock: true, image: "coffee.jpg" },
            { id: 13, name: "Toothpaste", brand: "Colgate", division: "personal-care", subBrand: "fresh", category: "personal-care", unit: "100g", price: 55, stock: true, image: "toothpaste.jpg" },
            { id: 14, name: "Detergent Powder", brand: "Surf Excel", division: "home-care", subBrand: "select", category: "home-care", unit: "1kg", price: 120, stock: true, image: "detergent.jpg" },
            { id: 15, name: "Soap", brand: "Lifebuoy", division: "personal-care", subBrand: "regular", category: "personal-care", unit: "100g", price: 35, stock: true, image: "soap.jpg" }
        ]
    };

    let productQuantities = {};
    let currentCategory = 'all';
    let searchTerm = '';
    let currentDivision = 'all';
    let currentBrand = 'all';
    let currentSubBrand = 'all';

    // Load shop info from localStorage (simulated)
    function loadShopInfo() {
        // Simulate getting shop data from localStorage
        const shopData = {
            name: "Kirana King",
            area: "South Mumbai",
            creditLimit: 50000
        };

        document.getElementById('currentShopName').textContent = shopData.name;
    }

    function loadProducts() {
        const productList = document.getElementById('productList');

        // Filter products based on all criteria
        const filteredProducts = dummyData.products.filter(product => {
            const matchesCategory = currentCategory === 'all' || product.category === currentCategory;
            const matchesSearch = !searchTerm ||
                product.name.toLowerCase().includes(searchTerm) ||
                product.brand.toLowerCase().includes(searchTerm);
            const matchesDivision = currentDivision === 'all' || product.division === currentDivision;
            const matchesBrand = currentBrand === 'all' || product.brand.toLowerCase().includes(currentBrand);
            const matchesSubBrand = currentSubBrand === 'all' || product.subBrand === currentSubBrand;

            return matchesCategory && matchesSearch && matchesDivision && matchesBrand && matchesSubBrand;
        });

        productList.innerHTML = '';

        if (filteredProducts.length === 0) {
            productList.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <iconify-icon icon="lucide:package-search" width="48" class="mb-3 opacity-50"></iconify-icon>
                    <p class="text-sm font-medium">No products found</p>
                    <p class="text-xs mt-1">Try a different search term or filter</p>
                </div>
            `;
            document.getElementById('productCount').textContent = '0 products';
            return;
        }

        document.getElementById('productCount').textContent = `${filteredProducts.length} products`;

        const imageRail = document.getElementById('imageRail');
        imageRail.innerHTML = '';

        filteredProducts.forEach(product => {
            const quantity = productQuantities[product.id] || 0;
            const productDiv = document.createElement('div');
            productDiv.className = `product-card p-4 rounded-xl border ${quantity > 0 ? 'selected border-indigo-300 bg-indigo-50' : 'border-slate-200 bg-white'} hover:border-slate-300 transition-all`;

            // IMAGE RAIL ITEM
            const imgDiv = document.createElement('div');
            imgDiv.className = `
                h-16 w-16 rounded-lg overflow-hidden cursor-pointer
                border transition-all duration-200
                ${quantity > 0 ? 'border-indigo-500 ring-2 ring-indigo-300' : 'border-slate-200'}
            `;

            imgDiv.innerHTML = `
                <img src="https://images.unsplash.com/photo-1586201375761-83865001e17b?w=200&h=200&fit=crop"
                     class="h-full w-full object-cover hover:scale-110 transition-transform duration-300">
            `;

            imgDiv.onclick = () => {
                productDiv.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                productDiv.classList.add('ring-2', 'ring-indigo-400');
                setTimeout(() => {
                    productDiv.classList.remove('ring-2', 'ring-indigo-400');
                }, 600);
            };

            imageRail.appendChild(imgDiv);

            productDiv.innerHTML = `
                <div class="flex items-start gap-3">
                    <!-- IMAGE -->
                    <div class="h-16 w-16 shrink-0 rounded-lg bg-slate-50
                                flex items-center justify-center border overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1763997560199-fef15cf478a6?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                             alt="${product.name}"
                             class="h-full w-full object-cover transition-transform duration-300
                                    ${quantity > 0 ? 'scale-110' : 'scale-100'}">
                    </div>

                    <!-- PRODUCT INFO -->
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-slate-900 leading-tight">
                            ${product.name}
                        </h4>
                        <p class="text-xs text-slate-500">
                            ${product.brand} • ${product.unit}
                        </p>

                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-semibold text-slate-900">
                                ₹${product.price}
                            </span>

                            ${!product.stock ? `
                                <span class="text-[10px] px-1.5 py-0.5 rounded
                                             bg-slate-100 text-slate-500 font-medium">
                                    Out of Stock
                                </span>
                            ` : ''}
                        </div>
                    </div>
                </div>

                ${product.stock ? `
                    <div class="flex items-center gap-3 mt-4">
                        <button onclick="decreaseQty(${product.id})" 
                                class="h-8 w-8 rounded-full border border-slate-200 flex items-center justify-center hover:bg-slate-50 transition-colors ${quantity === 0 ? 'opacity-50 cursor-not-allowed' : ''}"
                                ${quantity === 0 ? 'disabled' : ''}>
                            <iconify-icon icon="lucide:minus" width="14" class="text-slate-600"></iconify-icon>
                        </button>
                        
                        <div class="min-w-[60px] h-[44px] flex flex-col items-center justify-center text-center">
                            <span class="text-lg font-semibold ${quantity > 0 ? 'text-indigo-600' : 'text-slate-600'}">
                                ${quantity}
                            </span>

                            <p class="text-xs text-slate-500 leading-tight ${quantity > 0 ? 'opacity-100' : 'opacity-0'}">
                                ₹${(quantity * product.price).toLocaleString()}
                            </p>
                        </div>
                        
                        <button onclick="increaseQty(${product.id})" 
                                class="h-8 w-8 rounded-full border border-indigo-200 bg-indigo-50 flex items-center justify-center hover:bg-indigo-100 transition-colors">
                            <iconify-icon icon="lucide:plus" width="14" class="text-indigo-600"></iconify-icon>
                        </button>
                    </div>
                ` : ''}
            `;

            productList.appendChild(productDiv);
        });

        updateCart();
    }

    function increaseQty(productId) {
        const product = dummyData.products.find(p => p.id === productId);
        if (!product?.stock) return;

        productQuantities[productId] = (productQuantities[productId] || 0) + 1;
        loadProducts();
    }

    function decreaseQty(productId) {
        if (productQuantities[productId] > 0) {
            productQuantities[productId]--;
            if (productQuantities[productId] === 0) {
                delete productQuantities[productId];
            }
            loadProducts();
        }
    }

    function updateCart() {
        let totalItems = 0;
        let totalAmount = 0;

        dummyData.products.forEach(product => {
            const qty = productQuantities[product.id] || 0;
            if (qty > 0) {
                totalItems += qty;
                totalAmount += qty * product.price;
            }
        });

        document.getElementById('cartCount').textContent = totalItems;
        document.getElementById('totalItems').textContent = `${totalItems} item${totalItems !== 1 ? 's' : ''}`;
        document.getElementById('totalAmount').textContent = `₹${totalAmount.toLocaleString()}`;

        // Enable/disable review button
        document.getElementById('reviewBtn').disabled = totalItems === 0;

        // Save cart to localStorage
        const cartItems = [];
        dummyData.products.forEach(product => {
            if (productQuantities[product.id] > 0) {
                cartItems.push({
                    ...product,
                    quantity: productQuantities[product.id],
                    total: productQuantities[product.id] * product.price
                });
            }
        });
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
    }

    function searchProducts() {
        searchTerm = document.getElementById('productSearch').value.toLowerCase();
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

    function viewCart() {
        const totalItems = parseInt(document.getElementById('cartCount').textContent, 10);
        const totalAmount = document.getElementById('totalAmount').textContent.replace('₹', '');

        if (totalItems > 0) {
            showToast({
                text: `${totalItems} item${totalItems !== 1 ? 's' : ''} in cart • ₹${totalAmount}`,
                type: 'info'
            });
        } else {
            showToast({
                text: 'Your cart is empty',
                type: 'info'
            });
        }
    }

    function proceedToReview() {
        const totalItems = parseInt(document.getElementById('cartCount').textContent);
        if (totalItems > 0) {
            window.location.href = "{{ route('salesperson.orders.review', ['id' => 1]) }}";
        }
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        loadShopInfo();
        loadProducts();

        // Set active filter buttons
        setTimeout(() => {
            // Set default active states for all filter sections
            document.querySelectorAll('.filter-chip').forEach(chip => {
                if (chip.textContent === 'All' && chip.getAttribute('onclick').includes('all')) {
                    chip.classList.remove('bg-slate-100', 'text-slate-600');
                    chip.classList.add('active');
                }
            });
        }, 100);
    });
</script>
@endsection