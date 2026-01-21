{{-- resources/views/admin/reports/visit.blade.php --}}

@php
$pageConfig = [
    'title' => 'Shops Visits',
    'role' => 'Admin',
    'showBottomNav' => true
];
@endphp

@extends('layouts.admin')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Statistics Overview Grid -->
    <div class="p-4">
        <div class="grid grid-cols-2 gap-3 mb-6">
            <!-- Not Visited -->
            <div class="stat-card bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Not Visited</p>
                        <h3 class="stat-number text-gray-900">108</h3>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill bg-red-500" style="width: 69%"></div>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                        <iconify-icon icon="ph:x-circle" width="20" class="text-red-600"></iconify-icon>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Out of 156 total Shops</p>
            </div>

            <!-- Visited -->
            <div class="stat-card bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Visited</p>
                        <h3 class="stat-number text-gray-900">48</h3>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill bg-green-500" style="width: 31%"></div>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                        <iconify-icon icon="ph:check-circle" width="20" class="text-green-600"></iconify-icon>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">31% of all Shops</p>
            </div>

            <!-- With Order -->
            <div class="stat-card bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">With Order</p>
                        <h3 class="stat-number text-gray-900">32</h3>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill bg-blue-500" style="width: 67%"></div>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                        <iconify-icon icon="ph:shopping-cart" width="20" class="text-blue-600"></iconify-icon>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">67% of visited</p>
            </div>

            <!-- No Order -->
            <div class="stat-card bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">No Order</p>
                        <h3 class="stat-number text-gray-900">16</h3>
                        <div class="progress-bar mt-2">
                            <div class="progress-fill bg-yellow-500" style="width: 33%"></div>
                        </div>
                    </div>
                    <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                        <iconify-icon icon="ph:warning-circle" width="20" class="text-yellow-600"></iconify-icon>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">33% of visited</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="p-4 bg-white border-b border-gray-200">
        <div class="relative">
            <iconify-icon icon="ph:magnifying-glass" width="18"
                class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></iconify-icon>
            <input type="text" id="searchInput" placeholder="Search salespersons..."
                class="w-full pl-10 pr-4 py-3 bg-gray-100 rounded-xl text-sm border-0 focus:ring-2 focus:ring-blue-500 focus:bg-white transition-all outline-none">
        </div>
    </div>

    <!-- Salespersons List -->
    <main class="p-4 pb-24">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Sales Team</h3>
            <span class="text-sm text-gray-500">
                Today: {{ date('d M Y') }}
            </span>
        </div>

        <div id="salespersonsList" class="space-y-3"></div>
    </main>
</div>

<!-- Toast Notification -->
<div id="toast"
    class="hidden fixed top-4 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white px-4 py-3 rounded-lg text-sm shadow-lg z-50">
</div>
@endsection

