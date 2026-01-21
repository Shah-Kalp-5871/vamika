@extends('layouts.salesperson')

@section('content')
<style>
    /* Status colors */
    .status-not-visited {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .status-with-order {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .status-no-order {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fbbf24;
    }

    /* Tab styles */
    .tab-active {
        color: #ea580c;
        position: relative;
    }

    .tab-active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 3px;
        background-color: #ea580c;
        border-radius: 3px;
    }

    /* Card styles */
    .outlet-card {
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .outlet-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Pagination styles */
    .pagination-btn {
        padding: 0.5rem 1rem;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        border-radius: 0.5rem;
        transition: all 0.2s;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #f3f4f6;
        border-color: #9ca3af;
    }

    .pagination-btn.active {
        background: #ea580c;
        color: white;
        border-color: #ea580c;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .items-per-page {
        border: 1px solid #d1d5db;
        border-radius: 0.5rem;
        padding: 0.5rem 0.75rem;
        background: white;
        font-size: 0.875rem;
    }

    .search-container {
        position: relative;
    }

    .search-input {
        padding-left: 2.75rem;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    /* Action buttons */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-order {
        background-color: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .btn-order:hover {
        background-color: #bbf7d0;
    }

    .btn-no-order {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fbbf24;
    }

    .btn-no-order:hover {
        background-color: #fde68a;
    }

    /* Status indicators */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Progress bar */
    .progress-bar {
        height: 8px;
        border-radius: 4px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-fill-visited {
        background-color: #dcfce7;
    }

    .progress-fill-order {
        background-color: #86efac;
    }

    /* Loading animation */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }

    .animate-pulse {
        animation: pulse 1.5s ease-in-out infinite;
    }

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .search-input {
            padding-left: 2.5rem;
            font-size: 0.875rem;
        }
        
        .search-icon {
            left: 0.875rem;
        }
        
        .pagination-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .items-per-page {
            padding: 0.375rem 0.5rem;
            font-size: 0.75rem;
        }
        
        .action-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
    }

    /* Animation for tab content */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tab-content {
        animation: fadeIn 0.3s ease-out;
    }
</style>

<!-- Progress Bar -->
<div class="sticky top-16 bg-white z-10 border-b border-gray-200">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-2">
            <span class="text-sm font-medium text-gray-700">Today's Progress</span>
            <span class="text-sm font-semibold text-orange-600" id="progress-percentage">0%</span>
        </div>
        <div class="progress-bar">
            <div id="progress-fill-visited" class="progress-fill progress-fill-visited" style="width: 0%"></div>
            <div id="progress-fill-order" class="progress-fill progress-fill-order" style="width: 0%"></div>
        </div>
        <div class="flex justify-between mt-1">
            <span class="text-xs text-gray-500">Visited: <span id="visited-count">0</span></span>
            <span class="text-xs text-gray-500">Orders: <span id="orders-count">0</span></span>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="bg-white border-b border-gray-200">
    <div class="flex overflow-x-auto">
        <button onclick="switchTab('not-visited')" 
                class="tab-button flex-1 py-3 text-sm font-medium text-center min-w-max px-2"
                data-tab="not-visited">
            NOT VISITED (<span id="count-not-visited">0</span>)
        </button>
        <button onclick="switchTab('visited')" 
                class="tab-button flex-1 py-3 text-sm font-medium text-center min-w-max px-2"
                data-tab="visited">
            VISITED (<span id="count-visited">0</span>)
        </button>
        <button onclick="switchTab('orders')" 
                class="tab-button flex-1 py-3 text-sm font-medium text-center min-w-max px-2"
                data-tab="orders">
            ORDERS (<span id="count-orders">0</span>)
        </button>
        <button onclick="switchTab('no-orders')" 
                class="tab-button flex-1 py-3 text-sm font-medium text-center min-w-max px-2"
                data-tab="no-orders">
            NO ORDERS (<span id="count-no-orders">0</span>)
        </button>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="p-4 bg-white border-b border-gray-200">
    <div class="space-y-3">
        <!-- Search Bar -->
        <div class="relative">
            <iconify-icon icon="ph:magnifying-glass" width="20" class="search-icon"></iconify-icon>
            <input type="text" 
                   id="search-input" 
                   placeholder="Search by outlet name, ERP ID, or location..."
                   class="search-input w-full px-4 py-3 bg-gray-50 rounded-xl text-sm border border-gray-300 focus:ring-2 focus:ring-orange-500 focus:bg-white focus:border-orange-500 transition-all outline-none">
        </div>
        
        <!-- Filter and Pagination Controls -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <!-- Items per page -->
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Show:</span>
                <select id="items-per-page" class="items-per-page">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-sm text-gray-500">per page</span>
            </div>
            
            <!-- Total results -->
            <div id="results-info" class="text-sm text-gray-600">
                Showing 0 of 0 shops
            </div>
        </div>
    </div>
</div>

<!-- Outlet List -->
<main class="p-4 pb-28">
    <!-- Tab Content -->
    <div id="tab-content" class="tab-content">
        <div id="outlet-list" class="space-y-3">
            <!-- Outlet cards will be dynamically inserted here -->
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden py-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
            <iconify-icon icon="ph:storefront" width="24" class="text-gray-400"></iconify-icon>
        </div>
        <h3 class="text-gray-700 font-medium mb-1" id="empty-title">No Shops found</h3>
        <p class="text-gray-500 text-sm" id="empty-message">Try adjusting your search or filter</p>
    </div>

    <!-- Loading Skeleton -->
    <div id="loading-skeleton" class="hidden space-y-3">
        <div class="bg-white rounded-xl p-4 animate-pulse">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
                <div class="h-8 bg-gray-200 rounded w-16"></div>
            </div>
            <div class="h-10 bg-gray-200 rounded"></div>
        </div>
        <div class="bg-white rounded-xl p-4 animate-pulse" style="animation-delay: 0.1s">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
                <div class="h-8 bg-gray-200 rounded w-16"></div>
            </div>
            <div class="h-10 bg-gray-200 rounded"></div>
        </div>
    </div>

    <!-- Pagination -->
    <div id="pagination-container" class="hidden flex items-center justify-between mt-6 pt-6 border-t border-gray-200">
        <button onclick="changePage(-1)" 
                class="pagination-btn flex items-center gap-2">
            <iconify-icon icon="ph:caret-left" width="16"></iconify-icon>
            Previous
        </button>
        
        <div class="flex items-center gap-2">
            <span id="page-info" class="text-sm text-gray-600">Page 1 of 1</span>
        </div>
        
        <button onclick="changePage(1)" 
                class="pagination-btn flex items-center gap-2">
            Next
            <iconify-icon icon="ph:caret-right" width="16"></iconify-icon>
        </button>
    </div>
</main>

<!-- Toast Notification -->
<div id="toast"
    class="hidden fixed top-4 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-6 py-3 rounded-xl text-sm shadow-lg z-50">
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Outlet Visits',
        'subtitle' => 'Today: ' . date('d M Y'),
        'showBack' => false,
        'showBottomNav' => true,
        'headerRight' => 'refresh'
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Generate dummy data with 50 Shops - ALL START AS "NOT VISITED" by default
    function generateDummyData() {
        const outlets = [];
        const areas = ['Gandhi Nagar', 'Near Metro Station', 'Opposite Hospital', 'Market Lane', 'School Road', 'Highway Road', 'Residential Area', 'Shopping Complex'];
        const tags = [['GT', 'A'], ['GT'], ['A'], ['Pan Shop'], ['GT', 'Pan Shop'], ['A', 'Pan Shop']];
        
        for (let i = 1; i <= 50; i++) {
            // ALL outlets start as "not-visited" by default
            outlets.push({
                id: i,
                name: `Outlet ${i}`,
                erpId: `ERP${(1000 + i).toString().padStart(4, '0')}`,
                location: areas[Math.floor(Math.random() * areas.length)],
                tags: tags[Math.floor(Math.random() * tags.length)],
                status: 'not-visited', // not-visited, with-order, no-order
                visited: false,
                ordered: false,
                visitedTime: null,
                mtdVisit: false,
                mtdOrder: false,
                lastOrderAmount: 0,
                totalOrdersThisMonth: 0
            });
        }
        
        return outlets;
    }

    // State Management
    const state = {
        currentTab: 'not-visited',
        searchQuery: '',
        currentPage: 1,
        itemsPerPage: 10,
        allOutlets: generateDummyData(),
        filteredOutlets: [],
        totalPages: 1
    };

    // DOM Elements
    const elements = {
        outletList: document.getElementById('outlet-list'),
        emptyState: document.getElementById('empty-state'),
        emptyTitle: document.getElementById('empty-title'),
        emptyMessage: document.getElementById('empty-message'),
        loadingSkeleton: document.getElementById('loading-skeleton'),
        searchInput: document.getElementById('search-input'),
        toast: document.getElementById('toast'),
        paginationContainer: document.getElementById('pagination-container'),
        pageInfo: document.getElementById('page-info'),
        resultsInfo: document.getElementById('results-info'),
        itemsPerPageSelect: document.getElementById('items-per-page'),
        progressPercentage: document.getElementById('progress-percentage'),
        progressFillVisited: document.getElementById('progress-fill-visited'),
        progressFillOrder: document.getElementById('progress-fill-order'),
        visitedCount: document.getElementById('visited-count'),
        ordersCount: document.getElementById('orders-count')
    };

    // Initialize App
    document.addEventListener('DOMContentLoaded', () => {
        initializeApp();
        setupEventListeners();
        switchTab('not-visited');
    });

    function initializeApp() {
        updateTabCounts();
        updateProgressBar();
        loadFromLocalStorage();
        renderOutlets();
    }

    function setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        elements.searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                state.searchQuery = e.target.value.toLowerCase();
                state.currentPage = 1;
                renderOutlets();
            }, 300);
        });
        
        // Items per page change
        elements.itemsPerPageSelect.addEventListener('change', (e) => {
            state.itemsPerPage = parseInt(e.target.value);
            state.currentPage = 1;
            renderOutlets();
        });
    }

    function updateTabCounts() {
        const totalOutlets = state.allOutlets.length;
        const notVisitedOutlets = state.allOutlets.filter(o => o.status === 'not-visited').length;
        const visitedOutlets = state.allOutlets.filter(o => o.visited).length;
        const orderedOutlets = state.allOutlets.filter(o => o.ordered).length;
        const visitedNoOrderOutlets = state.allOutlets.filter(o => o.visited && !o.ordered).length;

        // Update counts in UI
        document.getElementById('count-not-visited').textContent = notVisitedOutlets;
        document.getElementById('count-visited').textContent = visitedOutlets;
        document.getElementById('count-orders').textContent = orderedOutlets;
        document.getElementById('count-no-orders').textContent = visitedNoOrderOutlets;
    }

    function updateProgressBar() {
        const total = state.allOutlets.length;
        const visited = state.allOutlets.filter(o => o.visited).length;
        const orders = state.allOutlets.filter(o => o.ordered).length;
        
        const visitedPercentage = (visited / total) * 100;
        const ordersPercentage = (orders / total) * 100;
        
        elements.progressPercentage.textContent = `${Math.round(visitedPercentage)}%`;
        elements.progressFillVisited.style.width = `${visitedPercentage}%`;
        elements.progressFillOrder.style.width = `${ordersPercentage}%`;
        
        elements.visitedCount.textContent = visited;
        elements.ordersCount.textContent = orders;
    }

    function switchTab(tab) {
        state.currentTab = tab;
        state.currentPage = 1;
        
        // Update active tab UI
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('tab-active', 'text-orange-600', 'bg-orange-50');
            button.classList.add('text-gray-600');
            
            if (button.dataset.tab === state.currentTab) {
                button.classList.add('tab-active', 'text-orange-600', 'bg-orange-50');
            }
        });
        
        renderOutlets();
    }

    function filterOutlets() {
        let filtered = state.allOutlets.filter(outlet => {
            // Tab filtering - SIMPLE LOGIC:
            // 1. NOT VISITED tab: shows outlets where salesperson hasn't marked anything yet
            // 2. VISITED tab: shows all outlets that were visited (both with and without orders)
            // 3. ORDERS tab: shows only visited outlets that placed orders
            // 4. NO ORDERS tab: shows only visited outlets that didn't place orders
            
            switch (state.currentTab) {
                case 'not-visited':
                    return outlet.status === 'not-visited';
                case 'visited':
                    return outlet.visited; // All visited outlets
                case 'orders':
                    return outlet.ordered; // Only with orders
                case 'no-orders':
                    return outlet.visited && !outlet.ordered; // Visited but no order
            }
            
            return false;
        });
        
        // Apply search filter
        if (state.searchQuery) {
            const searchLower = state.searchQuery.toLowerCase();
            filtered = filtered.filter(outlet => 
                outlet.name.toLowerCase().includes(searchLower) ||
                outlet.erpId.toLowerCase().includes(searchLower) ||
                outlet.location.toLowerCase().includes(searchLower) ||
                outlet.tags.some(tag => tag.toLowerCase().includes(searchLower))
            );
        }
        
        return filtered;
    }

    function renderOutlets() {
        // Show loading skeleton
        elements.loadingSkeleton.classList.remove('hidden');
        elements.outletList.classList.add('hidden');
        elements.emptyState.classList.add('hidden');
        elements.paginationContainer.classList.add('hidden');
        
        setTimeout(() => {
            // Get filtered outlets
            state.filteredOutlets = filterOutlets();
            
            // Calculate pagination
            state.totalPages = Math.ceil(state.filteredOutlets.length / state.itemsPerPage);
            if (state.currentPage > state.totalPages) {
                state.currentPage = Math.max(1, state.totalPages);
            }
            
            const startIndex = (state.currentPage - 1) * state.itemsPerPage;
            const endIndex = startIndex + state.itemsPerPage;
            const currentOutlets = state.filteredOutlets.slice(startIndex, endIndex);
            
            // Clear and render outlet list
            elements.outletList.innerHTML = '';
            
            if (currentOutlets.length === 0) {
                elements.emptyState.classList.remove('hidden');
                elements.loadingSkeleton.classList.add('hidden');
                
                // Update empty state message based on tab
                switch (state.currentTab) {
                    case 'not-visited':
                        elements.emptyTitle.textContent = 'All outlets visited!';
                        elements.emptyMessage.textContent = 'Great job! All outlets have been marked today.';
                        break;
                    case 'visited':
                        elements.emptyTitle.textContent = 'No visits yet';
                        elements.emptyMessage.textContent = 'Mark outlets as visited in the "Not Visited" tab';
                        break;
                    case 'orders':
                        elements.emptyTitle.textContent = 'No orders yet';
                        elements.emptyMessage.textContent = 'Orders will appear here once marked';
                        break;
                    case 'no-orders':
                        elements.emptyTitle.textContent = 'No visited without orders';
                        elements.emptyMessage.textContent = 'All visited outlets have orders so far';
                        break;
                }
            } else {
                // Render outlet cards
                currentOutlets.forEach((outlet, index) => {
                    const card = createOutletCard(outlet, index);
                    elements.outletList.appendChild(card);
                });
                
                elements.outletList.classList.remove('hidden');
                elements.loadingSkeleton.classList.add('hidden');
                
                // Show pagination if needed
                if (state.totalPages > 1) {
                    elements.paginationContainer.classList.remove('hidden');
                    updatePaginationInfo();
                }
            }
            
            // Update results info
            updateResultsInfo();
            
        }, 300); // Simulated loading delay
    }

    function createOutletCard(outlet, index) {
        const card = document.createElement('div');
        card.className = 'outlet-card bg-white rounded-xl p-4';
        card.style.animationDelay = `${index * 0.05}s`;
        
        // Determine status display
        let statusText, statusClass, statusIcon;
        switch (outlet.status) {
            case 'not-visited':
                statusText = 'Not Visited Yet';
                statusClass = 'status-not-visited';
                statusIcon = 'ph:clock';
                break;
            case 'with-order':
                statusText = 'Order Placed';
                statusClass = 'status-with-order';
                statusIcon = 'ph:check-circle';
                break;
            case 'no-order':
                statusText = 'Visited - No Order';
                statusClass = 'status-no-order';
                statusIcon = 'ph:x-circle';
                break;
        }
        
        card.innerHTML = `
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-bold text-gray-900 text-sm">${outlet.name}</h3>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-0.5 rounded">${outlet.erpId}</span>
                    </div>
                    <p class="text-xs text-gray-600 flex items-center gap-1 mb-2">
                        <iconify-icon icon="ph:map-pin" width="12"></iconify-icon>
                        ${outlet.location}
                    </p>
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        <span class="status-indicator ${statusClass}">
                            <iconify-icon icon="${statusIcon}" width="12"></iconify-icon>
                            ${statusText}
                        </span>
                        ${outlet.tags.map(tag => `
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                ${tag}
                            </span>
                        `).join('')}
                    </div>
                </div>
                ${outlet.visitedTime ? `
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Marked at</p>
                        <p class="text-sm font-medium text-gray-700">${outlet.visitedTime}</p>
                    </div>
                ` : ''}
            </div>
            
            <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5">
                        <iconify-icon icon="ph:calendar" width="12" class="text-gray-400"></iconify-icon>
                        <span class="text-xs text-gray-600">MTD:</span>
                        <span class="text-xs font-medium ${outlet.mtdVisit ? 'text-green-600' : 'text-red-600'}">
                            ${outlet.mtdVisit ? 'Visited' : 'Not Visited'}
                        </span>
                    </div>
                    ${outlet.ordered ? `
                        <div class="flex items-center gap-1.5">
                            <iconify-icon icon="ph:shopping-cart" width="12" class="text-gray-400"></iconify-icon>
                            <span class="text-xs text-gray-600">Orders:</span>
                            <span class="text-xs font-medium text-green-600">
                                ${outlet.totalOrdersThisMonth}
                            </span>
                        </div>
                    ` : ''}
                </div>
                
                <!-- ONLY 2 BUTTONS: Order & No Order - shown only in "Not Visited" tab -->
                ${outlet.status === 'not-visited' && state.currentTab === 'not-visited' ? `
                    <div class="flex gap-2">
                        <button onclick="markAsOrdered(${outlet.id})" 
                                class="action-btn btn-order">
                            Order
                        </button>
                        <button onclick="markAsNoOrder(${outlet.id})" 
                                class="action-btn btn-no-order">
                            No Order
                        </button>
                    </div>
                ` : outlet.status !== 'not-visited' ? `
                    <span class="text-xs text-gray-500 italic">Status: ${outlet.status === 'with-order' ? 'Order Placed' : 'Visited - No Order'}</span>
                ` : ''}
            </div>
            
            ${outlet.ordered ? `
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="text-xs text-gray-600">
                            Order amount: ₹${outlet.lastOrderAmount.toLocaleString()}
                        </div>
                        <div class="text-xs text-gray-600">
                            MTD orders: ${outlet.totalOrdersThisMonth}
                        </div>
                    </div>
                </div>
            ` : ''}
        `;
        
        return card;
    }

    // Action Functions - Simple 2-button logic
    function markAsOrdered(outletId) {
        const outlet = state.allOutlets.find(o => o.id === outletId);
        if (!outlet) return;
        
        const currentTime = new Date();
        const hour = currentTime.getHours();
        const minute = currentTime.getMinutes();
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        const visitedTime = `${displayHour}:${minute.toString().padStart(2, '0')} ${ampm}`;
        
        // Update outlet - Salesperson visited AND got order
        outlet.status = 'with-order';
        outlet.visited = true;
        outlet.ordered = true;
        outlet.mtdVisit = true;
        outlet.mtdOrder = true;
        outlet.visitedTime = visitedTime;
        outlet.lastOrderAmount = Math.floor(Math.random() * 5000) + 1000;
        outlet.totalOrdersThisMonth = 1;
        
        saveToLocalStorage();
        updateTabCounts();
        updateProgressBar();
        renderOutlets();
        showToast(`✅ ${outlet.name}: Order recorded!`, 'success');
        
        // Optionally navigate to order page
        setTimeout(() => {
            showToast(`Opening order details for ${outlet.name}`, 'info');
            // window.location.href = "{{ route('salesperson.orders.create') }}";
        }, 1500);
    }

    function markAsNoOrder(outletId) {
        const outlet = state.allOutlets.find(o => o.id === outletId);
        if (!outlet) return;
        
        const currentTime = new Date();
        const hour = currentTime.getHours();
        const minute = currentTime.getMinutes();
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : hour;
        const visitedTime = `${displayHour}:${minute.toString().padStart(2, '0')} ${ampm}`;
        
        // Update outlet - Salesperson visited but NO order
        outlet.status = 'no-order';
        outlet.visited = true;
        outlet.ordered = false;
        outlet.mtdVisit = true;
        outlet.visitedTime = visitedTime;
        
        saveToLocalStorage();
        updateTabCounts();
        updateProgressBar();
        renderOutlets();
        showToast(`ℹ️ ${outlet.name}: Marked as visited (no order)`, 'info');
    }

    function updatePaginationInfo() {
        elements.pageInfo.textContent = `Page ${state.currentPage} of ${state.totalPages}`;
        
        // Update button states
        const prevBtn = elements.paginationContainer.querySelector('button:first-child');
        const nextBtn = elements.paginationContainer.querySelector('button:last-child');
        
        if (prevBtn) prevBtn.disabled = state.currentPage === 1;
        if (nextBtn) nextBtn.disabled = state.currentPage === state.totalPages;
    }

    function updateResultsInfo() {
        const total = state.filteredOutlets.length;
        const start = Math.min((state.currentPage - 1) * state.itemsPerPage + 1, total);
        const end = Math.min(state.currentPage * state.itemsPerPage, total);
        
        elements.resultsInfo.textContent = `Showing ${start}-${end} of ${total} outlets`;
    }

    function changePage(direction) {
        const newPage = state.currentPage + direction;
        
        if (newPage >= 1 && newPage <= state.totalPages) {
            state.currentPage = newPage;
            renderOutlets();
            
            // Scroll to top of list
            elements.outletList.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function refreshData() {
        showToast('Refreshing outlet data...', 'info');
        
        // Simulate API call
        elements.loadingSkeleton.classList.remove('hidden');
        elements.outletList.classList.add('hidden');
        
        setTimeout(() => {
            // In real app, fetch new data from server
            // For demo, just reload current data
            updateTabCounts();
            updateProgressBar();
            renderOutlets();
            showToast('Data refreshed successfully', 'success');
        }, 1000);
    }

    function showStats() {
        const total = state.allOutlets.length;
        const notVisited = state.allOutlets.filter(o => o.status === 'not-visited').length;
        const visited = state.allOutlets.filter(o => o.visited).length;
        const orders = state.allOutlets.filter(o => o.ordered).length;
        const visitedNoOrder = state.allOutlets.filter(o => o.visited && !o.ordered).length;
        
        const message = `
            📊 Daily Stats:
            • Total Outlets: ${total}
            • Not Visited Yet: ${notVisited}
            • Visited Today: ${visited}
            • Orders Placed: ${orders}
            • Visited (No Order): ${visitedNoOrder}
            • Visit Rate: ${Math.round((visited / total) * 100)}%
            • Order Rate: ${visited > 0 ? Math.round((orders / visited) * 100) : 0}% of visited
        `;
        
        showToast(message, 5000);
    }

    // Utility Functions
    function showToast(message, type = 'info', duration = 3000) {
        elements.toast.textContent = message;
        
        // Reset classes
        elements.toast.classList.remove('bg-gray-900', 'bg-green-600', 'bg-red-600', 'bg-yellow-600');
        
        // Set color based on type
        switch (type) {
            case 'success':
                elements.toast.classList.add('bg-green-600');
                break;
            case 'error':
                elements.toast.classList.add('bg-red-600');
                break;
            case 'warning':
                elements.toast.classList.add('bg-yellow-600');
                break;
            default:
                elements.toast.classList.add('bg-gray-900');
        }
        
        elements.toast.classList.remove('hidden');
        
        setTimeout(() => {
            elements.toast.classList.add('hidden');
        }, duration);
    }

    function saveToLocalStorage() {
        try {
            localStorage.setItem('outlet_visits_simple_data', JSON.stringify(state.allOutlets));
        } catch (e) {
            console.error('Failed to save data:', e);
        }
    }

    function loadFromLocalStorage() {
        try {
            const saved = localStorage.getItem('outlet_visits_simple_data');
            if (saved) {
                state.allOutlets = JSON.parse(saved);
            }
        } catch (e) {
            console.error('Failed to load data:', e);
        }
    }
</script>
@endsection