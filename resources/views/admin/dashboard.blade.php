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
        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-slate-900 to-black p-6 text-white shadow-lg">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-white/10 rounded-lg border border-white/20">
                        <iconify-icon icon="lucide:trending-up" width="20" class="text-white"></iconify-icon>
                    </div>
                    <span class="text-xs font-medium text-slate-300 bg-white/10 px-2 py-1 rounded border border-white/20">This Month</span>
                </div>
                <p class="text-sm text-slate-300 font-medium">Total Revenue</p>
                <h3 id="monthlyRevenue" class="text-3xl font-semibold tracking-tight mt-1 text-white">₹0</h3>
            </div>
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-indigo-500 rounded-full blur-3xl opacity-20"></div>
        </div>

        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Total Shops -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.1s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:store" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalShops">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Shops</p>
            </div>

            <!-- Pending Orders -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.2s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:package" width="20" class="text-amber-500"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="pendingOrders">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Pending Orders</p>
            </div>

            <!-- Active Salespersons -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:users" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="activeSalespersons">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Salespersons</p>
            </div>

            <!-- Today's Revenue -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.4s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:dollar-sign" width="20" class="text-rose-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">₹<span id="todayRevenue">0</span></p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Revenue</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-3 gap-3">
            <!-- <button onclick="window.location.href='{{ route('admin.areas.index') }}'"
                    class="flex flex-col items-center justify-center gap-2 p-4 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                    <iconify-icon icon="lucide:map-pin" width="20"
                        class="text-slate-400 group-hover:text-indigo-600 transition-colors mb-1"></iconify-icon>
                    Areas
                </button> -->

            <button onclick="window.location.href='{{ route('admin.orders.consolidation') }}'"
                class="flex flex-col items-center justify-center gap-2 p-4 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:layers" width="20"
                    class="text-slate-400 group-hover:text-indigo-600 transition-colors mb-1"></iconify-icon>
                Consolidation
            </button>

            <button onclick="window.location.href='{{ route('admin.offers.index') }}'"
                class="flex flex-col items-center justify-center gap-2 p-4 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:tag" width="20"
                    class="text-slate-400 group-hover:text-indigo-600 transition-colors mb-1"></iconify-icon>
                Offers
            </button>
            <button onclick="window.location.href='{{ route('admin.reports.index') }}'"
                class="flex flex-col items-center justify-center gap-2 p-4 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:bar-chart" width="20"
                    class="text-slate-400 group-hover:text-indigo-600 transition-colors mb-1"></iconify-icon>
                Reports
            </button>
        </div>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Recent Activity</h3>
                <a href="#"
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
    const adminData = {
        adminStats: {
            monthlyRevenue: 185000,
            totalShops: 47,
            pendingOrders: 8,
            activeSalespersons: 12,
            todayRevenue: 7500
        },
        recentActivities: [
            { 
                type: 'order', 
                title: 'New Order Received', 
                description: 'Mohan Kirana Store placed order #ORD00127', 
                time: '10 minutes ago', 
                icon: 'shopping-bag' 
            },
            { 
                type: 'shop', 
                title: 'New Shop Registered', 
                description: 'Sunil Mart joined with referral code', 
                time: '2 hours ago', 
                icon: 'store' 
            },
            { 
                type: 'user', 
                title: 'New Salesperson Added', 
                description: 'Rajesh Kumar joined the team', 
                time: '1 day ago', 
                icon: 'user-plus' 
            },
            { 
                type: 'offer', 
                title: 'New Offer Created', 
                description: '20% discount on all beverages', 
                time: '2 days ago', 
                icon: 'tag' 
            }
        ]
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
            activityDiv.className = 'p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors cursor-pointer group animate-slide-up';

            const iconClass = getActivityColor(activity.type);
            const iconName = getActivityIcon(activity.type);

            activityDiv.innerHTML = `
            <div class="flex items-start gap-3 mb-3">
                <div class="h-10 w-10 rounded-full flex items-center justify-center ${iconClass.split(' ')[1]} border ${iconClass.split(' ')[2]} flex-shrink-0">
                    <iconify-icon icon="lucide:${iconName}" width="16" class="${iconClass.split(' ')[0]}"></iconify-icon>
                </div>
                <div class="flex-1 min-w-0">
                    <h4 class="text-sm font-semibold text-slate-900 truncate">${activity.title}</h4>
                    <p class="text-xs text-slate-500 mt-1 line-clamp-2">${activity.description}</p>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-[10px] font-medium px-2 py-1 rounded-full ${iconClass.split(' ')[1]} ${iconClass.split(' ')[0]} border ${iconClass.split(' ')[2]}">
                    ${activity.type.charAt(0).toUpperCase() + activity.type.slice(1)}
                </span>
                <span class="text-[10px] text-slate-400">${activity.time}</span>
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
                        window.location.href = "{{ route('admin.salespersons.index') }}";
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