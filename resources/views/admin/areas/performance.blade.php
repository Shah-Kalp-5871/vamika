@extends('layouts.admin')

@section('title', 'Area Performance - Admin')

@section('styles')
<style>
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

    .area-card {
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.3s ease;
    }

    .area-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .date-pill {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        background: white;
        border: 1px solid #E2E8F0;
        color: #64748B;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .date-pill:hover {
        background: #F8FAFC;
        color: #475569;
    }

    .date-pill.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    .date-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
    }

    .date-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .chart-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #F8FAFC;
        border-radius: 0.75rem;
        border: 2px dashed #E2E8F0;
    }

    .trend-up {
        background: #D1FAE5;
        color: #065F46;
    }

    .trend-neutral {
        background: #FEF3C7;
        color: #92400E;
    }

    .trend-down {
        background: #FEE2E2;
        color: #991B1B;
    }

    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100">
        <div class="px-6 py-4 space-y-3">
            <!-- Top Row: Back + Logo -->
            <div class="flex items-center gap-3">
                <!-- Back to Reports -->
                <a href="{{ route('admin.reports.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                    <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
                    Back to Reports
                </a>

                <!-- Logo -->
                <img src="{{ asset('assets/images/logo.png') }}" alt="Vamika Enterprise"
                    class="h-8 w-8 object-contain rounded-md border border-slate-200">
            </div>

            <!-- Title -->
            <div>
                <h1 class="text-lg font-semibold text-slate-900 tracking-tight">
                    Area Performance
                </h1>
                <p class="text-sm text-slate-500">
                    Performance metrics by delivery areas
                </p>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-6">
        <!-- Time Period Selector -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-wrap gap-3">
                <button class="date-pill active" onclick="setAreaPeriod('month')">
                    This Month
                </button>
                <button class="date-pill" onclick="setAreaPeriod('quarter')">
                    This Quarter
                </button>
                <button class="date-pill" onclick="setAreaPeriod('year')">
                    This Year
                </button>
                <button class="date-pill" onclick="setAreaPeriod('all')">
                    All Time
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <input type="date" class="date-input" id="areaStartDate" value="{{ date('Y-m-01') }}">
                <input type="date" class="date-input" id="areaEndDate" value="{{ date('Y-m-t') }}">
            </div>
            <button class="btn-primary w-full mt-4" onclick="loadAreaData()">
                Load Area Data
            </button>
        </div>

        <!-- Area Performance Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="activeAreasCount">8</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Areas</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="totalRevenue">₹15,78,420</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Revenue</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="totalOrders">1,245</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Orders</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="avgOrderValue">₹1,267</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Avg. per Order</p>
            </div>
        </div>

        <!-- Area Performance Cards -->
        <div id="areasContainer" class="space-y-4">
            <!-- Dynamic area cards will be loaded here -->
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="loading-spinner inline-block w-8 h-8 border-2 border-slate-200 border-t-slate-600 rounded-full animate-spin mb-4"></div>
                    <p class="text-sm text-slate-500">Loading area performance data...</p>
                </div>
            </div>
        </div>

        <!-- Area Comparison Chart -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Area Revenue Comparison</h3>
                <select class="text-xs border border-slate-200 rounded-lg px-3 py-1.5 bg-white" id="chartMetric">
                    <option value="revenue">By Revenue</option>
                    <option value="orders">By Orders</option>
                    <option value="growth">By Growth</option>
                </select>
            </div>
            <div class="chart-placeholder h-64">
                <iconify-icon icon="carbon:chart-bar" width="48"
                    style="color: #8B5CF6; margin-bottom: 1rem;"></iconify-icon>
                <p class="text-sm font-medium text-slate-600">Area Comparison Chart</p>
                <p class="text-xs text-slate-400 mt-1">Visual comparison of area performance</p>
            </div>
        </div>

        <!-- Growth Trends -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <h3 class="text-sm font-semibold text-slate-900 mb-4">Area Growth Trends</h3>
            <div class="space-y-3" id="growthTrends">
                <!-- Dynamic growth trends will be loaded here -->
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Sample data for area performance (in real app, this would come from API)
    const areaData = [
        {
            id: 1,
            name: 'Gandhi Nagar',
            revenue: 425430,
            orders: 342,
            growth: 15.2,
            shops: 12,
            salespersons: 4,
            topShop: 'Mohan Kirana Store',
            avgDeliveryTime: '28 mins',
            satisfaction: 4.8
        },
        {
            id: 2,
            name: 'Shahdara',
            revenue: 385670,
            orders: 298,
            growth: 8.7,
            shops: 10,
            salespersons: 3,
            topShop: 'Bansal Provision',
            avgDeliveryTime: '32 mins',
            satisfaction: 4.6
        },
        {
            id: 3,
            name: 'Preet Vihar',
            revenue: 356890,
            orders: 265,
            growth: 12.4,
            shops: 8,
            salespersons: 3,
            topShop: 'Sharma Super Mart',
            avgDeliveryTime: '30 mins',
            satisfaction: 4.7
        },
        {
            id: 4,
            name: 'Laxmi Nagar',
            revenue: 325430,
            orders: 240,
            growth: 5.3,
            shops: 7,
            salespersons: 2,
            topShop: 'Verma Departmental Store',
            avgDeliveryTime: '35 mins',
            satisfaction: 4.5
        },
        {
            id: 5,
            name: 'Krishna Nagar',
            revenue: 285670,
            orders: 210,
            growth: 18.9,
            shops: 6,
            salespersons: 2,
            topShop: 'Kumar Stores',
            avgDeliveryTime: '38 mins',
            satisfaction: 4.4
        },
        {
            id: 6,
            name: 'Geeta Colony',
            revenue: 245890,
            orders: 185,
            growth: 3.2,
            shops: 5,
            salespersons: 2,
            topShop: 'Colony Mart',
            avgDeliveryTime: '40 mins',
            satisfaction: 4.3
        }
    ];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadAreaPage();
        setupEventListeners();
    });

    function setupEventListeners() {
        document.getElementById('chartMetric').addEventListener('change', function(e) {
            updateChart(e.target.value);
        });
    }

    function loadAreaPage() {
        loadAreaCards();
        loadGrowthTrends();
        updateSummaryStats();
    }

    function updateSummaryStats() {
        const totalRevenue = areaData.reduce((sum, area) => sum + area.revenue, 0);
        const totalOrders = areaData.reduce((sum, area) => sum + area.orders, 0);
        const avgOrderValue = totalOrders > 0 ? Math.round(totalRevenue / totalOrders) : 0;
        const activeAreas = areaData.length;

        document.getElementById('activeAreasCount').textContent = activeAreas;
        document.getElementById('totalRevenue').textContent = '₹' + formatNumber(totalRevenue);
        document.getElementById('totalOrders').textContent = formatNumber(totalOrders);
        document.getElementById('avgOrderValue').textContent = '₹' + formatNumber(avgOrderValue);
    }

    function formatNumber(num) {
        if (num >= 10000000) {
            return (num / 10000000).toFixed(1) + 'Cr';
        } else if (num >= 100000) {
            return (num / 100000).toFixed(1) + 'L';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toLocaleString();
    }

    function loadAreaCards() {
        const container = document.getElementById('areasContainer');
        container.innerHTML = '';

        // Sort areas by revenue (highest first)
        const sortedAreas = [...areaData].sort((a, b) => b.revenue - a.revenue);

        sortedAreas.forEach((area, index) => {
            const avgOrderValue = Math.round(area.revenue / area.orders);
            const growthClass = area.growth >= 10 ? 'trend-up' : area.growth >= 0 ? 'trend-neutral' : 'trend-down';

            const card = document.createElement('div');
            card.className = 'area-card bg-white border border-slate-200 shadow-sm';
            card.innerHTML = `
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">${area.name}</h4>
                        <span class="text-xs px-2 py-1 rounded-full ${growthClass} inline-flex items-center mt-1">
                            <iconify-icon icon="${area.growth >= 0 ? 'lucide:trending-up' : 'lucide:trending-down'}" width="12" class="mr-1"></iconify-icon>
                            ${Math.abs(area.growth)}% ${area.growth >= 0 ? 'growth' : 'decline'}
                        </span>
                    </div>
                    <span class="text-xs font-semibold px-3 py-1 rounded-full bg-indigo-50 text-indigo-600">
                        Rank #${index + 1}
                    </span>
                </div>
                
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <div class="text-lg font-semibold text-slate-900">₹${formatNumber(area.revenue)}</div>
                        <div class="text-xs text-slate-500">Revenue</div>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <div class="text-lg font-semibold text-slate-900">${formatNumber(area.orders)}</div>
                        <div class="text-xs text-slate-500">Orders</div>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <div class="text-lg font-semibold text-slate-900">₹${formatNumber(avgOrderValue)}</div>
                        <div class="text-xs text-slate-500">Avg. Order</div>
                    </div>
                    <div class="p-2 bg-slate-50 rounded-lg">
                        <div class="text-lg font-semibold text-slate-900">${area.shops}</div>
                        <div class="text-xs text-slate-500">Shops</div>
                    </div>
                </div>
                
                <div class="text-xs text-slate-600 space-y-1">
                    <div class="flex justify-between">
                        <span>Top Shop:</span>
                        <span class="font-medium">${area.topShop}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Avg. Delivery:</span>
                        <span class="font-medium">${area.avgDeliveryTime}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Satisfaction:</span>
                        <span class="font-medium">${area.satisfaction}/5 ⭐</span>
                    </div>
                </div>
                
                <button class="w-full mt-4 px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors"
                        onclick="viewAreaDetails(${area.id}, '${area.name}')">
                    View Area Details
                </button>
            `;
            container.appendChild(card);
        });
    }

    function loadGrowthTrends() {
        const trendsContainer = document.getElementById('growthTrends');
        trendsContainer.innerHTML = '';

        // Sort by growth rate (highest first)
        const sortedByGrowth = [...areaData].sort((a, b) => b.growth - a.growth);

        sortedByGrowth.forEach(area => {
            const trendItem = document.createElement('div');
            trendItem.className = 'flex items-center justify-between p-3 bg-slate-50 rounded-lg';
            trendItem.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                    <span class="text-sm font-medium text-slate-900">${area.name}</span>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-sm ${area.growth >= 10 ? 'text-emerald-600' : area.growth >= 0 ? 'text-amber-600' : 'text-rose-600'}">
                        ${area.growth >= 0 ? '+' : ''}${area.growth}%
                    </span>
                    <div class="w-32 h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full ${area.growth >= 10 ? 'bg-emerald-500' : area.growth >= 0 ? 'bg-amber-500' : 'bg-rose-500'}" 
                             style="width: ${Math.min(100, Math.abs(area.growth) * 3)}%"></div>
                    </div>
                </div>
            `;
            trendsContainer.appendChild(trendItem);
        });
    }

    function viewAreaDetails(areaId, areaName) {
        showToast(`Loading analytics for ${areaName} area…`, 'info');
        
        // In a real application, you would redirect to area details page
        // window.location.href = `{{ route('admin.areas.performance') }}/${areaId}`;
        
        // For now, show a message
        setTimeout(() => {
            showToast(`Displaying detailed analytics for ${areaName}`, 'success');
        }, 1000);
    }

    function setAreaPeriod(period) {
        // Update active button
        document.querySelectorAll('.date-pill').forEach(btn => {
            btn.classList.remove('active');
        });
        event.currentTarget.classList.add('active');

        // Update date inputs based on period
        const today = new Date();
        const startDate = document.getElementById('areaStartDate');
        const endDate = document.getElementById('areaEndDate');
        
        let start = new Date();
        let end = new Date();
        
        switch(period) {
            case 'month':
                start.setDate(1);
                break;
            case 'quarter':
                const quarter = Math.floor(today.getMonth() / 3);
                start.setMonth(quarter * 3);
                start.setDate(1);
                break;
            case 'year':
                start.setMonth(0);
                start.setDate(1);
                break;
            case 'all':
                start.setFullYear(2024, 0, 1); // Start from Jan 1, 2024
                break;
        }
        
        startDate.value = start.toISOString().split('T')[0];
        endDate.value = end.toISOString().split('T')[0];
        
        // In real app, this would fetch data for the selected period
        console.log(`Loading area data for period: ${period} (${startDate.value} to ${endDate.value})`);
    }

    function loadAreaData() {
        const startDate = document.getElementById('areaStartDate').value;
        const endDate = document.getElementById('areaEndDate').value;

        showToast(`Loading area performance data from ${startDate} to ${endDate}`, 'info');
        
        // Show loading state
        const container = document.getElementById('areasContainer');
        container.innerHTML = `
            <div class="flex items-center justify-center py-12">
                <div class="text-center">
                    <div class="loading-spinner inline-block w-8 h-8 border-2 border-slate-200 border-t-slate-600 rounded-full animate-spin mb-4"></div>
                    <p class="text-sm text-slate-500">Fetching data for selected period...</p>
                </div>
            </div>
        `;
        
        // Simulate API call
        setTimeout(() => {
            showToast('Area performance data loaded successfully!', 'success');
            loadAreaPage();
        }, 1500);
    }

    function updateChart(metric) {
        console.log(`Updating chart with metric: ${metric}`);
        // In real app, this would update the chart visualization
        showToast(`Chart updated to show ${metric} comparison`, 'info');
    }

    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = `toast px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };
        
        const icons = {
            success: 'check-circle',
            error: 'x-circle',
            info: 'info'
        };
        
        toast.classList.add(...colors[type].split(' '));
        
        toast.innerHTML = `
            <iconify-icon icon="lucide:${icons[type]}" width="18"></iconify-icon>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection