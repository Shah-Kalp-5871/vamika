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
    .loading-spinner {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
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
    <div id="orderDetailsContainer" class="space-y-6">
        <!-- Order details will load here -->
        <div class="flex items-center justify-center py-12">
            <div class="loading-spinner"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        loadOrderDetails();
    });

    function loadOrderDetails() {
        const params = new URLSearchParams(window.location.search);
        const orderNumber = params.get('order');

        if (!orderNumber) {
            showError('Invalid order');
            return;
        }

        // Dummy data - In real app, this would come from Laravel controller
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
                    address: "123 Main Street, Mumbai, Maharashtra 400001",
                    deliveryTime: "10:30 AM - 12:00 PM",
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
                    address: "123 Main Street, Mumbai, Maharashtra 400001",
                    deliveryTime: "2:00 PM - 4:00 PM",
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
                    subtotal: 3200,
                    delivery: 0,
                    discount: 200,
                    total: 3000,
                    address: "123 Main Street, Mumbai, Maharashtra 400001",
                    deliveryTime: "9:00 AM - 11:00 AM",
                    items: [
                        { name: "Amul Milk", unit: "500ml", quantity: 10, price: 30, total: 300 },
                        { name: "Lays Chips", unit: "50g", quantity: 20, price: 20, total: 400 },
                        { name: "Parle-G Biscuits", unit: "100g", quantity: 30, price: 10, total: 300 },
                        { name: "Colgate Toothpaste", unit: "100g", quantity: 5, price: 55, total: 275 }
                    ]
                }
            ]
        };

        // Try to get from localStorage first (for demo purposes)
        const savedOrders = localStorage.getItem('shop_orders');
        const orders = savedOrders ? JSON.parse(savedOrders) : dummyData.orders;

        const order = orders.find(o => o.orderNumber === orderNumber);

        if (!order) {
            showError('Order not found');
            return;
        }

        renderOrder(order);
    }

    function renderOrder(order) {
        const container = document.getElementById('orderDetailsContainer');

        // Add shop owner info from localStorage or default
        const shopOwnerData = JSON.parse(localStorage.getItem('shop_owner')) || {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            address: "123 Main Street, Mumbai, Maharashtra 400001",
            phone: "+91 9876543210"
        };

        container.innerHTML = `
            <!-- Order Header -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Order #${order.orderNumber}</h2>
                        <p class="text-sm text-slate-500">${order.date} • ${order.time}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusColor(order.status)}">
                        ${order.status}
                    </span>
                </div>
                
                <!-- Customer Info -->
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-sm font-medium text-slate-900">${shopOwnerData.shopName}</p>
                    <p class="text-xs text-slate-500">${shopOwnerData.name} • ${shopOwnerData.phone}</p>
                </div>
            </div>

            <!-- Items Section -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-slate-900">
                        Items (${order.items.length})
                    </h3>
                    <button onclick="generateInvoice('${order.orderNumber}')" 
                            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                        <iconify-icon icon="lucide:printer" width="12"></iconify-icon>
                        Print Invoice
                    </button>
                </div>
                
                <div class="space-y-4">
                    ${order.items.map(item => `
                        <div class="flex justify-between items-center py-3 border-b border-slate-100 last:border-0">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                                    <iconify-icon icon="lucide:package" width="16" class="text-slate-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">${item.name}</p>
                                    <p class="text-xs text-slate-500">${item.unit}</p>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-xs text-slate-600">Qty: ${item.quantity}</span>
                                        <span class="text-xs text-slate-600">₹${item.price}/unit</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-semibold text-slate-900">₹${item.total}</span>
                        </div>
                    `).join('')}
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
                <h3 class="text-sm font-semibold text-slate-900 mb-2">Price Details</h3>
                
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
                
                ${order.paymentMethod ? `
                    <div class="pt-3 border-t border-slate-200">
                        <p class="text-xs text-slate-500">Payment Method</p>
                        <p class="text-sm font-medium text-slate-900">${order.paymentMethod}</p>
                    </div>
                ` : ''}
            </div>

            <!-- Delivery Information -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Delivery Information</h3>
                
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center">
                            <iconify-icon icon="lucide:map-pin" width="16" class="text-indigo-600"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-slate-900">Delivery Address</p>
                            <p class="text-sm text-slate-600 mt-1">${order.address || shopOwnerData.address}</p>
                        </div>
                    </div>
                    
                    ${order.deliveryTime ? `
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-amber-50 flex items-center justify-center">
                                <iconify-icon icon="lucide:clock" width="16" class="text-amber-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">Delivery Time</p>
                                <p class="text-sm text-slate-600 mt-1">${order.deliveryTime}</p>
                            </div>
                        </div>
                    ` : ''}
                    
                    ${order.deliveryPerson ? `
                        <div class="flex items-start gap-3">
                            <div class="h-10 w-10 rounded-full bg-emerald-50 flex items-center justify-center">
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
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <iconify-icon icon="lucide:info" width="20" class="text-amber-600 mt-0.5"></iconify-icon>
                        <div>
                            <h4 class="text-sm font-semibold text-amber-900">Order Status</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Your order is currently being processed. You'll receive updates on delivery timing.
                            </p>
                            <button onclick="cancelOrder('${order.orderNumber}')" 
                                    class="mt-3 px-4 py-2 text-sm font-medium text-rose-600 hover:text-rose-700 
                                           hover:bg-rose-50 rounded-lg border border-rose-200 transition-colors">
                                Cancel Order
                            </button>
                        </div>
                    </div>
                </div>
            ` : ''}
        `;
    }

    function getStatusColor(status) {
        const colors = {
            'Pending': 'bg-amber-50 text-amber-700 border border-amber-200',
            'Processing': 'bg-blue-50 text-blue-700 border border-blue-200',
            'Dispatched': 'bg-indigo-50 text-indigo-700 border border-indigo-200',
            'Delivered': 'bg-emerald-50 text-emerald-700 border border-emerald-200',
            'Cancelled': 'bg-rose-50 text-rose-700 border border-rose-200'
        };
        return colors[status] || 'bg-slate-50 text-slate-700 border border-slate-200';
    }

    function generateInvoice(orderNumber) {
        window.location.href = '{{ route('shop-owner.invoices.show', ['id' => 'INVOICE_ID']) }}'.replace('INVOICE_ID', orderNumber);
    }

    function cancelOrder(orderNumber) {
        if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
            // In real app, this would be an API call
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
        document.getElementById('orderDetailsContainer').innerHTML = `
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