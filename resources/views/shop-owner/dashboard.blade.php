@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Welcome, {{ explode(' ', $user->name)[0] }}!</h2>
            <p class="text-xs text-slate-500 font-medium">{{ $shop->name }} • {{ $shop->bit->name ?? 'No Area' }}</p>
        </div>
        <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
            <iconify-icon icon="lucide:user" width="20"></iconify-icon>
        </div>
    </header>

    <main class="p-6 space-y-8">
        <!-- Wallet Card -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 text-white shadow-lg shadow-emerald-100">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-white/10 rounded-lg border border-white/20">
                        <iconify-icon icon="lucide:wallet" width="20" class="text-white"></iconify-icon>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-200 bg-white/10 px-2.5 py-1 rounded-full border border-white/20">Wallet Balance</span>
                </div>
                <p class="text-xs text-emerald-100 font-medium opacity-80 uppercase tracking-widest">Available Credit</p>
                <h3 class="text-3xl font-black tracking-tight mt-1 text-white">₹{{ number_format($stats['walletBalance'], 2) }}</h3>
            </div>
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-400 rounded-full blur-3xl opacity-20"></div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-3">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm flex flex-col items-center text-center">
                <iconify-icon icon="lucide:package" width="20" class="text-indigo-600 mb-2"></iconify-icon>
                <p class="text-xl font-bold text-slate-900 tracking-tight">{{ $stats['totalOrders'] }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Total</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm flex flex-col items-center text-center">
                <iconify-icon icon="lucide:clock" width="20" class="text-amber-500 mb-2"></iconify-icon>
                <p class="text-xl font-bold text-slate-900 tracking-tight">{{ $stats['pendingOrders'] }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Pending</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm flex flex-col items-center text-center">
                <iconify-icon icon="lucide:check-circle" width="20" class="text-emerald-600 mb-2"></iconify-icon>
                <p class="text-xl font-bold text-slate-900 tracking-tight">{{ $stats['deliveredOrders'] }}</p>
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter mt-1">Delivered</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('shop-owner.products.index') }}" class="flex items-center justify-center gap-2 p-4 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:shopping-cart" width="18" class="text-indigo-500"></iconify-icon>
                Shop Items
            </a>
            <a href="{{ route('shop-owner.orders.index') }}" class="flex items-center justify-center gap-2 p-4 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:clipboard-list" width="18" class="text-indigo-500"></iconify-icon>
                My Orders
            </a>
            <a href="{{ route('shop-owner.wallet.index') }}" class="flex items-center justify-center gap-2 p-4 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:history" width="18" class="text-indigo-500"></iconify-icon>
                Transactions
            </a>
            <a href="{{ route('shop-owner.invoices.index') }}" class="flex items-center justify-center gap-2 p-4 rounded-xl border border-slate-200 bg-white text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:receipt" width="18" class="text-indigo-500"></iconify-icon>
                Invoices
            </a>
        </div>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Recent Activity</h3>
                <a href="{{ route('shop-owner.orders.index') }}" class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest flex items-center gap-1">
                    View more <iconify-icon icon="lucide:chevron-right" width="12"></iconify-icon>
                </a>
            </div>

            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-50">
                @forelse($recentActivities as $activity)
                    <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-xl flex items-center justify-center 
                                @if($activity['type'] == 'order') bg-blue-50 text-blue-600 border border-blue-100
                                @elseif($activity['type'] == 'payment') bg-emerald-50 text-emerald-600 border border-emerald-100
                                @else bg-slate-50 text-slate-600 border border-slate-100 @endif">
                                <iconify-icon icon="lucide:{{ $activity['icon'] }}" width="18"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-900">{{ $activity['title'] }}</h4>
                                <p class="text-[11px] text-slate-500 mt-0.5">{{ $activity['description'] }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-300 uppercase">{{ $activity['time'] }}</span>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-400">
                        <iconify-icon icon="lucide:activity" width="32" class="mb-2 opacity-30"></iconify-icon>
                        <p class="text-xs font-medium">No recent activities to show</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>
</div>
@endsection