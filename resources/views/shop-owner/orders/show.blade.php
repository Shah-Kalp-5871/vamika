@extends('layouts.shop-owner')

@php
$title = 'Order Details';
$subtitle = 'Complete order information';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.orders.index');
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

<div class="main-content p-4">
    <div id="orderShowContainer" class="space-y-6">
        <!-- Single order details will load here -->
        <div class="flex items-center justify-center py-12">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentOrder = null;

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadOrderShow();
    });

    function loadOrderShow() {
        // Get order ID from URL - Laravel passes this as route parameter
        const urlParts = window.location.pathname.split('/');
        const orderNumber = urlParts[urlParts.length - 1];

        if (!orderNumber || orderNumber === 'show') {
            showError('Invalid order');
            return;
        }

        // Load orders from localStorage or dummy data
        const savedOrders = localStorage.getItem('shop_orders');
        
        // Dummy data as fallback
        const dummyData = {
            orders: [
                {
                    orderNumber: "ORD00127",
                    date: "Dec 15, 2024",
                    time: "10:30 AM",
                    status: "Delivered",
                    subtotal: 2400,
                    delivery: 50,
                    discount: 100,
                    total: 2350,
                    paymentMethod: 'Cash on Delivery',
                    address: "123 Main Street, Mumbai, Maharashtra 400001",
                    phone: "+91 9876543210",
                    deliveryPerson: 'Rajesh Kumar',
                    deliveryTime: '10:30 AM - 12:00 PM',
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
                    subtotal: 1850,
                    delivery: 50,
                    discount: 0,
                    total: 1900,
                    paymentMethod: 'Cash on Delivery',
                    address: "123 Main Street, Mumbai, Maharashtra 400001",
                    phone: "+91 9876543210",
                    deliveryPerson: 'Rajesh Kumar',
                    deliveryTime: '2:00 PM - 4:00 PM',
                    items: [
                        { name: "Basmati Rice", unit: "5kg", quantity: 1, price: 450, total: 450 },
                        { name: "Toor Dal", unit: "1kg", quantity: 2, price: 180, total: 360 },
                        { name: "Sugar", unit: "1kg", quantity: 5, price: 45, total: 225 }
                    ]
                }
            ]
        };

        const orders = savedOrders ? JSON.parse(savedOrders) : dummyData.orders;

        currentOrder = orders.find(o => o.orderNumber === orderNumber);

        if (!currentOrder) {
            showError('Order not found');
            return;
        }

        renderOrder(currentOrder);
    }

    function renderOrder(order) {
        const container = document.getElementById('orderShowContainer');

        // Get shop owner info
        const shopOwnerData = JSON.parse(localStorage.getItem('shop_owner')) || {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            address: "123 Main Street, Mumbai, Maharashtra 400001",
            phone: "+91 9876543210"
        };

        container.innerHTML = `
            <!-- Order Header -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3 animate-slide-up">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Order #${order.orderNumber}</h2>
                        <p class="text-sm text-slate-500">${order.date} • ${order.time}</p>
                    </div>
                    ${getStatusBadge(order.status)}
                </div>
                
                <!-- Shop Info -->
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-sm font-medium text-slate-900">${shopOwnerData.shopName}</p>
                    <p class="text-xs text-slate-500">${shopOwnerData.name} • ${shopOwnerData.phone}</p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-slate-900">
                        Order Items (${order.items.length})
                    </h3>
                    <button onclick="printInvoice('${order.orderNumber}')" 
                            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                        <iconify-icon icon="lucide:printer" width="12"></iconify-icon>
                        Print
                    </button>
                </div>
                
                <div class="space-y-4">
                    ${order.items.map((item, index) => `
                        <div class="flex justify-between items-center py-3 ${index < order.items.length - 1 ? 'border-b border-slate-100' : ''}">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                                    <iconify-icon icon="lucide:package" width="16" class="text-slate-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">${item.name}</p>
                                    <p class="text-xs text-slate-500">${item.unit}</p>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-xs text-slate-600">Qty: ${item.quantity}</span>
                                        <span class="text-xs text-slate-600">₹${item.price} per unit</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-slate-900">₹${item.total}</p>
                                <p class="text-xs text-slate-500">₹${item.price} × ${item.quantity}</p>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3 animate-slide-up" style="animation-delay: 0.2s;">
                <h3 class="text-sm font-semibold text-slate-900 mb-2">Price Details</h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Subtotal</span>
                        <span class="font-medium">₹${order.subtotal || order.total}</span>
                    </div>
                    
                    ${order.delivery ? `
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-600">Delivery Charges</span>
                            <span class="font-medium">₹${order.delivery}</span>
                        </div>
                    ` : ''}
                    
                    ${order.discount ? `
                        <div class="flex justify-between text-sm">
                            <span class="text-emerald-600">Discount</span>
                            <span class="font-medium text-emerald-600">-₹${order.discount}</span>
                        </div>
                    ` : ''}
                    
                    <div class="flex justify-between font-semibold text-lg pt-3 border-t border-slate-200">
                        <span class="text-slate-900">Total Amount</span>
                        <span class="text-emerald-600">₹${order.total}</span>
                    </div>
                </div>
                
                ${order.paymentMethod ? `
                    <div class="pt-3 border-t border-slate-200">
                        <p class="text-xs text-slate-500">Payment Method</p>
                        <p class="text-sm font-medium text-slate-900">${order.paymentMethod}</p>
                    </div>
                ` : ''}
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 animate-slide-up" style="animation-delay: 0.3s;">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Delivery Information</h3>
                
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center flex-shrink: 0">
                            <iconify-icon icon="lucide:map-pin" width="16" class="text-indigo-600"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Delivery Address</p>
                            <p class="text-sm text-slate-600 mt-1">${order.address || shopOwnerData.address}</p>
                        </div>
                    </div>
                    
                    ${order.deliveryTime ? `
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-amber-50 flex items-center justify-center flex-shrink: 0">
                                <iconify-icon icon="lucide:clock" width="16" class="text-amber-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Delivery Time Slot</p>
                                <p class="text-sm text-slate-600 mt-1">${order.deliveryTime}</p>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${order.deliveryPerson ? `
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center flex-shrink: 0">
                                <iconify-icon icon="lucide:user" width="16" class="text-emerald-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Delivery Person</p>
                                <p class="text-sm text-slate-600 mt-1">${order.deliveryPerson}</p>
                            </div>
                        </div>
                    ` : ''}
                </div>
            </div>

            <!-- Order Actions -->
            ${order.status === 'Pending' || order.status === 'Processing' ? `
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 animate-slide-up" style="animation-delay: 0.4s;">
                    <div class="flex items-start gap-3">
                        <iconify-icon icon="lucide:info" width="20" class="text-amber-600 mt-0.5"></iconify-icon>
                        <div class="flex-1">
                            <h4 class="text-sm font-semibold text-amber-900">Order Status</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Your order is currently being processed. You'll receive updates on delivery timing.
                            </p>
                            <div class="flex gap-2 mt-3">
                                <button onclick="cancelOrder('${order.orderNumber}')" 
                                        class="px-4 py-2 text-sm font-medium text-rose-600 hover:text-rose-700 
                                               hover:bg-rose-50 rounded-lg border border-rose-200 transition-colors">
                                    Cancel Order
                                </button>
                                <button onclick="trackOrder('${order.orderNumber}')" 
                                        class="px-4 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 
                                               hover:bg-indigo-50 rounded-lg border border-indigo-200 transition-colors">
                                    Track Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            ` : ''}
        `;
    }

    function getStatusBadge(status) {
        const badges = {
            'Pending': {
                icon: 'clock',
                color: 'bg-amber-50 text-amber-700 border border-amber-200',
                text: 'Pending'
            },
            'Processing': {
                icon: 'settings',
                color: 'bg-blue-50 text-blue-700 border border-blue-200',
                text: 'Processing'
            },
            'Dispatched': {
                icon: 'truck',
                color: 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                text: 'Dispatched'
            },
            'Delivered': {
                icon: 'check-circle',
                color: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                text: 'Delivered'
            },
            'Cancelled': {
                icon: 'x-circle',
                color: 'bg-rose-50 text-rose-700 border border-rose-200',
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

    function printInvoice(orderNumber) {
        window.location.href = '{{ route("shop-owner.invoices.show", "") }}/' + orderNumber;
    }

    function trackOrder(orderNumber) {
        alert(`Tracking order #${orderNumber}\n\nIn a real application, this would show real-time tracking information.`);
    }

    function cancelOrder(orderNumber) {
        if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
            // In real app, this would be an API call to Laravel
            const savedOrders = localStorage.getItem('shop_orders');
            if (savedOrders) {
                const orders = JSON.parse(savedOrders);
                const orderIndex = orders.findIndex(o => o.orderNumber === orderNumber);
                if (orderIndex !== -1) {
                    orders[orderIndex].status = 'Cancelled';
                    localStorage.setItem('shop_orders', JSON.stringify(orders));
                    
                    // Show success message
                    alert('Order has been cancelled successfully.');
                    
                    // Reload the page to reflect changes
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            }
        }
    }

    function showError(msg) {
        document.getElementById('orderShowContainer').innerHTML = `
            <div class="empty-state">
                <iconify-icon icon="lucide:alert-circle" width="48" class="text-slate-400 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">${msg}</h4>
                <p class="text-sm text-slate-500 mb-6">Please check the order number and try again.</p>
                <button onclick="window.location.href='{{ route('shop-owner.orders.index') }}'"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                    <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                    Back to Orders
                </button>
            </div>
        `;
    }
</script>
@endsection