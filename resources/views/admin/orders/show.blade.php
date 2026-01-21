@extends('layouts.admin')

@section('title', 'Order Details')

@section('styles')
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
        padding-bottom: 80px;
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

    /* Main content container */
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

    /* Back button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        color: #64748B;
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .back-btn:hover {
        background: #F8FAFC;
        color: #475569;
    }

    /* Status badge */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Info card */
    .info-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        color: #64748B;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1E293B;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        padding: 0.75rem 2rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    .btn-danger {
        background: #EF4444;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: #DC2626;
    }

    .btn-success {
        background: #10B981;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-success:hover {
        background: #059669;
    }

    /* Items list */
    .item-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 1px solid #E5E7EB;
        border-radius: 0.5rem;
        background: white;
        transition: all 0.2s ease;
    }

    .item-card:hover {
        border-color: #6366F1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Invoice styles */
    .invoice-header {
        text-align: center;
        padding: 2rem;
        border-bottom: 2px solid #E5E7EB;
    }

    .invoice-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1E293B;
    }

    .invoice-number {
        font-size: 1.25rem;
        color: #6366F1;
        margin-top: 0.5rem;
    }

    /* Print styles */
    @media print {
        body {
            background: white;
            padding: 0;
        }

        .main-content {
            max-width: 100%;
            margin: 0;
            box-shadow: none;
            border: none;
            border-radius: 0;
        }

        .back-btn,
        .action-buttons,
        .no-print {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        .info-card {
            border: 1px solid #000;
            page-break-inside: avoid;
        }

        .item-card {
            border: 1px solid #000;
            page-break-inside: avoid;
        }
    }

    .print-only {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Vamika Enterprise" 
                     class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1">
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="back-btn mb-1 inline-flex">
                        <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                        Back to Orders
                    </a>
                    <h1 class="text-xl font-semibold text-slate-900 tracking-tight" id="orderNumber">
                        Loading...
                    </h1>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Order details and invoice
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span id="statusBadge" class="status-badge">Loading...</span>
                <span id="paymentBadge" class="status-badge">Loading...</span>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-6">
        <!-- Print Header -->
        <div class="invoice-header print-only">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number" id="printOrderNumber">Loading...</div>
            <div class="text-sm text-slate-500 mt-2" id="printDate">Loading...</div>
        </div>

        <!-- Order Information -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="info-label">Order Date & Time</div>
                    <div class="info-value" id="orderDateTime">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Order Type</div>
                    <div class="info-value" id="orderType">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Shop Name</div>
                    <div class="info-value" id="shopName">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Shop Owner</div>
                    <div class="info-value" id="shopOwner">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Area</div>
                    <div class="info-value" id="orderArea">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Salesperson</div>
                    <div class="info-value" id="salesperson">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Payment Method</div>
                    <div class="info-value" id="paymentMethod">Loading...</div>
                </div>
                <div>
                    <div class="info-label">Special Notes</div>
                    <div class="info-value" id="specialNotes">None</div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Items</h3>
            <div id="orderItemsList" class="space-y-3">
                <!-- Items will be loaded here -->
                <div class="text-center py-4">
                    <div class="loading-spinner"></div>
                    <p class="text-sm text-slate-400 mt-2">Loading items...</p>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Subtotal</span>
                    <span class="font-medium text-slate-900" id="subtotal">₹0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Delivery Charges</span>
                    <span class="font-medium text-slate-900" id="delivery">₹0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Discount</span>
                    <span class="font-medium text-emerald-600" id="discount">-₹0</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                    <span class="text-lg font-semibold text-slate-900">Total Amount</span>
                    <span class="text-2xl font-bold text-emerald-600" id="totalAmount">₹0</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary no-print">
                <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                Back to Orders
            </a>
            <a href="{{ route('admin.orders.update-status.form', request('id')) }}" class="btn-primary no-print">
                <iconify-icon icon="lucide:edit" width="16"></iconify-icon>
                Update Status
            </a>
            <button onclick="window.print()" class="btn-success no-print">
                <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
                Print Invoice
            </button>
            <button onclick="cancelOrder({{ request('id') }})" class="btn-danger no-print">
                <iconify-icon icon="lucide:x-circle" width="16"></iconify-icon>
                Cancel Order
            </button>
        </div>

        <!-- Print Footer -->
        <div class="text-center text-sm text-slate-500 print-only mt-8 pt-4 border-t border-slate-200">
            <p>Thank you for your business!</p>
            <p class="mt-1">This is a computer generated invoice. No signature required.</p>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Get order ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const orderId = parseInt(urlParams.get('id'));

    // Sample orders data
    const sampleOrders = [
        {
            id: 1,
            orderNumber: 'ORD00123',
            date: '2024-01-15',
            time: '10:30 AM',
            shop: 'Mohan Kirana Store',
            shopOwner: 'Mohan Singh',
            area: 'Gandhi Nagar',
            salesperson: 'Rajesh Kumar',
            type: 'physical',
            status: 'delivered',
            paymentMethod: 'cash',
            paymentStatus: 'paid',
            items: [
                { name: 'Aashirvaad Atta', unit: '5 kg', quantity: 2, price: 420, total: 840 },
                { name: 'Fortune Oil', unit: '1 liter', quantity: 3, price: 210, total: 630 },
                { name: 'Maggi Noodles', unit: 'Pack of 4', quantity: 5, price: 70, total: 350 }
            ],
            subtotal: 1820,
            delivery: 50,
            discount: 150,
            total: 1720,
            notes: 'Delivery before 12 PM'
        },
        {
            id: 2,
            orderNumber: 'ORD00124',
            date: '2024-01-14',
            time: '02:15 PM',
            shop: 'Gupta General Store',
            shopOwner: 'Ramesh Gupta',
            area: 'Gandhi Nagar',
            salesperson: 'Rajesh Kumar',
            type: 'physical',
            status: 'processing',
            paymentMethod: 'cash',
            paymentStatus: 'pending',
            items: [
                { name: 'Tata Salt', unit: '1 kg', quantity: 10, price: 28, total: 280 },
                { name: 'Parle-G Biscuits', unit: '300 gm', quantity: 8, price: 50, total: 400 },
                { name: 'Surf Excel', unit: '1 kg', quantity: 2, price: 180, total: 360 }
            ],
            subtotal: 1040,
            delivery: 50,
            discount: 0,
            total: 1090,
            notes: ''
        },
        {
            id: 3,
            orderNumber: 'ORD00125',
            date: '2024-01-12',
            time: '11:45 AM',
            shop: 'Bansal Provision',
            shopOwner: 'Sunil Bansal',
            area: 'Shahdara',
            salesperson: 'Suresh Patel',
            type: 'online',
            status: 'confirmed',
            paymentMethod: 'cash',
            paymentStatus: 'pending',
            items: [
                { name: 'Colgate Toothpaste', unit: '200 gm', quantity: 6, price: 85, total: 510 },
                { name: 'Dairy Milk Chocolate', unit: '150 gm', quantity: 12, price: 100, total: 1200 },
                { name: 'Red Label Tea', unit: '500 gm', quantity: 4, price: 300, total: 1200 },
                { name: 'Pepsi', unit: '2.25 liter', quantity: 6, price: 90, total: 540 }
            ],
            subtotal: 3450,
            delivery: 40,
            discount: 200,
            total: 3290,
            notes: 'Need receipt with GST'
        },
        {
            id: 4,
            orderNumber: 'ORD00126',
            date: '2024-01-10',
            time: '03:30 PM',
            shop: 'Sharma Super Mart',
            shopOwner: 'Rajesh Sharma',
            area: 'Preet Vihar',
            salesperson: 'Vikram Singh',
            type: 'physical',
            status: 'pending',
            paymentMethod: 'cash',
            paymentStatus: 'pending',
            items: [
                { name: 'Amul Butter', unit: '100 gm', quantity: 8, price: 60, total: 480 },
                { name: 'Nescafe Coffee', unit: '100 gm', quantity: 3, price: 220, total: 660 },
                { name: 'Lays Chips', unit: '50 gm', quantity: 20, price: 20, total: 400 }
            ],
            subtotal: 1540,
            delivery: 30,
            discount: 0,
            total: 1570,
            notes: ''
        },
        {
            id: 5,
            orderNumber: 'ORD00127',
            date: '2024-01-09',
            time: '09:15 AM',
            shop: 'Verma Departmental Store',
            shopOwner: 'Anil Verma',
            area: 'Laxmi Nagar',
            salesperson: 'Rajesh Kumar',
            type: 'online',
            status: 'dispatched',
            paymentMethod: 'cash',
            paymentStatus: 'pending',
            items: [
                { name: 'Dettol Soap', unit: '125 gm', quantity: 15, price: 45, total: 675 },
                { name: 'Harpic Toilet Cleaner', unit: '500 ml', quantity: 5, price: 120, total: 600 },
                { name: 'Kellogg\'s Cornflakes', unit: '500 gm', quantity: 2, price: 180, total: 360 }
            ],
            subtotal: 1635,
            delivery: 35,
            discount: 100,
            total: 1570,
            notes: 'Deliver to back entrance'
        },
        {
            id: 6,
            orderNumber: 'ORD00128',
            date: '2024-01-08',
            time: '04:45 PM',
            shop: 'City Grocery',
            shopOwner: 'Vijay Mehta',
            area: 'Shahdara',
            salesperson: 'Suresh Patel',
            type: 'physical',
            status: 'cancelled',
            paymentMethod: 'cash',
            paymentStatus: 'cancelled',
            items: [
                { name: 'Basmati Rice', unit: '5 kg', quantity: 3, price: 500, total: 1500 },
                { name: 'Sugar', unit: '5 kg', quantity: 2, price: 250, total: 500 }
            ],
            subtotal: 2000,
            delivery: 40,
            discount: 0,
            total: 2040,
            notes: 'Customer requested cancellation'
        }
    ];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        if (orderId) {
            loadOrderDetails(orderId);
        } else {
            document.getElementById('orderNumber').textContent = 'Order Not Found';
        }

        // Auto print if print parameter is set
        if (urlParams.get('print') === '1') {
            setTimeout(() => {
                window.print();
            }, 500);
        }
    });

    function loadOrderDetails(id) {
        const order = sampleOrders.find(o => o.id === id);

        if (!order) {
            document.getElementById('orderNumber').textContent = 'Order Not Found';
            return;
        }

        // Update header
        document.getElementById('orderNumber').textContent = `Order ${order.orderNumber}`;
        document.getElementById('printOrderNumber').textContent = `Invoice #${order.orderNumber}`;
        document.getElementById('printDate').textContent = `${order.date} ${order.time}`;

        // Status badges
        const statusBadge = document.getElementById('statusBadge');
        const statusConfig = {
            pending: { color: 'bg-amber-50 text-amber-700 border-amber-200', icon: 'clock', text: 'Pending' },
            confirmed: { color: 'bg-blue-50 text-blue-700 border-blue-200', icon: 'check', text: 'Confirmed' },
            processing: { color: 'bg-purple-50 text-purple-700 border-purple-200', icon: 'settings', text: 'Processing' },
            dispatched: { color: 'bg-indigo-50 text-indigo-700 border-indigo-200', icon: 'truck', text: 'Dispatched' },
            delivered: { color: 'bg-emerald-50 text-emerald-700 border-emerald-200', icon: 'check-circle', text: 'Delivered' },
            cancelled: { color: 'bg-rose-50 text-rose-700 border-rose-200', icon: 'x-circle', text: 'Cancelled' }
        };

        const status = statusConfig[order.status] || statusConfig.pending;
        statusBadge.className = `status-badge ${status.color}`;
        statusBadge.innerHTML = `<iconify-icon icon="lucide:${status.icon}" width="12"></iconify-icon> ${status.text}`;

        // Payment badge
        const paymentBadge = document.getElementById('paymentBadge');
        if (order.paymentStatus === 'paid') {
            paymentBadge.className = 'status-badge bg-emerald-50 text-emerald-700 border-emerald-200';
            paymentBadge.innerHTML = '<iconify-icon icon="lucide:check" width="12"></iconify-icon> Paid';
        } else if (order.paymentStatus === 'cancelled') {
            paymentBadge.className = 'status-badge bg-rose-50 text-rose-700 border-rose-200';
            paymentBadge.innerHTML = '<iconify-icon icon="lucide:x-circle" width="12"></iconify-icon> Cancelled';
        } else {
            paymentBadge.className = 'status-badge bg-amber-50 text-amber-700 border-amber-200';
            paymentBadge.innerHTML = '<iconify-icon icon="lucide:clock" width="12"></iconify-icon> Pending';
        }

        // Order information
        document.getElementById('orderDateTime').textContent = `${order.date} • ${order.time}`;
        document.getElementById('orderType').textContent = order.type === 'online' ? 'Online Order' : 'Physical Order';
        document.getElementById('shopName').textContent = order.shop;
        document.getElementById('shopOwner').textContent = order.shopOwner;
        document.getElementById('orderArea').textContent = order.area;
        document.getElementById('salesperson').textContent = order.salesperson;
        document.getElementById('paymentMethod').textContent = order.paymentMethod === 'cash' ? 'Cash on Delivery' : 'Card Payment';
        document.getElementById('specialNotes').textContent = order.notes || 'None';

        // Order items
        const itemsList = document.getElementById('orderItemsList');
        itemsList.innerHTML = order.items.map(item => `
            <div class="item-card">
                <div class="flex-1">
                    <div class="font-medium text-slate-900">${item.name}</div>
                    <div class="text-sm text-slate-500 mt-1">${item.unit}</div>
                </div>
                <div class="text-right">
                    <div class="font-semibold text-slate-900">₹${item.total}</div>
                    <div class="text-sm text-slate-500">${item.quantity} × ₹${item.price}</div>
                </div>
            </div>
        `).join('');

        // Order summary
        document.getElementById('subtotal').textContent = `₹${order.subtotal}`;
        document.getElementById('delivery').textContent = `₹${order.delivery}`;
        document.getElementById('discount').textContent = `-₹${order.discount}`;
        document.getElementById('totalAmount').textContent = `₹${order.total}`;
    }

    function cancelOrder(orderId) {
        if (confirm('Are you sure you want to cancel this order? This action cannot be undone.')) {
            // In real app, you would send cancel request to API
            showNotification('Order cancelled successfully', 'success');
            window.location.href = '{{ route("admin.orders.index") }}';
        }
    }

    function showNotification(message, type) {
        // Simple notification function (you can replace with a proper notification library)
        alert(message);
    }
</script>
@endsection