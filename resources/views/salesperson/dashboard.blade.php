@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">

    <header class="p-6 border-b border-slate-100 bg-white z-10">
        <div class="flex justify-between items-start">
            <div>
                <h2 class="text-xl font-normal text-slate-500 tracking-tight">{{ explode(' ', auth()->user()->name)[0] }}</h2>
            </div>
            <div class="flex flex-col items-end">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    <iconify-icon icon="lucide:map-pin" width="12" class="mr-1.5"></iconify-icon>
                    <span>{{ auth()->user()->area->name ?? 'No Area' }}</span>
                </span>
                <span class="text-xs text-slate-400 mt-2">{{ now()->format('l, M d') }}</span>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Today's Visits -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors">
                <div class="mb-3">
                    <iconify-icon icon="lucide:map-pin" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $todayVisits }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Visits</p>
            </div>

            <!-- Today's Orders -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors">
                <div class="mb-3">
                    <iconify-icon icon="lucide:package" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $todayOrders }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Orders</p>
            </div>

            <!-- Today's Sales -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors">
                <div class="mb-3">
                    <iconify-icon icon="lucide:indian-rupee" width="20" class="text-blue-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">₹{{ number_format($todayOrderValue) }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Sales</p>
            </div>

            <!-- Monthly Sales -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors">
                <div class="mb-3">
                    <iconify-icon icon="lucide:calendar" width="20" class="text-purple-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">₹{{ number_format($monthlySales) }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Monthly Sales</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-3">
            <button onclick="window.location.href='{{ route('salesperson.visits.create') }}'"
                class="flex items-center justify-center gap-2 p-3 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:map-pin" width="16" class="text-slate-400 group-hover:text-indigo-600 transition-colors"></iconify-icon>
                Log Visit
            </button>
            <button onclick="window.location.href='{{ route('salesperson.shops.index') }}'"
                class="flex items-center justify-center gap-2 p-3 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:plus-circle" width="16" class="text-slate-400 group-hover:text-indigo-600 transition-colors"></iconify-icon>
                New Order
            </button>
        </div>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Recent Orders</h3>
                <a href="{{ route('salesperson.sales.index') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center">
                    View all <iconify-icon icon="lucide:chevron-right" width="12" class="ml-1"></iconify-icon>
                </a>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="divide-y divide-slate-100">
                    @forelse($recentOrders as $order)
                        <div class="p-4 hover:bg-slate-50 transition-colors flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-semibold text-xs border border-slate-200">
                                    {{ strtoupper(substr($order->shop->name ?? 'Unknown', 0, 2)) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">{{ $order->shop->name ?? 'Unknown Shop' }}</h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="text-xs text-slate-500">{{ $order->items_count ?? 0 }} Items</span>
                                        <span class="text-[10px] text-slate-300">•</span>
                                        <span class="text-xs font-medium text-slate-700">₹{{ number_format($order->total_amount) }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                @php
                                    $badgeClass = match(strtolower($order->status)) {
                                        'pending' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'confirmed' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'delivered' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                        'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                        default => 'bg-slate-50 text-slate-600 border-slate-100',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <span class="text-[10px] text-slate-400">{{ $order->created_at->format('h:i A') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-400">
                            <iconify-icon icon="lucide:clipboard-list" width="32" class="mb-2 opacity-50"></iconify-icon>
                            <p class="text-sm">No recent orders</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Dashboard',
        'showBack' => false,
        'showBottomNav' => true,
        'headerRight' => 'profile'
    ];
@endphp
@endpush