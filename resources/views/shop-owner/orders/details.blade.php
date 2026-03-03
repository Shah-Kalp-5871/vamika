@extends('layouts.shop-owner')

@php
$title = 'Order Details';
$subtitle = 'Complete order information';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.orders.index');
@endphp

@section('content')
<div class="main-content p-4">
    <div id="orderDetailsContainer" class="space-y-6">
        @if(isset($order))
            <!-- Order Header -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Order #ORD{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h2>
                        <p class="text-sm text-slate-500">{{ $order->created_at->format('M d, Y') }} • {{ $order->created_at->format('h:i A') }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
                            'processing' => 'bg-blue-50 text-blue-700 border border-blue-200',
                            'shipped' => 'bg-indigo-50 text-indigo-700 border border-indigo-200',
                            'delivered' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                            'cancelled' => 'bg-rose-50 text-rose-700 border border-rose-200'
                        ];
                        $statusColor = $statusColors[strtolower($order->status)] ?? 'bg-slate-50 text-slate-700 border border-slate-200';
                    @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $statusColor }} capitalize">
                        {{ $order->status }}
                    </span>
                </div>
                
                <!-- Customer Info -->
                <div class="pt-3 border-t border-slate-100">
                    <p class="text-sm font-medium text-slate-900">{{ $order->shop->name ?? 'N/A' }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->name }} • {{ Auth::user()->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Items Section -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-semibold text-slate-900">
                        Items ({{ $order->items->count() }})
                    </h3>
                    <a href="{{ route('shop-owner.invoices.show', $order->id) }}" 
                            class="text-xs text-indigo-600 hover:text-indigo-700 font-medium flex items-center gap-1">
                        <iconify-icon icon="lucide:printer" width="12"></iconify-icon>
                        Print Invoice
                    </a>
                </div>
                
                <div class="space-y-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-3 border-b border-slate-100 last:border-0">
                            <div class="flex items-start gap-3">
                                <div class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 overflow-hidden">
                                    @if($item->product && $item->product->image_url)
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                    @else
                                        <iconify-icon icon="lucide:package" width="16" class="text-slate-600"></iconify-icon>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900">{{ $item->product->name ?? 'Unknown Product' }}</p>
                                    <p class="text-xs text-slate-500">{{ $item->product->unit ?? '' }}</p>
                                    <div class="flex items-center gap-4 mt-1">
                                        <span class="text-xs text-slate-600">Qty: {{ $item->quantity }}</span>
                                        <span class="text-xs text-slate-600">₹{{ number_format($item->price, 2) }}/unit</span>
                                    </div>
                                </div>
                            </div>
                            <span class="font-semibold text-slate-900">₹{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-3">
                <h3 class="text-sm font-semibold text-slate-900 mb-2">Price Details</h3>
                
                <div class="flex justify-between text-sm">
                    <span class="text-slate-600">Subtotal</span>
                    <span class="font-medium">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                
                <div class="flex justify-between font-semibold text-lg pt-3 border-t border-slate-200">
                    <span class="text-slate-900">Total Amount</span>
                    <span class="text-emerald-600">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                
                <div class="pt-3 border-t border-slate-200">
                    <p class="text-xs text-slate-500">Payment Method</p>
                    <p class="text-sm font-medium text-slate-900">Cash (COD)</p>
                </div>
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
                            <p class="text-sm text-slate-600 mt-1">{{ $order->shop->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Actions -->
            @if(in_array(strtolower($order->status), ['pending', 'processing']))
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                    <div class="flex items-start gap-3">
                        <iconify-icon icon="lucide:info" width="20" class="text-amber-600 mt-0.5"></iconify-icon>
                        <div>
                            <h4 class="text-sm font-semibold text-amber-900">Order Status</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Your order is currently being processed. You'll receive updates on delivery timing.
                            </p>
                            <button onclick="confirmCancelOrder()" 
                                    class="mt-3 px-4 py-2 text-sm font-medium text-rose-600 hover:text-rose-700 
                                           hover:bg-rose-50 rounded-lg border border-rose-200 transition-colors">
                                Cancel Order
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        @else
            <div class="empty-state">
                <iconify-icon icon="lucide:alert-circle" width="48" class="text-slate-400 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">Order Not Found</h4>
                <p class="text-sm text-slate-500 mb-6">Please check the order number and try again.</p>
                <a href="{{ route('shop-owner.orders.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                    <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                    Back to Orders
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function confirmCancelOrder() {
        Swal.fire({
            title: 'Cancel Order?',
            text: "Are you sure you want to cancel this order? This action will restore product stock.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('shop-owner.orders.cancel', ['id' => $order->id ?? 0]) }}";
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = "{{ csrf_token() }}";
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endsection
