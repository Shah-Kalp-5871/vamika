@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">My Orders</h2>
    </header>

    <main class="p-6 space-y-6">
        <!-- Search -->
        <div class="relative">
            <iconify-icon icon="lucide:search" width="20" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input type="text" id="orderSearch" placeholder="Search by Order ID..." 
                class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-medium">
        </div>

        <!-- Orders List -->
        <div id="ordersList" class="space-y-4">
            @forelse($orders as $order)
                <div class="order-card p-5 rounded-2xl border border-slate-100 bg-white shadow-sm hover:border-indigo-100 transition-all"
                    data-id="{{ $order->id }}">
                    
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-bold text-slate-900">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $order->created_at->format('M d, Y • h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                            @if($order->status == 'pending') bg-amber-50 text-amber-600 border border-amber-100
                            @elseif($order->status == 'delivered') bg-emerald-50 text-emerald-600 border border-emerald-100
                            @elseif($order->status == 'cancelled') bg-red-50 text-red-600 border border-red-100
                            @else bg-blue-50 text-blue-600 border border-blue-100 @endif">
                            {{ $order->status }}
                        </span>
                    </div>

                    <div class="space-y-3 mb-4">
                        @foreach($order->items->take(2) as $item)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-50">
                                    <iconify-icon icon="lucide:package" width="16"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-700">{{ $item->product->name }}</p>
                                    <p class="text-[10px] text-slate-400">Qty: {{ $item->quantity }} • ₹{{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                            <p class="text-xs font-bold text-slate-900">₹{{ number_format($item->subtotal, 2) }}</p>
                        </div>
                        @endforeach
                        
                        @if($order->items->count() > 2)
                        <p class="text-[10px] text-slate-400 font-bold uppercase text-center">+ {{ $order->items->count() - 2 }} more items</p>
                        @endif
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Total Amount</p>
                            <h4 class="text-lg font-black text-indigo-600">₹{{ number_format($order->total_amount, 2) }}</h4>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('shop-owner.orders.show', $order->id) }}" class="h-9 px-4 rounded-xl bg-slate-50 text-slate-600 text-[10px] font-bold uppercase flex items-center justify-center border border-slate-100 hover:bg-slate-100 transition-all">
                                Details
                            </a>
                            <a href="{{ route('shop-owner.invoices.show', $order->id) }}" class="h-9 px-4 rounded-xl bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase flex items-center justify-center border border-indigo-100 hover:bg-indigo-100 transition-all">
                                Invoice
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <iconify-icon icon="lucide:clipboard-list" width="48" class="mb-4 opacity-20"></iconify-icon>
                    <p class="text-sm font-medium">No orders found</p>
                    <a href="{{ route('shop-owner.products.index') }}" class="mt-4 inline-block text-[10px] font-bold text-indigo-600 uppercase tracking-widest border-b border-indigo-200 pb-1">Start Shopping</a>
                </div>
            @endforelse
        </div>
    </main>
</div>

<script>
    document.getElementById('orderSearch').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.order-card').forEach(card => {
            const id = card.dataset.id;
            if (id.includes(query)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection