@extends('layouts.admin')

@section('title', 'Top Products - Admin')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
    }

    @media (min-width: 640px) {
        .main-content {
            max-width: 42rem;
            margin: 2rem auto;
            min-height: calc(100vh - 4rem);
            background-color: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            border-radius: 1rem;
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }
    }

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .rank-1 {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: #7C2D12;
        border: 2px solid #FBBF24;
    }

    .rank-2 {
        background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
        color: #374151;
        border: 2px solid #D1D5DB;
    }

    .rank-3 {
        background: linear-gradient(135deg, #CD7F32, #A0522D);
        color: #7C2D12;
        border: 2px solid #F59E0B;
    }

    .rank-4-10 {
        background: #F3F4F6;
        color: #4B5563;
        border: 1px solid #E5E7EB;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    .back-link {
        color: #667eea;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .back-link:hover {
        color: #764ba2;
        gap: 0.75rem;
    }

    .category-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .category-grocery {
        background: rgba(34, 197, 94, 0.1);
        color: #16A34A;
        border: 1px solid rgba(34, 197, 94, 0.2);
    }

    .category-beverages {
        background: rgba(59, 130, 246, 0.1);
        color: #2563EB;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .category-snacks {
        background: rgba(245, 158, 11, 0.1);
        color: #D97706;
        border: 1px solid rgba(245, 158, 11, 0.2);
    }

    .category-personal-care {
        background: rgba(168, 85, 247, 0.1);
        color: #9333EA;
        border: 1px solid rgba(168, 85, 247, 0.2);
    }
    
    .date-input {
        width: 100%;
        padding: 0.5rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: white;
    }
    
    .date-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="flex items-start justify-between">
            <!-- Left: Logo + Back + Title -->
            <div class="flex items-start gap-4">
                <!-- Logo -->
                <img src="{{ asset('assets/images/logo.png') }}" alt="Vamika Enterprise"
                    class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1">

                <!-- Back + Page Info -->
                <div>
                    <a href="{{ route('admin.reports.index') }}" class="back-link mb-1 inline-flex">
                        <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
                        Back to Analytics
                    </a>

                    <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
                        Top Products
                    </h1>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Best selling products performance
                    </p>
                </div>
            </div>

            <!-- Right: Context -->
            <div class="flex flex-col items-end">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    <iconify-icon icon="carbon:analytics" width="12" class="mr-1.5"></iconify-icon>
                    Analytics
                </span>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select class="date-input">
                    <option>All Categories</option>
                    <option>Groceries</option>
                    <option>Beverages</option>
                    <option>Snacks</option>
                    <option>Personal Care</option>
                </select>
                <select class="date-input">
                    <option>Sort by Revenue</option>
                    <option>Sort by Quantity</option>
                    <option>Sort by Growth</option>
                    <option>Sort by Profit</option>
                </select>
                <select class="date-input">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Quarter</option>
                    <option>This Year</option>
                </select>
                <input type="text" class="date-input" placeholder="Search products...">
            </div>
            <div class="flex gap-3 mt-4">
                <button class="btn-primary px-6">
                    Apply Filters
                </button>
                <button class="btn-secondary" onclick="resetProductFilters()">
                    Reset
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">156</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Products</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">₹8,95,420</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Revenue</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">4,256</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Units Sold</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">₹210</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Avg. Price</p>
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <div class="flex justify-between items-center">
                    <h3 class="text-sm font-semibold text-slate-900">Top Performing Products</h3>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">Showing 10 of 156 products</span>
                    </div>
                </div>
            </div>
            <div class="divide-y divide-slate-100" id="productsList">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="bg-white rounded-xl border border-amber-200 shadow-sm p-4 bg-amber-50">
            <div class="flex items-start gap-3">
                <iconify-icon icon="lucide:alert-triangle" width="20" class="text-amber-600 mt-0.5"></iconify-icon>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-amber-900 mb-1">Low Stock Alert</h4>
                    <p class="text-xs text-amber-700">8 products are running low on stock. Consider restocking soon.</p>
                </div>
                <a href="#" class="text-xs font-medium text-amber-700 hover:text-amber-900">
                    View Inventory →
                </a>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Sample data for products
    const productsData = [
        { id: 1, name: 'Aashirvaad Atta', sales: 120, revenue: 50400, stock: 150, category: 'Groceries', price: 420, growth: 25.5, profitMargin: 18 },
        { id: 2, name: 'Fortune Sunflower Oil', sales: 95, revenue: 19950, stock: 200, category: 'Groceries', price: 210, growth: 12.3, profitMargin: 15 },
        { id: 3, name: 'Maggi Noodles', sales: 85, revenue: 5950, stock: 300, category: 'Snacks', price: 70, growth: 8.7, profitMargin: 22 },
        { id: 4, name: 'Colgate Toothpaste', sales: 78, revenue: 6630, stock: 250, category: 'Personal Care', price: 85, growth: 15.8, profitMargin: 25 },
        { id: 5, name: 'Parle-G Biscuits', sales: 72, revenue: 3600, stock: 400, category: 'Snacks', price: 50, growth: 5.2, profitMargin: 20 },
        { id: 6, name: 'Tata Tea', sales: 65, revenue: 8775, stock: 180, category: 'Beverages', price: 135, growth: 18.9, profitMargin: 16 },
        { id: 7, name: 'Dettol Soap', sales: 58, revenue: 4350, stock: 220, category: 'Personal Care', price: 75, growth: 22.1, profitMargin: 28 },
        { id: 8, name: 'Amul Butter', sales: 52, revenue: 6240, stock: 150, category: 'Groceries', price: 120, growth: 14.3, profitMargin: 17 },
        { id: 9, name: 'Lays Chips', sales: 48, revenue: 4800, stock: 280, category: 'Snacks', price: 100, growth: 9.8, profitMargin: 24 },
        { id: 10, name: 'Pepsi 2L', sales: 42, revenue: 3360, stock: 190, category: 'Beverages', price: 80, growth: 7.5, profitMargin: 19 }
    ];

    function loadProductsPage() {
        loadProductsList();
    }

    function loadProductsList() {
        const listContainer = document.getElementById('productsList');
        listContainer.innerHTML = '';

        productsData.forEach((product, index) => {
            const stockStatus = product.stock < 200 ? 'Low' : product.stock < 300 ? 'Medium' : 'High';
            const stockClass = product.stock < 200 ? 'text-rose-600 bg-rose-50' :
                product.stock < 300 ? 'text-amber-600 bg-amber-50' :
                    'text-emerald-600 bg-emerald-50';
            const categoryClass = `category-${product.category.toLowerCase().replace(' ', '-')}`;

            const item = document.createElement('div');
            item.className = 'p-4 hover:bg-slate-50 transition-colors';
            item.innerHTML = `
                <div class="flex items-center gap-4 mb-3">
                    <div class="rank-badge ${index < 3 ? `rank-${index + 1}` : 'rank-4-10'}">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">${product.name}</h4>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="category-badge ${categoryClass}">
                                        ${product.category}
                                    </span>
                                    <span class="text-xs text-slate-500">₹${product.price}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-sm font-semibold ${product.growth >= 15 ? 'text-emerald-600' : product.growth >= 5 ? 'text-amber-600' : 'text-rose-600'}">
                                    ${product.growth >= 0 ? '+' : ''}${product.growth}%
                                </div>
                                <div class="text-xs text-slate-500">Growth</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">₹${product.revenue.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">Revenue</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">${product.sales}</div>
                        <div class="text-xs text-slate-500">Units Sold</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">${product.profitMargin}%</div>
                        <div class="text-xs text-slate-500">Profit Margin</div>
                    </div>
                    <div class="p-3 ${stockClass} rounded-lg">
                        <div class="text-sm font-semibold">${product.stock}</div>
                        <div class="text-xs">Stock (${stockStatus})</div>
                    </div>
                </div>
                
                <!-- Stock progress bar -->
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Stock Level</span>
                        <span>${Math.round((product.stock / 500) * 100)}%</span>
                    </div>
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full ${product.stock < 200 ? 'bg-rose-500' : product.stock < 300 ? 'bg-amber-500' : 'bg-emerald-500'}" 
                             style="width: ${Math.min(100, (product.stock / 500) * 100)}%"></div>
                    </div>
                </div>
                
                <div class="flex gap-2 mt-3">
                    <button class="flex-1 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors"
                            onclick="viewProductDetails(${product.id})">
                        View Details
                    </button>
                    <button class="flex-1 px-3 py-1.5 text-xs font-medium ${product.stock < 200 ? 'text-rose-600 bg-rose-50 hover:bg-rose-100' : 'text-slate-600 bg-slate-50 hover:bg-slate-100'} rounded-lg transition-colors"
                            onclick="reorderProduct(${product.id}, '${product.name}')">
                        ${product.stock < 200 ? 'Reorder Now' : 'Add to Reorder List'}
                    </button>
                </div>
            `;
            listContainer.appendChild(item);
        });
    }

    function viewProductDetails(productId) {
        const product = productsData.find(p => p.id === productId);
        if (!product) return;

        // You can replace this with a modal or redirect to product details page
        Swal.fire({
            icon: 'info',
            title: 'Product Details',
            text: `Opening details for ${product.name}`
        });
        
        // For now, just show an alert. In a real app, you might want to:
        // window.location.href = '/admin/products/' + productId;
    }

    function reorderProduct(productId, productName) {
        Swal.fire({
            icon: 'success',
            title: 'Reorder Requested',
            text: `Reorder requested for: ${productName}`,
            timer: 2000,
            showConfirmButton: false
        });
        
        // In a real app, you might want to:
        // 1. Open a reorder modal
        // 2. Send AJAX request to update reorder status
        // 3. Update UI accordingly
    }

    function resetProductFilters() {
        // Reset filter selects to default
        document.querySelectorAll('.date-input').forEach(input => {
            if (input.tagName === 'SELECT') {
                input.selectedIndex = 0;
            } else if (input.tagName === 'INPUT') {
                input.value = '';
            }
        });
        
        loadProductsList();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', loadProductsPage);
</script>
@endsection