@section('styles')
<style>
    /* Animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slideUp 0.3s ease-out;
    }

    /* Status colors */
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-active {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-inactive {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Card hover effects */
    .stat-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Salesperson card clickable */
    .salesperson-card {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .salesperson-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    }

    /* Stats grid styles */
    .stat-number {
        font-size: 24px;
        font-weight: 700;
        line-height: 1.2;
    }

    .stat-label {
        font-size: 12px;
        font-weight: 500;
        opacity: 0.8;
    }

    /* Progress bars */
    .progress-bar {
        height: 6px;
        border-radius: 3px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 3px;
        transition: width 0.3s ease;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 4px;
        height: 3px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }
</style>
@endsection

@section('scripts')
<script>
    // Get the base URL from Laravel
    const salespersonDetailsUrl = '{{ route("admin.salespersons.details", ":id") }}';

    // Enhanced Dummy Data
    const dummyData = {
        overallStats: {
            totalShopss: 156,
            visitedToday: 48,
            notVisited: 108,
            withOrder: 32,
            noOrder: 16
        },
        salespersons: [
            {
                id: 1,
                name: "Rajesh Kumar",
                phone: "+91 98765 43210",
                email: "rajesh@vamika.com",
                area: "Gandhi Nagar",
                status: "active",
                assignedOutlets: 25,
                stats: {
                    visited: 8,
                    notVisited: 17,
                    withOrder: 6,
                    noOrder: 2
                },
                totalOrders: 45,
                lastVisit: "Today, 10:30 AM",
                visitsToday: [
                    { outlet: "SuperMart Central", time: "10:30 AM", orderAmount: 2450 },
                    { outlet: "Fresh Grocers", time: "11:45 AM", orderAmount: 0 },
                    { outlet: "Daily Needs", time: "12:30 PM", orderAmount: 1890 }
                ]
            },
            {
                id: 2,
                name: "Priya Sharma",
                phone: "+91 87654 32109",
                email: "priya@vamika.com",
                area: "Market Lane",
                status: "active",
                assignedOutlets: 20,
                stats: {
                    visited: 6,
                    notVisited: 14,
                    withOrder: 4,
                    noOrder: 2
                },
                totalOrders: 38,
                lastVisit: "Today, 11:15 AM",
                visitsToday: [
                    { outlet: "City Bazaar", time: "11:15 AM", orderAmount: 0 },
                    { outlet: "Quick Mart", time: "01:00 PM", orderAmount: 1560 }
                ]
            },
            {
                id: 3,
                name: "Amit Patel",
                phone: "+91 76543 21098",
                email: "amit@vamika.com",
                area: "Residential Area",
                status: "active",
                assignedOutlets: 22,
                stats: {
                    visited: 7,
                    notVisited: 15,
                    withOrder: 5,
                    noOrder: 2
                },
                totalOrders: 42,
                lastVisit: "Today, 09:45 AM",
                visitsToday: [
                    { outlet: "Fresh Grocers", time: "09:45 AM", orderAmount: 1890 },
                    { outlet: "SuperMart", time: "11:00 AM", orderAmount: 2340 }
                ]
            },
            {
                id: 4,
                name: "Sneha Reddy",
                phone: "+91 65432 10987",
                email: "sneha@vamika.com",
                area: "Highway Road",
                status: "inactive",
                assignedOutlets: 18,
                stats: {
                    visited: 0,
                    notVisited: 18,
                    withOrder: 0,
                    noOrder: 0
                },
                totalOrders: 35,
                lastVisit: "Yesterday, 03:45 PM",
                visitsToday: []
            },
            {
                id: 5,
                name: "Vikram Singh",
                phone: "+91 54321 09876",
                email: "vikram@vamika.com",
                area: "School Road",
                status: "active",
                assignedOutlets: 15,
                stats: {
                    visited: 5,
                    notVisited: 10,
                    withOrder: 3,
                    noOrder: 2
                },
                totalOrders: 28,
                lastVisit: "Today, 01:00 PM",
                visitsToday: [
                    { outlet: "Family Store", time: "01:00 PM", orderAmount: 0 },
                    { outlet: "Kids Corner", time: "02:30 PM", orderAmount: 890 }
                ]
            },
            {
                id: 6,
                name: "Anjali Mehta",
                phone: "+91 43210 98765",
                email: "anjali@vamika.com",
                area: "Central Mall",
                status: "active",
                assignedOutlets: 30,
                stats: {
                    visited: 12,
                    notVisited: 18,
                    withOrder: 9,
                    noOrder: 3
                },
                totalOrders: 52,
                lastVisit: "Today, 02:45 PM",
                visitsToday: [
                    { outlet: "Mall Store", time: "09:30 AM", orderAmount: 3450 },
                    { outlet: "Food Court", time: "11:00 AM", orderAmount: 1250 }
                ]
            }
        ]
    };

    // State Management
    const state = {
        searchQuery: '',
        isLoading: false
    };

    // DOM Elements
    const elements = {
        searchInput: document.getElementById('searchInput'),
        toast: document.getElementById('toast'),
        salespersonsList: document.getElementById('salespersonsList')
    };

    // Initialize App
    document.addEventListener('DOMContentLoaded', () => {
        initializeApp();
        setupEventListeners();
    });

    function initializeApp() {
        renderSalespersons();
        updateOverallStats();
    }

    function setupEventListeners() {
        // Search input
        elements.searchInput.addEventListener('input', (e) => {
            state.searchQuery = e.target.value.toLowerCase();
            renderSalespersons();
        });
    }

    function updateOverallStats() {
        // Update the overall stats cards with live data
        const overallStats = dummyData.overallStats;

        // Calculate percentages for progress bars
        const visitedPercent = (overallStats.visitedToday / overallStats.totalOutlets * 100).toFixed(1);
        const withOrderPercent = (overallStats.withOrder / overallStats.visitedToday * 100).toFixed(1);
        const noOrderPercent = (overallStats.noOrder / overallStats.visitedToday * 100).toFixed(1);

        // In a real app, you would update the DOM elements here
    }

    function renderSalespersons() {
        let filteredSalespersons = dummyData.salespersons.filter(sp => {
            if (state.searchQuery && !(
                sp.name.toLowerCase().includes(state.searchQuery) ||
                sp.area.toLowerCase().includes(state.searchQuery) ||
                sp.email.toLowerCase().includes(state.searchQuery)
            )) return false;
            return true;
        });

        elements.salespersonsList.innerHTML = '';

        if (filteredSalespersons.length === 0) {
            elements.salespersonsList.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <iconify-icon icon="ph:users" width="48" class="text-gray-300 mb-3"></iconify-icon>
                    <p>No salespersons found</p>
                </div>
            `;
            return;
        }

        filteredSalespersons.forEach((sp, index) => {
            const spCard = document.createElement('div');
            spCard.className = 'salesperson-card bg-white rounded-xl shadow-sm p-4 animate-slide-up';
            spCard.style.animationDelay = `${index * 0.05}s`;

            // Create a clickable link wrapper
            const linkWrapper = document.createElement('a');
            linkWrapper.href = salespersonDetailsUrl.replace(':id', sp.id);
            linkWrapper.className = 'block no-underline text-inherit';

            const statusClass = sp.status === 'active' ? 'status-active' : 'status-inactive';
            const statusText = sp.status === 'active' ? 'Active' : 'Inactive';

            // Calculate percentages
            const visitedPercent = sp.stats.visited > 0 ? (sp.stats.visited / sp.assignedOutlets * 100).toFixed(0) : 0;
            const withOrderPercent = sp.stats.visited > 0 ? (sp.stats.withOrder / sp.stats.visited * 100).toFixed(0) : 0;

            linkWrapper.innerHTML = `
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-bold text-gray-900">${sp.name}</h4>
                            <span class="status-badge ${statusClass}">${statusText}</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">${sp.area} • ${sp.assignedOutlets} Shops</p>
                        <p class="text-xs text-gray-500 flex items-center gap-1">
                            <iconify-icon icon="ph:clock" width="12"></iconify-icon>
                            Last visit: ${sp.lastVisit}
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-lg font-bold text-blue-700">${sp.totalOrders}</span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">MTD Orders</p>
                    </div>
                </div>
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-4 gap-2 mb-4">
                    <!-- Not Visited -->
                    <div class="text-center p-2 bg-gray-50 rounded-lg">
                        <p class="text-lg font-bold text-gray-700">${sp.stats.notVisited}</p>
                        <p class="text-xs text-gray-600">Not Visited</p>
                    </div>
                    
                    <!-- Visited -->
                    <div class="text-center p-2 bg-green-50 rounded-lg">
                        <p class="text-lg font-bold text-green-700">${sp.stats.visited}</p>
                        <p class="text-xs text-green-600">Visited</p>
                    </div>
                    
                    <!-- With Order -->
                    <div class="text-center p-2 bg-blue-50 rounded-lg">
                        <p class="text-lg font-bold text-blue-700">${sp.stats.withOrder}</p>
                        <p class="text-xs text-blue-600">With Order</p>
                    </div>
                    
                    <!-- No Order -->
                    <div class="text-center p-2 bg-yellow-50 rounded-lg">
                        <p class="text-lg font-bold text-yellow-700">${sp.stats.noOrder}</p>
                        <p class="text-xs text-yellow-600">No Order</p>
                    </div>
                </div>
                
                <!-- Progress Bars -->
                <div class="space-y-2">
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Visit Progress</span>
                            <span>${visitedPercent}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-green-500" style="width: ${visitedPercent}%"></div>
                        </div>
                    </div>
                    ${sp.stats.visited > 0 ? `
                    <div>
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Order Conversion</span>
                            <span>${withOrderPercent}%</span>
                        </div>
                        <div class="progress-bar">
                            <div class="progress-fill bg-blue-500" style="width: ${withOrderPercent}%"></div>
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;

            spCard.appendChild(linkWrapper);

            // Quick Actions (outside the link wrapper)
            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'flex gap-2 mt-4 pt-4 border-t border-gray-100';
            actionsDiv.innerHTML = `
                <button onclick="event.stopPropagation(); callSalesperson(${sp.id})" 
                        class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 flex items-center justify-center gap-2">
                    <iconify-icon icon="ph:phone" width="14"></iconify-icon>
                    Call
                </button>
                <button onclick="event.stopPropagation(); messageSalesperson(${sp.id})" 
                        class="flex-1 px-3 py-2 bg-green-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 flex items-center justify-center gap-2">
                    <iconify-icon icon="ph:chat-circle-text" width="14"></iconify-icon>
                    WhatsApp
                </button>
            `;

            spCard.appendChild(actionsDiv);
            elements.salespersonsList.appendChild(spCard);
        });
    }

    // Action Functions
    function callSalesperson(spId) {
        const sp = dummyData.salespersons.find(s => s.id === spId);
        if (!sp) return;

        // Remove spaces for tel:
        const phone = sp.phone.replace(/\s+/g, '');
        window.location.href = `tel:${phone}`;
    }

    function messageSalesperson(spId) {
        const sp = dummyData.salespersons.find(s => s.id === spId);
        if (!sp) return;

        // WhatsApp requires country code without +
        const phone = sp.phone.replace(/\D/g, '');
        window.open(`https://wa.me/${phone}`, '_blank');
    }

    // Utility Functions
    function showToast(message) {
        elements.toast.textContent = message;
        elements.toast.classList.remove('hidden');

        setTimeout(() => {
            elements.toast.classList.add('hidden');
        }, 3000);
    }
</script>
@endsection