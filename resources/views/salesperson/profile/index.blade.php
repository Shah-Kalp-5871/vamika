@extends('layouts.salesperson')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-slate-50">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 mb-6">
        <h2 class="text-xl font-bold text-slate-900">My Profile</h2>
    </header>

    <main class="px-6 space-y-6">
        <!-- Profile Card -->
        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col items-center text-center">
            <div class="h-24 w-24 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 mb-4 border-4 border-white shadow-md">
                <iconify-icon icon="lucide:user" width="48"></iconify-icon>
            </div>
            <h3 class="text-lg font-bold text-slate-900">{{ $user->name }}</h3>
            <p class="text-xs font-medium text-slate-400 uppercase tracking-widest mt-1">Salesperson</p>
            
            <div class="mt-4 inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-600 text-[10px] font-bold border border-slate-200">
                <iconify-icon icon="lucide:map-pin" width="12" class="mr-1.5"></iconify-icon>
                {{ $user->area->name ?? 'No Area Assigned' }}
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Orders</p>
                <p class="text-xl font-black text-slate-900">{{ number_format($stats['total_orders']) }}</p>
            </div>
            <div class="bg-white p-5 rounded-3xl border border-slate-100 shadow-sm text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Sales</p>
                <p class="text-xl font-black text-indigo-600 font-mono">₹{{ number_format($stats['total_sales']) }}</p>
            </div>
        </div>

        <!-- Details List -->
        <div class="bg-white rounded-3xl overflow-hidden border border-slate-100 shadow-sm">
            <div class="p-4 border-b border-slate-50">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Account Details</h4>
            </div>
            <div class="divide-y divide-slate-50">
                <div class="p-4 flex justify-between items-center">
                    <span class="text-xs font-medium text-slate-500">Employee ID</span>
                    <span class="text-xs font-bold text-slate-900">{{ $user->employee_id ?? 'N/A' }}</span>
                </div>
                <div class="p-4 flex justify-between items-center">
                    <span class="text-xs font-medium text-slate-500">Email Address</span>
                    <span class="text-xs font-bold text-slate-900">{{ $user->email }}</span>
                </div>
                <div class="p-4 flex justify-between items-center">
                    <span class="text-xs font-medium text-slate-500">Member Since</span>
                    <span class="text-xs font-bold text-slate-900">{{ $user->created_at->format('M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-3">
            <button class="w-full py-4 rounded-2xl bg-white border border-slate-200 text-sm font-bold text-slate-700 hover:bg-slate-50 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:settings" width="18" class="text-slate-400"></iconify-icon>
                Account Settings
            </button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-4 rounded-2xl bg-red-50 text-sm font-bold text-red-600 hover:bg-red-100 active:scale-[0.98] transition-all flex items-center justify-center gap-2 border border-red-100">
                    <iconify-icon icon="lucide:log-out" width="18"></iconify-icon>
                    Log Out
                </button>
            </form>
        </div>
    </main>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Profile',
        'showBack' => false,
        'showBottomNav' => true
    ];
@endphp
@endpush