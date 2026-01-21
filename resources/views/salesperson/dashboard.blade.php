@extends('layouts.salesperson')

@section('content')
<style>
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
        0%, 100% {
            opacity: 0.6;
        }
        50% {
            opacity: 1;
        }
    }
</style>

<!-- Intro Overlay -->
<div id="introOverlay" class="intro-overlay">
    <div class="intro-content">
        <div class="logo-container">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="width: 90vh; height: auto;">
        </div>
    </div>
</div>

<!-- Main Content (initially hidden) -->
<div id="mainContent"
    class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200"
    style="display: none;">

    <header class="p-6 border-b border-slate-100 bg-white z-10">
        <div class="flex justify-between items-start">
            <div>
                <h2 id="salespersonName" class="text-xl font-normal text-slate-500 tracking-tight">Loading...</h2>
            </div>
            <div class="flex flex-col items-end">
                <span
                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    <iconify-icon icon="lucide:map-pin" width="12" class="mr-1.5"></iconify-icon>
                    <span id="assignedArea">...</span>
                </span>
                <span class="text-xs text-slate-400 mt-2" id="currentDate"></span>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-8">
        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Today's Orders -->
            <div
                class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors">
                <div class="mb-3">
                    <iconify-icon icon="lucide:package" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="todayOrders">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Orders</p>
            </div>

            <!-- Pending -->
            <div
                class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors">
                <div class="mb-3">
                    <iconify-icon icon="lucide:clock" width="20" class="text-amber-500"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="pendingDeliveries">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Pending</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 gap-3">
            <button onclick="window.location.href='{{ route('salesperson.shops.select') }}'"
                class="flex items-center justify-center gap-2 p-3 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:plus-circle" width="16"
                    class="text-slate-400 group-hover:text-indigo-600 transition-colors"></iconify-icon>
                New Order
            </button>
        </div>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Recent Activity</h3>
                <a href="{{ route('salesperson.sales.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center">
                    View all <iconify-icon icon="lucide:chevron-right" width="12" class="ml-1"></iconify-icon>
                </a>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div id="todayOrdersList" class="divide-y divide-slate-100">
                    <div class="p-8 text-center">
                        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                            role="status" aria-label="loading"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Dashboard',
        'showBack' => false,
        'showBottomNav' => true,
        'headerRight' => 'profile'
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Dummy data (inlined as per reference theme)
    const dummyData = {
        salesperson: {
            name: "Rahul Sharma",
            assignedArea: "South Mumbai",
            totalSales: 45250,
            todayOrders: 12,
            pendingDeliveries: 5
        },
        shops: [
            { id: 1, name: "Kirana King", area: "South Mumbai" },
            { id: 2, name: "Fresh Mart", area: "South Mumbai" },
            { id: 3, name: "Daily Needs", area: "South Mumbai" },
            { id: 4, name: "City Store", area: "Bandra" }
        ],
        orders: [
            { id: 101, shop: "Kirana King", items: 15, amount: 2400, status: "Pending", time: "10:30 AM" },
            { id: 102, shop: "Fresh Mart", items: 8, amount: 1250, status: "Delivered", time: "11:15 AM" },
            { id: 103, shop: "Daily Needs", items: 24, amount: 5600, status: "Processing", time: "01:45 PM" },
            { id: 104, shop: "Sunrise Store", items: 5, amount: 800, status: "Pending", time: "02:30 PM" }
        ]
    };

    // Function to get status badge class
    function getStatusColor(status) {
        const s = status.toLowerCase();
        if (s === 'pending') return 'bg-orange-50 text-orange-600 border-orange-100';
        if (s === 'processing') return 'bg-blue-50 text-blue-600 border-blue-100';
        if (s === 'delivered') return 'bg-emerald-50 text-emerald-600 border-emerald-100';
        return 'bg-slate-50 text-slate-600 border-slate-100';
    }

    // Load dashboard data
    function loadDashboard() {
        // Set user info
        document.getElementById('salespersonName').textContent = dummyData.salesperson.name;
        document.getElementById('assignedArea').textContent = dummyData.salesperson.assignedArea;

        // Set stats
        document.getElementById('todayOrders').textContent = dummyData.salesperson.todayOrders;
        document.getElementById('pendingDeliveries').textContent = dummyData.salesperson.pendingDeliveries;

        // Load today's orders
        loadTodayOrders();
    }

    function loadTodayOrders() {
        const ordersList = document.getElementById('todayOrdersList');
        ordersList.innerHTML = '';

        if (dummyData.orders.length === 0) {
            ordersList.innerHTML = `
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <iconify-icon icon="lucide:clipboard-list" width="32" class="mb-2 opacity-50"></iconify-icon>
                    <p class="text-sm">No orders yet today</p>
                </div>
            `;
            return;
        }

        dummyData.orders.slice(0, 5).forEach(order => {
            const orderDiv = document.createElement('div');
            orderDiv.className = 'group p-4 hover:bg-slate-50 transition-colors cursor-pointer flex items-center justify-between';

            const shopInitials = order.shop.substring(0, 2).toUpperCase();
            const badgeClass = getStatusColor(order.status);

            orderDiv.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-semibold text-xs border border-slate-200">
                        ${shopInitials}
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-900">${order.shop}</h4>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs text-slate-500">${order.items} Items</span>
                            <span class="text-[10px] text-slate-300">•</span>
                            <span class="text-xs font-medium text-slate-700">₹${order.amount}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col items-end gap-1">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border ${badgeClass}">
                        ${order.status}
                    </span>
                    <span class="text-[10px] text-slate-400">${order.time}</span>
                </div>
            `;
            ordersList.appendChild(orderDiv);
        });
    }

    // Show intro overlay and set timeout to hide it after animation
    document.addEventListener('DOMContentLoaded', function () {
        const introOverlay = document.getElementById('introOverlay');
        const mainContent = document.getElementById('mainContent');

        // Set current date
        const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', dateOptions);

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
            }, 500); // Match this with CSS transition duration
        }, 500); // Total loading time (matches loading animation)
    });
</script>
@endsection