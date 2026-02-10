@extends('layouts.salesperson')

@section('content')
<div class="max-w-md mx-auto min-h-screen bg-slate-50 flex flex-col pb-24">
    <!-- Header -->
    <header class="bg-white p-6 border-b border-slate-100 sticky top-0 z-10 shadow-sm">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Select Active Bit</h1>
        <p class="text-sm text-slate-500 mt-1">Which area are you working in today?</p>
        
        <!-- Search -->
        <div class="mt-6 relative">
            <iconify-icon icon="lucide:search" width="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input type="text" id="bitSearch" placeholder="Search by name or pincode..." 
                   class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                   oninput="filterBits(this.value)">
        </div>
    </header>

    <!-- Bits List -->
    <main class="flex-1 p-4">
        <div id="bitList" class="grid grid-cols-1 gap-4">
            @foreach($bits as $bit)
            <form action="{{ route('salesperson.bits.update') }}" method="POST" id="bitForm{{ $bit->id }}">
                @csrf
                <input type="hidden" name="bit_id" value="{{ $bit->id }}">
                <div onclick="document.getElementById('bitForm{{ $bit->id }}').submit()"
                     class="bit-card relative overflow-hidden bg-white p-5 rounded-2xl border border-slate-200 shadow-sm transition-all animate-slide-up group cursor-pointer hover:border-indigo-600 hover:shadow-md hover:shadow-indigo-50 active:scale-[0.98]">
                    
                    @if(Auth::user()->bit_id == $bit->id)
                        <div class="absolute top-0 right-0 px-3 py-1 bg-emerald-500 text-white text-[10px] font-bold rounded-bl-xl uppercase tracking-wider">
                            Active Area
                        </div>
                    @endif

                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center group-hover:bg-indigo-600 group-hover:text-white transition-all duration-300">
                            <iconify-icon icon="lucide:map-pin" width="28"></iconify-icon>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $bit->name }}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-slate-500 flex items-center gap-1 bg-slate-50 px-2 py-0.5 rounded-lg">
                                    <iconify-icon icon="lucide:store" width="12"></iconify-icon>
                                    {{ $bit->shops_count }} Shops
                                </span>
                                <span class="text-xs text-slate-400 font-mono tracking-tighter">{{ $bit->code }}</span>
                            </div>
                        </div>

                        <div class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-all">
                            <iconify-icon icon="lucide:arrow-right" width="20"></iconify-icon>
                        </div>
                    </div>
                </div>
            </form>
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden flex flex-col items-center justify-center py-20 text-slate-400 text-center">
            <iconify-icon icon="lucide:map-pin-off" width="48" class="mb-4 opacity-20"></iconify-icon>
            <p class="text-sm font-medium">No Bits Found</p>
            <p class="text-xs mt-1">Try a different search term</p>
        </div>
    </main>
</div>

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up {
        animation: slideUp 0.3s ease forwards;
    }
</style>

<script>
    function filterBits(query) {
        const term = query.toLowerCase().trim();
        const cards = document.querySelectorAll('.bit-card');
        const list = document.getElementById('bitList');
        const empty = document.getElementById('emptyState');
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            const code = card.querySelector('.text-slate-400').textContent.toLowerCase();
            const pincode = card.querySelectorAll('span')[2].textContent.toLowerCase();
            
            if (name.includes(term) || code.includes(term) || pincode.includes(term)) {
                card.parentElement.classList.remove('hidden');
                visibleCount++;
            } else {
                card.parentElement.classList.add('hidden');
            }
        });

        if (visibleCount === 0) {
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
        'title' => 'Select Bit',
        'showBack' => false,
        'showBottomNav' => false
    ];
@endphp
@endpush
