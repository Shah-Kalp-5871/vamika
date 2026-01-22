@extends('layouts.shop-owner')

@section('content')
<div class="max-w-4xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center justify-between sticky top-0 z-30 no-print">
        <div class="flex items-center gap-4">
            <a href="{{ route('shop-owner.invoices.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
                <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
            </a>
            <h2 class="text-lg font-bold text-slate-900">Tax Invoice</h2>
        </div>
        <button onclick="window.print()" class="h-10 px-4 rounded-xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-widest flex items-center gap-2 hover:bg-slate-800 transition-all">
            <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
            Print
        </button>
    </header>

    <main class="p-8 space-y-12">
        <!-- Invoice Header -->
        <div class="flex flex-col md:flex-row justify-between gap-8">
            <div class="space-y-4">
                <div class="h-12 w-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-xl shadow-indigo-100">
                    <iconify-icon icon="lucide:store" width="28"></iconify-icon>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tighter">Vamika Enterprise</h1>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">Wholesale Distributor</p>
                </div>
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider space-y-1">
                    <p>GSTIN: 07AABCU9603R1ZM</p>
                    <p>Delhi NCR, India</p>
                </div>
            </div>

            <div class="text-left md:text-right space-y-1">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Invoice Number</p>
                <h2 class="text-xl font-black text-slate-900">#INV-{{ $order->id }}</h2>
                <div class="pt-4">
                    <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Date Issued</p>
                    <p class="text-sm font-bold text-slate-900">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <div class="h-px bg-slate-100"></div>

        <!-- Bill To -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            <div>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-4">Bill To</p>
                <h4 class="text-base font-black text-slate-900">{{ $order->shop->name }}</h4>
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider space-y-1 mt-2">
                    <p>{{ $order->shop->address }}</p>
                    <p>Phone: {{ $order->shop->phone }}</p>
                </div>
            </div>
            <div class="text-left md:text-right">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-4">Payment Info</p>
                <div class="text-[10px] text-slate-500 font-bold uppercase tracking-wider space-y-1">
                    <p>Status: <span class="text-emerald-600">{{ strtoupper($order->payment_status) }}</span></p>
                    <p>Method: CASH ON DELIVERY</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">
                        <th class="pb-4">Description</th>
                        <th class="pb-4 text-center">Qty</th>
                        <th class="pb-4 text-right">Price</th>
                        <th class="pb-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($order->items as $item)
                        <tr>
                            <td class="py-6">
                                <p class="text-sm font-bold text-slate-900">{{ $item->product->name }}</p>
                                <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">{{ $item->product->unit }}</p>
                            </td>
                            <td class="py-6 text-center text-sm font-bold text-slate-900">{{ $item->quantity }}</td>
                            <td class="py-6 text-right text-sm font-bold text-slate-900">₹{{ number_format($item->price, 2) }}</td>
                            <td class="py-6 text-right text-sm font-black text-slate-900">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end pt-8 border-t border-slate-100">
            <div class="w-full md:w-64 space-y-4">
                <div class="flex justify-between text-xs font-bold uppercase tracking-widest text-slate-400">
                    <span>Subtotal</span>
                    <span class="text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between text-xs font-bold uppercase tracking-widest text-slate-400">
                    <span>Tax (GST 18%)</span>
                    <span class="text-slate-900">Inclusive</span>
                </div>
                <div class="h-px bg-slate-50"></div>
                <div class="flex justify-between items-center bg-indigo-50 p-4 rounded-2xl">
                    <span class="text-[10px] font-black uppercase tracking-widest text-indigo-600">Total Amount</span>
                    <span class="text-xl font-black text-indigo-600">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="pt-12 text-center text-[10px] text-slate-300 font-bold uppercase tracking-[0.2em] space-y-2">
            <p>Thank you for your business</p>
            <p>Vamika Enterprise • Computer Generated Invoice</p>
        </div>
    </main>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .sm\:shadow-xl { box-shadow: none !important; }
        .sm\:my-8 { margin: 0 !important; }
        .sm\:rounded-2xl { border-radius: 0 !important; }
        .sm\:border { border: none !important; }
    }
</style>
@endsection