@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">

    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100 p-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-900">My Visits</h2>
        <a href="{{ route('salesperson.visits.create') }}" 
           class="inline-flex items-center justify-center p-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-colors">
            <iconify-icon icon="lucide:plus" width="20"></iconify-icon>
            <span class="ml-1 text-sm font-medium">Log Visit</span>
        </a>
    </header>

    <main class="p-4 space-y-4">
        
        <!-- Visits List -->
        @forelse($visits as $visit)
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm transition-all hover:bg-slate-50">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-semibold text-sm border border-indigo-200">
                            {{ strtoupper(substr($visit->shop->name ?? '?', 0, 2)) }}
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900">{{ $visit->shop->name ?? 'Unknown Shop' }}</h3>
                            <p class="text-xs text-slate-500">{{ $visit->visit_date->format('M d, Y • h:i A') }}</p>
                        </div>
                    </div>
                    
                    @if($visit->location_lat && $visit->location_lng)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-medium bg-emerald-50 text-emerald-600 border border-emerald-100" title="Location Verified">
                            <iconify-icon icon="lucide:map-pin" width="10" class="mr-1"></iconify-icon>
                            Verified
                        </span>
                    @endif
                </div>
                
                @if($visit->notes)
                    <div class="pl-13 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100 mt-2">
                        <p class="line-clamp-2">{{ $visit->notes }}</p>
                    </div>
                @endif
            </div>
        @empty
            <div class="flex flex-col items-center justify-center py-16 text-slate-400">
                <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                    <iconify-icon icon="lucide:footprints" width="32" class="opacity-50"></iconify-icon>
                </div>
                <p class="text-sm font-medium text-slate-900">No visits recorded</p>
                <p class="text-xs mt-1 text-slate-500">Log your first shop visit today!</p>
                
                <a href="{{ route('salesperson.visits.create') }}" 
                   class="mt-6 px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 active:scale-95 transition-all shadow-sm">
                    Log Visit Now
                </a>
            </div>
        @endforelse

    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Visits',
        'showBack' => false,
        'showBottomNav' => true,
        'headerRight' => 'add-visit'
    ];
@endphp
@endpush