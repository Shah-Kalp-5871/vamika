@extends('layouts.salesperson')

@section('content')
<style>
    /* Reference Theme's Custom Styles */
    .shop-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .area-dropdown {
        scrollbar-width: thin;
        scrollbar-color: #E4E4E7 transparent;
    }
</style>

<div
    class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">

    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100">
        <div class="space-y-4">
            <!-- Search & Filters -->
            <div class="space-y-4 pt-2">
                <!-- Search -->
                <div class="relative">
                    <iconify-icon icon="lucide:search" width="18"
                        class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" id="shopSearch" oninput="filterShops()" placeholder="Search shops by name..."
                        class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-100
                           focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                </div>

                <!-- Area Filter -->
                <div class="relative area-search-container">
                    <input type="text" id="areaSearch" readonly value="All Areas" onclick="toggleAreaDropdown()" class="w-full pl-4 pr-10 py-3 rounded-xl border border-slate-200 bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-100
                           focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                    <iconify-icon icon="lucide:chevron-down" width="18"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>

                    <div id="areaDropdown" class="area-dropdown absolute top-full left-0 right-0 mt-1 bg-white
                           border border-slate-200 rounded-xl shadow-lg
                           max-h-64 overflow-y-auto z-50 hidden">
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="space-y-6 p-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalShopsCount">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Shops</p>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="activeShopsCount">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Shops</p>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="todayOrdersCount">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Orders</p>
            </div>
        </div>

        <!-- Shop List -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Shop List</h3>
                <span id="shopCount" class="text-xs text-slate-500">0 shops</span>
            </div>

            <div id="shopListContainer" class="space-y-4">
                <!-- Loading State -->
                <div class="p-8 text-center">
                    <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                        role="status" aria-label="loading"></div>
                </div>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="hidden flex flex-col items-center justify-center py-12 text-slate-400">
                <iconify-icon icon="lucide:store" width="48" class="mb-3 opacity-50"></iconify-icon>
                <p class="text-sm font-medium">No shops found</p>
                <p class="text-xs mt-1">Try changing your search or add a new shop</p>
            </div>
        </div>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Manage Shops',
        'subtitle' => null,
        'showBack' => false,
        'showBottomNav' => true,
        'headerRight' => 'add-shop'
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Dummy data matching your original structure
    const dummyData = {
        shops: [
            { id: 1, name: "Kirana King", owner: "Rajesh Kumar", phone: "9876543210", area: "South Mumbai", address: "123 Main Street", type: "grocery", status: "active", lastOrder: "2024-01-15", totalOrders: 24 },
            { id: 2, name: "Fresh Mart", owner: "Sunil Patel", phone: "9876543211", area: "South Mumbai", address: "456 Market Lane", type: "supermarket", status: "active", lastOrder: "2024-01-14", totalOrders: 18 },
            { id: 3, name: "Daily Needs", owner: "Vikram Singh", phone: "9876543212", area: "South Mumbai", address: "789 Cross Road", type: "general", status: "active", lastOrder: "2024-01-13", totalOrders: 12 },
            { id: 4, name: "City Store", owner: "Anil Mehta", phone: "9876543213", area: "Bandra", address: "101 Western Road", type: "grocery", status: "active", lastOrder: "2024-01-12", totalOrders: 8 },
            { id: 5, name: "Medical Care", owner: "Dr. Sharma", phone: "9876543214", area: "Bandra", address: "202 Health Street", type: "medical", status: "active", lastOrder: "2024-01-11", totalOrders: 15 },
            { id: 6, name: "Quick Shop", owner: "Ramesh Gupta", phone: "9876543215", area: "Andheri", address: "303 Fast Lane", type: "grocery", status: "inactive", lastOrder: "2023-12-20", totalOrders: 6 }
        ],
        orders: [
            { id: 101, shop: "Kirana King", items: 15, amount: 2400, status: "Pending", date: new Date().toISOString().split('T')[0] },
            { id: 102, shop: "Fresh Mart", items: 8, amount: 1250, status: "Delivered", date: new Date().toISOString().split('T')[0] },
            { id: 103, shop: "Daily Needs", items: 24, amount: 5600, status: "Processing", date: new Date().toISOString().split('T')[0] }
        ],
        areas: [
            { id: 1, name: "South Mumbai" },
            { id: 2, name: "Bandra" },
            { id: 3, name: "Andheri" }
        ]
    };

    let selectedArea = 'all';
    let areaDropdownOpen = false;
    let searchQuery = '';

    function loadShops() {
        loadAreaDropdown();
        loadShopStats();
        filterShops();
    }

    function loadAreaDropdown() {
        const areaSearch = document.getElementById('areaSearch');
        const areaDropdown = document.getElementById('areaDropdown');

        // Get unique areas
        const areas = ['all', ...new Set(dummyData.shops.map(shop => shop.area))];

        // Clear existing options
        areaDropdown.innerHTML = '';

        // Populate area dropdown
        areas.forEach(area => {
            const option = document.createElement('div');
            option.className = `px-4 py-3 text-sm text-slate-600 hover:bg-slate-50 cursor-pointer transition-colors ${selectedArea === area ? 'bg-indigo-50 text-indigo-600' : ''}`;
            option.textContent = area === 'all' ? 'All Areas' : area;
            option.onclick = () => {
                selectedArea = area;
                areaSearch.value = area === 'all' ? 'All Areas' : area;
                areaDropdown.classList.add('hidden');
                areaDropdownOpen = false;
                filterShops();
                loadShopStats();
            };
            areaDropdown.appendChild(option);
        });

        // Set initial value
        areaSearch.value = selectedArea === 'all' ? 'All Areas' : selectedArea;
    }

    function toggleAreaDropdown() {
        const areaDropdown = document.getElementById('areaDropdown');
        areaDropdownOpen = !areaDropdownOpen;
        areaDropdown.classList.toggle('hidden', !areaDropdownOpen);
    }

    function filterAreaOptions() {
        const searchValue = document.getElementById('areaSearch').value.toLowerCase();
        const areaOptions = document.querySelectorAll('#areaDropdown > div');

        areaOptions.forEach(option => {
            const text = option.textContent.toLowerCase();
            if (text.includes(searchValue) || searchValue === '') {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        });
    }

    function loadShopStats() {
        const filteredShops = getFilteredShops();

        // Animate counters as in your original code
        animateCounter('totalShopsCount', filteredShops.length);
        animateCounter('activeShopsCount', filteredShops.filter(s => s.status === 'active').length);

        // Get today's date
        const today = new Date().toISOString().split('T')[0];
        const todayOrders = dummyData.orders.filter(order => {
            return order.date === today && filteredShops.some(shop => shop.name === order.shop);
        }).length;

        animateCounter('todayOrdersCount', todayOrders);
    }

    function animateCounter(elementId, targetValue) {
        const element = document.getElementById(elementId);
        const currentValue = parseInt(element.textContent) || 0;
        const duration = 500;
        const increment = (targetValue - currentValue) / (duration / 16);
        let current = currentValue;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
                current = targetValue;
                clearInterval(timer);
            }

            element.textContent = Math.round(current).toLocaleString();
        }, 16);
    }

    function getFilteredShops() {
        // First filter by area
        let filteredShops = selectedArea === 'all' ?
            [...dummyData.shops] :
            dummyData.shops.filter(shop => shop.area === selectedArea);

        // Then filter by search query if it exists
        if (searchQuery.trim() !== '') {
            const query = searchQuery.toLowerCase().trim();
            filteredShops = filteredShops.filter(shop =>
                shop.name.toLowerCase().includes(query) ||
                shop.owner.toLowerCase().includes(query) ||
                shop.phone.includes(query)
            );
        }

        return filteredShops;
    }

    function filterShops() {
        // Get search query
        searchQuery = document.getElementById('shopSearch').value;

        const container = document.getElementById('shopListContainer');
        const emptyState = document.getElementById('emptyState');
        const shopCount = document.getElementById('shopCount');

        const filteredShops = getFilteredShops();

        shopCount.textContent = `${filteredShops.length} shop${filteredShops.length !== 1 ? 's' : ''}`;

        if (filteredShops.length === 0) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        container.innerHTML = '';

        filteredShops.forEach(shop => {
            const shopCard = document.createElement('div');
            shopCard.className = 'shop-card p-4 rounded-xl border border-slate-200 bg-white transition-all';

            // Format last order date
            let lastOrderText = 'No orders yet';
            if (shop.lastOrder && shop.lastOrder !== 'No orders yet') {
                const lastOrderDate = new Date(shop.lastOrder);
                lastOrderText = lastOrderDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }

            // Format shop type
            const shopTypeMap = {
                'grocery': 'Grocery Store',
                'medical': 'Medical Store',
                'general': 'General Store',
                'supermarket': 'Supermarket',
                'other': 'Other'
            };

            const shopTypeText = shopTypeMap[shop.type] || shop.type || 'Unknown';

            shopCard.innerHTML = `
                <div class="flex items-start gap-3 mb-4">
                    <div class="h-12 w-12 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100">
                        <iconify-icon icon="lucide:store" width="20" class="text-indigo-600"></iconify-icon>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900 truncate">${shop.name}</h3>
                                <p class="text-xs text-slate-500">${shop.owner} • ${shop.area}</p>
                                <p class="text-xs text-slate-400 mt-1">${shopTypeText}</p>
                            </div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium ${shop.status === 'active' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-slate-50 text-slate-500 border border-slate-100'}">
                                ${shop.status === 'active' ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex items-center gap-1">
                                <iconify-icon icon="lucide:phone" width="12" class="text-slate-400"></iconify-icon>
                                <span class="text-xs text-slate-600">${shop.phone}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <iconify-icon icon="lucide:package" width="12" class="text-slate-400"></iconify-icon>
                                <span class="text-xs text-slate-600">${shop.totalOrders} orders</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-xs text-slate-500">
                        <iconify-icon icon="lucide:calendar" width="12"></iconify-icon>
                        <span>Last order: ${lastOrderText}</span>
                    </div>
                    <button onclick="createOrderForShop(${shop.id})" 
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 active:scale-95 transition-all">
                        Create Order
                    </button>
                </div>
            `;

            // Add click event to view shop details
            shopCard.addEventListener('click', (e) => {
                if (!e.target.closest('button')) {
                    // Show shop details (you can redirect or open modal)
                    window.location.href = "{{ route('salesperson.shops.show', ':id') }}".replace(':id', shop.id);
                }
            });

            container.appendChild(shopCard);
        });

        // Update stats with filtered results
        loadShopStats();
    }

    function createOrderForShop(shopId) {
        const shop = dummyData.shops.find(s => s.id === shopId);
        const area = dummyData.areas.find(a => a.name === shop.area);

        if (shop && area) {
            // Store in localStorage for now (you might want to use session in Laravel)
            localStorage.setItem('selectedShop', JSON.stringify(shop));
            localStorage.setItem('selectedArea', JSON.stringify(area));
            window.location.href = "{{ route('salesperson.orders.create') }}";
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', (event) => {
        const areaSearchContainer = document.querySelector('.area-search-container');
        const areaDropdown = document.getElementById('areaDropdown');

        if (areaDropdownOpen && !areaSearchContainer.contains(event.target)) {
            areaDropdown.classList.add('hidden');
            areaDropdownOpen = false;
        }
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        loadShops();
    });
</script>
@endsection