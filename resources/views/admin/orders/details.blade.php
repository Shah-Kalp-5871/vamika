@extends('layouts.admin')

@section('title', 'Order Details')

@section('styles')
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

    /* Status badges */
    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-with-order {
        background-color: #dcfce7;
        color: #166534;
    }

    .status-no-order {
        background-color: #fef3c7;
        color: #92400e;
    }

    /* Amount styling */
    .order-amount {
        font-size: 32px;
        font-weight: 800;
        line-height: 1;
    }

    .amount-positive {
        color: #166534;
    }

    .amount-zero {
        color: #6b7280;
    }

    /* Item card styling */
    .item-card {
        transition: all 0.2s ease;
    }

    .item-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    /* Divider */
    .custom-divider {
        height: 1px;
        background: linear-gradient(to right, transparent, #e5e7eb, transparent);
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

@section('content')
@php
    // Using session or request data - in real app this would come from controller
    $order = session('order_details') ?? [
        'salesperson_id' => request('salesperson_id'),
        'salesperson_name' => 'Rajesh Kumar',
        'outlet_name' => 'Mohan Kirana Store',
        'visit_data' => [
            'id' => 'VIS001',
            'orderId' => 'ORD00123',
            'orderAmount' => 1720,
            'time' => '10:30 AM',
            'status' => 'with-order',
            'items' => [
                [
                    'name' => 'Aashirvaad Atta',
                    'quantity' => 2,
                    'price' => 420,
                    'total' => 840
                ],
                [
                    'name' => 'Fortune Oil',
                    'quantity' => 3,
                    'price' => 210,
                    'total' => 630
                ],
                [
                    'name' => 'Maggi Noodles',
                    'quantity' => 5,
                    'price' => 70,
                    'total' => 350
                ]
            ]
        ]
    ];

    $salespersonId = $order['salesperson_id'] ?? '';
    $salespersonName = $order['salesperson_name'] ?? '';
    $outletName = $order['outlet_name'] ?? '';
    $visitData = $order['visit_data'] ?? [];

    $visitId = $visitData['id'] ?? '';
    $orderId = $visitData['orderId'] ?? null;
    $orderAmount = $visitData['orderAmount'] ?? 0;
    $visitTime = $visitData['time'] ?? '';
    $status = $visitData['status'] ?? '';
    $items = $visitData['items'] ?? [];
@endphp

<!-- Order Header -->
<div class="bg-white shadow-sm">
    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $outletName }}</h1>
                <p class="text-sm text-gray-600">
                    Visit by: {{ $salespersonName }}
                </p>
            </div>
            <div class="text-right">
                <span class="status-badge {{ $status === 'with-order' ? 'status-with-order' : 'status-no-order' }}">
                    {{ $status === 'with-order' ? 'With Order' : 'No Order' }}
                </span>
            </div>
        </div>

        <!-- Order ID -->
        @if($orderId)
        <div class="flex items-center gap-2 mb-3">
            <iconify-icon icon="ph:receipt" width="18" class="text-gray-500"></iconify-icon>
            <span class="text-sm font-medium text-gray-700">Order ID: {{ $orderId }}</span>
        </div>
        @endif

        <!-- Visit Time -->
        <div class="flex items-center gap-2">
            <iconify-icon icon="ph:clock" width="18" class="text-gray-500"></iconify-icon>
            <span class="text-sm text-gray-600">Visit Time: {{ $visitTime }}</span>
        </div>
    </div>
</div>

<!-- Order Amount -->
<div class="p-4">
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl p-6 text-center shadow-sm animate-fade-in">
        <p class="text-sm text-gray-600 mb-2">Total Order Value</p>
        <p class="order-amount {{ $orderAmount > 0 ? 'amount-positive' : 'amount-zero' }}">
            ₹{{ number_format($orderAmount, 0) }}
        </p>
        <p class="text-sm text-gray-500 mt-2">
            {{ $orderAmount > 0 ? 'Payment collected' : 'No order placed' }}
        </p>
    </div>
</div>

<!-- Order Items (Only show if there are items) -->
@if(!empty($items))
<div class="p-4">
    <div class="bg-white rounded-xl shadow-sm p-4 mb-4 animate-fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-900">Order Items</h3>
            <span class="text-sm text-gray-500">{{ count($items) }} items</span>
        </div>
        
        <div class="space-y-3">
            @foreach($items as $index => $item)
            <div class="item-card bg-gray-50 rounded-xl p-4">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-6 h-6 rounded-full bg-blue-100 flex items-center justify-center text-xs font-medium text-blue-700">
                                {{ $index + 1 }}
                            </span>
                            <h4 class="font-medium text-gray-900">{{ $item['name'] }}</h4>
                        </div>
                        <div class="grid grid-cols-2 gap-4 mt-3">
                            <div>
                                <p class="text-xs text-gray-500">Quantity</p>
                                <p class="text-sm font-medium text-gray-900">{{ $item['quantity'] }} units</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Unit Price</p>
                                <p class="text-sm font-medium text-gray-900">₹{{ number_format($item['price'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="custom-divider my-3"></div>
                
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Item Total</span>
                    <span class="text-lg font-bold text-gray-900">₹{{ number_format($item['total'], 2) }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Action Buttons -->
@if($status === 'with-order')
<div class="p-4 pb-24">
    <div class="bg-white rounded-xl shadow-sm p-4 animate-fade-in" style="animation-delay: 0.3s">
        <h3 class="font-semibold text-gray-900 mb-4">Order Actions</h3>
        <div class="grid grid-cols-2 gap-3">
            <button onclick="printOrder()" 
                    class="py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 flex items-center justify-center gap-2">
                <iconify-icon icon="ph:printer" width="18"></iconify-icon>
                Print Invoice
            </button>
            <button onclick="shareOrder()" 
                    class="py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 flex items-center justify-center gap-2">
                <iconify-icon icon="ph:share-network" width="18"></iconify-icon>
                Share
            </button>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
    // Action Functions
    function viewOutletDetails() {
        const outletName = "{{ addslashes($outletName) }}";
        Swal.fire({
            icon: 'info',
            title: 'Outlet Details',
            text: `Viewing details for outlet: ${outletName}`
        });
    }

    function printOrder() {
        window.print();
    }

    function shareOrder() {
        const orderId = "{{ $orderId ? addslashes($orderId) : 'N/A' }}";
        const outletName = "{{ addslashes($outletName) }}";
        const amount = "{{ $orderAmount }}";
        
        const shareText = `Order ${orderId} - ${outletName}\nAmount: ₹${amount}\n\nShared from Vamika Admin Dashboard`;
        
        if (navigator.share) {
            navigator.share({
                title: `Order ${orderId}`,
                text: shareText
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Share Not Supported',
                text: 'Share feature not supported in this browser. You can manually copy the order info.'
            });
        }
    }
</script>
@endsection