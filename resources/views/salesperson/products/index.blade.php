@extends('layouts.salesperson')

@section('content')
<style>
    .product-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .category-chip.active {
        background-color: #4F46E5;
        color: white;
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

    @media (max-width: 640px) {
        .container-padding {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .filter-chip {
            padding: 6px 12px;
            font-size: 13px;
        }
    }
</style>

<!-- Mobile/Tablet View -->
<div class="block">
    <div class="min-h-screen pb-24">
        <!-- Header -->
        <header class="top-0 z-20 bg-white border-b border-slate-100">
            <div class="p-4">
                <!-- Three Filter Sections - Mobile -->
                <div class="mb-4 space-y-3">
                    <!-- Product Division Filter -->
                    <div>
                        <h4 class="text-xs font-medium text-slate-500 mb-2">PRODUCT DIVISION</h4>
                        <div class="flex gap-2 overflow-x-auto pb-2">
                            <div onclick="filterByDivision('all', 'Mobile')" class="filter-chip active">All</div>
                            <div onclick="filterByDivision('food', 'Mobile')" class="filter-chip">Food</div>
                            <div onclick="filterByDivision('beverages', 'Mobile')" class="filter-chip">Beverages</div>
                            <div onclick="filterByDivision('personal-care', 'Mobile')" class="filter-chip">Personal Care</div>
                            <div onclick="filterByDivision('home-care', 'Mobile')" class="filter-chip">Home Care</div>
                        </div>
                    </div>

                    <!-- Product Brand Filter -->
                    <div>
                        <h4 class="text-xs font-medium text-slate-500 mb-2">PRODUCT BRAND</h4>
                        <div class="flex gap-2 overflow-x-auto pb-2">
                            <div onclick="filterByBrand('all', 'Mobile')" class="filter-chip active">All</div>
                            <div onclick="filterByBrand('ashirwad', 'Mobile')" class="filter-chip">Ashirwad</div>
                            <div onclick="filterByBrand('tata', 'Mobile')" class="filter-chip">Tata</div>
                            <div onclick="filterByBrand('amul', 'Mobile')" class="filter-chip">Amul</div>
                            <div onclick="filterByBrand('coca-cola', 'Mobile')" class="filter-chip">Coca-Cola</div>
                            <div onclick="filterByBrand('lays', 'Mobile')" class="filter-chip">Lays</div>
                            <div onclick="filterByBrand('parle', 'Mobile')" class="filter-chip">Parle</div>
                        </div>
                    </div>

                    <!-- Product Sub-Brand Filter -->
                    <div>
                        <h4 class="text-xs font-medium text-slate-500 mb-2">PRODUCT SUB-BRAND</h4>
                        <div class="flex gap-2 overflow-x-auto pb-2">
                            <div onclick="filterBySubBrand('all', 'Mobile')" class="filter-chip active">All</div>
                            <div onclick="filterBySubBrand('select', 'Mobile')" class="filter-chip">Select</div>
                            <div onclick="filterBySubBrand('premium', 'Mobile')" class="filter-chip">Premium</div>
                            <div onclick="filterBySubBrand('regular', 'Mobile')" class="filter-chip">Regular</div>
                            <div onclick="filterBySubBrand('gold', 'Mobile')" class="filter-chip">Gold</div>
                            <div onclick="filterBySubBrand('fresh', 'Mobile')" class="filter-chip">Fresh</div>
                        </div>
                    </div>
                </div>

                <!-- Search Bar - Mobile -->
                <div class="relative mb-4">
                    <iconify-icon icon="lucide:search" width="18"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" id="productSearchMobile" placeholder="Search products by name or brand..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm placeholder-slate-400 transition-all"
                        onkeyup="searchProducts('Mobile')">
                </div>

                <!-- Category Filter Chips - Mobile -->
                <div class="flex gap-2 overflow-x-auto pb-2">
                    <button onclick="filterByCategory('all', 'Mobile')"
                        class="category-chip active px-3 py-1.5 rounded-full bg-indigo-600 text-white text-xs font-medium whitespace-nowrap transition-colors">
                        All Products
                    </button>
                    <button onclick="filterByCategory('staples', 'Mobile')"
                        class="category-chip px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors whitespace-nowrap">
                        Staples
                    </button>
                    <button onclick="filterByCategory('beverages', 'Mobile')"
                        class="category-chip px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors whitespace-nowrap">
                        Beverages
                    </button>
                    <button onclick="filterByCategory('snacks', 'Mobile')"
                        class="category-chip px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors whitespace-nowrap">
                        Snacks
                    </button>
                    <button onclick="filterByCategory('dairy', 'Mobile')"
                        class="category-chip px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors whitespace-nowrap">
                        Dairy
                    </button>
                    <button onclick="filterByCategory('personal-care', 'Mobile')"
                        class="category-chip px-3 py-1.5 rounded-full bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-colors whitespace-nowrap">
                        Personal Care
                    </button>
                </div>
            </div>
        </header>

        <!-- Main Content - Mobile -->
        <main class="container-padding p-4">
            <div class="py-4">
                <!-- Stats -->
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Product Catalog</h3>
                        <p id="productCountMobile" class="text-xs text-slate-500">Loading...</p>
                    </div>
                    <button onclick="toggleView('Mobile')"
                        class="p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors">
                        <iconify-icon icon="lucide:grid-3x3" width="16" class="text-slate-600"></iconify-icon>
                    </button>
                </div>

                <!-- Product List - Mobile -->
                <div id="productListMobile" class="space-y-3">
                    <div class="py-8 text-center">
                        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                            role="status" aria-label="loading"></div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyStateMobile"
                    class="hidden flex flex-col items-center justify-center py-12 text-slate-400">
                    <iconify-icon icon="lucide:package-search" width="32" class="mb-3 opacity-50"></iconify-icon>
                    <p class="text-sm font-medium">No products found</p>
                    <p class="text-xs mt-1">Try a different search or filter</p>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Products',
        'showBack' => false,
        'showBottomNav' => true
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Updated Product Data with new fields
    const productData = {
        products: [
            {
                id: 1,
                name: "Aata",
                brand: "Ashirwad",
                division: "food",
                subBrand: "select",
                category: "staples",
                unit: "5kg",
                price: 250,
                mrp: 275,
                image: "aata.jpg"
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
                image: "rice.jpg"
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
                image: "dal.jpg"
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
                image: "oil.jpg"
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
                image: "cola.jpg"
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
                image: "chips.jpg"
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
                image: "milk.jpg"
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
                image: "biscuits.jpg"
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
                image: "sugar.jpg"
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
                image: "toothpaste.jpg"
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
                image: "detergent.jpg"
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
                image: "tea.jpg"
            }
        ]
    };

    let currentCategory = 'all';
    let currentSearch = '';
    let currentDivision = 'all';
    let currentBrand = 'all';
    let currentSubBrand = 'all';
    let isGridView = true;

    function initializePage() {
        loadProducts('Mobile');
        updateProductCounts();
    }

    function loadProducts(version) {
        const productList = document.getElementById(`productList${version}`);
        const emptyState = document.getElementById(`emptyState${version}`);

        if (!productList) return;

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

        filteredProducts.forEach(product => {
            const productCard = document.createElement('div');
            productCard.className = 'product-card bg-white rounded-xl border border-slate-200 transition-all overflow-hidden';

            if (version === 'Mobile') {
                productCard.className += ' flex items-start gap-3 p-3';
                productCard.innerHTML = `
                    <div class="h-16 w-16 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 overflow-hidden flex-shrink-0">
                        <img src="https://placehold.co/64x64/e2e8f0/94a3b8?text=${product.name.substring(0, 1)}" 
                             alt="${product.name}" 
                             class="h-full w-full object-cover">
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900 truncate">${product.name}</h4>
                                <p class="text-xs text-slate-500">${product.brand} • ${product.unit}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-sm font-semibold text-slate-900">₹${product.price}</span>
                            <span class="text-xs text-slate-400 line-through">₹${product.mrp}</span>
                            <span class="text-[10px] px-1 py-0.5 rounded bg-red-50 text-red-600 font-medium">
                                Save ₹${product.mrp - product.price}
                            </span>
                        </div>
                        <div class="mt-2">
                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-medium">
                                ${product.category}
                            </span>
                        </div>
                    </div>
                `;
            } else {
                productCard.className += ' p-4';
                productCard.innerHTML = `
                    <div class="h-40 w-full rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 overflow-hidden mb-3">
                        <img src="https://placehold.co/200x200/e2e8f0/94a3b8?text=${product.name.substring(0, 1)}" 
                             alt="${product.name}" 
                             class="h-full w-full object-cover">
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-slate-900 truncate">${product.name}</h4>
                                <p class="text-xs text-slate-500">${product.brand} • ${product.unit}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-semibold text-slate-900">₹${product.price}</span>
                            <span class="text-sm text-slate-400 line-through">₹${product.mrp}</span>
                            <span class="text-xs px-1.5 py-0.5 rounded bg-red-50 text-red-600 font-medium">
                                Save ₹${product.mrp - product.price}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                            <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600 font-medium">
                                ${product.category}
                            </span>
                            <button onclick="addToCart(${product.id})" 
                                    class="text-xs px-3 py-1 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-600 font-medium hover:bg-indigo-100 transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                `;
            }

            productList.appendChild(productCard);
        });

        updateProductCounts();
    }

    function searchProducts(version) {
        const searchInput = document.getElementById(`productSearch${version}`);
        currentSearch = searchInput.value.toLowerCase();
        loadProducts(version);
    }

    function filterByCategory(category, version) {
        currentCategory = category;

        // Update active chip for current version
        const chips = document.querySelectorAll('.category-chip');
        chips.forEach(chip => {
            chip.classList.remove('active', 'bg-indigo-600', 'text-white');
            chip.classList.add('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
        });

        // Activate the clicked chip
        const activeChip = event.currentTarget;
        activeChip.classList.remove('bg-slate-100', 'text-slate-600', 'hover:bg-slate-200');
        activeChip.classList.add('active', 'bg-indigo-600', 'text-white');

        loadProducts(version);
    }

    function filterByDivision(division, version) {
        currentDivision = division;

        // Update active chip for division
        updateFilterChips('division', division, version);

        loadProducts(version);
    }

    function filterByBrand(brand, version) {
        currentBrand = brand;

        // Update active chip for brand
        updateFilterChips('brand', brand, version);

        loadProducts(version);
    }

    function filterBySubBrand(subBrand, version) {
        currentSubBrand = subBrand;

        // Update active chip for subBrand
        updateFilterChips('subBrand', subBrand, version);

        loadProducts(version);
    }

    function updateFilterChips(filterType, value, version) {
        // Get all filter chips of this type
        const filterChips = document.querySelectorAll(`[onclick*="filterBy${filterType.charAt(0).toUpperCase() + filterType.slice(1)}('"]`);

        // Reset all chips of this type
        filterChips.forEach(chip => {
            if (chip.getAttribute('onclick').includes(version)) {
                chip.classList.remove('active');
                chip.classList.add('bg-slate-100', 'text-slate-600');
            }
        });

        // Activate the clicked chip
        const activeChip = event.currentTarget;
        activeChip.classList.remove('bg-slate-100', 'text-slate-600');
        activeChip.classList.add('active');
    }

    function toggleView(version) {
        isGridView = !isGridView;
        const icon = document.querySelector(`button[onclick="toggleView('${version}')"] iconify-icon`);
        if (icon) {
            icon.icon = isGridView ? 'lucide:grid-3x3' : 'lucide:list';
        }
        // In a real app, you would switch between grid and list views
    }

    function addToCart(productId) {
        const product = productData.products.find(p => p.id === productId);
        if (product) {
            showToast({
                text: `${product.name} added to cart`,
                type: 'success'
            });
            // In a real app, you would update cart state
        }
    }

    function updateProductCounts() {
        const filteredProducts = productData.products.filter(product => {
            const matchesCategory = currentCategory === 'all' || product.category === currentCategory;
            const matchesSearch = !currentSearch ||
                product.name.toLowerCase().includes(currentSearch) ||
                product.brand.toLowerCase().includes(currentSearch);
            const matchesDivision = currentDivision === 'all' || product.division === currentDivision;
            const matchesBrand = currentBrand === 'all' || product.brand.toLowerCase().includes(currentBrand);
            const matchesSubBrand = currentSubBrand === 'all' || product.subBrand === currentSubBrand;

            return matchesCategory && matchesSearch && matchesDivision && matchesBrand && matchesSubBrand;
        });

        // Update mobile count
        const mobileCount = document.getElementById('productCountMobile');
        if (mobileCount) {
            mobileCount.textContent = `${filteredProducts.length} products found`;
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', initializePage);
</script>
@endsection