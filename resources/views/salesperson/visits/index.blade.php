@extends('layouts.salesperson')

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

@section('content')
<style>
    /* Status colors */
    .status-not-visited {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fca5a5;
    }

    .status-with-order {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #86efac;
    }

    .status-no-order {
        background-color: #fef3c7;
        color: #166534;
        border: 1px solid #fbbf24;
    }

    /* Tab styles */
    .tab-active {
        color: #4F46E5;
        position: relative;
    }

    .tab-active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 40px;
        height: 3px;
        background-color: #4F46E5;
        border-radius: 3px;
    }

    /* Card styles */
    .outlet-card {
        transition: all 0.2s ease;
        border: 1px solid #e5e7eb;
    }

    .outlet-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .search-input {
        padding-left: 2.75rem;
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
    }

    /* Action buttons */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-order {
        background-color: #dcfce7;
        color: #166534;
        border-color: #86efac;
    }

    .btn-order:hover {
        background-color: #bbf7d0;
    }

    .btn-no-order {
        background-color: #fef3c7;
        color: #92400e;
        border-color: #fbbf24;
    }

    .btn-no-order:hover {
        background-color: #fde68a;
    }

    /* Status indicators */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    /* Progress bar */
    .progress-bar {
        height: 8px;
        border-radius: 4px;
        background-color: #e5e7eb;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .progress-fill-visited {
        background-color: #E2E8F0;
    }

    .progress-fill-order {
        background-color: #4F46E5;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<!-- Progress Bar -->
<div class="sticky top-0 bg-white z-20 border-b border-gray-100">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Today's Progress</span>
            <span class="text-sm font-bold text-indigo-600" id="progress-percentage">{{ $stats['total'] > 0 ? round(($stats['visited'] / $stats['total']) * 100) : 0 }}%</span>
        </div>
        <div class="progress-bar">
            @php
                $visitedPercent = $stats['total'] > 0 ? ($stats['visited'] / $stats['total']) * 100 : 0;
                $ordersPercent = $stats['total'] > 0 ? ($stats['orders'] / $stats['total']) * 100 : 0;
            @endphp
            <div class="progress-fill progress-fill-order" style="width: {{ $ordersPercent }}%"></div>
            <div class="progress-fill progress-fill-visited" style="width: {{ $visitedPercent - $ordersPercent }}%"></div>
        </div>
        <div class="flex justify-between mt-1">
            <span class="text-[10px] font-medium text-gray-400">Visited: <span class="text-gray-700">{{ $stats['visited'] }}</span></span>
            <span class="text-[10px] font-medium text-gray-400">Orders: <span class="text-indigo-600 font-bold">{{ $stats['orders'] }}</span></span>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex overflow-x-auto hide-scrollbar border-t border-gray-50">
        <button onclick="switchTab('not-visited')" 
                class="tab-button flex-1 py-3 text-xs font-bold text-center min-w-max px-4 transition-all"
                data-tab="not-visited">
            NOT VISITED ({{ $stats['not_visited'] }})
        </button>
        <button onclick="switchTab('visited')" 
                class="tab-button flex-1 py-3 text-xs font-bold text-center min-w-max px-4 transition-all"
                data-tab="visited">
            VISITED ({{ $stats['visited'] }})
        </button>
        <button onclick="switchTab('ordered')" 
                class="tab-button flex-1 py-3 text-xs font-bold text-center min-w-max px-4 transition-all"
                data-tab="ordered">
            ORDERS ({{ $stats['orders'] }})
        </button>
        <button onclick="switchTab('no_order')" 
                class="tab-button flex-1 py-3 text-xs font-bold text-center min-w-max px-4 transition-all"
                data-tab="no_order">
            NO ORDERS ({{ $stats['no_orders'] }})
        </button>
    </div>
</div>

<!-- Search Bar -->
<div class="p-4 bg-white border-b border-gray-100">
    <div class="relative">
        <iconify-icon icon="lucide:search" width="18" class="search-icon"></iconify-icon>
        <input type="text" 
               id="search-input" 
               oninput="filterOutlets()"
               placeholder="Search shop name or area..."
               class="search-input w-full px-4 py-3 bg-slate-50 rounded-xl text-sm border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:bg-white focus:border-indigo-300 transition-all outline-none">
    </div>
</div>

<!-- Outlet List -->
<main class="p-4 pb-32">
    <div id="outlet-list" class="space-y-3">
        @foreach($shops as $shop)
            <div class="outlet-card bg-white rounded-xl p-4 transition-all" 
                 data-id="{{ $shop->id }}"
                 data-name="{{ strtolower($shop->name) }}"
                 data-area="{{ strtolower($shop->area->name ?? '') }}"
                 data-status="{{ $shop->status_today }}"
                 data-visited="{{ $shop->visited_today ? 'true' : 'false' }}">
                
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-bold text-gray-900 text-sm truncate">{{ $shop->name }}</h3>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1 mb-2">
                            <iconify-icon icon="lucide:map-pin" width="12"></iconify-icon>
                            {{ $shop->area->name ?? 'No Area' }}
                        </p>
                        
                        <div class="flex flex-wrap gap-1.5">
                            @if($shop->status_today == 'pending')
                                <span class="status-indicator status-not-visited">
                                    <iconify-icon icon="lucide:clock" width="12"></iconify-icon>
                                    Not Visited
                                </span>
                            @elseif($shop->status_today == 'ordered')
                                <span class="status-indicator status-with-order">
                                    <iconify-icon icon="lucide:check-circle" width="12"></iconify-icon>
                                    Order Placed
                                </span>
                            @else
                                <span class="status-indicator status-no-order">
                                    <iconify-icon icon="lucide:x-circle" width="12"></iconify-icon>
                                    Visited - No Order
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($shop->visited_today)
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Visited at</p>
                            <p class="text-xs font-bold text-slate-700">{{ $shop->visited_time }}</p>
                        </div>
                    @endif
                </div>
                
                <div class="flex items-center justify-between pt-3 border-t border-gray-50 mt-3">
                    <div class="flex items-center gap-3">
                        <a href="tel:{{ $shop->phone }}" class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                            <iconify-icon icon="lucide:phone" width="16"></iconify-icon>
                        </a>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shop->address) }}" target="_blank" class="h-8 w-8 rounded-lg bg-slate-50 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                            <iconify-icon icon="lucide:navigation" width="16"></iconify-icon>
                        </a>
                    </div>
                    
                    @if(!$shop->visited_today)
                        <div class="flex gap-2">
                            <form action="{{ route('salesperson.visits.no-order', $shop->id) }}" method="POST" onsubmit="return confirm('Mark as Visited without Order?')">
                                @csrf
                                <button type="submit" class="action-btn btn-no-order">No Order</button>
                            </form>
                            <a href="{{ route('salesperson.orders.create', ['shop_id' => $shop->id, 'visit' => 'true']) }}" 
                               class="action-btn btn-order">
                                Take Order
                            </a>
                        </div>
                    @else
                        <span class="text-[10px] font-bold text-gray-400 uppercase italic">Recorded</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="hidden py-12 text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-50 flex items-center justify-center">
            <iconify-icon icon="lucide:store" width="24" class="text-slate-300"></iconify-icon>
        </div>
        <h3 class="text-slate-900 font-bold mb-1">No Shops Found</h3>
        <p class="text-slate-500 text-sm">Try adjusting your search or filter</p>
    </div>
