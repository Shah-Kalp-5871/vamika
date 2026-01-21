@extends('layouts.admin')

@section('title', 'Manage Products - Admin')

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
            <!-- Total Products -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:package" width="18" class="text-indigo-600"></iconify-icon>
                    <span class="text-xs text-slate-500">Total</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileTotalProducts">0</p>
            </div>

            <!-- In Stock -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:check-circle" width="18" class="text-emerald-600"></iconify-icon>
                    <span class="text-xs text-slate-500">In Stock</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileInStock">0</p>
            </div>

            <!-- Low Stock -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:alert-circle" width="18" class="text-amber-500"></iconify-icon>
                    <span class="text-xs text-slate-500">Low Stock</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileLowStock">0</p>
            </div>

            <!-- Out of Stock -->
            <div class="mobile-stat-card">
                <div class="flex items-center justify-between mb-2">
                    <iconify-icon icon="lucide:x-circle" width="18" class="text-rose-600"></iconify-icon>
                    <span class="text-xs text-slate-500">Out of Stock</span>
                </div>
                <p class="text-xl font-bold text-slate-900" id="mobileOutOfStock">0</p>
            </div>
        </div>

        <!-- Mobile Bulk Actions -->
        <div id="mobileBulkActions" class="mobile-bulk-actions">
            <span class="text-xs font-semibold text-indigo-700" id="mobileSelectedCount">0 selected</span>
            <div class="flex gap-2">
                <button onclick="bulkExportProducts()" class="mobile-quick-action-btn">
                    <iconify-icon icon="lucide:download" width="12"></iconify-icon>
                    Export
                </button>
                <button onclick="bulkUpdateStock()" class="mobile-quick-action-btn">
                    <iconify-icon icon="lucide:edit" width="12"></iconify-icon>
                    Update Stock
                </button>
                <button onclick="bulkDeleteProducts()" class="mobile-quick-action-btn">
                    <iconify-icon icon="lucide:trash-2" width="12"></iconify-icon>
                    Delete
                </button>
                <button onclick="clearSelection()" class="mobile-quick-action-btn">
                    <iconify-icon icon="lucide:x" width="12"></iconify-icon>
                    Clear
                </button>
            </div>
        </div>

        <!-- Mobile Quick Actions -->
        <div class="mobile-quick-actions">
            <button onclick="showAllProducts()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:grid" width="12"></iconify-icon>
                All
            </button>
            <button onclick="showInStock()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:check" width="12"></iconify-icon>
                In Stock
            </button>
            <button onclick="showLowStock()" class="mobile-quick-action-btn">
                <iconify-icon icon="lucide:alert-circle" width="12"></iconify-icon>
                Low Stock
            </button>
            <button onclick="exportToExcel()" class="mobile-quick-action-btn">
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
                        <input type="text" placeholder="Product name, SKU, category..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            id="mobileProductSearch">
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Category</label>
                        <select class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            id="mobileCategoryFilter">
                            <option value="all">All Categories</option>
                            <option value="Groceries">Groceries</option>
                            <option value="Food">Food</option>
                            <option value="Home Care">Home Care</option>
                            <option value="Personal Care">Personal Care</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Dairy">Dairy</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1">Stock Status</label>
                        <select class="w-full text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            id="mobileStockFilter">
                            <option value="all">All Stock</option>
                            <option value="instock">In Stock</option>
                            <option value="low">Low Stock</option>
                            <option value="out">Out of Stock</option>
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
            <div id="mobileProductsTable"></div>
        </div>

        <!-- Mobile Loading State -->
        <div id="mobileLoadingState" class="flex items-center justify-center py-8">
            <div class="text-center">
                <div class="loading-spinner mb-3"></div>
                <p class="text-sm text-slate-500">Loading products...</p>
            </div>
        </div>
    </main>
</div>

