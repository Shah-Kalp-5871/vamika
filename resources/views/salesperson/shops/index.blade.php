@extends('layouts.salesperson')

@section('content')
<style>
    /* Reference Theme's Custom Styles */
    .shop-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }
</style>

<div
    class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">

    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100 p-4">
        <div class="space-y-4">
            <!-- Search -->
            <div class="relative">
                <iconify-icon icon="lucide:search" width="18"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text" id="shopSearch" oninput="filterShops()" placeholder="Search shops by name, owner or phone..."
                    class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white
                           focus:outline-none focus:ring-2 focus:ring-indigo-100
                           focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
            </div>
        </div>
    </header>

    <main class="space-y-6 p-4">
        <!-- Stats Cards -->
        <div class="grid grid-cols-3 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $totalShops }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Shops</p>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $activeShops }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Shops</p>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $todayOrders }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Today's Orders</p>
            </div>
        </div>

        <!-- Shop List -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Shop List</h3>
                <span id="shopCount" class="text-xs text-slate-500">{{ $shops->count() }} shops</span>
            </div>

            <div id="shopListContainer" class="space-y-4">
                @forelse($shops as $shop)
                    @php
                        // Determine shop status class
                        $statusClass = match(strtolower($shop->status)) {
                            'active' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                            'inactive' => 'bg-slate-50 text-slate-500 border border-slate-100',
                            'blocked' => 'bg-red-50 text-red-600 border border-red-100',
                            default => 'bg-slate-50 text-slate-500 border-slate-100',
                        };
                    @endphp
                    <div class="shop-card p-4 rounded-xl border border-slate-200 bg-white transition-all"
                         data-name="{{ strtolower($shop->name) }}"
                         data-owner="{{ strtolower($shop->user->name ?? '') }}"
                         data-phone="{{ $shop->phone }}">
                        
                        <div class="flex items-start gap-3 mb-4">
                            <div class="h-12 w-12 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100">
                                <iconify-icon icon="lucide:store" width="20" class="text-indigo-600"></iconify-icon>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ $shop->name }}</h3>
                                        <p class="text-xs text-slate-500">{{ $shop->user->name ?? 'N/A' }} • {{ $shop->area->name ?? 'N/A' }}</p>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($shop->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 mt-2">
                                    <div class="flex items-center gap-1">
                                        <iconify-icon icon="lucide:phone" width="12" class="text-slate-400"></iconify-icon>
                                        <span class="text-xs text-slate-600">{{ $shop->phone }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <iconify-icon icon="lucide:package" width="12" class="text-slate-400"></iconify-icon>
                                        <span class="text-xs text-slate-600">{{ $shop->orders_count }} orders</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <iconify-icon icon="lucide:calendar" width="12"></iconify-icon>
                                <span>Visits: {{ $shop->visits_count }}</span>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ route('salesperson.shops.show', $shop->id) }}" 
                                    class="px-3 py-2 rounded-lg bg-slate-100 text-slate-600 text-xs font-medium hover:bg-slate-200 transition-all">
                                    Details
                                </a>
                                <a href="{{ route('salesperson.orders.create', ['shop_id' => $shop->id]) }}" 
                                    class="px-3 py-2 rounded-lg bg-indigo-600 text-white text-xs font-medium hover:bg-indigo-700 transition-all">
                                    Order
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div id="emptyState" class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <iconify-icon icon="lucide:store" width="48" class="mb-3 opacity-50"></iconify-icon>
                        <p class="text-sm font-medium">No shops found</p>
                        <p class="text-xs mt-1">You haven't been assigned any shops yet.</p>
                    </div>
                @endforelse
                
                <!-- Search Empty State (Hidden by default) -->
                <div id="searchEmptyState" class="hidden flex flex-col items-center justify-center py-12 text-slate-400">
                    <iconify-icon icon="lucide:search-x" width="48" class="mb-3 opacity-50"></iconify-icon>
                    <p class="text-sm font-medium">No matching shops found</p>
                    <p class="text-xs mt-1">Try changing your search terms</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Manage Shops',
        'subtitle' => null,
        'showBack' => false,
        'showBottomNav' => true,
        'headerRight' => 'add-shop'
    ];
@endphp
@endpush

@section('scripts')
<script>
    function filterShops() {
        const query = document.getElementById('shopSearch').value.toLowerCase();
        const shops = document.querySelectorAll('.shop-card');
        let visibleCount = 0;
        
        shops.forEach(shop => {
            const name = shop.dataset.name;
            const owner = shop.dataset.owner;
            const phone = shop.dataset.phone;
            
            if (name.includes(query) || owner.includes(query) || phone.includes(query)) {
                shop.style.display = 'block';
                visibleCount++;
            } else {
                shop.style.display = 'none';
            }
        });
        
        // Update Count
        document.getElementById('shopCount').textContent = `${visibleCount} shop${visibleCount !== 1 ? 's' : ''}`;
        
        // Show empty state if no results
        const emptyState = document.getElementById('searchEmptyState');
        if (visibleCount === 0 && shops.length > 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
</script>
@endsection