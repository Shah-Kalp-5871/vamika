@extends('layouts.salesperson')

@section('content')
<style>
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-active {
        background-color: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }
    
    .status-inactive {
        background-color: #F3F4F6;
        color: #374151;
        border: 1px solid #D1D5DB;
    }
    
    .info-card {
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 0.75rem;
        padding: 1.5rem;
        transition: all 0.2s;
    }
    
    .info-card:hover {
        border-color: #D1D5DB;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
    
    .tab-button {
        padding: 0.75rem 1rem;
        border-bottom: 2px solid transparent;
        color: #6B7280;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .tab-button.active {
        color: #4F46E5;
        border-bottom-color: #4F46E5;
    }
    
    .order-row {
        transition: all 0.2s;
        border-bottom: 1px solid #F3F4F6;
    }
    
    .order-row:hover {
        background-color: #F9FAFB;
    }
    
    .order-status {
        padding: 0.25rem 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .status-pending {
        background-color: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }
    
    .status-processing {
        background-color: #DBEAFE;
        color: #1E40AF;
        border: 1px solid #BFDBFE;
    }
    
    .status-delivered {
        background-color: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }
    
    .status-cancelled {
        background-color: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }
</style>

<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <div class="p-6 border-b border-slate-100">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-slate-900" id="shopName">Loading...</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span id="shopStatus" class="status-badge status-active">Active</span>
                    <span class="text-sm text-slate-500" id="shopType">Grocery Store</span>
                </div>
            </div>
            <button onclick="createOrder()" 
                    class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <iconify-icon icon="lucide:plus-circle" width="16"></iconify-icon>
                New Order
            </button>
        </div>
    </div>

    <main class="p-6 space-y-6">
        <!-- Shop Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Owner Info -->
            <div class="info-card">
                <div class="flex items-center gap-3 mb-4">
                    <div class="h-12 w-12 rounded-full bg-indigo-50 flex items-center justify-center border border-indigo-100">
                        <iconify-icon icon="lucide:user" width="20" class="text-indigo-600"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900" id="ownerName">Owner Name</h3>
                        <p class="text-sm text-slate-500" id="ownerPhone">Phone Number</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <iconify-icon icon="lucide:map-pin" width="16" class="text-slate-400"></iconify-icon>
                        <span id="shopArea">Area Name</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <iconify-icon icon="lucide:home" width="16" class="text-slate-400"></iconify-icon>
                        <span id="shopAddress">Shop Address</span>
                    </div>
                </div>
            </div>

            <!-- Shop Stats -->
            <div class="info-card">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Shop Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="totalOrders">0</p>
                        <p class="text-xs text-slate-500">Total Orders</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="avgOrderValue">₹0</p>
                        <p class="text-xs text-slate-500">Avg Order Value</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="lastOrder">--</p>
                        <p class="text-xs text-slate-500">Last Order</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="totalRevenue">₹0</p>
                        <p class="text-xs text-slate-500">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="border-b border-slate-200">
            <div class="flex space-x-6">
                <button onclick="switchTab('orders')" 
                        class="tab-button active" id="ordersTab">
                    <iconify-icon icon="lucide:package" width="16" class="mr-2"></iconify-icon>
                    Recent Orders
                </button>
                <button onclick="switchTab('products')" 
                        class="tab-button" id="productsTab">
                    <iconify-icon icon="lucide:shopping-cart" width="16" class="mr-2"></iconify-icon>
                    Popular Products
                </button>
                <button onclick="switchTab('notes')" 
                        class="tab-button" id="notesTab">
                    <iconify-icon icon="lucide:clipboard" width="16" class="mr-2"></iconify-icon>
                    Notes
                </button>
            </div>
        </div>

        <!-- Orders Tab Content -->
        <div id="ordersTabContent" class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Recent Orders</h3>
                <a href="{{ route('salesperson.orders.create') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    View All Orders
                </a>
            </div>
            
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Items</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody" class="divide-y divide-slate-200">
                            <!-- Orders will be loaded here -->
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Products Tab Content -->
        <div id="productsTabContent" class="hidden space-y-4">
            <h3 class="text-sm font-semibold text-slate-900">Frequently Ordered Products</h3>
            <div id="productsList" class="space-y-3">
                <!-- Products will be loaded here -->
                <div class="p-8 text-center">
                    <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Notes Tab Content -->
        <div id="notesTabContent" class="hidden space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Shop Notes</h3>
                <button onclick="addNote()" class="px-3 py-1.5 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                    Add Note
                </button>
            </div>
            <div id="notesList" class="space-y-3">
                <!-- Notes will be loaded here -->
                <div class="p-4 rounded-lg border border-slate-200 bg-slate-50">
                    <p class="text-sm text-slate-500">No notes yet</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-6 border-t border-slate-200">
            <button onclick="callOwner()" 
                    class="p-4 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center border border-green-100">
                    <iconify-icon icon="lucide:phone" width="18" class="text-green-600"></iconify-icon>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-slate-900">Call Owner</p>
                    <p class="text-xs text-slate-500">Make a phone call</p>
                </div>
            </button>
            
            <button onclick="viewLocation()" 
                    class="p-4 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center border border-blue-100">
                    <iconify-icon icon="lucide:map-pin" width="18" class="text-blue-600"></iconify-icon>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-slate-900">View Location</p>
                    <p class="text-xs text-slate-500">Open in maps</p>
                </div>
            </button>
            
            <button onclick="scheduleVisit()" 
                    class="p-4 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-purple-50 flex items-center justify-center border border-purple-100">
                    <iconify-icon icon="lucide:calendar" width="18" class="text-purple-600"></iconify-icon>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-slate-900">Schedule Visit</p>
                    <p class="text-xs text-slate-500">Plan next visit</p>
                </div>
            </button>
        </div>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Shop Details',
        'showBack' => true,
        'backUrl' => route('salesperson.shops.index'),
        'showBottomNav' => true
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Dummy data for shop details
    const shopData = {
        id: 1,
        name: "Kirana King",
        owner: "Rajesh Kumar",
        phone: "9876543210",
        area: "South Mumbai",
        address: "123 Main Street, South Mumbai, Mumbai - 400001",
        type: "grocery",
        status: "active",
        totalOrders: 24,
        avgOrderValue: 1850,
        lastOrder: "2024-01-15",
        totalRevenue: 44400,
        orders: [
            { id: 101, date: "2024-01-15", items: 15, amount: 2400, status: "Pending" },
            { id: 102, date: "2024-01-14", items: 8, amount: 1250, status: "Delivered" },
            { id: 103, date: "2024-01-12", items: 24, amount: 5600, status: "Processing" },
            { id: 104, date: "2024-01-10", items: 5, amount: 800, status: "Delivered" },
            { id: 105, date: "2024-01-08", items: 12, amount: 1950, status: "Delivered" }
        ],
        popularProducts: [
            { name: "Aata (5kg)", quantity: 45, frequency: "Weekly" },
            { name: "Sugar (5kg)", quantity: 32, frequency: "Weekly" },
            { name: "Rice (10kg)", quantity: 28, frequency: "Bi-weekly" },
            { name: "Cooking Oil (1L)", quantity: 25, frequency: "Weekly" },
            { name: "Tea Powder (500g)", quantity: 22, frequency: "Monthly" }
        ],
        notes: [
            { id: 1, text: "Prefers morning deliveries", date: "2024-01-10", author: "You" },
            { id: 2, text: "Interested in bulk discounts for rice", date: "2024-01-05", author: "You" }
        ]
    };

    function loadShopDetails() {
        // Update basic info
        document.getElementById('shopName').textContent = shopData.name;
        document.getElementById('ownerName').textContent = shopData.owner;
        document.getElementById('ownerPhone').textContent = shopData.phone;
        document.getElementById('shopArea').textContent = shopData.area;
        document.getElementById('shopAddress').textContent = shopData.address;
        document.getElementById('shopType').textContent = shopData.type.charAt(0).toUpperCase() + shopData.type.slice(1) + " Store";
        
        // Update status badge
        const statusBadge = document.getElementById('shopStatus');
        statusBadge.textContent = shopData.status.charAt(0).toUpperCase() + shopData.status.slice(1);
        statusBadge.className = shopData.status === 'active' ? 
            'status-badge status-active' : 'status-badge status-inactive';
        
        // Update stats
        document.getElementById('totalOrders').textContent = shopData.totalOrders;
        document.getElementById('avgOrderValue').textContent = '₹' + shopData.avgOrderValue.toLocaleString();
        document.getElementById('lastOrder').textContent = formatDate(shopData.lastOrder);
        document.getElementById('totalRevenue').textContent = '₹' + shopData.totalRevenue.toLocaleString();
        
        // Load orders
        loadOrders();
        // Load popular products
        loadPopularProducts();
        // Load notes
        loadNotes();
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    }

    function getStatusClass(status) {
        const statusMap = {
            'pending': 'status-pending',
            'processing': 'status-processing',
            'delivered': 'status-delivered',
            'cancelled': 'status-cancelled'
        };
        return statusMap[status.toLowerCase()] || 'status-pending';
    }

    function loadOrders() {
        const tableBody = document.getElementById('ordersTableBody');
        tableBody.innerHTML = '';
        
        if (shopData.orders.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                        <iconify-icon icon="lucide:package" width="32" class="mx-auto mb-3 opacity-50"></iconify-icon>
                        <p class="text-sm font-medium">No orders yet</p>
                    </td>
                </tr>
            `;
            return;
        }
        
        shopData.orders.forEach(order => {
            const row = document.createElement('tr');
            row.className = 'order-row';
            
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                    #${order.id}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    ${formatDate(order.date)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    ${order.items} items
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                    ₹${order.amount.toLocaleString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="order-status ${getStatusClass(order.status)}">
                        ${order.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                    <button onclick="viewOrder(${order.id})" class="text-indigo-600 hover:text-indigo-900">
                        View
                    </button>
                </td>
            `;
            tableBody.appendChild(row);
        });
    }

    function loadPopularProducts() {
        const productsList = document.getElementById('productsList');
        productsList.innerHTML = '';
        
        shopData.popularProducts.forEach(product => {
            const productDiv = document.createElement('div');
            productDiv.className = 'flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-white';
            
            productDiv.innerHTML = `
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-slate-900">${product.name}</h4>
                    <p class="text-xs text-slate-500">Ordered ${product.frequency.toLowerCase()}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-slate-900">${product.quantity} units</p>
                    <p class="text-xs text-slate-500">Total ordered</p>
                </div>
            `;
            productsList.appendChild(productDiv);
        });
    }

    function loadNotes() {
        const notesList = document.getElementById('notesList');
        notesList.innerHTML = '';
        
        if (shopData.notes.length === 0) {
            notesList.innerHTML = `
                <div class="p-4 rounded-lg border border-slate-200 bg-slate-50">
                    <p class="text-sm text-slate-500">No notes yet</p>
                </div>
            `;
            return;
        }
        
        shopData.notes.forEach(note => {
            const noteDiv = document.createElement('div');
            noteDiv.className = 'p-3 rounded-lg border border-slate-200 bg-white';
            
            noteDiv.innerHTML = `
                <p class="text-sm text-slate-900 mb-2">${note.text}</p>
                <div class="flex items-center justify-between text-xs text-slate-500">
                    <span>${formatDate(note.date)}</span>
                    <span>Added by ${note.author}</span>
                </div>
            `;
            notesList.appendChild(noteDiv);
        });
    }

    function switchTab(tabName) {
        // Update tab buttons
        document.getElementById('ordersTab').classList.remove('active');
        document.getElementById('productsTab').classList.remove('active');
        document.getElementById('notesTab').classList.remove('active');
        
        document.getElementById(`${tabName}Tab`).classList.add('active');
        
        // Show/hide tab content
        document.getElementById('ordersTabContent').classList.add('hidden');
        document.getElementById('productsTabContent').classList.add('hidden');
        document.getElementById('notesTabContent').classList.add('hidden');
        
        document.getElementById(`${tabName}TabContent`).classList.remove('hidden');
    }

    function createOrder() {
        // Store shop data for order creation
        localStorage.setItem('selectedShop', JSON.stringify({
            id: shopData.id,
            name: shopData.name,
            owner: shopData.owner,
            phone: shopData.phone,
            area: shopData.area,
            address: shopData.address
        }));
        
        window.location.href = "{{ route('salesperson.orders.create') }}";
    }

    function viewOrder(orderId) {
        // In a real app, this would redirect to order details
        alert(`Viewing order #${orderId} - This would show order details in a real application`);
    }

    function callOwner() {
        window.open(`tel:${shopData.phone}`, '_blank');
    }

    function viewLocation() {
        // In a real app, this would open maps with the shop location
        const address = encodeURIComponent(shopData.address);
        window.open(`https://maps.google.com/?q=${address}`, '_blank');
    }

    function scheduleVisit() {
        // In a real app, this would open a calendar/scheduling interface
        alert('Schedule visit feature - This would open a calendar in a real application');
    }

    function addNote() {
        const noteText = prompt('Enter your note:');
        if (noteText && noteText.trim() !== '') {
            // Add note to local data
            const newNote = {
                id: shopData.notes.length + 1,
                text: noteText.trim(),
                date: new Date().toISOString().split('T')[0],
                author: 'You'
            };
            shopData.notes.unshift(newNote);
            loadNotes();
            
            // In a real app, you would save to backend here
            alert('Note added successfully!');
        }
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        loadShopDetails();
    });
</script>
@endsection