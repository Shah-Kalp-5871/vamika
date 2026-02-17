@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Admin Dashboard',
    'subtitle' => 'Overview & analytics',
    'role' => 'Super Admin',
    'showBottomNav' => true
];
@endphp

<style>
    /* Intro overlay styles - MATCHING SALESPERSON SIDE */
    .intro-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        z-index: 9999999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .intro-content {
        text-align: center;
        color: white;
        animation: fadeInUp 0.8s ease-out;
    }

    .logo-container {
        margin-bottom: 2rem;
        position: relative;
    }

    .logo-circle {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .logo-text {
        font-size: 2.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    .logo-subtext {
        font-size: 1rem;
        font-weight: 300;
        letter-spacing: 4px;
        opacity: 0.9;
    }

    .loading-bar {
        width: 200px;
        height: 3px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
        margin: 2rem auto 0;
    }

    .loading-progress {
        width: 0%;
        height: 100%;
        background: white;
        border-radius: 10px;
        animation: loading 2.5s ease-out forwards;
    }

    .loading-text {
        margin-top: 1rem;
        font-size: 0.875rem;
        opacity: 0.8;
        animation: pulse 1.5s infinite;
    }

    @keyframes loading {
        0% {
            width: 0%;
        }

        100% {
            width: 100%;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }
    }

    @keyframes fadeOut {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
        }
    }

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

    /* Custom animation for cards */
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
</style>

<!-- Intro Overlay - MATCHING SALESPERSON SIDE -->
<div id="introOverlay" class="intro-overlay">
    <div class="intro-content">
        <div class="logo-container">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="width: 90vh; height: auto;">
        </div>

        <!-- <div class="loading-bar">
                <div class="loading-progress"></div>
            </div>
            
            <p class="loading-text" style="color: #333;">Loading Admin Portal...</p> -->
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="main-content" style="display: none;">
    <main class="p-6 space-y-8">
        <!-- Primary Stats Card (Revenue) -->
        <div class="admin-card bg-slate-900 border-none relative overflow-hidden text-white shadow-xl animate-in">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <div class="p-3 bg-white/10 rounded-xl border border-white/20 backdrop-blur-sm">
                        <iconify-icon icon="lucide:trending-up" width="24" class="text-emerald-400"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-bold tracking-widest uppercase text-emerald-400 bg-emerald-400/10 px-3 py-1.5 rounded-lg border border-emerald-400/20">Monthly Growth</span>
                </div>
                <p class="text-sm text-slate-400 font-semibold uppercase tracking-wider">Total Revenue</p>
                <h3 id="monthlyRevenue" class="text-4xl font-bold tracking-tight mt-2 text-white font-outfit">₹0</h3>
            </div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-blue-500/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-2 gap-5">
            <!-- Total Shops -->
            <div class="admin-card admin-card-emerald animate-in" style="animation-delay: 0.1s;">
                <div class="mb-4 flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shadow-sm border border-emerald-100">
                        <iconify-icon icon="lucide:store" width="22"></iconify-icon>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tracking-tight font-outfit" id="totalShops">0</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-1">Total Shops</p>
            </div>

            <!-- Pending Orders -->
            <div class="admin-card admin-card-amber animate-in" style="animation-delay: 0.2s;">
                <div class="mb-4 flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm border border-amber-100">
                        <iconify-icon icon="lucide:package" width="22"></iconify-icon>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tracking-tight font-outfit" id="pendingOrders">0</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-1">Pending Orders</p>
            </div>

            <!-- Active Salespersons -->
            <div class="admin-card admin-card-blue animate-in" style="animation-delay: 0.3s;">
                <div class="mb-4 flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm border border-blue-100">
                        <iconify-icon icon="lucide:users" width="22"></iconify-icon>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tracking-tight font-outfit" id="activeSalespersons">0</p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-1">Active Salespersons</p>
            </div>

            <!-- Today's Revenue -->
            <div class="admin-card admin-card-rose animate-in" style="animation-delay: 0.4s;">
                <div class="mb-4 flex items-center justify-between">
                    <div class="h-10 w-10 rounded-xl bg-rose-50 flex items-center justify-center text-rose-600 shadow-sm border border-rose-100">
                        <iconify-icon icon="lucide:dollar-sign" width="22"></iconify-icon>
                    </div>
                </div>
                <p class="text-2xl font-bold text-slate-900 tracking-tight font-outfit">₹<span id="todayRevenue">0</span></p>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mt-1">Today's Revenue</p>
            </div>
        </div>

        <!-- Redesigned Quick Actions -->
        <section>
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400">Core Actions</h3>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <!-- Consolidation -->
                <button onclick="window.location.href='{{ route('admin.orders.consolidation') }}'"
                    class="group relative flex flex-col items-start p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-xl hover:border-emerald-200 transition-all duration-300 overflow-hidden text-left animate-in" style="animation-delay: 0.5s;">
                    <div class="mb-5 p-4 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        <iconify-icon icon="lucide:layers" width="24"></iconify-icon>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 mb-1">Consolidation</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-wider">Item Aggregation</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <iconify-icon icon="lucide:layers" width="96"></iconify-icon>
                    </div>
                </button>

                <!-- Offers -->
                <button onclick="window.location.href='{{ route('admin.offers.index') }}'"
                    class="group relative flex flex-col items-start p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-xl hover:border-amber-200 transition-all duration-300 overflow-hidden text-left animate-in" style="animation-delay: 0.6s;">
                    <div class="mb-5 p-4 bg-amber-50 rounded-xl text-amber-600 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        <iconify-icon icon="lucide:tag" width="24"></iconify-icon>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 mb-1">Offers</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-wider">Promotions</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <iconify-icon icon="lucide:tag" width="96"></iconify-icon>
                    </div>
                </button>

                <!-- Reports -->
                <button onclick="window.location.href='{{ route('admin.reports.index') }}'"
                    class="group relative flex flex-col items-start p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-xl hover:border-blue-200 transition-all duration-300 overflow-hidden text-left animate-in" style="animation-delay: 0.7s;">
                    <div class="mb-5 p-4 bg-blue-50 rounded-xl text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        <iconify-icon icon="lucide:bar-chart-3" width="24"></iconify-icon>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 mb-1">Reports</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-wider">Analytics</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <iconify-icon icon="lucide:bar-chart-3" width="96"></iconify-icon>
                    </div>
                </button>

                <!-- Settings -->
                <button onclick="window.location.href='{{ route('admin.settings.index') }}'"
                    class="group relative flex flex-col items-start p-6 rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-xl hover:border-slate-300 transition-all duration-300 overflow-hidden text-left animate-in" style="animation-delay: 0.8s;">
                    <div class="mb-5 p-4 bg-slate-50 rounded-xl text-slate-600 group-hover:bg-slate-600 group-hover:text-white transition-all duration-300 shadow-sm">
                        <iconify-icon icon="lucide:settings" width="24"></iconify-icon>
                    </div>
                    <div>
                        <span class="block text-sm font-bold text-slate-900 mb-1">Settings</span>
                        <span class="block text-[10px] text-slate-500 font-medium uppercase tracking-wider">Configuration</span>
                    </div>
                    <div class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:opacity-10 transition-opacity">
                        <iconify-icon icon="lucide:settings" width="96"></iconify-icon>
                    </div>
                </button>
            </div>
        </section>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Recent Activity</h3>
                <a href="{{ route('admin.orders.index') }}"
                    class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center">
                    View all <iconify-icon icon="lucide:chevron-right" width="12" class="ml-1"></iconify-icon>
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3">
                <!-- Activity items will be dynamically added here -->
                <div id="activityList" class="contents">
                    <!-- Loading spinner -->
                    <div class="col-span-2 p-8 text-center">
                        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                            role="status" aria-label="loading"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Dummy data for admin (if not in dummy-data.js)
    // Dynamic data from Controller
    const adminData = {
        adminStats: {
            monthlyRevenue: {{ $data['monthlyRevenue'] }},
            totalShops: {{ $data['totalShops'] }},
            pendingOrders: {{ $data['pendingOrders'] }},
            activeSalespersons: {{ $data['activeSalespersons'] }},
            todayRevenue: {{ $data['todayRevenue'] }}
        },
        recentActivities: @json($data['recentActivities'])
    };

    // Function to get icon based on activity type
    function getActivityIcon(type) {
        const icons = {
            'order': 'shopping-bag',
            'shop': 'store',
            'user': 'user-plus',
            'offer': 'tag',
            'report': 'bar-chart'
        };
        return icons[type] || 'activity';
    }

    // Function to get activity color
    function getActivityColor(type) {
        const colors = {
            'order': 'text-blue-600 bg-blue-50 border-blue-100',
            'shop': 'text-emerald-600 bg-emerald-50 border-emerald-100',
            'user': 'text-purple-600 bg-purple-50 border-purple-100',
            'offer': 'text-rose-600 bg-rose-50 border-rose-100',
            'report': 'text-indigo-600 bg-indigo-50 border-indigo-100'
        };
        return colors[type] || 'text-slate-600 bg-slate-50 border-slate-100';
    }

    // Load dashboard data
    function loadDashboard() {
        // Set current date
        const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', dateOptions);

        // Load stats
        const stats = adminData.adminStats;
        document.getElementById('monthlyRevenue').textContent = '₹' + stats.monthlyRevenue.toLocaleString();
        document.getElementById('totalShops').textContent = stats.totalShops;
        document.getElementById('pendingOrders').textContent = stats.pendingOrders;
        document.getElementById('activeSalespersons').textContent = stats.activeSalespersons;
        document.getElementById('todayRevenue').textContent = stats.todayRevenue.toLocaleString();

        // Load recent activities
        loadRecentActivities();
    }

    function loadRecentActivities() {
        const activityList = document.getElementById('activityList');
        activityList.innerHTML = '';

        if (adminData.recentActivities.length === 0) {
            activityList.innerHTML = `
            <div class="col-span-2 flex flex-col items-center justify-center py-12 text-slate-400">
                <iconify-icon icon="lucide:clipboard-list" width="32" class="mb-2 opacity-50"></iconify-icon>
                <p class="text-sm">No recent activities</p>
            </div>
        `;
            return;
        }

        adminData.recentActivities.forEach(activity => {
            const activityDiv = document.createElement('div');
            activityDiv.className = 'admin-card p-4 hover:border-emerald-200 transition-all cursor-pointer group animate-in';

            const iconClass = getActivityColor(activity.type);
            const iconName = getActivityIcon(activity.type);

            activityDiv.innerHTML = `
            <div class="flex items-start gap-4 mb-4">
                <div class="h-10 w-10 rounded-xl flex items-center justify-center ${iconClass.split(' ')[1]} border ${iconClass.split(' ')[2]} flex-shrink-0 shadow-sm">
                    <iconify-icon icon="lucide:${iconName}" width="20" class="${iconClass.split(' ')[0]}"></iconify-icon>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-bold text-slate-900 truncate">${activity.title}</h4>
                    <p class="text-[10px] font-medium text-slate-500 mt-1 line-clamp-2 leading-relaxed">${activity.description}</p>
                </div>
            </div>
            <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                <span class="text-[9px] font-bold uppercase tracking-wider px-2 py-1 rounded-lg ${iconClass.split(' ')[1]} ${iconClass.split(' ')[0]} border ${iconClass.split(' ')[2]}">
                    ${activity.type}
                </span>
                <span class="text-[10px] font-medium text-slate-400 flex items-center">
                    <iconify-icon icon="lucide:clock" width="10" class="mr-1"></iconify-icon>
                    ${activity.time}
                </span>
            </div>
        `;

            // Add click handler
            activityDiv.addEventListener('click', function () {
                // Navigate based on activity type
                switch (activity.type) {
                    case 'order':
                        window.location.href = "{{ route('admin.orders.index') }}";
                        break;
                    case 'shop':
                        window.location.href = "#"; // No shop page in routes yet
                        break;
                    case 'user':
                        window.location.href = "{{ route('admin.bits.index') }}";
                        break;
                    case 'offer':
                        window.location.href = "{{ route('admin.offers.index') }}";
                        break;
                    default:
                        window.location.href = "#";
                }
            });

            activityList.appendChild(activityDiv);
        });
    }

    // Show intro overlay and set timeout to hide it after animation
    document.addEventListener('DOMContentLoaded', function () {
        const introOverlay = document.getElementById('introOverlay');
        const mainContent = document.getElementById('mainContent');

        // After 2.5 seconds (matching loading animation), hide intro and show main content
        setTimeout(function () {
            introOverlay.style.opacity = '0';
            introOverlay.style.transform = 'scale(1.1)';

            // After fade out animation completes, remove overlay and show content
            setTimeout(function () {
                introOverlay.style.display = 'none';
                mainContent.style.display = 'block';

                // Enable scrolling again
                document.body.style.overflow = 'auto';

                // Load dashboard data
                loadDashboard();

                // Animate cards
                document.querySelectorAll('.animate-slide-up').forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.1}s`;
                });
            }, 500);
        }, 500);
    });
</script>
@endsection