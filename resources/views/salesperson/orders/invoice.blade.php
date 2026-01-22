@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 border-b border-slate-100 bg-white z-10 hidden-print">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('salesperson.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
                    <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
                </a>
                <h2 class="text-lg font-bold text-slate-900">Order Invoice</h2>
            </div>
            <button onclick="window.print()" class="p-2 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors">
                <iconify-icon icon="lucide:printer" width="20"></iconify-icon>
            </button>
        </div>
    </header>

    <main class="p-6 md:p-8 space-y-8 print:p-0">
        <!-- Brand / Header -->
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-black text-indigo-600 tracking-tighter">VAMIKA</h1>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">Premium Distributor</p>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                    {{ $order->status }}
                </span>
                <p class="text-sm font-bold text-slate-900 mt-2">#ORD-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->format('M d, Y h:i A') }}</p>
            </div>
        </div>

        <hr class="border-slate-100">

        <!-- Info Grid -->
        <div class="grid grid-cols-2 gap-8">
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Ship To</h4>
                <p class="text-sm font-bold text-slate-900">{{ $order->shop->name }}</p>
                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ $order->shop->address }}</p>
                <p class="text-xs text-slate-500 mt-2">
                    <span class="font-medium">Phone:</span> {{ $order->shop->phone }}
                </p>
            </div>
            <div class="text-right">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Salesperson</h4>
                <p class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ auth()->user()->area->name ?? 'N/A' }} Area</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="space-y-4">
            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Order Items</h4>
            <div class="border border-slate-100 rounded-2xl overflow-hidden shadow-sm">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 border-b border-slate-100 text-[10px] text-slate-400 font-bold uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-3">Description</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-right">Price</th>
                            <th class="px-4 py-3 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="px-4 py-3">
                                <p class="font-bold text-slate-900">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">{{ $item->product->sku }}</p>
                            </td>
                            <td class="px-4 py-3 text-center font-medium">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-right text-slate-500">₹{{ number_format($item->price, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Summary -->
        <div class="flex justify-end pt-4">
            <div class="w-full max-w-[240px] space-y-3">
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Subtotal</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs text-slate-500">
                    <span>Tax (0%)</span>
                    <span>₹0.00</span>
                </div>
                <div class="h-px bg-slate-100"></div>
                <div class="flex justify-between items-center bg-indigo-50 p-3 rounded-xl">
                    <span class="text-sm font-bold text-indigo-900">Grand Total</span>
                    <span class="text-lg font-black text-indigo-600">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($order->notes)
        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
            <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Order Notes</h4>
            <p class="text-xs text-slate-600 italic leading-relaxed">{{ $order->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="pt-8 text-center">
            <p class="text-[10px] text-slate-400 font-medium">Thank you for your business!</p>
            <p class="text-[8px] text-slate-300 mt-1 uppercase tracking-widest">Digital Generated Invoice • VAMIKA ERP</p>
        </div>
    </main>
</div>

<style>
    @media print {
        .hidden-print { display: none !important; }
        body { background: white; }
        .sm\:shadow-xl { shadow: none !important; }
        .sm\:my-8 { margin: 0 !important; }
        .sm\:border { border: none !important; }
    }
</style>
@endsection