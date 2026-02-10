@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen bg-slate-50 relative pb-24">
    <!-- Header -->
    <header class="bg-white p-6 border-b border-slate-100 shadow-sm sticky top-0 z-20">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Select Shop</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-bold uppercase tracking-wider border border-indigo-100">
                        Active Bit
                    </span>
                    <span class="text-sm font-medium text-slate-600">{{ $bit->name }}</span>
                </div>
            </div>
            <a href="{{ route('salesperson.bits.select') }}" class="h-10 w-10 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                <iconify-icon icon="lucide:settings-2" width="20"></iconify-icon>
            </a>
        </div>

        <!-- Search -->
        <div class="relative group">
            <iconify-icon icon="lucide:search" width="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></iconify-icon>
            <input type="text" id="shopSearch" placeholder="Search shops by name, owner, or phone..." 
                   class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                   oninput="filterShops(this.value)">
        </div>
    </header>

    <main class="p-4 space-y-4">
        <!-- Dashboard Link Card -->
        <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between group">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <iconify-icon icon="lucide:check-circle" width="20"></iconify-icon>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Total Unvisited</h4>
                    <p class="text-xs text-slate-500">{{ $shops->count() }} shops remaining</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xl font-bold text-slate-900">{{ $shops->count() }}</span>
            </div>
        </div>

        <!-- Shops List -->
        <div id="shopList" class="space-y-3">
            @forelse($shops as $shop)
            <div class="shop-card bg-white p-4 rounded-2xl border border-slate-200 shadow-sm hover:border-indigo-500 hover:shadow-md transition-all active:scale-[0.98] animate-slide-up">
                <div class="flex items-center gap-4">
                    <div class="h-14 w-14 rounded-2xl bg-slate-50 text-slate-600 flex items-center justify-center border border-slate-100 font-bold text-lg">
                        {{ strtoupper(substr($shop->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <h3 class="font-bold text-slate-900 truncate">{{ $shop->name }}</h3>
                            <iconify-icon icon="lucide:chevron-right" width="18" class="text-slate-300"></iconify-icon>
                        </div>
                        <div class="flex flex-col gap-1">
                            <div class="flex items-center gap-2 text-xs text-slate-500">
                                <iconify-icon icon="lucide:user" width="12" class="text-slate-400"></iconify-icon>
                                <span>{{ $shop->owner }}</span>
                                <span class="text-slate-300">•</span>
                                <iconify-icon icon="lucide:phone" width="12" class="text-slate-400"></iconify-icon>
                                <span>{{ $shop->phone }}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] font-bold text-indigo-600 border border-indigo-100 bg-indigo-50 px-1.5 py-0.5 rounded leading-none uppercase">
                                    {{ $shop->orders_count }} Orders
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-50 flex gap-2">
                    <a href="{{ route('salesperson.orders.create') }}?shop_id={{ $shop->id }}" 
                       class="flex-1 bg-indigo-600 text-white text-center py-2.5 rounded-xl text-sm font-bold hover:bg-indigo-700 transition-colors no-underline">
                        Create Order
                    </a>
                    <form id="no-order-form-{{ $shop->id }}" action="{{ route('salesperson.visits.no-order', $shop->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="button" class="w-full bg-slate-100 text-slate-600 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-200 transition-colors"
                                onclick="confirmNoOrder({{ $shop->id }})">
                            No Order
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-20 text-slate-400 text-center">
                <iconify-icon icon="lucide:store" width="64" class="mb-4 opacity-10"></iconify-icon>
                <p class="text-sm font-medium">Clear for today!</p>
                <p class="text-xs mt-1">All shops in this bit have been visited</p>
                <a href="{{ route('salesperson.bits.select') }}" class="mt-6 text-indigo-600 font-bold text-sm no-underline flex items-center gap-2">
                    Switch Active Bit <iconify-icon icon="lucide:arrow-right" width="16"></iconify-icon>
                </a>
            </div>
            @endforelse
        </div>

        <!-- Empty Results (for search) -->
        <div id="resultsEmptyState" class="hidden flex flex-col items-center justify-center py-20 text-slate-400 text-center">
            <iconify-icon icon="lucide:search" width="48" class="mb-4 opacity-20"></iconify-icon>
            <p class="text-sm font-medium">No results found</p>
            <p class="text-xs mt-1">Try a different shop name or owner</p>
        </div>
    </main>
</div>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
</style>

<script>
    function confirmNoOrder(shopId) {
        Swal.fire({
            title: 'No Order?',
            text: "Mark this shop as visited with no order for today?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6366F1',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, mark it'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('no-order-form-' + shopId).submit();
            }
        });
    }

    function filterShops(query) {
        const term = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.shop-card');
        const list = document.getElementById('shopList');
        const empty = document.getElementById('resultsEmptyState');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const owner = card.querySelector('p').textContent.toLowerCase();
            const phone = card.querySelectorAll('span')[1].textContent;
            
            if (name.includes(term) || owner.includes(term) || phone.includes(term)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        if (visibleCount === 0 && term !== '') {
            list.classList.add('hidden');
            empty.classList.remove('hidden');
        } else {
            list.classList.remove('hidden');
            empty.classList.add('hidden');
        }
    }
</script>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Select Shop',
        'showBack' => true,
        'backUrl' => route('salesperson.dashboard'),
        'showBottomNav' => true
    ];
@endphp
@endpush