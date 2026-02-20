@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100 p-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('salesperson.shops.index') }}" class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors">
                <iconify-icon icon="lucide:arrow-left" width="18" class="text-slate-600"></iconify-icon>
            </a>
            <div>
                <h1 class="text-lg font-semibold text-slate-900 tracking-tight">Add New Shop</h1>
                <p class="text-xs text-slate-500">Register a new shop in your bit</p>
            </div>
        </div>
    </header>

    <main class="p-4">
        <form action="{{ route('salesperson.shops.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <!-- Shop Details Section -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Shop Information</h3>
                
                <div class="space-y-1">
                    <label for="name" class="text-sm font-medium text-slate-700">Shop Name <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm transition-all"
                        placeholder="e.g. Krishna General Store">
                    @error('name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label for="bit_id" class="text-sm font-medium text-slate-700">Select Bit <span class="text-rose-500">*</span></label>
                    <select name="bit_id" id="bit_id" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm transition-all bg-white">
                        <option value="">Select a bit</option>
                        @foreach($bits as $bit)
                            <option value="{{ $bit->id }}" {{ old('bit_id') == $bit->id ? 'selected' : '' }}>
                                {{ $bit->name }} ({{ $bit->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('bit_id') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label for="address" class="text-sm font-medium text-slate-700">Full Address <span class="text-rose-500">*</span></label>
                    <textarea name="address" id="address" rows="3" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm transition-all"
                        placeholder="Enter complete shop address...">{{ old('address') }}</textarea>
                    @error('address') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <hr class="border-slate-100">

            <!-- Owner Details Section -->
            <div class="space-y-4">
                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider">Owner Account Details</h3>
                
                <div class="grid grid-cols-1 gap-4">
                    <div class="space-y-1">
                        <label for="owner_name" class="text-sm font-medium text-slate-700">Owner Name <span class="text-rose-500">*</span></label>
                        <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm transition-all"
                            placeholder="Full name of owner">
                        @error('owner_name') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="phone" class="text-sm font-medium text-slate-700">Phone Number <span class="text-rose-500">*</span></label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm transition-all"
                            placeholder="Primary contact number">
                        @error('phone') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="email" class="text-sm font-medium text-slate-700">Email Address <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 text-sm transition-all"
                        placeholder="Owner's email for login">
                    @error('email') <p class="text-xs text-rose-500 mt-1">{{ $message }}</p> @enderror
                    <p class="text-[10px] text-slate-400">This will be used as the username for the shop owner app.</p>
                </div>

            </div>

            <div class="pt-4 pb-8">
                <button type="submit" 
                        @if($is_off_hours ?? false) disabled @endif
                        class="w-full py-4 bg-indigo-600 text-white rounded-xl font-semibold shadow-lg shadow-indigo-200 transition-all flex items-center justify-center gap-2 {{ ($is_off_hours ?? false) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-indigo-700 active:scale-[0.98]' }}">
                    <iconify-icon icon="lucide:check-circle" width="20"></iconify-icon>
                    Create Shop Account
                </button>
                <p class="text-center text-[10px] text-slate-400 mt-3">
                    By clicking this, you confirm the details are correct. Shops can only be edited by Admin after creation.
                </p>
            </div>
        </form>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Register Shop',
        'showBack' => true,
        'showBottomNav' => false
    ];
@endphp
@endpush
