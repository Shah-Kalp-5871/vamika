@extends('layouts.admin')

@section('title', 'Manage Offers')
@section('subtitle', 'Discounts & promotions')

@php
$pageConfig = [
    'showBottomNav' => true,
];
@endphp

@section('styles')
    <style>
        .main-content {
            max-width: 100%;
            margin: 0 auto;
            min-height: 100vh;
            background-color: #FAFAFA;
        }

        @media (min-width: 640px) {
            .main-content {
                max-width: 42rem;
                margin: 2rem auto;
                min-height: calc(100vh - 4rem);
                background-color: white;
                box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                border-radius: 1rem;
                border: 1px solid #E2E8F0;
                overflow: hidden;
            }
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-slide-up {
            animation: slideUp 0.5s ease forwards;
        }
    </style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-6 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Active Offers -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up">
                <div class="mb-3">
                    <iconify-icon icon="lucide:check-circle" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $activeOffers }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Offers</p>
            </div>

            <!-- Inactive Offers -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.1s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:x-circle" width="20" class="text-slate-400"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $inactiveOffers }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Inactive Offers</p>
            </div>
        </div>

        <!-- Tabs and Filters -->
        <div class="space-y-4">
            <!-- Tabs -->
            <div class="flex overflow-x-auto border-b border-slate-200 px-6 -mx-6">
                <a href="{{ route('admin.offers.index') }}" 
                   class="px-4 py-3 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 {{ !request('status') ? 'border-primary text-primary bg-primary/5' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <iconify-icon icon="lucide:layers" width="16"></iconify-icon>
                    All Offers
                </a>
                <a href="{{ route('admin.offers.index', ['status' => 'active']) }}" 
                   class="px-4 py-3 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 {{ request('status') == 'active' ? 'border-emerald-500 text-emerald-600 bg-emerald-50' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <iconify-icon icon="lucide:check-circle" width="16"></iconify-icon>
                    Active Offers
                </a>
                <a href="{{ route('admin.offers.index', ['status' => 'inactive']) }}" 
                   class="px-4 py-3 text-sm font-medium border-b-2 transition-colors flex items-center gap-2 {{ request('status') == 'inactive' ? 'border-slate-500 text-slate-600 bg-slate-50' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <iconify-icon icon="lucide:x-circle" width="16"></iconify-icon>
                    Inactive Offers
                </a>
            </div>

            <!-- Search and Create -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative flex-1">
                    <form action="{{ route('admin.offers.index') }}" method="GET">
                        <iconify-icon icon="lucide:search" width="16"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search offers by name..."
                            class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>
                <a href="{{ route('admin.offers.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors no-underline">
                    <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                    Create Offer
                </a>
            </div>
        </div>

        <!-- Offers List -->
        <div class="space-y-4">
            @forelse($offers as $offer)
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow animate-slide-up" style="cursor: pointer;" onclick="window.location='{{ route('admin.offers.edit', $offer->id) }}'">
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                            <div class="flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-2">
                                    <iconify-icon icon="lucide:tag" class="text-indigo-600" width="20"></iconify-icon>
                                    <h3 class="text-lg font-semibold text-slate-900">{{ $offer->title }}</h3>
                                    
                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $offer->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                                        {{ ucfirst($offer->status) }}
                                    </span>

                                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        {{ $offer->discount_type == 'percentage' ? $offer->discount_value . '% OFF' : '₹' . $offer->discount_value . ' OFF' }}
                                    </span>
                                </div>
                                
                                <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 mt-3">
                                    <div class="flex items-center gap-1">
                                        <iconify-icon icon="lucide:calendar" width="14" class="text-slate-400"></iconify-icon>
                                        <span>{{ $offer->start_date->format('d M') }} - {{ $offer->end_date->format('d M Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.offers.edit', $offer->id) }}"
                                   class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                   onclick="event.stopPropagation()">
                                    <iconify-icon icon="lucide:edit" width="16"></iconify-icon>
                                </a>
                                <form id="delete-form-{{ $offer->id }}" action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" onclick="confirmDelete({{ $offer->id }}); event.stopPropagation();">
                                        <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="mb-0">
                            <p class="text-sm text-slate-600">
                                <iconify-icon icon="lucide:info" width="14" class="text-slate-400 mr-2"></iconify-icon>
                                {{ $offer->description ?? 'No description' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-12 bg-white rounded-xl border border-slate-200 border-dashed">
                    <iconify-icon icon="lucide:gift" width="48" class="text-slate-300 mb-4"></iconify-icon>
                    <h4 class="text-lg font-medium text-slate-900 mb-1">No Offers Found</h4>
                    <p class="text-sm text-slate-500 mb-4">Try adjusting your filters or create a new offer</p>
                    <a href="{{ route('admin.offers.create') }}" class="btn-primary">
                        <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                        Create Offer
                    </a>
                </div>
            @endforelse
            
            <!-- Pagination -->
            <div class="mt-4">
                {{ $offers->withQueryString()->links() }}
            </div>
        </div>
    </main>
</div>

<!-- Add Offer Button -->
<a href="{{ route('admin.offers.create') }}" class="fixed bottom-20 right-4 sm:bottom-8 sm:right-6 md:right-auto md:left-1/2 md:-translate-x-1/2 w-14 h-14 bg-indigo-600 text-white rounded-full shadow-lg flex items-center justify-center hover:bg-indigo-700 transition-colors z-50 sm:hidden">
    <iconify-icon icon="lucide:plus" width="24"></iconify-icon>
</a>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Any specific JS init
    });

    function confirmDelete(offerId) {
        Swal.fire({
            title: 'Delete Offer?',
            text: "Are you sure? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + offerId).submit();
            }
        });
    }
</script>
@endsection