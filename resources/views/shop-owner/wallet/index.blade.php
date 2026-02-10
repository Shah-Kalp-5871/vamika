@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">My Wallet</h2>
    </header>

    <main class="p-6 space-y-8">
        <!-- Balance Card -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 p-8 text-white shadow-2xl shadow-slate-200">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-8">
                    <div class="h-12 w-12 rounded-2xl bg-white/10 flex items-center justify-center border border-white/20 backdrop-blur-xl">
                        <iconify-icon icon="lucide:wallet" width="24" class="text-white"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400 bg-white/5 px-3 py-1.5 rounded-full border border-white/10">Active Wallet</span>
                </div>
                <p class="text-[10px] text-white/70 font-bold uppercase tracking-[0.2em] mb-1">Available Balance</p>
                <h3 class="text-4xl font-black tracking-tight text-white">₹{{ number_format($wallet->balance ?? 0, 2) }}</h3>
                
                <div class="mt-8 flex items-center gap-6">
                    <div>
                        <p class="text-[9px] text-white/50 font-bold uppercase tracking-widest mb-1">Total Credits</p>
                        <p class="text-sm font-bold text-emerald-400">+₹{{ number_format($transactions->where('type', 'credit')->sum('amount'), 2) }}</p>
                    </div>
                    <div class="w-px h-8 bg-white/10"></div>
                    <div>
                        <p class="text-[9px] text-white/50 font-bold uppercase tracking-widest mb-1">Total Debits</p>
                        <p class="text-sm font-bold text-rose-400">-₹{{ number_format(abs($transactions->where('type', 'debit')->sum('amount')), 2) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-indigo-500/20 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-[100px]"></div>
        </div>

        <!-- Transactions -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Recent Transactions</h3>
                <iconify-icon icon="lucide:filter" width="16" class="text-slate-400"></iconify-icon>
            </div>

            <div class="space-y-3">
                @forelse($transactions as $transaction)
                    <div class="p-4 rounded-2xl border border-slate-100 bg-white shadow-sm flex items-center justify-between hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center 
                                @if($transaction->type == 'credit') bg-emerald-50 text-emerald-600 border border-emerald-100
                                @else bg-rose-50 text-rose-600 border border-rose-100 @endif">
                                <iconify-icon icon="lucide:{{ $transaction->type == 'credit' ? 'arrow-down-left' : 'arrow-up-right' }}" width="20"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $transaction->description }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $transaction->created_at->format('M d, Y • h:i A') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-black @if($transaction->type == 'credit') text-emerald-600 @else text-rose-600 @endif">
                                {{ $transaction->type == 'credit' ? '+' : '-' }}₹{{ number_format(abs($transaction->amount), 2) }}
                            </p>
                            <p class="text-[10px] text-slate-400 font-bold mt-0.5">₹{{ number_format($transaction->balance_after, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="p-12 text-center text-slate-400 border-2 border-dashed border-slate-100 rounded-3xl">
                        <iconify-icon icon="lucide:history" width="48" class="mb-4 opacity-10"></iconify-icon>
                        <p class="text-sm font-medium">No transactions yet</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</div>
@endsection