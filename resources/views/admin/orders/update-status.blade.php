<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order Status</title>
    
    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    
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

        /* Form styles */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #374151;
        }

        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #D1D5DB;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background-color: white;
            transition: all 0.2s ease;
        }

        .form-select:focus {
            outline: none;
            border-color: #6366F1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
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
        }

        .btn-secondary:hover {
            background: #F9FAFB;
        }

        /* Status indicator */
        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }

        .status-active {
            background-color: #F0F9FF;
            border: 1px solid #BAE6FD;
            color: #0369A1;
        }

        /* Order info card */
        .order-info-card {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .info-label {
            color: #64748B;
        }

        .info-value {
            color: #1E293B;
            font-weight: 500;
        }
    </style>
</head>
<body class="text-slate-600">

    <!-- Main Content -->
    <div class="main-content">

        <!-- Header -->
        @include('layouts.partials.admin.header')

        <main class="p-6">
            <!-- Current Order Info -->
            <div class="order-info-card">
                <div class="info-row">
                    <span class="info-label">Order Number:</span>
                    <span class="info-value" id="currentOrderNumber">Loading...</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Shop Name:</span>
                    <span class="info-value" id="currentShopName">Loading...</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Current Status:</span>
                    <span class="info-value" id="currentStatus">Loading...</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Amount:</span>
                    <span class="info-value" id="currentAmount">Loading...</span>
                </div>
            </div>

            <!-- Status Update Form -->
            <form id="statusForm" class="space-y-6" onsubmit="handleSubmit(event)">
                @csrf
                <input type="hidden" id="orderId" value="{{ request()->id ?? '' }}">

                <div class="form-group">
                    <label class="form-label" for="statusSelect">New Status *</label>
                    <select id="statusSelect" class="form-select" required>
                        <option value="">Select new status</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="processing">Processing</option>
                        <option value="dispatched">Dispatched</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="statusNotes">Update Notes (Optional)</label>
                    <textarea id="statusNotes" class="form-input" rows="4"
                        placeholder="Add any notes about this status update (e.g., reason for change, delivery instructions, etc.)"></textarea>
                </div>

                <!-- Status Descriptions -->
                <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                    <h3 class="font-semibold text-slate-900 mb-3">Status Descriptions</h3>
                    <div class="space-y-2 text-sm text-slate-600">
                        <div class="flex items-start gap-2">
                            <iconify-icon icon="lucide:clock" width="14" class="text-amber-500 mt-0.5"></iconify-icon>
                            <span><strong>Pending:</strong> Order received but not yet processed</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <iconify-icon icon="lucide:check" width="14" class="text-blue-500 mt-0.5"></iconify-icon>
                            <span><strong>Confirmed:</strong> Order verified and confirmed</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <iconify-icon icon="lucide:settings" width="14"
                                class="text-purple-500 mt-0.5"></iconify-icon>
                            <span><strong>Processing:</strong> Order is being prepared for dispatch</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <iconify-icon icon="lucide:truck" width="14" class="text-indigo-500 mt-0.5"></iconify-icon>
                            <span><strong>Dispatched:</strong> Order is out for delivery</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <iconify-icon icon="lucide:check-circle" width="14"
                                class="text-emerald-500 mt-0.5"></iconify-icon>
                            <span><strong>Delivered:</strong> Order successfully delivered to customer</span>
                        </div>
                        <div class="flex items-start gap-2">
                            <iconify-icon icon="lucide:x-circle" width="14" class="text-rose-500 mt-0.5"></iconify-icon>
                            <span><strong>Cancelled:</strong> Order has been cancelled</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4 pt-6 border-t border-slate-200">
                    <a href="{{ route('admin.orders.index') }}" class="btn-secondary no-underline">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Update Status
                    </button>
                </div>
            </form>
        </main>

    </div>

    <script>
        // Get order ID from URL
        const urlParams = new URLSearchParams(window.location.search);
        const orderId = parseInt(urlParams.get('id')) || parseInt(document.getElementById('orderId').value);

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
                loadOrderInfo(orderId);
            } else {
                document.getElementById('currentOrderNumber').textContent = 'Order Not Found';
            }
        });

        function loadOrderInfo(id) {
            const order = sampleOrders.find(o => o.id === id);

            if (!order) {
                document.getElementById('currentOrderNumber').textContent = 'Order Not Found';
                return;
            }

            // Update order info
            document.getElementById('currentOrderNumber').textContent = order.orderNumber;
            document.getElementById('currentShopName').textContent = order.shop;
            document.getElementById('currentAmount').textContent = `₹${order.total}`;

            // Set current status with proper formatting
            const statusMap = {
                pending: { text: 'Pending', icon: 'clock', color: 'text-amber-600' },
                confirmed: { text: 'Confirmed', icon: 'check', color: 'text-blue-600' },
                processing: { text: 'Processing', icon: 'settings', color: 'text-purple-600' },
                dispatched: { text: 'Dispatched', icon: 'truck', color: 'text-indigo-600' },
                delivered: { text: 'Delivered', icon: 'check-circle', color: 'text-emerald-600' },
                cancelled: { text: 'Cancelled', icon: 'x-circle', color: 'text-rose-600' }
            };

            const status = statusMap[order.status] || statusMap.pending;
            const statusElement = document.getElementById('currentStatus');
            statusElement.innerHTML = `
                <span class="${status.color} inline-flex items-center gap-1">
                    <iconify-icon icon="lucide:${status.icon}" width="14"></iconify-icon>
                    ${status.text}
                </span>
            `;

            // Set current status in dropdown
            document.getElementById('statusSelect').value = order.status;
        }

        function handleSubmit(event) {
            event.preventDefault();

            const orderId = document.getElementById('orderId').value;
            const newStatus = document.getElementById('statusSelect').value;
            const notes = document.getElementById('statusNotes').value;

            if (!newStatus) {
                alert('Please select a new status');
                return;
            }

            // In real app, you would send this data to your backend
            const updateData = {
                orderId,
                newStatus,
                notes,
                updatedAt: new Date().toISOString(),
                updatedBy: 'Admin' // In real app, get from session
            };

            console.log('Updating order status:', updateData);

            // Show success message
            alert(`Order status updated to ${newStatus}`);

            // Redirect back to order details page
            window.location.href = `{{ route('admin.orders.show', '') }}/${orderId}`;
        }
    </script>

    @include('layouts.partials.admin.footer')
</body>
</html>