@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Invoices</h2>
    </header>

    <main class="p-6 space-y-6">
        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="p-5 rounded-2xl bg-white border border-slate-100 shadow-sm hover:border-indigo-100 transition-all group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <iconify-icon icon="lucide:receipt" width="18"></iconify-icon>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900">#{{ $order->id }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $order->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black text-slate-900">₹{{ number_format($order->total_amount, 2) }}</p>
                            <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 rounded-full 
                                @if($order->payment_status == 'paid') bg-emerald-50 text-emerald-600 @else bg-amber-50 text-amber-600 @endif">
                                {{ $order->payment_status }}
                            </span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-slate-50">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $order->items->count() }} Items</p>
                        <a href="{{ route('shop-owner.invoices.show', $order->id) }}" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-700 transition-colors">
                            View Invoice
                            <iconify-icon icon="lucide:chevron-right" width="14"></iconify-icon>
                        </a>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center flex flex-col items-center justify-center">
                    <div class="h-20 w-20 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                        <iconify-icon icon="lucide:file-text" width="40"></iconify-icon>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">No invoices yet</h3>
                    <p class="text-xs text-slate-400 mt-1">Invoices appear here once orders are delivered.</p>
                </div>
            @endforelse
        </div>
    </main>
</div>
@endsection