</main>

<script>
    let currentTab = 'not-visited';

    function switchTab(tab) {
        currentTab = tab;
        
        // Update UI
        document.querySelectorAll('.tab-button').forEach(btn => {
            if (btn.dataset.tab === tab) {
                btn.classList.add('tab-active');
                btn.classList.remove('text-gray-400');
                btn.classList.add('text-indigo-600');
            } else {
                btn.classList.remove('tab-active');
                btn.classList.remove('text-indigo-600');
                btn.classList.add('text-gray-400');
            }
        });

        filterOutlets();
    }

    function filterOutlets() {
        const query = document.getElementById('search-input').value.toLowerCase();
        const cards = document.querySelectorAll('.outlet-card');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.dataset.name;
            const area = card.dataset.area;
            const status = card.dataset.status;
            const visited = card.dataset.visited === 'true';

            const matchesSearch = name.includes(query) || area.includes(query);
            let matchesTab = false;

            switch (currentTab) {
                case 'not-visited':
                    matchesTab = !visited;
                    break;
                case 'visited':
                    matchesTab = visited;
                    break;
                case 'ordered':
                    matchesTab = status === 'ordered';
                    break;
                case 'no_order':
                    matchesTab = status === 'no_order';
                    break;
            }

            if (matchesSearch && matchesTab) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        document.getElementById('empty-state').classList.toggle('hidden', visibleCount > 0);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', () => {
        switchTab('not-visited');
    });
</script>
@endsection