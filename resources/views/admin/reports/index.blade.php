{{-- resources/views/admin/reports/index.blade.php --}}

@php
$pageConfig = [
    'title' => 'Reports & Analytics',
    'role' => 'Admin',
    'showBottomNav' => true
];
@endphp

@extends('layouts.admin')

@section('content')
<div class="main-content">
    <main class="p-6 space-y-8">

        <!-- Date Range Selector -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col lg:flex-row gap-4 w-full lg:w-auto">
                    <div class="flex flex-col lg:flex-row gap-3 w-full">
                        <input type="date" class="date-input w-full lg:w-40" id="startDate" value="2024-01-01">
                        <input type="date" class="date-input w-full lg:w-40" id="endDate" value="2024-01-31">
                    </div>
                    <button class="btn-primary px-6" onclick="loadReports()">
                        Apply Range
                    </button>
                </div>

                <!-- Quick Dates -->
                <div class="flex gap-2 overflow-x-auto py-2 w-full lg:w-auto">
                    <div class="date-pill active" onclick="setDateRange('today')">
                        Today
                    </div>
                    <div class="date-pill" onclick="setDateRange('week')">
                        This Week
                    </div>
                    <div class="date-pill" onclick="setDateRange('month')">
                        This Month
                    </div>
                    <div class="date-pill" onclick="setDateRange('quarter')">
                        This Quarter
                    </div>
                    <div class="date-pill" onclick="setDateRange('year')">
                        This Year
                    </div>
                </div>
            </div>
        </div>

        <!-- Primary Revenue Card -->
        <div
            class="relative overflow-hidden rounded-xl bg-gradient-to-br from-slate-900 to-black p-6 text-white shadow-lg animate-slide-up">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-white/10 rounded-lg border border-white/20">
                        <iconify-icon icon="lucide:trending-up" width="20" class="text-white"></iconify-icon>
                    </div>
                    <span
                        class="text-xs font-medium text-slate-300 bg-white/10 px-2 py-1 rounded border border-white/20"
                        id="selectedPeriod">This Month</span>
                </div>
                <p class="text-sm text-slate-300 font-medium">Total Revenue</p>
                <h3 class="text-3xl font-semibold tracking-tight mt-1 text-white" id="totalRevenue">₹1,25,430</h3>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs px-2 py-1 rounded-full trend-up inline-flex items-center">
                        <iconify-icon icon="lucide:trending-up" width="12" class="mr-1"></iconify-icon>
                        12.5% from last period
                    </span>
                </div>
            </div>
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
        </div>

        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Total Orders -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.1s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:shopping-bag" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalOrders">342</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Orders</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs px-2 py-1 rounded-full trend-up inline-flex items-center">
                        <iconify-icon icon="lucide:trending-up" width="10" class="mr-1"></iconify-icon>
                        8.2%
                    </span>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.2s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:credit-card" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="avgOrderValue">₹3,667</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Avg. Order Value</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs px-2 py-1 rounded-full trend-up inline-flex items-center">
                        <iconify-icon icon="lucide:trending-up" width="10" class="mr-1"></iconify-icon>
                        4.1%
                    </span>
                </div>
            </div>

            <!-- New Customers -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:users" width="20" class="text-amber-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="newCustomers">28</p>
                <p class="text-xs text-slate-500 font-medium mt-1">New Customers</p>
                <div class="flex items-center gap-2 mt-2">
                    <span class="text-xs px-2 py-1 rounded-full trend-down inline-flex items-center">
                        <iconify-icon icon="lucide:trending-down" width="10" class="mr-1"></iconify-icon>
                        3.7%
                    </span>
                </div>
            </div>

            <!-- Top Product -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.4s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:award" width="20" class="text-rose-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">120</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Top Product Sales</p>
                <div class="text-xs text-slate-600 mt-2 truncate" title="Aashirvaad Atta">Aashirvaad Atta</div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-900">Performance Charts</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Revenue Trend -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Revenue Trend</h4>
                        <select class="text-xs border border-slate-200 rounded-lg px-3 py-1.5 bg-white">
                            <option>Daily</option>
                            <option>Weekly</option>
                            <option>Monthly</option>
                        </select>
                    </div>
                    <div class="chart-placeholder">
                        <iconify-icon icon="carbon:chart-line" width="48"
                            style="color: #667eea; margin-bottom: 1rem;"></iconify-icon>
                        <p class="text-sm font-medium text-slate-600">Revenue Chart</p>
                        <p class="text-xs text-slate-400 mt-1">Daily revenue for selected period</p>
                    </div>
                </div>

                <!-- Order Distribution -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Order Status Distribution</h4>
                        <div class="text-xs text-slate-500">Current Month</div>
                    </div>
                    <div class="chart-placeholder">
                        <iconify-icon icon="carbon:chart-pie" width="48"
                            style="color: #10B981; margin-bottom: 1rem;"></iconify-icon>
                        <p class="text-sm font-medium text-slate-600">Order Status Pie Chart</p>
                        <p class="text-xs text-slate-400 mt-1">Distribution of order statuses</p>
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Top Products</h4>
                        <select class="text-xs border border-slate-200 rounded-lg px-3 py-1.5 bg-white">
                            <option>By Revenue</option>
                            <option>By Quantity</option>
                        </select>
                    </div>
                    <div class="chart-placeholder">
                        <iconify-icon icon="carbon:chart-bar" width="48"
                            style="color: #F59E0B; margin-bottom: 1rem;"></iconify-icon>
                        <p class="text-sm font-medium text-slate-600">Product Performance</p>
                        <p class="text-xs text-slate-400 mt-1">Top selling products analysis</p>
                    </div>
                </div>

                <!-- Area Performance -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Area Performance</h4>
                        <div class="text-xs text-slate-500">Revenue by area</div>
                    </div>
                    <div class="chart-placeholder">
                        <iconify-icon icon="carbon:map" width="48"
                            style="color: #8B5CF6; margin-bottom: 1rem;"></iconify-icon>
                        <p class="text-sm font-medium text-slate-600">Area Comparison</p>
                        <p class="text-xs text-slate-400 mt-1">Revenue by delivery area</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Lists -->
        <div class="space-y-4">
            <h3 class="text-sm font-semibold text-slate-900">Leaderboards</h3>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <!-- Top Salespersons -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Top Salespersons</h4>
                        <span class="text-xs text-slate-500 px-2 py-1 bg-slate-50 rounded-lg">5 ranked</span>
                    </div>
                    <div id="topSalespersons" class="space-y-3">
                        <!-- Dynamic content -->
                    </div>
                </div>

                <!-- Top Shops -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Top Shops</h4>
                        <span class="text-xs text-slate-500 px-2 py-1 bg-slate-50 rounded-lg">5 ranked</span>
                    </div>
                    <div id="topShops" class="space-y-3">
                        <!-- Dynamic content -->
                    </div>
                </div>

                <!-- Top Products -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-semibold text-slate-900">Top Products</h4>
                        <span class="text-xs text-slate-500 px-2 py-1 bg-slate-50 rounded-lg">5 ranked</span>
                    </div>
                    <div id="topProducts" class="space-y-3">
                        <!-- Dynamic content -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Actions -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <button class="btn-primary inline-flex items-center justify-center" onclick="exportReport()">
                    <iconify-icon icon="lucide:download" width="16" class="mr-2"></iconify-icon>
                    Export Full Report
                </button>
                <button class="btn-secondary inline-flex items-center justify-center" onclick="printReport()">
                    <iconify-icon icon="lucide:printer" width="16" class="mr-2"></iconify-icon>
                    Print Report
                </button>
            </div>
        </div>
    </main>
