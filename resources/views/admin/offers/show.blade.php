@extends('layouts.admin')

@section('title', 'Offer Details')
@section('subtitle', 'View offer information')

@section('content')
<div class="main-content">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-slate-100 bg-white -mt-px flex justify-between items-center">
        <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
            {{ $offer->title }}
        </h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium no-underline">
                <iconify-icon icon="lucide:edit" width="16"></iconify-icon>
                Edit
            </a>
            <form id="delete-form" action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="button" onclick="confirmDelete()" class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors text-sm font-medium">
                    <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                    Delete
                </button>
            </form>
            <script>
                function confirmDelete() {
                    Swal.fire({
                        title: 'Delete Offer?',
                        text: "Are you sure you want to delete this offer? This action cannot be undone.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#E11D48',
                        cancelButtonColor: '#64748B',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-form').submit();
                        }
                    });
                }
            </script>
        </div>
    </div>

    <main class="p-6 space-y-6">
        <!-- Status and Type -->
        <div class="flex flex-wrap items-center gap-3">
            <span class="px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1 {{ $offer->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                <iconify-icon icon="lucide:{{ $offer->status == 'active' ? 'check-circle' : 'clock' }}" width="14"></iconify-icon>
                {{ ucfirst($offer->status) }}
            </span>
            
            <span class="px-3 py-1 rounded-full text-sm font-medium flex items-center gap-1 {{ $offer->discount_type == 'percentage' ? 'bg-orange-100 text-orange-700' : 'bg-green-100 text-green-700' }}">
                <iconify-icon icon="lucide:{{ $offer->discount_type == 'percentage' ? 'percent' : 'indian-rupee' }}" width="14"></iconify-icon>
                {{ $offer->discount_type == 'percentage' ? $offer->discount_value . '% OFF' : '₹' . $offer->discount_value . ' OFF' }}
            </span>
        </div>

        <!-- Basic Info -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Offer Details</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-500 mb-1">Description</label>
                    <div class="text-slate-900">{{ $offer->description ?? 'No description provided.' }}</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-1">Discount Value</label>
                        <div class="text-slate-900 font-medium">
                            @if($offer->discount_type == 'percentage')
                                {{ $offer->discount_value }}%
                            @else
                                ₹{{ $offer->discount_value }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-500 mb-1">Validity Period</label>
                        <div class="text-slate-900">
                            {{ $offer->start_date->format('d M Y') }} to {{ $offer->end_date->format('d M Y') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-900 mb-4">Timeline</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Created</span>
                    <span class="text-sm font-medium text-slate-900">{{ $offer->created_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Last Updated</span>
                    <span class="text-sm font-medium text-slate-900">{{ $offer->updated_at->format('d M Y, h:i A') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Start Date</span>
                    <span class="text-sm font-medium text-slate-900">{{ $offer->start_date->format('d M Y') }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">End Date</span>
                    <span class="text-sm font-medium text-slate-900">{{ $offer->end_date->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection