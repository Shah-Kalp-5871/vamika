@extends('layouts.shop-owner')

@php
$title = 'My Orders';
$subtitle = 'Track & manage your orders';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.dashboard');
@endphp

@section('content')
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

    /* Main content container - MOBILE ONLY */
    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
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

    /* Order card hover effect */
    .order-card {
        transition: all 0.2s ease;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Status badge styles */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Hide on larger screens */
    @media (min-width: 768px) {
        body::before {
            content: "Mobile Only View - Please view on mobile device";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999999;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            padding: 20px;
        }
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

    /* Empty state */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
        background: #F8FAFC;
        border-radius: 0.75rem;
        border: 2px dashed #E2E8F0;
    }
</style>

<div class="main-content">
    <main class="p-4">
        <!-- Search Bar -->
        <div class="relative mb-4">
            <iconify-icon icon="lucide:search" width="18"
                class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input type="text" id="orderSearch" placeholder="Search by order number or item..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white
                      focus:outline-none focus:ring-2 focus:ring-indigo-100
                      focus:border-indigo-300 text-sm placeholder-slate-400 transition-all"
                oninput="searchOrders()">
        </div>

        <!-- Filter Tabs -->
        <div class="flex gap-2 mb-4">
            <button onclick="filterOrders('all')" class="flex-1 px-4 py-2.5 rounded-lg bg-indigo-600 text-white text-sm font-medium
                   transition-colors flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:package" width="14"></iconify-icon>
                All Orders
            </button>

            <button onclick="filterOrders('history')" class="flex-1 px-4 py-2.5 rounded-lg border border-slate-200 bg-white text-slate-600
                   text-sm font-medium hover:bg-slate-50 transition-colors
                   flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:history" width="14"></iconify-icon>
                History
            </button>
        </div>

        <!-- Order Stats -->
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="p-3 rounded-lg border border-slate-200 bg-white text-center">
                <p class="text-lg font-semibold text-slate-900" id="totalOrdersCount">0</p>
                <p class="text-xs text-slate-500">Total Orders</p>
            </div>
            <div class="p-3 rounded-lg border border-slate-200 bg-white text-center">
                <p class="text-lg font-semibold text-emerald-600" id="deliveredCount">0</p>
                <p class="text-xs text-slate-500">Delivered</p>
            </div>
            <div class="p-3 rounded-lg border border-slate-200 bg-white text-center">
                <p class="text-lg font-semibold text-indigo-600" id="pendingCount">0</p>
                <p class="text-xs text-slate-500">Pending</p>
            </div>
        </div>

        <!-- Orders List -->
        <div id="ordersList" class="space-y-3">
            <!-- Orders will be loaded here -->
            <div class="flex items-center justify-center py-12">
                <div class="loading-spinner"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state hidden">
            <div class="flex flex-col items-center justify-center">
                <iconify-icon icon="lucide:package" width="48" class="text-slate-400 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">No Orders Found</h4>
                <p class="text-sm text-slate-500 mb-6 max-w-md">
                    No orders match your current filters. Try adjusting your search or start shopping.
                </p>
                <button onclick="window.location.href='{{ route('shop-owner.products.index') }}'"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                    <iconify-icon icon="lucide:shopping-cart" width="16"></iconify-icon>
                    Start Shopping
                </button>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    let orders = [];
    let currentFilter = 'all';
    let searchTerm = '';

    // Dummy data - In real app, this would come from Laravel controller
    const dummyData = {
        shopOwner: {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            address: "123 Main Street, Mumbai, Maharashtra 400001",
            phone: "+91 9876543210"
        },
        orders: [
            {
                orderNumber: "ORD00127",
                date: "Dec 15, 2024",
                time: "10:30 AM",
                status: "Delivered",
                total: 2400,
                items: [
                    { name: "Aashirvaad Atta", unit: "5kg", quantity: 2, price: 250, total: 500 },
                    { name: "Fortune Oil", unit: "1L", quantity: 1, price: 190, total: 190 },
                    { name: "Tata Salt", unit: "1kg", quantity: 3, price: 25, total: 75 },
                    { name: "Coca-Cola", unit: "750ml", quantity: 4, price: 40, total: 160 }
                ]
            },
            {
                orderNumber: "ORD00126",
                date: "Dec 14, 2024",
                time: "02:45 PM",
                status: "Processing",
                total: 1850,
                items: [
                    { name: "Basmati Rice", unit: "5kg", quantity: 1, price: 450, total: 450 },
                    { name: "Toor Dal", unit: "1kg", quantity: 2, price: 180, total: 360 },
                    { name: "Sugar", unit: "1kg", quantity: 5, price: 45, total: 225 }
                ]
            },
            {
                orderNumber: "ORD00125",
                date: "Dec 12, 2024",
                time: "09:15 AM",
                status: "Pending",
                total: 3200,
                items: [
                    { name: "Amul Milk", unit: "500ml", quantity: 10, price: 30, total: 300 },
                    { name: "Lays Chips", unit: "50g", quantity: 20, price: 20, total: 400 },
                    { name: "Parle-G Biscuits", unit: "100g", quantity: 30, price: 10, total: 300 },
                    { name: "Colgate Toothpaste", unit: "100g", quantity: 5, price: 55, total: 275 }
                ]
            },
            {
                orderNumber: "ORD00124",
                date: "Dec 10, 2024",
                time: "04:20 PM",
                status: "Dispatched",
                total: 1475,
                items: [
                    { name: "Tea Powder", unit: "250g", quantity: 2, price: 120, total: 240 },
                    { name: "Nescafe Coffee", unit: "50g", quantity: 3, price: 85, total: 255 },
                    { name: "Bournvita", unit: "500g", quantity: 2, price: 190, total: 380 }
                ]
            },
            {
                orderNumber: "ORD00123",
                date: "Dec 8, 2024",
                time: "11:00 AM",
                status: "Cancelled",
                total: 950,
                items: [
                    { name: "Surf Excel", unit: "1kg", quantity: 2, price: 120, total: 240 },
                    { name: "Vim Liquid", unit: "500ml", quantity: 1, price: 85, total: 85 }
                ]
            }
        ]
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadOrdersPage();
    });

    function loadOrdersPage() {
        // Load orders from localStorage or dummy data
        const savedOrders = localStorage.getItem('shop_orders');
        if (savedOrders) {
            orders = JSON.parse(savedOrders);
        } else {
            // Use dummy data if no orders in localStorage
            orders = dummyData.orders.map(order => ({
                ...order,
                paymentMethod: 'Cash on Delivery',
                address: dummyData.shopOwner.address,
                phone: dummyData.shopOwner.phone,
                deliveryPerson: 'Rajesh Kumar',
                deliveryTime: '10:30 AM - 12:00 PM'
            }));

            // Save to localStorage for persistence
            localStorage.setItem('shop_orders', JSON.stringify(orders));
        }

        // Update stats
        updateOrderStats();

        // Render orders
        filterOrders('all');
    }

    function updateOrderStats() {
        const totalOrders = orders.length;
        const deliveredCount = orders.filter(order => order.status === 'Delivered').length;
        const pendingCount = orders.filter(order => order.status === 'Pending' || order.status === 'Processing').length;

        document.getElementById('totalOrdersCount').textContent = totalOrders;
        document.getElementById('deliveredCount').textContent = deliveredCount;
        document.getElementById('pendingCount').textContent = pendingCount;
    }

    function filterOrders(filter) {
        currentFilter = filter;

        // Update button styles
        const allBtn = document.querySelector('button[onclick="filterOrders(\'all\')"]');
        const historyBtn = document.querySelector('button[onclick="filterOrders(\'history\')"]');

        allBtn.classList.remove('bg-indigo-600', 'text-white');
        allBtn.classList.add('border', 'border-slate-200', 'bg-white', 'text-slate-600', 'hover:bg-slate-50');
        historyBtn.classList.remove('bg-indigo-600', 'text-white');
        historyBtn.classList.add('border', 'border-slate-200', 'bg-white', 'text-slate-600', 'hover:bg-slate-50');

        // Activate clicked button
        if (filter === 'all') {
            allBtn.classList.remove('border', 'border-slate-200', 'bg-white', 'text-slate-600', 'hover:bg-slate-50');
            allBtn.classList.add('bg-indigo-600', 'text-white');
        } else {
            historyBtn.classList.remove('border', 'border-slate-200', 'bg-white', 'text-slate-600', 'hover:bg-slate-50');
            historyBtn.classList.add('bg-indigo-600', 'text-white');
        }

        renderOrders();
    }

    function searchOrders() {
        searchTerm = document.getElementById('orderSearch').value.toLowerCase();
        renderOrders();
    }

    function renderOrders() {
        const ordersList = document.getElementById('ordersList');
        const emptyState = document.getElementById('emptyState');

        // Filter orders
        let filteredOrders = [...orders];

        // Apply tab filter
        if (currentFilter === 'history') {
            filteredOrders = orders.filter(order =>
                order.status === 'Delivered' || order.status === 'Cancelled'
            );
        }

        // Apply search filter
        if (searchTerm) {
            filteredOrders = filteredOrders.filter(order =>
                order.orderNumber.toLowerCase().includes(searchTerm) ||
                order.items.some(item => item.name.toLowerCase().includes(searchTerm))
            );
        }

        // Sort by date (newest first)
        filteredOrders.sort((a, b) => new Date(b.date) - new Date(a.date));

        // Check if empty
        if (filteredOrders.length === 0) {
            ordersList.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        ordersList.innerHTML = '';

        // Render orders
        filteredOrders.forEach((order, index) => {
            const orderCard = document.createElement('div');
            orderCard.className = 'order-card bg-white rounded-xl border border-slate-200 shadow-sm transition-all overflow-hidden animate-slide-up';
            orderCard.style.animationDelay = `${index * 0.05}s`;

            // Status badge
            const statusBadge = getStatusBadge(order.status);

            // Show first 2 items
            const previewItems = order.items.slice(0, 2);
            const remainingItems = order.items.length - 2;

            orderCard.innerHTML = `
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="text-sm font-semibold text-slate-900">Order #${order.orderNumber}</h3>
                                <p class="text-xs text-slate-500 mt-0.5">${order.date} • ${order.time}</p>
                            </div>
                            ${statusBadge}
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            ${previewItems.map(item => `
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded bg-slate-50 flex items-center justify-center border border-slate-200">
                                            <iconify-icon icon="lucide:package" width="12" class="text-slate-600"></iconify-icon>
                                        </div>
                                        <div>
                                            <p class="text-xs font-medium text-slate-900">${item.name}</p>
                                            <p class="text-[10px] text-slate-500">${item.unit} • Qty: ${item.quantity}</p>
                                        </div>
                                    </div>
                                    <span class="text-xs font-semibold text-slate-900">₹${item.total}</span>
                                </div>
                            `).join('')}
                            
                            ${remainingItems > 0 ? `
                                <div class="text-center">
                                    <span class="text-xs text-slate-500">+ ${remainingItems} more item${remainingItems > 1 ? 's' : ''}</span>
                                </div>
                            ` : ''}
                        </div>
                        
                       <div class="flex items-center justify-between pt-3 border-t border-slate-100">
    <div>
        <p class="text-xs text-slate-500">Total Amount</p>
        <p class="text-lg font-semibold text-emerald-600">₹${order.total}</p>
    </div>

    <div class="flex gap-2">
        <!-- VIEW ORDER -->
        <button 
            onclick="window.location.href='{{ route('shop-owner.orders.details', ['id' => $order->id ?? 'dummy']) }}?order=${order.orderNumber}'"
            class="px-3 py-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg border border-slate-200 transition-colors flex items-center gap-1">
            <iconify-icon icon="lucide:eye" width="12"></iconify-icon>
            View
        </button>

        <!-- INVOICE -->
        <button 
            onclick="window.location.href='{{ route('shop-owner.invoices.show',  ['id' => $order->id ?? 'dummy']) }}/${order.orderNumber}'"
            class="px-3 py-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg border border-slate-200 transition-colors flex items-center gap-1">
            <iconify-icon icon="lucide:file-text" width="12"></iconify-icon>
            Invoice
        </button>
    </div>
</div>

                    </div>
                `;
            ordersList.appendChild(orderCard);
        });
    }

    function getStatusBadge(status) {
        const badges = {
            'Pending': {
                icon: 'clock',
                color: 'bg-amber-50 text-amber-700 border-amber-200',
                text: 'Pending'
            },
            'Processing': {
                icon: 'settings',
                color: 'bg-blue-50 text-blue-700 border-blue-200',
                text: 'Processing'
            },
            'Dispatched': {
                icon: 'truck',
                color: 'bg-indigo-50 text-indigo-700 border-indigo-200',
                text: 'Dispatched'
            },
            'Delivered': {
                icon: 'check-circle',
                color: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                text: 'Delivered'
            },
            'Cancelled': {
                icon: 'x-circle',
                color: 'bg-rose-50 text-rose-700 border-rose-200',
                text: 'Cancelled'
            }
        };

        const badge = badges[status] || badges['Pending'];
        return `
                <span class="status-badge ${badge.color}">
                    <iconify-icon icon="lucide:${badge.icon}" width="12"></iconify-icon>
                    ${badge.text}
                </span>
            `;
    }
</script>
@endsection