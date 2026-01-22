@extends('layouts.admin')

@section('title', 'Manage Orders')


@php
$pageConfig = [
    'showBottomNav' => true,
];
@endphp

@section('styles')
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
        padding-bottom: 80px;
    }

    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #E4E4E7;
        border-radius: 20px;
    }

    /* Main content container - Mobile optimized */
    .main-content {
        max-width: 100%;
        margin: 0;
        min-height: 100vh;
        background-color: #FAFAFA;
        overflow-x: hidden;
    }

    @media (min-width: 768px) {
        .main-content {
            max-width: 100%;
            margin: 0;
            min-height: 100vh;
        }
    }

    /* Mobile-first Tabulator Styles */
    .tabulator {
        border: none !important;
        background: transparent !important;
        font-family: 'Inter', sans-serif !important;
        font-size: 14px !important;
    }

    /* Mobile header - sticky */
    .tabulator .tabulator-header {
        border-bottom: 1px solid #E2E8F0 !important;
        background-color: #F8FAFC !important;
        color: #64748B !important;
        font-weight: 600 !important;
        font-size: 12px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 10 !important;
        white-space: nowrap !important;
    }

    .tabulator .tabulator-header .tabulator-col {
        border-right: none !important;
        background-color: transparent !important;
        padding: 12px 8px !important;
        min-width: 80px !important;
        white-space: nowrap !important;
    }

    .tabulator .tabulator-header .tabulator-col.tabulator-sortable:hover {
        background-color: #E2E8F0 !important;
    }

    /* Mobile table body */
    .tabulator .tabulator-tableHolder {
        background: white !important;
        overflow-x: auto !important;
        -webkit-overflow-scrolling: touch !important;
    }

    .tabulator .tabulator-table {
        background: white !important;
        min-width: 1100px !important;
    }

    /* Mobile row styling */
    .tabulator-row {
        border-bottom: 1px solid #F1F5F9 !important;
        background: white !important;
        min-height: 50px !important;
    }

    .tabulator-row:hover {
        background-color: #F8FAFC !important;
    }

    .tabulator-row.tabulator-row-even {
        background-color: #FAFAFA !important;
    }

    .tabulator-row.tabulator-row-even:hover {
        background-color: #F1F5F9 !important;
    }

    /* Mobile cell styling */
    .tabulator-cell {
        border-right: none !important;
        padding: 10px 8px !important;
        color: #475569 !important;
        font-size: 13px !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    /* Image cell styling */
    .product-image-cell {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px !important;
    }

    .product-image {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        border: 1px solid #E2E8F0;
        background-color: #F8FAFC;
    }

    .product-image-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        font-weight: 600;
    }

    /* Mobile pagination */
    .tabulator-paginator {
        border-top: 1px solid #E2E8F0 !important;
        background: #F8FAFC !important;
        padding: 12px 8px !important;
        color: #64748B !important;
        font-size: 13px !important;
        flex-wrap: wrap !important;
    }

    .tabulator-page {
        padding: 6px 10px !important;
        border: 1px solid #E2E8F0 !important;
        border-radius: 6px !important;
        background: white !important;
        color: #475569 !important;
        font-weight: 500 !important;
        font-size: 12px !important;
        margin: 2px !important;
        transition: all 0.2s ease !important;
        min-width: 32px !important;
        height: 32px !important;
    }

    .tabulator-page.active {
        background: #4F46E5 !important;
        color: white !important;
        border-color: #4F46E5 !important;
    }

    .tabulator-page:not(.disabled):hover {
        background: #E0E7FF !important;
        border-color: #C7D2FE !important;
    }

    /* Status badges - Mobile optimized */
    .status-badge {
        padding: 3px 8px !important;
        border-radius: 12px !important;
        font-size: 11px !important;
        font-weight: 600 !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 3px !important;
        white-space: nowrap !important;
        border: 1px solid !important;
    }

    /* Stock status colors */
    .stock-instock { background-color: #D1FAE5 !important; color: #065F46 !important; border-color: #6EE7B7 !important; }
    .stock-low { background-color: #FEF3C7 !important; color: #92400E !important; border-color: #FCD34D !important; }
    .stock-out { background-color: #FEE2E2 !important; color: #991B1B !important; border-color: #FCA5A5 !important; }

    /* Category colors */
    .category-badge {
        padding: 3px 8px !important;
        border-radius: 12px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        background: #F3F4F6 !important;
        color: #4B5563 !important;
        border: 1px solid #E5E7EB !important;
    }

    /* Mobile action buttons */
    .mobile-action-btn {
        padding: 4px 8px !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 3px !important;
        border: 1px solid #E2E8F0 !important;
        background: white !important;
        color: #475569 !important;
        text-decoration: none !important;
        white-space: nowrap !important;
    }

    .mobile-action-btn:hover {
        background: #F8FAFC !important;
        border-color: #CBD5E1 !important;
    }

    /* Mobile quick actions */
    .mobile-quick-actions {
        display: flex !important;
        overflow-x: auto !important;
        gap: 6px !important;
        padding: 8px !important;
        background: #F8FAFC !important;
        border-radius: 8px !important;
        margin-bottom: 12px !important;
        -webkit-overflow-scrolling: touch !important;
        scrollbar-width: none !important;
        -ms-overflow-style: none !important;
    }

    .mobile-quick-actions::-webkit-scrollbar {
        display: none !important;
    }

    .mobile-quick-action-btn {
        padding: 6px 10px !important;
        border-radius: 6px !important;
        font-size: 11px !important;
        font-weight: 500 !important;
        background: white !important;
        border: 1px solid #E2E8F0 !important;
        color: #475569 !important;
        cursor: pointer !important;
        display: flex !important;
        align-items: center !important;
        gap: 4px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
    }

    .mobile-quick-action-btn:hover {
        background: #F1F5F9 !important;
        border-color: #CBD5E1 !important;
    }

    /* Mobile filter section */
    .mobile-filter-section {
        background: #F8FAFC !important;
        border-radius: 8px !important;
        padding: 12px !important;
        margin-bottom: 12px !important;
        border: 1px solid #E2E8F0 !important;
    }

    /* Mobile stats grid */
    .mobile-stats-grid {
        display: grid !important;
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 16px !important;
    }

    .mobile-stat-card {
        padding: 12px !important;
        border-radius: 8px !important;
        border: 1px solid #E2E8F0 !important;
        background: white !important;
    }

    /* Mobile bulk actions */
    .mobile-bulk-actions {
        display: none !important;
        align-items: center !important;
        gap: 8px !important;
        padding: 10px !important;
        background: #E0E7FF !important;
        border-radius: 8px !important;
        margin-bottom: 12px !important;
        animation: slideUp 0.3s ease !important;
        overflow-x: auto !important;
        white-space: nowrap !important;
    }

    .mobile-bulk-actions.show {
        display: flex !important;
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

    /* Mobile table wrapper for horizontal scroll */
    .mobile-table-wrapper {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #E2E8F0;
        background: white;
    }

    .mobile-table-scroll-hint {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(79, 70, 229, 0.1);
        color: #4F46E5;
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 500;
        pointer-events: none;
        z-index: 5;
        animation: pulse 2s infinite;
        display: none;
    }

    @keyframes pulse {
        0%, 100% { opacity: 0.7; }
        50% { opacity: 1; }
    }

    @media (max-width: 767px) {
        .mobile-table-scroll-hint {
            display: block;
        }
    }

    /* Mobile table responsive adjustments */
    @media (max-width: 640px) {
        .tabulator .tabulator-header .tabulator-col {
            padding: 10px 6px !important;
            font-size: 11px !important;
            min-width: 70px !important;
        }
        
        .tabulator-cell {
            padding: 8px 6px !important;
            font-size: 12px !important;
        }
        
        .tabulator .tabulator-table {
            min-width: 1000px !important;
        }
        
        .status-badge {
            font-size: 9px !important;
            padding: 2px 6px !important;
        }
        
        .mobile-action-btn {
            font-size: 9px !important;
            padding: 3px 6px !important;
        }
        
        .product-image, .product-image-placeholder {
            width: 32px;
            height: 32px;
        }
    }

    /* Price styling */
    .price-cell {
        font-weight: 600;
        color: #059669;
    }

    /* Stock indicator */
    .stock-indicator {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 500;
    }

    .stock-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .stock-dot-instock { background-color: #10B981; }
    .stock-dot-low { background-color: #F59E0B; }
    .stock-dot-out { background-color: #EF4444; }

    /* Mobile floating action button */
    .mobile-fab {
        position: fixed;
        bottom: 90px;
        right: 20px;
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        border: none;
        border-radius: 50%;
        color: white;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 100;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .mobile-fab:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    /* Add button for desktop */
    .add-btn {
        position: fixed;
        bottom: 6rem;
        right: 1rem;
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    @media (min-width: 768px) {
        .add-btn {
            bottom: 2rem;
            right: 2rem;
        }
        .mobile-fab {
            display: none;
        }
    }

    @media (max-width: 767px) {
        .add-btn {
            display: none;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-4 space-y-4">
        <!-- Mobile Stats Grid -->
        <div class="mobile-stats-grid">
            <!-- Total Orders -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:shopping-bag" width="18" class="text-indigo-600"></iconify-icon>
                    <span class="text-xs text-slate-500">Total</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileTotalOrders">0</p>
            </div>

            <!-- Pending -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:clock" width="18" class="text-amber-500"></iconify-icon>
                    <span class="text-xs text-slate-500">Pending</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobilePendingOrders">0</p>
            </div>

            <!-- Delivered -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:check-circle" width="18" class="text-emerald-600"></iconify-icon>
                    <span class="text-xs text-slate-500">Delivered</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileDeliveredOrders">0</p>
            </div>

            <!-- Cancelled -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:x-circle" width="18" class="text-rose-600"></iconify-icon>
                    <span class="text-xs text-slate-500">Cancelled</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileCancelledOrders">0</p>
            </div>
        </div>

        <!-- Mobile Bulk Actions -->
        <div id="mobileBulkActions" class="mobile-bulk-actions">
            <span class="text-xs font-semibold text-indigo-700" id="mobileSelectedCount">0 selected</span>
            <div class="flex gap-2">
                <button onclick="bulkExportOrders()" class="mobile-quick-action-btn">
                    <iconify-icon icon="lucide:download" width="12"></iconify-icon>
                    Export
                </button>
                <button onclick="clearSelection()" class="mobile-quick-action-btn">
                    <iconify-icon icon="lucide:x" width="12"></iconify-icon>
                    Clear
                </button>
            </div>
        </div>

        <!-- Mobile Quick Actions -->
        <div class="mobile-quick-actions">
            <button onclick="showAllOrders()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:grid" width="12"></iconify-icon>
                All
            </button>
            <button onclick="showPendingOrders()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:clock" width="12"></iconify-icon>
                Pending
            </button>
            <button onclick="showDeliveredOrders()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:check-circle" width="12"></iconify-icon>
                Delivered
            </button>
            <button onclick="exportOrders()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:download" width="12"></iconify-icon>
                Export
            </button>
            <button onclick="toggleFilters()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:filter" width="12"></iconify-icon>
                Filter
            </button>
        </div>

        <!-- Mobile Filter Section -->
        <div id="mobileFilterSection" class="mobile-filter-section" style="display: none;">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1">Search</label>
                    <div class="relative">
                        <iconify-icon icon="lucide:search" width="14"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                        <input type="text" placeholder="Order ID, Shop Name, Status..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            id="mobileOrderSearch">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Status</label>
                        <select class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            id="mobileStatusFilter">
                            <option value="all">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-2 pt-2">
                    <button onclick="clearMobileFilters()" class="flex-1 px-3 py-2 text-xs font-medium text-slate-600 hover:text-slate-700 hover:bg-slate-100 rounded-lg border border-slate-200 transition-colors">
                        Clear
                    </button>
                    <button onclick="applyMobileFilters()" class="flex-1 px-3 py-2 text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors">
                        Apply
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Table Container -->
        <div class="mobile-table-wrapper">
            <div class="mobile-table-scroll-hint">← Scroll →</div>
            <div id="ordersTable"></div>
        </div>

        <!-- Mobile Loading State -->
        <div id="mobileLoadingState" class="flex items-center justify-center py-8">
            <div class="text-center">
                <div class="loading-spinner mb-3"></div>
                <p class="text-sm text-slate-500">Loading orders...</p>
            </div>
        </div>
    </main>
</div>

<!-- Mobile Floating Action Button -->
<button onclick="window.location.href='{{ route('salesperson.orders.create') }}'" class="mobile-fab">
    <iconify-icon icon="lucide:plus"></iconify-icon>
</button>

<!-- Desktop Add Button -->
<button onclick="window.location.href='{{ route('salesperson.orders.create') }}'" class="add-btn no-underline">
    <iconify-icon icon="lucide:plus"></iconify-icon>
</button>
@endsection

@section('scripts')
<!-- Include Tabulator CSS & JS -->
<link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

<script>
    let ordersTable;
    let allOrders = [];
    let selectedOrders = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadOrdersPage();
        setupMobileEventListeners();
    });

    function loadOrdersPage() {
        allOrders = @json($orders).map(order => {
            return {
                id: order.id,
                shopName: order.shop ? order.shop.name : 'Unknown Shop',
                salespersonName: order.salesperson ? order.salesperson.name : 'Unknown',
                totalAmount: parseFloat(order.total_amount),
                status: order.status,
                createdAt: order.created_at,
                dateDisplay: new Date(order.created_at).toLocaleDateString()
            };
        });
        
        initializeMobileTabulator();
        updateMobileStats();
        
        document.getElementById('mobileLoadingState').style.display = 'none';
    }

    function setupMobileEventListeners() {
        // Search input with debounce
        let searchTimer;
        const searchInput = document.getElementById('mobileOrderSearch');
        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => {
                    if (ordersTable) {
                        ordersTable.setFilter([
                            [
                                {field: "id", type: "like", value: e.target.value},
                                {field: "shopName", type: "like", value: e.target.value},
                                {field: "status", type: "like", value: e.target.value}
                            ]
                        ]);
                    }
                }, 300);
            });
        }

        // Filter change listeners
        const statusFilter = document.getElementById('mobileStatusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', applyMobileFilters);
        }

        // Touch events for mobile scrolling
        const tableWrapper = document.querySelector('.mobile-table-wrapper');
        if (tableWrapper) {
            let startX;
            let scrollLeft;
            
            tableWrapper.addEventListener('touchstart', (e) => {
                startX = e.touches[0].pageX;
                scrollLeft = tableWrapper.scrollLeft;
            });
            
            tableWrapper.addEventListener('touchmove', (e) => {
                const x = e.touches[0].pageX;
                const walk = (x - startX) * 2;
                tableWrapper.scrollLeft = scrollLeft - walk;
            });
        }
    }

    function initializeMobileTabulator() {
        const tableElement = document.getElementById('ordersTable');
        
        const mobileColumns = [
            {
                title: "",
                field: "selected",
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerHozAlign: "center",
                width: 50,
                resizable: false,
                headerSort: false,
            },
            {
                title: "ID",
                field: "id",
                width: 80,
                sorter: "number",
                resizable: false,
                formatter: function(cell) {
                    return `<div class="font-semibold text-indigo-600 text-xs">#${cell.getValue()}</div>`;
                }
            },
            {
                title: "SHOP",
                field: "shopName",
                width: 180,
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    const row = cell.getRow().getData();
                    return `<div class="text-xs">
                        <div class="font-medium truncate">${row.shopName}</div>
                        <div class="text-slate-500 text-2xs">${row.salespersonName}</div>
                    </div>`;
                }
            },
            {
                title: "AMOUNT",
                field: "totalAmount",
                width: 100,
                sorter: "number",
                resizable: false,
                formatter: function(cell) {
                    return `<div class="text-xs font-semibold text-emerald-600">₹${cell.getValue().toLocaleString()}</div>`;
                }
            },
            {
                title: "STATUS",
                field: "status",
                width: 120,
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    const status = cell.getValue();
                    let colorClass = 'bg-slate-100 text-slate-600 border-slate-200';
                    let icon = 'clock';
                    
                    if(status === 'confirmed') { colorClass = 'bg-blue-50 text-blue-600 border-blue-200'; icon = 'check-circle'; }
                    if(status === 'processing') { colorClass = 'bg-purple-50 text-purple-600 border-purple-200'; icon = 'refresh-cw'; }
                    if(status === 'shipped') { colorClass = 'bg-amber-50 text-amber-600 border-amber-200'; icon = 'truck'; }
                    if(status === 'delivered') { colorClass = 'bg-emerald-50 text-emerald-600 border-emerald-200'; icon = 'check-square'; }
                    if(status === 'cancelled') { colorClass = 'bg-rose-50 text-rose-600 border-rose-200'; icon = 'x-circle'; }
                    
                    return `<span class="status-badge ${colorClass}">
                        <iconify-icon icon="lucide:${icon}" width="10"></iconify-icon>
                        ${status.toUpperCase()}
                    </span>`;
                }
            },
            {
                title: "DATE",
                field: "dateDisplay",
                width: 100,
                sorter: "date",
                resizable: false,
                formatter: function(cell) {
                    return `<div class="text-xs text-slate-500">${cell.getValue()}</div>`;
                }
            },
            {
                title: "ACTIONS",
                width: 100,
                hozAlign: "center",
                headerSort: false,
                resizable: false,
                formatter: function(cell) {
                    const id = cell.getData().id;
                    const url = `{{ url('admin/orders') }}/${id}`;
                    return `<a href="${url}" class="mobile-action-btn">
                        <iconify-icon icon="lucide:eye" width="12"></iconify-icon>
                        View
                    </a>`;
                }
            }
        ];

        ordersTable = new Tabulator(tableElement, {
            data: allOrders,
            layout: "fitDataStretch",
            responsiveLayout: false,
            pagination: "local",
            paginationSize: 20,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            resizableRows: false,
            selectable: true,
            selectableRangeMode: "click",
            headerSort: true,
            height: "500px",
            columns: mobileColumns,
            rowSelectionChanged: function(data, rows) {
                selectedOrders = data.map(row => row.id);
                const bulkActions = document.getElementById('mobileBulkActions');
                const selectedCount = document.getElementById('mobileSelectedCount');
                
                if (selectedOrders.length > 0) {
                    bulkActions.classList.add('show');
                    selectedCount.textContent = `${selectedOrders.length} selected`;
                } else {
                    bulkActions.classList.remove('show');
                }
            },
            rowClick: function(e, row) {
                if (!e.target.closest('.mobile-action-btn') && 
                    !e.target.closest('.tabulator-row-selection-checkbox')) {
                    window.location.href = `{{ url('admin/orders') }}/${row.getData().id}`;
                }
            },
            renderStarted: function() {
                if(document.getElementById('mobileLoadingState')) document.getElementById('mobileLoadingState').style.display = 'flex';
            },
            renderComplete: function() {
                if(document.getElementById('mobileLoadingState')) document.getElementById('mobileLoadingState').style.display = 'none';
                updateMobileStats();
            }
        });

        // Hide scroll hint after first scroll
        let scrollHint = document.querySelector('.mobile-table-scroll-hint');
        if (scrollHint) {
            const tableHolder = tableElement.querySelector('.tabulator-tableHolder');
            if (tableHolder) {
                tableHolder.addEventListener('scroll', function() {
                    scrollHint.style.opacity = '0';
                    setTimeout(() => {
                        scrollHint.style.display = 'none';
                    }, 300);
                }, { once: true });
            }
        }
    }

    function updateMobileStats() {
        const total = allOrders.length;
        const pending = allOrders.filter(o => o.status === 'pending').length;
        const delivered = allOrders.filter(o => o.status === 'delivered').length;
        const cancelled = allOrders.filter(o => o.status === 'cancelled').length;

        if(document.getElementById('mobileTotalOrders')) document.getElementById('mobileTotalOrders').textContent = total.toLocaleString();
        if(document.getElementById('mobilePendingOrders')) document.getElementById('mobilePendingOrders').textContent = pending.toLocaleString();
        if(document.getElementById('mobileDeliveredOrders')) document.getElementById('mobileDeliveredOrders').textContent = delivered.toLocaleString();
        if(document.getElementById('mobileCancelledOrders')) document.getElementById('mobileCancelledOrders').textContent = cancelled.toLocaleString();
    }

    function applyMobileFilters() {
        const status = document.getElementById('mobileStatusFilter').value;
        
        if (status !== 'all') {
            ordersTable.setFilter('status', '=', status);
        } else {
            ordersTable.clearFilter();
        }
        
        document.getElementById('mobileFilterSection').style.display = 'none';
    }

    function clearMobileFilters() {
        const searchInput = document.getElementById('mobileOrderSearch');
        const statusFilter = document.getElementById('mobileStatusFilter');
        
        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = 'all';
        
        if (ordersTable) {
            ordersTable.clearFilter();
            ordersTable.clearHeaderFilter();
        }
        document.getElementById('mobileFilterSection').style.display = 'none';
    }

    function toggleFilters() {
        const filterSection = document.getElementById('mobileFilterSection');
        if (filterSection) {
            filterSection.style.display = filterSection.style.display === 'none' ? 'block' : 'none';
        }
    }

    function showAllOrders() {
        clearMobileFilters();
    }

    function showPendingOrders() {
        const statusFilter = document.getElementById('mobileStatusFilter');
        if (statusFilter) statusFilter.value = 'pending';
        applyMobileFilters();
    }

    function showDeliveredOrders() {
        const statusFilter = document.getElementById('mobileStatusFilter');
        if (statusFilter) statusFilter.value = 'delivered';
        applyMobileFilters();
    }

    function exportOrders() {
        ordersTable.download("csv", "orders.csv");
        showMobileNotification('Export started', 'info');
    }

    function bulkExportOrders() {
        if (selectedOrders.length === 0) {
            showMobileNotification('Please select orders first', 'warning');
            return;
        }
        
        const selectedData = allOrders.filter(order => selectedOrders.includes(order.id));
        
        let csvContent = "Order ID,Shop Name,Salesperson,Amount,Status,Date\n";
        selectedData.forEach(order => {
            csvContent += `"#${order.id}","${order.shopName}","${order.salespersonName}",${order.totalAmount},"${order.status}","${order.dateDisplay}"\n`;
        });
        
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `orders_selected_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        showMobileNotification(`${selectedOrders.length} order(s) exported`, 'success');
    }

    function clearSelection() {
        ordersTable.deselectRow();
        selectedOrders = [];
        document.getElementById('mobileBulkActions').classList.remove('show');
    }

    function showMobileNotification(message, type) {
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800',
            warning: 'bg-amber-50 border-amber-200 text-amber-800'
        };

        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 left-4 z-50 px-4 py-3 rounded-lg border ${colors[type]} animate-slide-up shadow-lg`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium">${message}</span>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="p-1">
                    <iconify-icon icon="lucide:x" width="16"></iconify-icon>
                </button>
            </div>
        `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-10px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
@endsection
