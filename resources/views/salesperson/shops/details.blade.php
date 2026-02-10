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
                <h1 class="text-xl font-semibold text-slate-900" id="shopName">{{ $shop->name }}</h1>
                <div class="flex items-center gap-3 mt-2">
                    <span id="shopStatus" class="status-badge {{ $shop->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ ucfirst($shop->status) }}
                    </span>
                    <span class="text-sm text-slate-500" id="shopType">Shop #{{ $shop->id }}</span>
                </div>
            </div>
            <a href="{{ route('salesperson.orders.create', ['shop_id' => $shop->id]) }}" 
                    @if($is_off_hours ?? false) 
                        onclick="event.preventDefault()" 
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg opacity-50 cursor-not-allowed flex items-center gap-2"
                    @else
                        class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2"
                    @endif>
                <iconify-icon icon="lucide:plus-circle" width="16"></iconify-icon>
                New Order
            </a>
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
                        <h3 class="text-sm font-semibold text-slate-900" id="ownerName">{{ $shop->user->name ?? $shop->owner_name }}</h3>
                        <p class="text-sm text-slate-500" id="ownerPhone">{{ $shop->phone }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <iconify-icon icon="lucide:map-pin" width="16" class="text-slate-400"></iconify-icon>
                        <span id="shopArea">{{ $shop->bit->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-slate-600">
                        <iconify-icon icon="lucide:home" width="16" class="text-slate-400"></iconify-icon>
                        <span id="shopAddress">{{ $shop->address }}</span>
                    </div>
                </div>
            </div>

            <!-- Shop Stats -->
            <div class="info-card">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Shop Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="totalOrders">{{ $stats['total_orders'] }}</p>
                        <p class="text-xs text-slate-500">Total Orders</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="avgOrderValue">₹{{ number_format($stats['avg_order_value'], 2) }}</p>
                        <p class="text-xs text-slate-500">Avg Order Value</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="lastOrder">
                            {{ $stats['last_order'] ? $stats['last_order']->created_at->format('M d') : '--' }}
                        </p>
                        <p class="text-xs text-slate-500">Last Order</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-semibold text-slate-900" id="totalRevenue">₹{{ number_format($stats['total_revenue'], 2) }}</p>
                        <p class="text-xs text-slate-500">Total Revenue</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs content simplified for direct blade usage -->
        <div class="border-b border-slate-200">
            <div class="flex space-x-6">
                <button onclick="switchTab('orders')" class="tab-button active" id="ordersTab">
                    <iconify-icon icon="lucide:package" width="16" class="mr-2"></iconify-icon>
                    Recent Orders
                </button>
                <button onclick="switchTab('products')" class="tab-button" id="productsTab">
                    <iconify-icon icon="lucide:shopping-cart" width="16" class="mr-2"></iconify-icon>
                    Top Products
                </button>
            </div>
        </div>

        <!-- Orders Tab Content -->
        <div id="ordersTabContent" class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900">Recent Orders</h3>
            </div>
            
            <div class="bg-white rounded-lg border border-slate-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody id="ordersTableBody" class="divide-y divide-slate-200">
                            @forelse($shop->orders as $order)
                                <tr class="order-row">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">#{{ $order->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $order->created_at->format('M d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="order-status @php
                                            echo match(strtolower($order->status)) {
                                                'pending' => 'status-pending',
                                                'processing' => 'status-processing',
                                                'delivered' => 'status-delivered',
                                                'cancelled' => 'status-cancelled',
                                                default => 'status-pending'
                                            };
                                        @endphp">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                                        <a href="{{ route('salesperson.orders.review', $order->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                        <iconify-icon icon="lucide:package" width="32" class="mx-auto mb-3 opacity-50"></iconify-icon>
                                        <p class="text-sm font-medium">No orders yet</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Products Tab Content -->
        <div id="productsTabContent" class="hidden space-y-4">
            <h3 class="text-sm font-semibold text-slate-900">Frequently Ordered Products</h3>
            <div id="productsList" class="space-y-3">
                @forelse($popularProducts as $product)
                    <div class="flex items-center justify-between p-3 rounded-lg border border-slate-200 bg-white">
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-slate-900">{{ $product->name }}</h4>
                            <p class="text-xs text-slate-500">Ordered {{ $product->order_frequency }} times</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-slate-900">{{ number_format($product->total_quantity) }} units</p>
                            <p class="text-xs text-slate-500">Total volume</p>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        <p class="text-sm">No product data available</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-slate-200">
            <a href="tel:{{ $shop->phone }}" 
                    class="p-4 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center border border-green-100">
                    <iconify-icon icon="lucide:phone" width="18" class="text-green-600"></iconify-icon>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-slate-900">Call Owner</p>
                    <p class="text-xs text-slate-500">{{ $shop->phone }}</p>
                </div>
            </a>
            
            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shop->address) }}" target="_blank"
                    class="p-4 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center border border-blue-100">
                    <iconify-icon icon="lucide:map-pin" width="18" class="text-blue-600"></iconify-icon>
                </div>
                <div class="text-left">
                    <p class="text-sm font-medium text-slate-900">Get Directions</p>
                    <p class="text-xs text-slate-500">Open in Maps</p>
                </div>
            </a>
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
    function switchTab(tabName) {
        // Update tab buttons
        document.getElementById('ordersTab').classList.remove('active');
        document.getElementById('productsTab').classList.remove('active');
        
        document.getElementById(`${tabName}Tab`).classList.add('active');
        
        // Show/hide tab content
        document.getElementById('ordersTabContent').classList.add('hidden');
        document.getElementById('productsTabContent').classList.add('hidden');
        
        document.getElementById(`${tabName}TabContent`).classList.remove('hidden');
    }
</script>
@endsection