<!-- Mobile Floating Action Button -->
<button onclick="showMobileActions()" class="mobile-fab ">
    <iconify-icon icon="lucide:plus"></iconify-icon>
</button>

<!-- Desktop Add Button -->
<a href="{{ route('admin.products.create') }}" class="add-btn no-underline">
    <iconify-icon icon="lucide:plus"></iconify-icon>
</a>
@endsection

@section('scripts')
<!-- Include Tabulator CSS & JS -->
<link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">
<script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>

<script>
    let productsTable;
    let allProducts = [];
    let selectedProducts = [];

    // Product images for demo
    const productImages = [
        'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1490818387583-1baba5e638af?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1606788075767-48f6c1c8d1e9?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1588964895597-cfccd6e2dbf9?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1539136788836-5699e78bfc75?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1556905055-8f358a7a47b2?w=100&h=100&fit=crop',
        'https://images.unsplash.com/photo-1556905200-279565513a2d?w=100&h=100&fit=crop'
    ];

    // Generate sample data for products
    function generateProducts(count) {
        const categories = ['Groceries', 'Food', 'Home Care', 'Personal Care', 'Beverages', 'Dairy'];
        const units = ['kg', 'g', 'L', 'ml', 'piece', 'pack', 'dozen'];
        const brands = ['Nestle', 'Unilever', 'ITC', 'HUL', 'Amul', 'Britannia', 'Patanjali', 'Dabur'];
        
        const products = [];
        for (let i = 1; i <= count; i++) {
            const category = categories[Math.floor(Math.random() * categories.length)];
            const stock = Math.floor(Math.random() * 100);
            let stockStatus = 'instock';
            if (stock === 0) stockStatus = 'out';
            else if (stock <= 10) stockStatus = 'low';
            
            const basePrice = Math.floor(Math.random() * 500) + 50;
            const mrp = basePrice + Math.floor(Math.random() * 100);
            const discount = Math.floor(Math.random() * 30);
            const sellingPrice = mrp - (mrp * discount / 100);
            
            // Randomly assign image or placeholder
            const hasImage = Math.random() > 0.3;
            const imageIndex = Math.floor(Math.random() * productImages.length);
            
            products.push({
                id: i,
                sku: 'SKU' + String(10000 + i).substring(1),
                name: `${brands[Math.floor(Math.random() * brands.length)]} ${category} Product ${i}`,
                category: category,
                brand: brands[Math.floor(Math.random() * brands.length)],
                unit: units[Math.floor(Math.random() * units.length)],
                packSize: `${Math.floor(Math.random() * 10) + 1}${units[Math.floor(Math.random() * units.length)]}`,
                mrp: mrp,
                price: Math.round(sellingPrice),
                stock: stock,
                stockStatus: stockStatus,
                minStock: 10,
                maxStock: 100,
                lastUpdated: `2024-01-${String(Math.floor(Math.random() * 15) + 1).padStart(2, '0')}`,
                status: stockStatus === 'out' ? 'Inactive' : 'Active',
                image: hasImage ? productImages[imageIndex] : null,
                description: `Premium quality ${category.toLowerCase()} product from ${brands[Math.floor(Math.random() * brands.length)]}.`
            });
        }
        return products;
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadProductsPage();
        setupMobileEventListeners();
    });

    function setupMobileEventListeners() {
        // Search input with debounce
        let searchTimer;
        document.getElementById('mobileProductSearch').addEventListener('input', function(e) {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => {
                if (productsTable) {
                    productsTable.setFilter([
                        [
                            {field: "sku", type: "like", value: e.target.value},
                            {field: "name", type: "like", value: e.target.value},
                            {field: "category", type: "like", value: e.target.value},
                            {field: "brand", type: "like", value: e.target.value}
                        ]
                    ]);
                }
            }, 300);
        });

        // Filter change listeners
        document.getElementById('mobileCategoryFilter').addEventListener('change', applyMobileFilters);
        document.getElementById('mobileStockFilter').addEventListener('change', applyMobileFilters);

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

    function loadProductsPage() {
        // Generate sample products (500+ for testing)
        allProducts = generateProducts(500);
        
        // Initialize Tabulator with mobile-first configuration
        initializeMobileTabulator();
        updateMobileStats();
        
        // Hide loading state after a short delay
        setTimeout(() => {
            document.getElementById('mobileLoadingState').style.display = 'none';
        }, 500);
    }

    function initializeMobileTabulator() {
        const tableElement = document.getElementById('mobileProductsTable');
        
        // Define mobile-optimized columns with image column
        const mobileColumns = [
            {
                title: "",
                field: "selected",
                formatter: "rowSelection",
                titleFormatter: "rowSelection",
                hozAlign: "center",
                headerHozAlign: "center",
                width: 60,
                resizable: false,
                headerSort: false,
            },
            {
                title: "IMAGE",
                field: "image",
                width: 70,
                hozAlign: "center",
                headerHozAlign: "center",
                resizable: false,
                formatter: function(cell) {
                    const imageUrl = cell.getValue();
                    const row = cell.getRow().getData();
                    const initials = row.name.substring(0, 2).toUpperCase();
                    
                    if (imageUrl) {
                        return `<div class="product-image-cell">
                            <img src="${imageUrl}" alt="${row.name}" class="product-image" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\"product-image-placeholder\"></div>`;
                    } else {
                        return `<div class="product-image-cell">
                            <div class="product-image-placeholder">${initials}</div>
                        </div>`;
                    }
                },
                cellClick: function(e, cell) {
                    // Open view mode when clicking on image
                    const rowData = cell.getRow().getData();
                    viewProduct(rowData.id);
                }
            },
            {
                title: "SKU",
                field: "sku",
                width: 100,
                headerFilter: "input",
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    return `<div class="font-semibold text-indigo-600 text-xs">${cell.getValue()}</div>`;
                }
            },
            {
                title: "PRODUCT",
                field: "name",
                width: 180,
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    const row = cell.getRow().getData();
                    return `<div class="text-xs">
                        <div class="font-medium truncate">${row.name}</div>
                        <div class="text-slate-500 text-2xs">${row.brand}</div>
                    </div>`;
                }
            },
            {
                title: "CATEGORY",
                field: "category",
                width: 120,
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    return `<span class="category-badge">${cell.getValue()}</span>`;
                }
            },
            {
                title: "PACK",
                field: "packSize",
                width: 80,
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    return `<div class="text-xs font-medium">${cell.getValue()}</div>`;
                }
            },
            {
                title: "MRP",
                field: "mrp",
                width: 80,
                sorter: "number",
                resizable: false,
                formatter: function(cell) {
                    return `<div class="text-xs text-slate-500 line-through">₹${cell.getValue()}</div>`;
                }
            },
            {
                title: "PRICE",
                field: "price",
                width: 80,
                sorter: "number",
                resizable: false,
                formatter: function(cell) {
                    const row = cell.getRow().getData();
                    const discount = Math.round(((row.mrp - row.price) / row.mrp) * 100);
                    return `<div class="text-xs">
                        <div class="font-semibold text-emerald-600">₹${cell.getValue()}</div>
                        ${discount > 0 ? `<div class="text-rose-600 text-2xs">${discount}% off</div>` : ''}
                    </div>`;
                }
            },
            {
                title: "STOCK",
                field: "stock",
                width: 80,
                sorter: "number",
                resizable: false,
                formatter: function(cell) {
                    const row = cell.getRow().getData();
                    const stockConfig = {
                        instock: { class: 'stock-instock', dot: 'stock-dot-instock', text: 'In Stock' },
                        low: { class: 'stock-low', dot: 'stock-dot-low', text: 'Low Stock' },
                        out: { class: 'stock-out', dot: 'stock-dot-out', text: 'Out of Stock' }
                    };
                    
                    const config = stockConfig[row.stockStatus] || stockConfig.instock;
                    return `<div class="text-xs">
                        <div class="flex items-center gap-1">
                            <span class="stock-dot ${config.dot}"></span>
                            <span class="font-medium">${cell.getValue()}</span>
                        </div>
                        <div class="text-slate-500 text-2xs">${config.text}</div>
                    </div>`;
                }
            },
            {
                title: "STATUS",
                field: "status",
                width: 90,
                sorter: "string",
                resizable: false,
                formatter: function(cell) {
                    const status = cell.getValue();
                    const isActive = status === 'Active';
                    const icon = isActive ? 'check-circle' : 'x-circle';
                    const color = isActive ? 'stock-instock' : 'stock-out';
                    return `<span class="status-badge ${color}">
                        <iconify-icon icon="lucide:${icon}" width="10"></iconify-icon>
                        ${status}
                    </span>`;
                }
            },
            {
                title: "UPDATED",
                field: "lastUpdated",
                width: 90,
                sorter: "date",
                resizable: false,
                formatter: function(cell) {
                    const date = new Date(cell.getValue());
                    return `<div class="text-xs">
                        <div>${date.getDate()}/${date.getMonth() + 1}</div>
                    </div>`;
                }
            },
            {
                title: "ACTIONS",
                field: "actions",
                width: 100,
                hozAlign: "center",
                headerSort: false,
                resizable: false,
                formatter: function(cell) {
                    const row = cell.getRow().getData();
                    return `<div class="flex gap-1">
                        <button onclick="viewProduct(${row.id})" class="mobile-action-btn" title="View">
                            <iconify-icon icon="lucide:eye" width="12"></iconify-icon>
                            View
                        </button>
                        <button onclick="editProduct(${row.id})" class="mobile-action-btn" title="Edit">
                            <iconify-icon icon="lucide:edit" width="12"></iconify-icon>
                            Edit
                        </button>
                    </div>`;
                }
            }
        ];

        // Create Tabulator instance with mobile-first configuration
        productsTable = new Tabulator(tableElement, {
            data: allProducts,
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
                selectedProducts = data.map(row => row.id);
                const bulkActions = document.getElementById('mobileBulkActions');
                const selectedCount = document.getElementById('mobileSelectedCount');
                
                if (selectedProducts.length > 0) {
                    bulkActions.classList.add('show');
                    selectedCount.textContent = `${selectedProducts.length} selected`;
                } else {
                    bulkActions.classList.remove('show');
                }
            },
            rowClick: function(e, row) {
                // Prevent row click when clicking on buttons or image
                if (!e.target.closest('.mobile-action-btn') && 
                    !e.target.closest('.tabulator-row-selection-checkbox') &&
                    !e.target.closest('.product-image-cell')) {
                    viewProduct(row.getData().id);
                }
            },
            renderStarted: function() {
                // Show loading indicator
                document.getElementById('mobileLoadingState').style.display = 'flex';
            },
            renderComplete: function() {
                // Hide loading indicator
                document.getElementById('mobileLoadingState').style.display = 'none';
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
        const totalProducts = allProducts.length;
        const inStockProducts = allProducts.filter(p => p.stockStatus === 'instock').length;
        const lowStockProducts = allProducts.filter(p => p.stockStatus === 'low').length;
        const outOfStockProducts = allProducts.filter(p => p.stockStatus === 'out').length;

        // Update mobile stats
        document.getElementById('mobileTotalProducts').textContent = totalProducts.toLocaleString();
        document.getElementById('mobileInStock').textContent = inStockProducts.toLocaleString();
        document.getElementById('mobileLowStock').textContent = lowStockProducts.toLocaleString();
        document.getElementById('mobileOutOfStock').textContent = outOfStockProducts.toLocaleString();
    }

    function applyMobileFilters() {
        const category = document.getElementById('mobileCategoryFilter').value;
        const stock = document.getElementById('mobileStockFilter').value;
        
        let filters = [];
        
        if (category !== 'all') {
            filters.push({field: "category", type: "=", value: category});
        }
        
        if (stock !== 'all') {
            filters.push({field: "stockStatus", type: "=", value: stock});
        }
        
        if (filters.length > 0) {
            productsTable.setFilter(filters);
        } else {
            productsTable.clearFilter();
        }
        
        // Hide filter section after applying
        document.getElementById('mobileFilterSection').style.display = 'none';
    }

    function clearMobileFilters() {
        document.getElementById('mobileProductSearch').value = '';
        document.getElementById('mobileCategoryFilter').value = 'all';
        document.getElementById('mobileStockFilter').value = 'all';
        
        productsTable.clearFilter();
        productsTable.clearHeaderFilter();
        document.getElementById('mobileFilterSection').style.display = 'none';
    }

    function toggleFilters() {
        const filterSection = document.getElementById('mobileFilterSection');
        filterSection.style.display = filterSection.style.display === 'none' ? 'block' : 'none';
    }

    function showAllProducts() {
        clearMobileFilters();
    }

    function showInStock() {
        document.getElementById('mobileStockFilter').value = 'instock';
        applyMobileFilters();
    }

    function showLowStock() {
        document.getElementById('mobileStockFilter').value = 'low';
        applyMobileFilters();
    }

    function bulkExportProducts() {
        if (selectedProducts.length === 0) {
            showMobileNotification('Please select products first', 'warning');
            return;
        }
        
        // Filter selected products
        const selectedData = allProducts.filter(product => selectedProducts.includes(product.id));
        
        // Create CSV content
        let csvContent = "SKU,Product Name,Category,Brand,Unit,Pack Size,MRP,Price,Stock,Status,Last Updated\n";
        selectedData.forEach(product => {
            csvContent += `"${product.sku}","${product.name}","${product.category}","${product.brand}","${product.unit}","${product.packSize}",${product.mrp},${product.price},${product.stock},"${product.status}","${product.lastUpdated}"\n`;
        });
        
        // Download CSV
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `products_${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
        
        showMobileNotification(`${selectedProducts.length} product(s) exported`, 'success');
    }

    function bulkUpdateStock() {
        if (selectedProducts.length === 0) {
            showMobileNotification('Please select products first', 'warning');
            return;
        }
        
        // Show stock update dialog
        const actionSheet = document.createElement('div');
        actionSheet.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end justify-center';
        actionSheet.innerHTML = `
            <div class="bg-white w-full max-w-md rounded-t-2xl p-4 animate-slide-up">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Update Stock</h3>
                    <button onclick="this.closest('.fixed').remove()" class="p-2">
                        <iconify-icon icon="lucide:x" width="20"></iconify-icon>
                    </button>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">New Stock Quantity</label>
                        <input type="number" id="newStockQuantity" 
                            class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Enter stock quantity" min="0">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Action</label>
                        <select id="stockAction" class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="set">Set to this quantity</option>
                            <option value="add">Add to existing stock</option>
                            <option value="subtract">Subtract from existing stock</option>
                        </select>
                    </div>
                    <button onclick="applyBulkStockUpdate()" class="w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700">
                        Update ${selectedProducts.length} Product(s)
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(actionSheet);
        
        // Close on background tap
        actionSheet.addEventListener('click', function(e) {
            if (e.target === this) {
                this.remove();
            }
        });
    }

    function applyBulkStockUpdate() {
        const quantity = parseInt(document.getElementById('newStockQuantity').value);
        const action = document.getElementById('stockAction').value;
        
        if (isNaN(quantity) || quantity < 0) {
            showMobileNotification('Please enter a valid quantity', 'error');
            return;
        }
        
        // Update products
        allProducts.forEach(product => {
            if (selectedProducts.includes(product.id)) {
                let newStock = product.stock;
                
                switch(action) {
                    case 'set':
                        newStock = quantity;
                        break;
                    case 'add':
                        newStock = product.stock + quantity;
                        break;
                    case 'subtract':
                        newStock = Math.max(0, product.stock - quantity);
                        break;
                }
                
                product.stock = newStock;
                
                // Update stock status
                if (newStock === 0) product.stockStatus = 'out';
                else if (newStock <= 10) product.stockStatus = 'low';
                else product.stockStatus = 'instock';
                
                product.status = product.stockStatus === 'out' ? 'Inactive' : 'Active';
                product.lastUpdated = new Date().toISOString().split('T')[0];
            }
        });
        
        // Refresh table
        productsTable.replaceData(allProducts);
        updateMobileStats();
        clearSelection();
        
        // Close dialog
        document.querySelector('.fixed').remove();
        showMobileNotification(`Stock updated for ${selectedProducts.length} product(s)`, 'success');
    }

    function bulkDeleteProducts() {
        if (selectedProducts.length === 0) {
            showMobileNotification('Please select products first', 'warning');
            return;
        }
        
        if (confirm(`Are you sure you want to delete ${selectedProducts.length} product(s)?`)) {
            // Remove products
            allProducts = allProducts.filter(product => !selectedProducts.includes(product.id));
            
            // Refresh table
            productsTable.replaceData(allProducts);
            updateMobileStats();
            clearSelection();
            showMobileNotification(`${selectedProducts.length} product(s) deleted`, 'success');
        }
    }

    function clearSelection() {
        productsTable.deselectRow();
        selectedProducts = [];
        document.getElementById('mobileBulkActions').classList.remove('show');
    }

    function viewProduct(productId) {
        window.location.href = '{{ route("admin.products.stock", "") }}/' + productId;
    }

    function editProduct(productId) {
        window.location.href = '{{ route("admin.products.edit", ":id") }}'.replace(':id', productId);
    }

    function exportToExcel() {
        productsTable.download("csv", "products.csv");
        showMobileNotification('Export started', 'info');
    }

    function showMobileActions() {
        // Create action sheet
        const actionSheet = document.createElement('div');
        actionSheet.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-end justify-center';
        actionSheet.innerHTML = `
            <div class="bg-white w-full max-w-md rounded-t-2xl p-4 animate-slide-up">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-slate-900">Quick Actions</h3>
                    <button onclick="this.closest('.fixed').remove()" class="p-2">
                        <iconify-icon icon="lucide:x" width="20"></iconify-icon>
                    </button>
                </div>
                <div class="space-y-2">
                    <a href="{{ route('admin.products.create') }}" class="w-full text-left p-3 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center gap-3 no-underline text-slate-900">
                        <iconify-icon icon="lucide:plus-circle" class="text-indigo-600" width="20"></iconify-icon>
                        <span>Add New Product</span>
                    </a>
                    <button onclick="exportToExcel()" class="w-full text-left p-3 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center gap-3">
                        <iconify-icon icon="lucide:download" class="text-emerald-600" width="20"></iconify-icon>
                        <span>Export Products</span>
                    </button>
                    <button onclick="toggleFilters()" class="w-full text-left p-3 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center gap-3">
                        <iconify-icon icon="lucide:filter" class="text-amber-600" width="20"></iconify-icon>
                        <span>Filter Products</span>
                    </button>
                    <button onclick="showLowStock()" class="w-full text-left p-3 rounded-lg border border-slate-200 hover:bg-slate-50 flex items-center gap-3">
                        <iconify-icon icon="lucide:alert-circle" class="text-orange-600" width="20"></iconify-icon>
                        <span>View Low Stock</span>
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(actionSheet);
        
        // Close on background tap
        actionSheet.addEventListener('click', function(e) {
            if (e.target === this) {
                this.remove();
            }
        });
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