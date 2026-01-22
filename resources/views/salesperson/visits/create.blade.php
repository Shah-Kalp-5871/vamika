@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">

    <header class="p-6 border-b border-slate-100 bg-white z-10 flex items-center gap-4">
        <a href="{{ route('salesperson.visits.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-semibold text-slate-900">Log New Visit</h2>
    </header>

    <main class="p-6">
        <form action="{{ route('salesperson.visits.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Shop Selection -->
            <div class="space-y-2">
                <label for="shop_id" class="text-sm font-medium text-slate-700">Select Shop</label>
                <select name="shop_id" id="shop_id" required
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm appearance-none cursor-pointer">
                    <option value="" disabled selected>Choose a shop...</option>
                    @foreach($shops as $shop)
                        <option value="{{ $shop->id }}" {{ old('shop_id', request('shop_id')) == $shop->id ? 'selected' : '' }}>
                            {{ $shop->name }} ({{ $shop->area->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Notes -->
            <div class="space-y-2">
                <label for="notes" class="text-sm font-medium text-slate-700">Visit Notes</label>
                <textarea name="notes" id="notes" rows="4" placeholder="Enter details about your visit..."
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm placeholder-slate-400 resize-none">{{ old('notes') }}</textarea>
            </div>

            <!-- Location (Hidden) -->
            <input type="hidden" name="location_lat" id="location_lat">
            <input type="hidden" name="location_lng" id="location_lng">

            <!-- Location Status -->
            <div class="flex items-center gap-3 p-4 rounded-xl bg-slate-50 border border-slate-100">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600">
                    <iconify-icon icon="lucide:map-pin" width="20"></iconify-icon>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-900">Location Tagging</p>
                    <p id="locationStatus" class="text-xs text-slate-500">Checking location...</p>
                </div>
                <button type="button" onclick="getLocation()" class="text-xs font-medium text-indigo-600 hover:text-indigo-700">
                    Retry
                </button>
            </div>

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="p-4 rounded-xl bg-red-50 text-red-600 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-3.5 px-4 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-100 flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:check-circle" width="18"></iconify-icon>
                <span>Log Visit</span>
            </button>
        </form>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Log Visit',
        'showBack' => true,
        'showBottomNav' => false
    ];
@endphp
@endpush

@section('scripts')
<script>
    function getLocation() {
        const statusEl = document.getElementById('locationStatus');
        
        if (!navigator.geolocation) {
            statusEl.textContent = 'Geolocation is not supported by your browser';
            return;
        }

        statusEl.textContent = 'Acquiring location...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                document.getElementById('location_lat').value = position.coords.latitude;
                document.getElementById('location_lng').value = position.coords.longitude;
                statusEl.textContent = `Location acquired: ${position.coords.latitude.toFixed(4)}, ${position.coords.longitude.toFixed(4)}`;
                statusEl.classList.add('text-emerald-600');
            },
            (error) => {
                statusEl.textContent = 'Unable to retrieve location. Please enable GPS.';
                statusEl.classList.add('text-red-500');
            }
        );
    }

    // Auto get location on load
    document.addEventListener('DOMContentLoaded', getLocation);
</script>
@endsection
