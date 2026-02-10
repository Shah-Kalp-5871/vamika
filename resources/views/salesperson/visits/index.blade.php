@extends('layouts.salesperson')

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Daily Visits',
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
        height: 10px;
        border-radius: 5px;
        background-color: #f1f5f9;
        overflow: hidden;
        display: flex;
    }

    .progress-fill {
        height: 100%;
        transition: width 0.3s ease;
    }

    .progress-fill-order {
        background-color: #10b981; /* Green */
    }

    .progress-fill-no-order {
        background-color: #f59e0b; /* Amber */
    }

    .progress-fill-not-visited {
        background-color: #e2e8f0; /* Light Slate */
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
<div class="sticky top-0 bg-white z-20 border-b border-gray-100 shadow-sm">
    <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-2">
            <div class="flex flex-col">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Today's Progress</span>
                <p class="text-[10px] text-gray-400">Area: <span class="text-indigo-600 font-bold uppercase">{{ Auth::user()->bit->name ?? 'None' }}</span></p>
            </div>
            <div class="flex flex-col items-end gap-1.5">
                <span class="text-sm font-bold text-indigo-600" id="progress-percentage">
                    {{ $stats['total'] > 0 ? round(($stats['visited'] / $stats['total']) * 100) : 0 }}%
                </span>
                <a href="{{ route('salesperson.bits.select') }}" 
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-indigo-50 border border-indigo-100 text-[10px] font-bold text-indigo-600 uppercase tracking-wide hover:bg-indigo-100 transition-all shadow-sm">
                    <iconify-icon icon="lucide:map-pin" width="12"></iconify-icon>
                    Change Bit
                </a>
            </div>
        </div>
        <div class="progress-bar">
            @php
                $ordersPercent = $stats['total'] > 0 ? ($stats['orders'] / $stats['total']) * 100 : 0;
                $noOrdersPercent = $stats['total'] > 0 ? ($stats['no_orders'] / $stats['total']) * 100 : 0;
            @endphp
            <div class="progress-fill progress-fill-order" style="width: {{ $ordersPercent }}%" title="Orders"></div>
            <div class="progress-fill progress-fill-no-order" style="width: {{ $noOrdersPercent }}%" title="No Orders"></div>
        </div>
        <div class="flex justify-between mt-2">
            <div class="flex items-center gap-3">
                <span class="flex items-center gap-1 text-[10px] font-medium text-gray-500">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Orders: <span class="text-gray-900 font-bold">{{ $stats['orders'] }}</span>
                </span>
                <span class="flex items-center gap-1 text-[10px] font-medium text-gray-500">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    No Order: <span class="text-gray-900 font-bold">{{ $stats['no_orders'] }}</span>
                </span>
            </div>
            <span class="text-[10px] font-medium text-gray-400 italic">
                Left: <span class="text-indigo-600 font-bold">{{ $stats['not_visited'] }}</span>
            </span>
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
               placeholder="Search shop name or bit..."
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
                 data-area="{{ strtolower($shop->bit->name ?? '') }}"
                 data-status="{{ $shop->status_today }}"
                 data-visited="{{ $shop->visited_today ? 'true' : 'false' }}">
                
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-bold text-gray-900 text-sm truncate">{{ $shop->name }}</h3>
                        </div>
                        <p class="text-xs text-gray-500 flex items-center gap-1 mb-2">
                            <iconify-icon icon="lucide:map-pin" width="12"></iconify-icon>
                            {{ $shop->bit->name ?? 'No Bit' }}
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
                            <button type="button" 
                                    onclick="openNoOrderModal({{ $shop->id }})"
                                    @if($is_off_hours ?? false) disabled @endif
                                    class="action-btn btn-no-order {{ ($is_off_hours ?? false) ? 'opacity-50 cursor-not-allowed' : '' }}">
                                No Order
                            </button>
                            @if($is_off_hours ?? false)
                                <button disabled class="action-btn btn-order opacity-50 cursor-not-allowed">
                                    Take Order
                                </button>
                            @else
                                <a href="{{ route('salesperson.orders.create', ['shop_id' => $shop->id, 'visit' => 'true']) }}" 
                                   class="action-btn btn-order">
                                    Take Order
                                </a>
                            @endif
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

<!-- No Order Modal -->
<div id="no-order-modal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeNoOrderModal()"></div>
    <div class="relative w-full max-w-sm bg-white rounded-3xl shadow-2xl transform transition-all scale-95 opacity-0 overflow-hidden" id="modal-container">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">Mark as No Order</h3>
                <button onclick="closeNoOrderModal()" class="p-2 -mr-2 text-slate-400 hover:text-slate-600">
                    <iconify-icon icon="lucide:x" width="20"></iconify-icon>
                </button>
            </div>
            
            <form id="no-order-form" method="POST" action="">
                @csrf
                    <div class="space-y-4">
                        <p class="text-sm text-slate-500">Please select a reason for no order:</p>
                        
                        <div class="grid grid-cols-1 gap-2">
                            @foreach(['shop_closed' => 'Shop Closed', 'owner_not_available' => 'Owner Not Available', 'stock_sufficient' => 'Stock Sufficient', 'payment_issue' => 'Payment Issue', 'other' => 'Other'] as $value => $label)
                                <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition-all has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                    <input type="radio" name="no_order_reason" value="{{ $value }}" class="w-4 h-4 text-indigo-600" required>
                                    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Additional Notes (Optional)</label>
                            <textarea name="notes" rows="3" placeholder="Enter more details about why there was no order..." 
                                class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all outline-none resize-none"></textarea>
                        </div>
                    </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeNoOrderModal()" class="flex-1 py-3 rounded-xl border border-slate-200 text-sm font-bold text-slate-600">Cancel</button>
                    <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-200">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

    function openNoOrderModal(shopId) {
        const modal = document.getElementById('no-order-modal');
        const container = document.getElementById('modal-container');
        const form = document.getElementById('no-order-form');
        
        form.action = `/salesperson/visits/${shopId}/no-order`;
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            container.classList.remove('scale-95', 'opacity-0');
            container.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeNoOrderModal() {
        const modal = document.getElementById('no-order-modal');
        const container = document.getElementById('modal-container');
        
        container.classList.remove('scale-100', 'opacity-100');
        container.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 200);
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