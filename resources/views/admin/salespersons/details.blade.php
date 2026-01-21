@extends('layouts.admin')

@section('content')
@php
// Get the ID from the route parameter, not query string
$salespersonId = request()->route('id') ?? 1;

$pageConfig = [
    'title' => 'Salesperson Details',
    'subtitle' => 'Detailed performance & information',
    'showBack' => true,
    'backUrl' => route('admin.reports.visit'),
    'role' => 'Admin',
    'showBottomNav' => true,
];
@endphp

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        -webkit-tap-highlight-color: transparent;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background-color: #f8fafc;
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.4s ease-out;
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

    /* Progress bars */
    .progress-bar {
        height: 8px;
        border-radius: 4px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    /* Visit item styles */
    .visit-item {
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .visit-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        background-color: #f9fafb !important;
    }

    /* Order amount styling */
    .order-amount {
        font-weight: 700;
    }

    .order-positive {
        color: #166534;
    }

    .order-zero {
        color: #6b7280;
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

<!-- Salesperson Header -->
<div class="bg-white shadow-sm">
    <div class="p-4">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                <iconify-icon icon="ph:user" width="28" class="text-blue-600"></iconify-icon>
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-gray-900" id="salespersonName">Loading...</h1>
                <p class="text-sm text-gray-600" id="salespersonArea">Loading...</p>
            </div>
            <div class="text-center">
                <div class="w-14 h-14 rounded-full bg-green-100 flex items-center justify-center mb-1">
                    <span class="text-lg font-bold text-green-700" id="totalOrders">0</span>
                </div>
                <p class="text-xs text-gray-500">MTD Orders</p>
            </div>
        </div>

        <!-- Status Badge -->
        <div class="inline-block" id="statusBadge">
            <span class="status-badge status-active">Active</span>
        </div>
    </div>
</div>

<!-- Stats Overview -->
<div class="p-4">
    <h3 class="font-semibold text-gray-900 mb-3">Today's Performance</h3>
    <div class="grid grid-cols-2 gap-3 mb-6">
        <!-- Not Visited -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Not Visited</p>
                    <h3 class="text-2xl font-bold text-gray-900" id="notVisited">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center">
                    <iconify-icon icon="ph:x-circle" width="20" class="text-gray-600"></iconify-icon>
                </div>
            </div>
        </div>

        <!-- Visited -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Visited</p>
                    <h3 class="text-2xl font-bold text-green-700" id="visited">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <iconify-icon icon="ph:check-circle" width="20" class="text-green-600"></iconify-icon>
                </div>
            </div>
        </div>

        <!-- With Order -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">With Order</p>
                    <h3 class="text-2xl font-bold text-blue-700" id="withOrder">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <iconify-icon icon="ph:shopping-cart" width="20" class="text-blue-600"></iconify-icon>
                </div>
            </div>
        </div>

        <!-- No Order -->
        <div class="bg-white rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">No Order</p>
                    <h3 class="text-2xl font-bold text-yellow-700" id="noOrder">0</h3>
                </div>
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <iconify-icon icon="ph:warning-circle" width="20" class="text-yellow-600"></iconify-icon>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information -->
<div class="p-4">
    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 animate-fade-in">
        <h3 class="font-semibold text-gray-900 mb-4">Contact Information</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                    <iconify-icon icon="ph:phone" width="18" class="text-blue-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Phone Number</p>
                    <p class="font-medium text-gray-900" id="phoneNumber">Loading...</p>
                </div>
                <button onclick="callSalesperson()"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium">
                    Call
                </button>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                    <iconify-icon icon="ph:envelope" width="18" class="text-green-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Email Address</p>
                    <p class="font-medium text-gray-900" id="email">Loading...</p>
                </div>
                <button onclick="emailSalesperson()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">
                    Email
                </button>
            </div>

            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <iconify-icon icon="ph:map-pin" width="18" class="text-purple-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Assigned Area</p>
                    <p class="font-medium text-gray-900" id="assignedArea">Loading...</p>
                </div>
                <button onclick="viewArea()"
                    class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">
                    View
                </button>
            </div>
        </div>
    </div>

    <!-- Performance Metrics -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 animate-fade-in" style="animation-delay: 0.1s">
        <h3 class="font-semibold text-gray-900 mb-4">Performance Metrics</h3>
        <div class="space-y-4">
            <div>
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Visit Completion Rate</span>
                    <span id="visitRate">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill bg-green-500" id="visitRateBar" style="width: 0%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Order Conversion Rate</span>
                    <span id="conversionRate">0%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill bg-blue-500" id="conversionRateBar" style="width: 0%"></div>
                </div>
            </div>
            <div>
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>Average Order Value</span>
                    <span id="avgOrderValue">₹0</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill bg-purple-500" id="avgOrderBar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Visits -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 animate-fade-in" style="animation-delay: 0.2s">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold text-gray-900">Today's Visits</h3>
            <span class="text-sm text-gray-500" id="visitCount">0 visits</span>
        </div>
        <div id="todaysVisitsList" class="space-y-3">
            <!-- Visits will be dynamically loaded here -->
            <div class="text-center py-8 text-gray-500">
                <iconify-icon icon="ph:clock" width="32" class="text-gray-300 mb-2"></iconify-icon>
                <p>Loading today's visits...</p>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 animate-fade-in" style="animation-delay: 0.3s">
        <h3 class="font-semibold text-gray-900 mb-4">Recent Activity</h3>
        <div class="space-y-3">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mt-1">
                    <iconify-icon icon="ph:check-circle" width="14" class="text-blue-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">Started work for the day</p>
                    <p class="text-xs text-gray-500" id="startTime">Today at 9:00 AM</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center mt-1">
                    <iconify-icon icon="ph:storefront" width="14" class="text-green-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">Visited 8 outlets so far</p>
                    <p class="text-xs text-gray-500">Last update: <span id="lastUpdate">Today at 1:30 PM</span></p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center mt-1">
                    <iconify-icon icon="ph:currency-inr" width="14" class="text-purple-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">Total order value today</p>
                    <p class="text-xs text-gray-500" id="totalValue">₹6,780</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Dummy Data for Salesperson Details with Order IDs
    const salespersonData = {
        1: {
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
                {
                    id: 101,
                    outlet: "SuperMart Central",
                    time: "10:30 AM",
                    orderAmount: 2450,
                    status: "with-order",
                    orderId: "ORD-2024-001",
                    outletId: 201,
                    items: [
                        { name: "Product A", quantity: 5, price: 300, total: 1500 },
                        { name: "Product B", quantity: 2, price: 475, total: 950 }
                    ]
                },
                {
                    id: 102,
                    outlet: "Fresh Grocers",
                    time: "11:45 AM",
                    orderAmount: 0,
                    status: "no-order",
                    orderId: null,
                    outletId: 202,
                    items: []
                },
                {
                    id: 103,
                    outlet: "Daily Needs",
                    time: "12:30 PM",
                    orderAmount: 1890,
                    status: "with-order",
                    orderId: "ORD-2024-003",
                    outletId: 203,
                    items: [
                        { name: "Product C", quantity: 3, price: 630, total: 1890 }
                    ]
                },
                {
                    id: 104,
                    outlet: "Quick Store",
                    time: "1:15 PM",
                    orderAmount: 890,
                    status: "with-order",
                    orderId: "ORD-2024-004",
                    outletId: 204,
                    items: [
                        { name: "Product D", quantity: 1, price: 890, total: 890 }
                    ]
                }
            ],
            performance: {
                visitRate: 32,
                conversionRate: 75,
                avgOrderValue: 1743
            },
            activity: {
                startTime: "Today at 9:00 AM",
                lastUpdate: "Today at 1:30 PM",
                totalValue: 5230
            }
        },
        2: {
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
                {
                    id: 201,
                    outlet: "City Bazaar",
                    time: "11:15 AM",
                    orderAmount: 0,
                    status: "no-order",
                    orderId: null,
                    outletId: 301,
                    items: []
                },
                {
                    id: 202,
                    outlet: "Quick Mart",
                    time: "01:00 PM",
                    orderAmount: 1560,
                    status: "with-order",
                    orderId: "ORD-2024-005",
                    outletId: 302,
                    items: [
                        { name: "Product E", quantity: 4, price: 390, total: 1560 }
                    ]
                },
                {
                    id: 203,
                    outlet: "Local Store",
                    time: "02:30 PM",
                    orderAmount: 890,
                    status: "with-order",
                    orderId: "ORD-2024-006",
                    outletId: 303,
                    items: [
                        { name: "Product F", quantity: 2, price: 445, total: 890 }
                    ]
                }
            ],
            performance: {
                visitRate: 30,
                conversionRate: 67,
                avgOrderValue: 1225
            },
            activity: {
                startTime: "Today at 9:30 AM",
                lastUpdate: "Today at 2:30 PM",
                totalValue: 2450
            }
        },
        3: {
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
                {
                    id: 301,
                    outlet: "Fresh Grocers",
                    time: "09:45 AM",
                    orderAmount: 1890,
                    status: "with-order",
                    orderId: "ORD-2024-007",
                    outletId: 401,
                    items: [
                        { name: "Product G", quantity: 3, price: 630, total: 1890 }
                    ]
                },
                {
                    id: 302,
                    outlet: "SuperMart",
                    time: "11:00 AM",
                    orderAmount: 2340,
                    status: "with-order",
                    orderId: "ORD-2024-008",
                    outletId: 402,
                    items: [
                        { name: "Product H", quantity: 6, price: 390, total: 2340 }
                    ]
                },
                {
                    id: 303,
                    outlet: "Corner Shop",
                    time: "12:15 PM",
                    orderAmount: 0,
                    status: "no-order",
                    orderId: null,
                    outletId: 403,
                    items: []
                }
            ],
            performance: {
                visitRate: 32,
                conversionRate: 71,
                avgOrderValue: 1410
            },
            activity: {
                startTime: "Today at 9:15 AM",
                lastUpdate: "Today at 12:15 PM",
                totalValue: 4230
            }
        },
        4: {
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
            visitsToday: [],
            performance: {
                visitRate: 0,
                conversionRate: 0,
                avgOrderValue: 0
            },
            activity: {
                startTime: "Not started",
                lastUpdate: "Yesterday at 3:45 PM",
                totalValue: 0
            }
        },
        5: {
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
                {
                    id: 501,
                    outlet: "Family Store",
                    time: "01:00 PM",
                    orderAmount: 0,
                    status: "no-order",
                    orderId: null,
                    outletId: 501,
                    items: []
                },
                {
                    id: 502,
                    outlet: "Kids Corner",
                    time: "02:30 PM",
                    orderAmount: 890,
                    status: "with-order",
                    orderId: "ORD-2024-009",
                    outletId: 502,
                    items: [
                        { name: "Product I", quantity: 1, price: 890, total: 890 }
                    ]
                },
                {
                    id: 503,
                    outlet: "School Store",
                    time: "03:15 PM",
                    orderAmount: 1250,
                    status: "with-order",
                    orderId: "ORD-2024-010",
                    outletId: 503,
                    items: [
                        { name: "Product J", quantity: 5, price: 250, total: 1250 }
                    ]
                }
            ],
            performance: {
                visitRate: 33,
                conversionRate: 60,
                avgOrderValue: 1070
            },
            activity: {
                startTime: "Today at 10:00 AM",
                lastUpdate: "Today at 3:15 PM",
                totalValue: 2140
            }
        },
        6: {
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
                {
                    id: 601,
                    outlet: "Mall Store",
                    time: "09:30 AM",
                    orderAmount: 3450,
                    status: "with-order",
                    orderId: "ORD-2024-011",
                    outletId: 601,
                    items: [
                        { name: "Product K", quantity: 10, price: 345, total: 3450 }
                    ]
                },
                {
                    id: 602,
                    outlet: "Food Court",
                    time: "11:00 AM",
                    orderAmount: 1250,
                    status: "with-order",
                    orderId: "ORD-2024-012",
                    outletId: 602,
                    items: [
                        { name: "Product L", quantity: 5, price: 250, total: 1250 }
                    ]
                },
                {
                    id: 603,
                    outlet: "ElectroMart",
                    time: "01:30 PM",
                    orderAmount: 0,
                    status: "no-order",
                    orderId: null,
                    outletId: 603,
                    items: []
                },
                {
                    id: 604,
                    outlet: "Book Store",
                    time: "02:45 PM",
                    orderAmount: 890,
                    status: "with-order",
                    orderId: "ORD-2024-013",
                    outletId: 604,
                    items: [
                        { name: "Product M", quantity: 2, price: 445, total: 890 }
                    ]
                }
            ],
            performance: {
                visitRate: 40,
                conversionRate: 75,
                avgOrderValue: 1863
            },
            activity: {
                startTime: "Today at 9:00 AM",
                lastUpdate: "Today at 2:45 PM",
                totalValue: 5590
            }
        }
    };

    // Get salesperson ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const salespersonId = urlParams.get('id') || "{{ $salespersonId }}";

    // Initialize the page
    document.addEventListener('DOMContentLoaded', () => {
        loadSalespersonData(parseInt(salespersonId));
    });

    function loadSalespersonData(id) {
        const sp = salespersonData[id];

        if (!sp) {
            // Redirect back if salesperson not found
            window.location.href = "{{ route('admin.dashboard') }}";
            return;
        }

        // Update header information
        document.getElementById('salespersonName').textContent = sp.name;
        document.getElementById('salespersonArea').textContent = sp.area;
        document.getElementById('totalOrders').textContent = sp.totalOrders;

        // Update status badge
        const statusBadge = document.getElementById('statusBadge');
        statusBadge.innerHTML = `
            <span class="status-badge ${sp.status === 'active' ? 'status-active' : 'status-inactive'}">
                ${sp.status === 'active' ? 'Active' : 'Inactive'}
            </span>
        `;

        // Update stats
        document.getElementById('notVisited').textContent = sp.stats.notVisited;
        document.getElementById('visited').textContent = sp.stats.visited;
        document.getElementById('withOrder').textContent = sp.stats.withOrder;
        document.getElementById('noOrder').textContent = sp.stats.noOrder;

        // Update contact information
        document.getElementById('phoneNumber').textContent = sp.phone;
        document.getElementById('email').textContent = sp.email;
        document.getElementById('assignedArea').textContent = `${sp.area} (${sp.assignedOutlets} outlets)`;

        // Update performance metrics
        document.getElementById('visitRate').textContent = sp.performance.visitRate + '%';
        document.getElementById('visitRateBar').style.width = sp.performance.visitRate + '%';

        document.getElementById('conversionRate').textContent = sp.performance.conversionRate + '%';
        document.getElementById('conversionRateBar').style.width = sp.performance.conversionRate + '%';

        document.getElementById('avgOrderValue').textContent = '₹' + sp.performance.avgOrderValue.toLocaleString();
        document.getElementById('avgOrderBar').style.width = Math.min(sp.performance.avgOrderValue / 2000 * 100, 100) + '%';

        // Update today's visits
        const visitsList = document.getElementById('todaysVisitsList');
        const visitCount = document.getElementById('visitCount');

        if (sp.visitsToday.length === 0) {
            visitsList.innerHTML = `
                <div class="text-center py-8 text-gray-500">
                    <iconify-icon icon="ph:calendar-blank" width="32" class="text-gray-300 mb-2"></iconify-icon>
                    <p>No visits recorded today</p>
                </div>
            `;
            visitCount.textContent = "No visits";
        } else {
            visitCount.textContent = `${sp.visitsToday.length} visits`;
            visitsList.innerHTML = sp.visitsToday.map(visit => `
                <div class="visit-item flex items-center justify-between p-3 bg-gray-50 rounded-lg" 
                     onclick="viewOrderDetails(${visit.id}, ${id})">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-medium text-gray-900">${visit.outlet}</h4>
                            <span class="text-xs ${visit.status === 'with-order' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'} px-2 py-1 rounded-full">
                                ${visit.status === 'with-order' ? 'With Order' : 'No Order'}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500">${visit.time}</p>
                    </div>
                    <div class="text-right">
                        <p class="order-amount ${visit.orderAmount > 0 ? 'order-positive' : 'order-zero'}">
                            ${visit.orderAmount > 0 ? '₹' + visit.orderAmount.toLocaleString() : 'No Order'}
                        </p>
                        <span class="text-xs ${visit.orderAmount > 0 ? 'text-green-600' : 'text-gray-500'}">
                            ${visit.orderAmount > 0 ? 'View Order →' : 'Visit Details →'}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        // Update recent activity
        document.getElementById('startTime').textContent = sp.activity.startTime;
        document.getElementById('lastUpdate').textContent = sp.activity.lastUpdate;
        document.getElementById('totalValue').textContent = '₹' + sp.activity.totalValue.toLocaleString();
    }

    // Redirect to order details page
    function viewOrderDetails(visitId, salespersonId) {
        // Find the visit data
        const sp = salespersonData[salespersonId];
        const visit = sp.visitsToday.find(v => v.id === visitId);

        if (visit) {
            // Create a form to pass data to order-details page
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('admin.orders.details', ':orderId') }}".replace(':orderId', visit.orderId);

            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            form.appendChild(csrfToken);

            // Add salesperson info
            const spInput = document.createElement('input');
            spInput.type = 'hidden';
            spInput.name = 'salesperson_id';
            spInput.value = salespersonId;
            form.appendChild(spInput);

            // Add salesperson name
            const spNameInput = document.createElement('input');
            spNameInput.type = 'hidden';
            spNameInput.name = 'salesperson_name';
            spNameInput.value = sp.name;
            form.appendChild(spNameInput);

            // Add visit data as JSON
            const visitInput = document.createElement('input');
            visitInput.type = 'hidden';
            visitInput.name = 'visit_data';
            visitInput.value = JSON.stringify(visit);
            form.appendChild(visitInput);

            // Add outlet info
            const outletInput = document.createElement('input');
            outletInput.type = 'hidden';
            outletInput.name = 'outlet_name';
            outletInput.value = visit.outlet;
            form.appendChild(outletInput);

            // Submit the form
            document.body.appendChild(form);
            form.submit();
        }
    }

    function callSalesperson() {
        const sp = salespersonData[salespersonId];
        if (!sp) return;

        // Clean phone number for tel:
        const phone = sp.phone.replace(/\s+/g, '');
        window.location.href = `tel:${phone}`;
    }

    function emailSalesperson() {
        const sp = salespersonData[salespersonId];
        if (!sp) return;

        window.location.href = `mailto:${sp.email}`;
    }

    function viewArea() {
        const sp = salespersonData[salespersonId];
        if (!sp) return;

        // Optional: Google Maps search
        window.open(
            `https://www.google.com/maps/search/${encodeURIComponent(sp.area)}`,
            '_blank'
        );
    }
</script>
@endsection