</div>
@endsection

@section('styles')
<style>
    /* Main content container similar to reference */
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

    /* Chart placeholders */
    .chart-placeholder {
        height: 250px;
        background: linear-gradient(135deg, #667eea0d 0%, #764ba20d 100%);
        border-radius: 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #64748B;
        border: 1px dashed #E2E8F0;
    }

    /* Rank badges */
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

    /* Trend indicators */
    .trend-up {
        color: #10B981;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .trend-down {
        color: #EF4444;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .trend-neutral {
        color: #6B7280;
        background: rgba(107, 114, 128, 0.1);
        border: 1px solid rgba(107, 114, 128, 0.2);
    }

    /* Custom button styles */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
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
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
        cursor: pointer;
    }

    .btn-secondary:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }

    /* Quick date pills */
    .date-pill {
        padding: 0.5rem 1rem;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #4B5563;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .date-pill:hover {
        border-color: #667eea;
        color: #667eea;
    }

    .date-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    /* Date inputs */
    .date-input {
        padding: 0.75rem;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
        background: white;
        color: #374151;
        font-size: 0.875rem;
        transition: all 0.3s ease;
    }

    .date-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Custom scrollbar */
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
</style>
@endsection

@section('scripts')
<script>
    // Sample data for reports
    const topSalespersonsData = [
        { name: 'Rajesh Kumar', orders: 45, revenue: 125430, target: 120000 },
        { name: 'Suresh Patel', orders: 38, revenue: 108920, target: 110000 },
        { name: 'Vikram Singh', orders: 32, revenue: 95670, target: 100000 },
        { name: 'Amit Sharma', orders: 28, revenue: 78450, target: 90000 },
        { name: 'Ravi Verma', orders: 25, revenue: 69800, target: 80000 }
    ];

    const topShopsData = [
        { name: 'Mohan Kirana Store', orders: 45, revenue: 125430, area: 'Gandhi Nagar' },
        { name: 'Gupta General Store', orders: 32, revenue: 108920, area: 'Gandhi Nagar' },
        { name: 'Bansal Provision', orders: 28, revenue: 95670, area: 'Shahdara' },
        { name: 'Sharma Super Mart', orders: 25, revenue: 78450, area: 'Preet Vihar' },
        { name: 'Verma Departmental Store', orders: 19, revenue: 69800, area: 'Laxmi Nagar' }
    ];

    const topProductsData = [
        { name: 'Aashirvaad Atta', sales: 120, revenue: 50400 },
        { name: 'Fortune Sunflower Oil', sales: 95, revenue: 19950 },
        { name: 'Maggi Noodles', sales: 85, revenue: 5950 },
        { name: 'Colgate Toothpaste', sales: 78, revenue: 6630 },
        { name: 'Parle-G Biscuits', sales: 72, revenue: 3600 }
    ];

    function loadReportsPage() {
        // Animate cards
        document.querySelectorAll('.animate-slide-up').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        loadTopLists();
        setDateRange('month');
    }

    function setDateRange(range) {
        const today = new Date();
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');

        // Update quick dates UI
        document.querySelectorAll('.date-pill').forEach(date => {
            date.classList.remove('active');
        });
        
        // Find and activate the clicked date pill
        const event = window.event || {};
        if (event.currentTarget) {
            event.currentTarget.classList.add('active');
        } else {
            // If event is not available (called programmatically), find the right pill
            document.querySelectorAll('.date-pill').forEach(pill => {
                if (pill.textContent.trim().toLowerCase().includes(range)) {
                    pill.classList.add('active');
                }
            });
        }

        let start, end;

        switch (range) {
            case 'today':
                start = today;
                end = today;
                document.getElementById('selectedPeriod').textContent = 'Today';
                break;
            case 'week':
                const weekStart = new Date(today);
                weekStart.setDate(weekStart.getDate() - weekStart.getDay());
                start = weekStart;
                end = today;
                document.getElementById('selectedPeriod').textContent = 'This Week';
                break;
            case 'month':
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = today;
                document.getElementById('selectedPeriod').textContent = 'This Month';
                break;
            case 'quarter':
                const quarter = Math.floor(today.getMonth() / 3);
                start = new Date(today.getFullYear(), quarter * 3, 1);
                end = today;
                document.getElementById('selectedPeriod').textContent = 'This Quarter';
                break;
            case 'year':
                start = new Date(today.getFullYear(), 0, 1);
                end = today;
                document.getElementById('selectedPeriod').textContent = 'This Year';
                break;
            case 'custom':
                document.getElementById('selectedPeriod').textContent = 'Custom Range';
                return; // Let user select custom dates
            default:
                // Default to month
                start = new Date(today.getFullYear(), today.getMonth(), 1);
                end = today;
                document.getElementById('selectedPeriod').textContent = 'This Month';
        }

        startDate.value = formatDate(start);
        endDate.value = formatDate(end);

        loadReports();
    }

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function loadReports() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // In real app, this would fetch data from API
        console.log(`Loading reports for ${startDate} to ${endDate}`);

        // Simulate data update with slight variations
        updateSummaryCards();
        loadTopLists();
    }

    function updateSummaryCards() {
        // Randomize data slightly for demo
        const baseRevenue = 125430;
        const randomFactor = 0.8 + Math.random() * 0.4; // 0.8 to 1.2

        document.getElementById('totalRevenue').textContent =
            '₹' + Math.round(baseRevenue * randomFactor).toLocaleString();
        document.getElementById('totalOrders').textContent =
            Math.round(342 * randomFactor);
        document.getElementById('avgOrderValue').textContent =
            '₹' + Math.round(3667 * randomFactor).toLocaleString();
        document.getElementById('newCustomers').textContent =
            Math.round(28 * randomFactor);
    }

    function loadTopLists() {
        // Load top salespersons
        const salespersonsList = document.getElementById('topSalespersons');
        salespersonsList.innerHTML = '';

        topSalespersonsData.forEach((sp, index) => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="rank-badge rank-${index + 1}">${index + 1}</div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">${sp.name}</h4>
                        <p class="text-xs text-slate-500">${sp.orders} orders</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-slate-900">₹${sp.revenue.toLocaleString()}</div>
                    <div class="text-xs ${sp.revenue >= sp.target ? 'text-emerald-600' : 'text-rose-600'}">
                        ${sp.revenue >= sp.target ? '✓ Target met' : '✗ Target missed'}
                    </div>
                </div>
            `;
            salespersonsList.appendChild(item);
        });

        // Load top shops
        const shopsList = document.getElementById('topShops');
        shopsList.innerHTML = '';

        topShopsData.forEach((shop, index) => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="rank-badge rank-${index + 1}">${index + 1}</div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">${shop.name}</h4>
                        <p class="text-xs text-slate-500">${shop.area}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-slate-900">₹${shop.revenue.toLocaleString()}</div>
                    <div class="text-xs text-slate-500">${shop.orders} orders</div>
                </div>
            `;
            shopsList.appendChild(item);
        });

        // Load top products
        const productsList = document.getElementById('topProducts');
        productsList.innerHTML = '';

        topProductsData.forEach((product, index) => {
            const item = document.createElement('div');
            item.className = 'flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="rank-badge rank-${index + 1}">${index + 1}</div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">${product.name}</h4>
                        <p class="text-xs text-slate-500">${product.sales} sold</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm font-semibold text-slate-900">₹${product.revenue.toLocaleString()}</div>
                </div>
            `;
            productsList.appendChild(item);
        });
    }

    function exportReport() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        // Show toast notification
        console.log(`Exporting report from ${startDate} to ${endDate}…`);
        
        // In a real app, you would make an API call to generate the report
        alert(`Report export started for ${startDate} to ${endDate}. Check your downloads.`);
    }

    function printReport() {
        window.print();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', loadReportsPage);
</script>
@endsection