@extends('layouts.admin')

@section('title', 'Shop Analysis')

@php
$pageConfig = [
    'showBottomNav' => true,
];
@endphp

@section('css')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
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

    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #f8fafc;
    }

    @media (min-width: 640px) {
        .main-content {
            max-width: 100%;
            margin: 1rem auto;
            min-height: calc(100vh - 2rem);
            background-color: white;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.3s ease-out forwards;
    }

    /* Cards */
    .stat-card {
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        padding: 1.25rem;
        transition: all 0.2s ease;
    }

    .stat-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .badge-success {
        background-color: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    /* Filter controls */
    .filter-select {
        padding: 0.5rem 2rem 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background-color: white;
        font-size: 0.875rem;
        color: #1e293b;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.5rem center;
        background-size: 1em;
    }

    .filter-select:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Table */
    .data-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .data-table th {
        background: #f8fafc;
        padding: 0.25rem 0.50rem;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    .data-table td {
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.875rem;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .data-table tr:hover {
        background: #f8fafc;
    }

    /* Date picker */
    .date-input {
        padding: 0.5rem 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.5rem;
        background-color: white;
        font-size: 0.875rem;
        color: #1e293b;
    }

    .date-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s ease;
        text-decoration: none;
        border: 1px solid transparent;
        cursor: pointer;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        border: 1px solid #d1d5db;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    /* Trend indicators */
    .trend-up {
        color: #10b981;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .trend-down {
        color: #ef4444;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Chart container */
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }

    /* Export dropdown */
    .export-dropdown {
        position: relative;
    }

    .export-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.5rem;
        background: white;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        min-width: 180px;
        z-index: 10;
        display: none;
    }

    .export-menu.show {
        display: block;
    }

    .export-option {
        padding: 0.75rem 1rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: #374151;
        text-decoration: none;
        transition: background 0.2s ease;
        cursor: pointer;
    }

    .export-option:hover {
        background: #f8fafc;
    }

    /* Toast notification */
    .toast {
        position: fixed;
        bottom: 100px;
        right: 20px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        padding: 1rem;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        z-index: 1000;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.3s ease;
    }

    .toast.show {
        transform: translateY(0);
        opacity: 1;
    }

    .toast-success {
        border-left: 4px solid #10b981;
    }

    .toast-info {
        border-left: 4px solid #3b82f6;
    }

    .toast-error {
        border-left: 4px solid #ef4444;
    }
</style>
@endsection

@section('content')
@php
    // Get parameters from request
    $shop_id = request()->get('shop_id') ?? 0;
    $filter_type = request()->get('filter_type') ?? 'monthly';
    $start_date = request()->get('start_date') ?? date('Y-m-01');
    $end_date = request()->get('end_date') ?? date('Y-m-t');

    // Dummy shop data
    $shop_data = [
        'shop_id' => $shop_id,
        'shop_name' => "SuperMart Electronics",
        'area' => "Downtown Business District",
        'salesperson' => "John Doe",
        'status' => "active",
        'contact_person' => "Rajesh Sharma",
        'phone' => "+91 98765 43210",
        'email' => "shop@supermart.com",
        'address' => "123 Main Street, Business District",
        'registration_date' => "2023-01-15"
    ];

    // Helper functions
    function generateDummyData($filter_type) {
        $data = [];
        
        if ($filter_type === 'yearly') {
            // Yearly data - last 5 years
            $years = ['2020', '2021', '2022', '2023', '2024'];
            foreach ($years as $year) {
                $orders = rand(400, 700);
                $revenue = rand(5000000, 10000000);
                $growth = rand(-15, 30);
                $data[] = [
                    'period' => $year,
                    'orders' => $orders,
                    'revenue' => $revenue,
                    'growth' => ($growth >= 0 ? '+' : '') . $growth . '%'
                ];
            }
        } elseif ($filter_type === 'custom') {
            // Custom date range - generate monthly data for range
            $months = 6; // Default 6 months for custom
            for ($i = 0; $i < $months; $i++) {
                $monthName = date('M', strtotime("-$i months"));
                $orders = rand(30, 60);
                $revenue = rand(400000, 900000);
                $growth = rand(-10, 25);
                $data[] = [
                    'period' => $monthName,
                    'orders' => $orders,
                    'revenue' => $revenue,
                    'growth' => ($growth >= 0 ? '+' : '') . $growth . '%'
                ];
            }
            $data = array_reverse($data);
        } else {
            // Monthly data - last 12 months
            $monthly_trends = [];
            for ($i = 11; $i >= 0; $i--) {
                $monthName = date('M', strtotime("-$i months"));
                $orders = rand(20, 60);
                $revenue = rand(300000, 900000);
                $growth = rand(-10, 25);
                $monthly_trends[] = [
                    'period' => $monthName,
                    'orders' => $orders,
                    'revenue' => $revenue,
                    'growth' => ($growth >= 0 ? '+' : '') . $growth . '%'
                ];
            }
            $data = $monthly_trends;
        }
        
        return $data;
    }

    function generatePerformanceStats($filter_type) {
        $baseOrders = $filter_type === 'yearly' ? 6000 : ($filter_type === 'custom' ? 180 : 156);
        $baseRevenue = $filter_type === 'yearly' ? 85000000 : ($filter_type === 'custom' ? 2700000 : 2845000);
        
        return [
            'total_orders' => $baseOrders + rand(-50, 50),
            'total_revenue' => $baseRevenue + rand(-100000, 100000),
            'avg_order_value' => 18237 + rand(-500, 500),
            'total_products' => 42,
            'order_frequency' => $filter_type === 'yearly' ? 500 : ($filter_type === 'custom' ? 30 : 13),
            'customer_rating' => 4.5
        ];
    }

    function generateTopProducts() {
        $products = [
            ['name' => 'Wireless Headphones', 'orders' => 45, 'revenue' => 675000, 'growth' => '+12%'],
            ['name' => 'Smartphone Case', 'orders' => 38, 'revenue' => 114000, 'growth' => '+8%'],
            ['name' => 'USB-C Cable', 'orders' => 32, 'revenue' => 96000, 'growth' => '+15%'],
            ['name' => 'Power Bank 10000mAh', 'orders' => 28, 'revenue' => 196000, 'growth' => '+5%'],
            ['name' => 'Bluetooth Speaker', 'orders' => 25, 'revenue' => 375000, 'growth' => '+22%']
        ];
        
        // Add some randomness
        foreach ($products as &$product) {
            $product['orders'] += rand(-5, 5);
            $product['revenue'] += rand(-10000, 10000);
        }
        
        // Sort by orders
        usort($products, function($a, $b) {
            return $b['orders'] - $a['orders'];
        });
        
        return $products;
    }

    function generateRecentOrders() {
        $statuses = ['delivered', 'processing', 'pending', 'cancelled'];
        $orders = [];
        
        for ($i = 0; $i < 5; $i++) {
            $daysAgo = rand(0, 30);
            $date = date('Y-m-d', strtotime("-$daysAgo days"));
            $orders[] = [
                'id' => 'ORD-' . (1000 + rand(1, 999)),
                'date' => $date,
                'amount' => rand(15000, 60000),
                'status' => $statuses[rand(0, 1)] // Mostly delivered or processing
            ];
        }
        
        // Sort by date descending
        usort($orders, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });
        
        return $orders;
    }

    // Generate data based on current filter
    $monthly_trends = generateDummyData($filter_type);
    $performance_stats = generatePerformanceStats($filter_type);
    $top_products = generateTopProducts();
    $recent_orders = generateRecentOrders();

    // Calculate growth percentage for current period
    $current_period = $monthly_trends[count($monthly_trends) - 1] ?? ['orders' => 50, 'revenue' => 900000];
    $prev_period = $monthly_trends[count($monthly_trends) - 2] ?? ['orders' => 48, 'revenue' => 865000];

    $order_growth = $prev_period['orders'] > 0 ? 
        (($current_period['orders'] - $prev_period['orders']) / $prev_period['orders']) * 100 : 0;

    $revenue_growth = $prev_period['revenue'] > 0 ? 
        (($current_period['revenue'] - $prev_period['revenue']) / $prev_period['revenue']) * 100 : 0;
@endphp

<div class="main-content">
    <main class="p-6 space-y-6">
        <!-- Shop Header with Export Options -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 animate-fade-in-up">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">{{ $shop_data['shop_name'] }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-sm text-slate-600">Shop ID: {{ $shop_data['shop_id'] }}</span>
                    <span class="text-sm text-slate-600">Area: {{ $shop_data['area'] }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="export-dropdown">
                    <button id="exportBtn" class="btn btn-success">
                        <iconify-icon icon="lucide:download" width="16"></iconify-icon>
                        Export Report
                    </button>
                    <div id="exportMenu" class="export-menu">
                        <div class="export-option" onclick="exportReport('pdf')">
                            <iconify-icon icon="lucide:file-text" width="16" class="text-red-500"></iconify-icon>
                            Download as PDF
                        </div>
                        <div class="export-option" onclick="exportReport('word')">
                            <iconify-icon icon="lucide:file-text" width="16" class="text-blue-500"></iconify-icon>
                            Download as Word
                        </div>
                        <div class="export-option" onclick="exportReport('excel')">
                            <iconify-icon icon="lucide:file-spreadsheet" width="16" class="text-green-500"></iconify-icon>
                            Download as Excel
                        </div>
                    </div>
                </div>
                <button onclick="window.print()" class="btn btn-secondary">
                    <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
                    Print
                </button>
            </div>
        </div>

        <!-- Date Filter Controls -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 animate-fade-in-up">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex-1">
                    <div class="text-sm font-medium text-slate-700 mb-2">Report Period</div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <select id="filterType" class="filter-select">
                            <option value="monthly" {{ $filter_type === 'monthly' ? 'selected' : '' }}>Monthly Report</option>
                            <option value="yearly" {{ $filter_type === 'yearly' ? 'selected' : '' }}>Yearly Report</option>
                            <option value="custom" {{ $filter_type === 'custom' ? 'selected' : '' }}>Date Wise</option>
                        </select>
                        
                        <div id="dateRangeInputs" class="flex-1 flex gap-3 {{ $filter_type === 'custom' ? '' : 'hidden' }}">
                            <div class="flex-1">
                                <label class="block text-xs text-slate-500 mb-1">From Date</label>
                                <input type="date" id="startDate" class="date-input w-full" value="{{ $start_date }}">
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs text-slate-500 mb-1">To Date</label>
                                <input type="date" id="endDate" class="date-input w-full" value="{{ $end_date }}">
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn btn-primary mt-4 lg:mt-0" onclick="applyFilters()">
                    <iconify-icon icon="lucide:filter" width="16"></iconify-icon>
                    Apply Filters
                </button>
            </div>
        </div>

        <!-- Performance Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Total Orders -->
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Orders</p>
                        <p class="text-2xl font-bold text-slate-900 mt-2">{{ number_format($performance_stats['total_orders']) }}</p>
                        <div class="flex items-center mt-2">
                            @if($order_growth >= 0)
                            <span class="trend-up">
                                <iconify-icon icon="lucide:trending-up" width="16"></iconify-icon>
                                {{ number_format(abs($order_growth), 1) }}%
                            </span>
                            @else
                            <span class="trend-down">
                                <iconify-icon icon="lucide:trending-down" width="16"></iconify-icon>
                                {{ number_format(abs($order_growth), 1) }}%
                            </span>
                            @endif
                            <span class="text-sm text-slate-500 ml-2">vs last period</span>
                        </div>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-full border border-blue-100">
                        <iconify-icon icon="lucide:shopping-bag" width="24" class="text-blue-600"></iconify-icon>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Total Revenue</p>
                        <p class="text-2xl font-bold text-slate-900 mt-2">₹{{ number_format($performance_stats['total_revenue']) }}</p>
                        <div class="flex items-center mt-2">
                            @if($revenue_growth >= 0)
                            <span class="trend-up">
                                <iconify-icon icon="lucide:trending-up" width="16"></iconify-icon>
                                {{ number_format(abs($revenue_growth), 1) }}%
                            </span>
                            @else
                            <span class="trend-down">
                                <iconify-icon icon="lucide:trending-down" width="16"></iconify-icon>
                                {{ number_format(abs($revenue_growth), 1) }}%
                            </span>
                            @endif
                            <span class="text-sm text-slate-500 ml-2">vs last period</span>
                        </div>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-full border border-emerald-100">
                        <iconify-icon icon="lucide:indian-rupee" width="24" class="text-emerald-600"></iconify-icon>
                    </div>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="stat-card animate-fade-in-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">Average Order Value</p>
                        <p class="text-2xl font-bold text-slate-900 mt-2">₹{{ number_format($performance_stats['avg_order_value']) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-sm text-slate-500">Per order average</span>
                        </div>
                    </div>
                    <div class="p-3 bg-purple-50 rounded-full border border-purple-100">
                        <iconify-icon icon="lucide:bar-chart" width="24" class="text-purple-600"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Order Trends Chart -->
            <div class="bg-white rounded-xl border border-slate-200 p-6 animate-fade-in-up">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">Order Trends</h3>
                        <p class="text-sm text-slate-500">Performance overview</p>
                    </div>
                    <select id="chartType" class="filter-select text-sm" onchange="updateChart()">
                        <option value="orders">Orders Volume</option>
                        <option value="revenue">Revenue</option>
                        <option value="both">Both</option>
                    </select>
                </div>
                <div class="chart-container">
                    <canvas id="trendsChart"></canvas>
                </div>
                <div class="mt-6">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Period</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                                <th>Growth</th>
                            </tr>
                        </thead>
                        <tbody id="trendTableBody">
                            @foreach(array_slice($monthly_trends, -6) as $trend)
                            <tr>
                                <td class="font-medium">{{ $trend['period'] }}</td>
                                <td>{{ $trend['orders'] }}</td>
                                <td class="font-semibold">₹{{ number_format($trend['revenue']) }}</td>
                                <td>
                                    <span class="{{ strpos($trend['growth'], '+') !== false ? 'trend-up' : 'trend-down' }}">
                                        {{ $trend['growth'] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Top Products & Recent Orders -->
            <div class="space-y-6">
                <!-- Top Products -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Top Performing Products</h3>
                            <p class="text-sm text-slate-500">Best selling items this period</p>
                        </div>
                        <button class="btn btn-secondary text-sm" onclick="refreshTopProducts()">
                            <iconify-icon icon="lucide:refresh-cw" width="14"></iconify-icon>
                            Refresh
                        </button>
                    </div>
                    <div class="space-y-4" id="topProductsList">
                        @foreach($top_products as $index => $product)
                        @php
                            $percentage = ($product['orders'] / $top_products[0]['orders']) * 100;
                        @endphp
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center font-semibold text-slate-700">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <p class="font-medium text-slate-900">{{ $product['name'] }}</p>
                                    <p class="text-sm text-slate-500 mt-0.5">{{ number_format($product['orders']) }} orders</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">₹{{ number_format($product['revenue']) }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs {{ strpos($product['growth'], '+') !== false ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $product['growth'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="bg-white rounded-xl border border-slate-200 p-6 animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Recent Orders</h3>
                            <p class="text-sm text-slate-500">Latest transactions</p>
                        </div>
                        <button class="btn btn-secondary text-sm" onclick="refreshRecentOrders()">
                            <iconify-icon icon="lucide:refresh-cw" width="14"></iconify-icon>
                            Refresh
                        </button>
                    </div>
                    <div class="space-y-3" id="recentOrdersList">
                        @foreach($recent_orders as $order)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-lg transition-colors">
                            <div>
                                <p class="font-medium text-slate-900">{{ $order['id'] }}</p>
                                <p class="text-sm text-slate-500 mt-0.5">{{ date('d M Y', strtotime($order['date'])) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">₹{{ number_format($order['amount']) }}</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    {{ $order['status'] === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 
                                           ($order['status'] === 'processing' ? 'bg-amber-50 text-amber-700 border border-amber-100' : 
                                           'bg-blue-50 text-blue-700 border border-blue-100') }}">
                                    {{ ucfirst($order['status']) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Information -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 animate-fade-in-up">
            <h3 class="text-lg font-semibold text-slate-900 mb-6">Shop Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:user" width="16" class="text-slate-400"></iconify-icon>
                        <span class="text-sm text-slate-600">Contact Person:</span>
                        <span class="text-sm font-medium text-slate-900">{{ $shop_data['contact_person'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:phone" width="16" class="text-slate-400"></iconify-icon>
                        <span class="text-sm text-slate-600">Phone:</span>
                        <span class="text-sm font-medium text-slate-900">{{ $shop_data['phone'] }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:mail" width="16" class="text-slate-400"></iconify-icon>
                        <span class="text-sm text-slate-600">Email:</span>
                        <span class="text-sm font-medium text-slate-900">{{ $shop_data['email'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:map-pin" width="16" class="text-slate-400"></iconify-icon>
                        <span class="text-sm text-slate-600">Area:</span>
                        <span class="text-sm font-medium text-slate-900">{{ $shop_data['area'] }}</span>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:calendar" width="16" class="text-slate-400"></iconify-icon>
                        <span class="text-sm text-slate-600">Registered:</span>
                        <span class="text-sm font-medium text-slate-900">{{ date('d M Y', strtotime($shop_data['registration_date'])) }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:user-check" width="16" class="text-slate-400"></iconify-icon>
                        <span class="text-sm text-slate-600">Salesperson:</span>
                        <span class="text-sm font-medium text-slate-900">{{ $shop_data['salesperson'] }}</span>
                    </div>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-100">
                <p class="text-sm text-slate-600">
                    <iconify-icon icon="lucide:map-pin" width="14" class="mr-2 text-slate-400"></iconify-icon>
                    {{ $shop_data['address'] }}
                </p>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
    // Chart instance
    let trendsChart = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize chart
        initializeChart();
        
        // Export dropdown
        const exportBtn = document.getElementById('exportBtn');
        const exportMenu = document.getElementById('exportMenu');

        exportBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exportMenu.classList.toggle('show');
        });

        document.addEventListener('click', function() {
            exportMenu.classList.remove('show');
        });

        exportMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Filter type change
        document.getElementById('filterType').addEventListener('change', function() {
            const filterType = this.value;
            const dateRangeInputs = document.getElementById('dateRangeInputs');
            
            if (filterType === 'custom') {
                dateRangeInputs.classList.remove('hidden');
            } else {
                dateRangeInputs.classList.add('hidden');
            }
        });

        // Animate cards
        document.querySelectorAll('.animate-fade-in-up').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.05}s`;
        });
    });

    function initializeChart() {
        const ctx = document.getElementById('trendsChart').getContext('2d');
        const data = @json($monthly_trends);
        
        const labels = data.map(item => item.period);
        const ordersData = data.map(item => item.orders);
        const revenueData = data.map(item => item.revenue / 10000); // Scale down for chart
        
        trendsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Orders',
                    data: ordersData,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y'
                }, {
                    label: 'Revenue (₹10K)',
                    data: revenueData,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label.includes('Revenue')) {
                                    return label + ': ₹' + (context.parsed.y * 10000).toLocaleString();
                                }
                                return label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Orders'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Revenue (₹10K)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });
    }

    function updateChart() {
        const chartType = document.getElementById('chartType').value;
        
        if (trendsChart) {
            trendsChart.data.datasets[0].hidden = (chartType === 'revenue');
            trendsChart.data.datasets[1].hidden = (chartType === 'orders');
            trendsChart.update();
        }
    }

    function applyFilters() {
        const filterType = document.getElementById('filterType').value;
        let url = `{{ route('admin.shops.analysis') }}?shop_id={{ $shop_id }}&filter_type=${filterType}`;
        
        if (filterType === 'custom') {
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            if (startDate && endDate) {
                url += `&start_date=${startDate}&end_date=${endDate}`;
            }
        }
        
        showToast({
            message: 'Applying filters...',
            subtext: 'Updating data for selected period',
            type: 'info'
        });
        
        // Simulate loading
        setTimeout(() => {
            window.location.href = url;
        }, 500);
    }

    function refreshTopProducts() {
        showToast({
            message: 'Refreshing top products...',
            type: 'info'
        });
        
        // In a real app, this would fetch new data from server
        setTimeout(() => {
            showToast({
                message: 'Top products refreshed!',
                type: 'success'
            });
        }, 800);
    }

    function refreshRecentOrders() {
        showToast({
            message: 'Refreshing recent orders...',
            type: 'info'
        });
        
        // In a real app, this would fetch new data from server
        setTimeout(() => {
            showToast({
                message: 'Recent orders refreshed!',
                type: 'success'
            });
        }, 800);
    }

    function exportReport(format) {
        const shopName = "{{ addslashes($shop_data['shop_name']) }}";
        const period = "{{ $filter_type === 'monthly' ? date('F Y') : ($filter_type === 'yearly' ? date('Y') : date('d M Y', strtotime($start_date)) . ' to ' . date('d M Y', strtotime($end_date))) }}";
        
        let message = '';
        
        switch(format) {
            case 'pdf':
                message = `Generating PDF report for ${shopName}...`;
                generatePDFReport();
                break;
            case 'word':
                message = `Generating Word report for ${shopName}...`;
                generateWordReport();
                break;
            case 'excel':
                message = `Generating Excel report for ${shopName}...`;
                generateExcelReport();
                break;
        }
        
        showToast({
            message: message,
            subtext: `Period: ${period}`,
            type: 'info'
        });
        
        // Hide export menu
        document.getElementById('exportMenu').classList.remove('show');
    }

    function generatePDFReport() {
        // Using jsPDF to generate PDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Add content to PDF
        doc.setFontSize(20);
        doc.text('Shop Analysis Report', 20, 20);
        
        doc.setFontSize(12);
        doc.text(`Shop: {{ $shop_data['shop_name'] }}`, 20, 35);
        doc.text(`Period: {{ $filter_type === 'monthly' ? date('F Y') : ($filter_type === 'yearly' ? date('Y') : date('d M Y', strtotime($start_date)) . ' to ' . date('d M Y', strtotime($end_date))) }}`, 20, 45);
        
        // Add table with performance stats
        doc.autoTable({
            startY: 55,
            head: [['Metric', 'Value']],
            body: [
                ['Total Orders', '{{ number_format($performance_stats['total_orders']) }}'],
                ['Total Revenue', '₹{{ number_format($performance_stats['total_revenue']) }}'],
                ['Avg Order Value', '₹{{ number_format($performance_stats['avg_order_value']) }}'],
                ['Customer Rating', '{{ $performance_stats['customer_rating'] }}/5']
            ]
        });
        
        // Save the PDF
        doc.save('Shop_Report_{{ $shop_data['shop_id'] }}_{{ date('Ymd') }}.pdf');
        
        setTimeout(() => {
            showToast({
                message: 'PDF report downloaded successfully!',
                type: 'success'
            });
        }, 1000);
    }

    function generateWordReport() {
        // Create a simple text file as Word document
        const content = `
SHOP ANALYSIS REPORT
====================

Shop Information:
-----------------
Shop Name: {{ $shop_data['shop_name'] }}
Shop ID: {{ $shop_data['shop_id'] }}
Contact Person: {{ $shop_data['contact_person'] }}
Phone: {{ $shop_data['phone'] }}
Email: {{ $shop_data['email'] }}
Address: {{ $shop_data['address'] }}
Registration Date: {{ date('d M Y', strtotime($shop_data['registration_date'])) }}

Performance Summary:
-------------------
Period: {{ $filter_type === 'monthly' ? date('F Y') : ($filter_type === 'yearly' ? date('Y') : date('d M Y', strtotime($start_date)) . ' to ' . date('d M Y', strtotime($end_date))) }}
Total Orders: {{ number_format($performance_stats['total_orders']) }}
Total Revenue: ₹{{ number_format($performance_stats['total_revenue']) }}
Average Order Value: ₹{{ number_format($performance_stats['avg_order_value']) }}
Order Frequency: {{ $performance_stats['order_frequency'] }} per month
Customer Rating: {{ $performance_stats['customer_rating'] }}/5

Top Products:
------------
@foreach($top_products as $product)
- {{ $product['name'] }}: {{ $product['orders'] }} orders (₹{{ number_format($product['revenue']) }}) {{ $product['growth'] }}

@endforeach

Report Generated: {{ date('d M Y H:i:s') }}
        `;
        
        const blob = new Blob([content], { type: 'application/msword' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Shop_Report_{{ $shop_data['shop_id'] }}_{{ date('Ymd') }}.doc';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        setTimeout(() => {
            showToast({
                message: 'Word report downloaded successfully!',
                type: 'success'
            });
        }, 1000);
    }

    function generateExcelReport() {
        // Create CSV content
        const csvContent = `
Shop Analysis Report - {{ $shop_data['shop_name'] }}
Generated: {{ date('d M Y H:i:s') }}

Performance Metrics,Value
Total Orders,{{ $performance_stats['total_orders'] }}
Total Revenue,{{ $performance_stats['total_revenue'] }}
Average Order Value,{{ $performance_stats['avg_order_value'] }}
Order Frequency,{{ $performance_stats['order_frequency'] }}
Customer Rating,{{ $performance_stats['customer_rating'] }}

Top Products,Orders,Revenue,Growth
@foreach($top_products as $product)
{{ $product['name'] }},{{ $product['orders'] }},{{ $product['revenue'] }},{{ $product['growth'] }}

@endforeach

Period,Orders,Revenue,Growth
@foreach(array_slice($monthly_trends, -6) as $trend)
{{ $trend['period'] }},{{ $trend['orders'] }},{{ $trend['revenue'] }},{{ $trend['growth'] }}

@endforeach
        `;
        
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Shop_Report_{{ $shop_data['shop_id'] }}_{{ date('Ymd') }}.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        URL.revokeObjectURL(url);
        
        setTimeout(() => {
            showToast({
                message: 'Excel report downloaded successfully!',
                type: 'success'
            });
        }, 1000);
    }

    function showToast({ message, subtext = '', type = 'info' }) {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        const toastSubtext = document.getElementById('toastSubtext');
        
        // Set message and type
        toastMessage.textContent = message;
        toastSubtext.textContent = subtext;
        
        // Set type-specific styling
        toast.className = 'toast';
        toast.classList.add(`toast-${type}`);
        
        // Show toast
        toast.classList.add('show');
        
        // Auto hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
</script>
@endsection