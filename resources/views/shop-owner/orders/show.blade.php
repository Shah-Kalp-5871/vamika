@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.orders.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Order Details</h2>
    </header>

    <main class="p-6 space-y-8">
        <!-- Status Card -->
        <div class="p-6 rounded-2xl border border-slate-100 bg-slate-50 flex items-center justify-between">
            <div>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Order Status</p>
                <h3 class="text-lg font-black text-slate-900 capitalize">{{ $order->status }}</h3>
            </div>
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center 
                @if($order->status == 'pending') bg-amber-100 text-amber-600
                @elseif($order->status == 'delivered') bg-emerald-100 text-emerald-600
                @else bg-blue-100 text-blue-600 @endif">
                <iconify-icon icon="lucide:{{ $order->status == 'pending' ? 'clock' : ($order->status == 'delivered' ? 'check-circle' : 'package') }}" width="24"></iconify-icon>
            </div>
        </div>

        <!-- Details Grid -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-3">Order Info</h4>
                <p class="text-sm font-bold text-slate-900">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $order->created_at->format('M d, Y') }}</p>
                <p class="text-xs text-slate-500 mt-0.5">{{ $order->created_at->format('h:i A') }}</p>
            </div>
            <div class="text-right">
                <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-3">Shop Info</h4>
                <p class="text-sm font-bold text-slate-900">{{ $order->shop->name }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $order->shop->phone }}</p>
            </div>
        </div>

        <hr class="border-slate-50">

        <!-- Items -->
        <div class="space-y-4">
            <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Order Items</h4>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-50 overflow-hidden">
                            @if($item->product->image_url)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                            @else
                                <iconify-icon icon="lucide:package" width="20"></iconify-icon>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $item->product->name }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5">Qty: {{ $item->quantity }} • ₹{{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                    <p class="text-sm font-black text-slate-900">₹{{ number_format($item->subtotal, 2) }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Summary -->
        <div class="p-6 rounded-2xl bg-slate-900 text-white flex items-center justify-between shadow-xl shadow-slate-200">
            <div>
                <p class="text-[10px] text-white/70 font-bold uppercase tracking-widest mb-1">Grand Total</p>
                <h3 class="text-2xl font-black text-white">₹{{ number_format($order->total_amount, 2) }}</h3>
            </div>
            <a href="{{ route('shop-owner.invoices.show', $order->id) }}" class="h-12 px-6 rounded-xl bg-white/10 text-white text-[10px] font-bold uppercase tracking-widest flex items-center justify-center border border-white/20 hover:bg-white/20 transition-all">
                View Invoice
            </a>
        </div>

        <!-- Timeline / Additional Info -->
        @if($order->notes)
        <div class="p-6 rounded-2xl bg-slate-50 border border-slate-100">
            <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-2">Order Notes</h4>
            <p class="text-xs text-slate-600 italic leading-relaxed">{{ $order->notes }}</p>
        </div>
        @endif
    </main>
</div>
@endsection