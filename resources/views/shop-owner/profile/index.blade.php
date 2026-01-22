@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center justify-between sticky top-0 z-30">
        <h2 class="text-lg font-bold text-slate-900">My Profile</h2>
        <a href="{{ route('shop-owner.profile.edit') }}" class="p-2 rounded-xl bg-slate-50 text-slate-500 hover:text-indigo-600 transition-colors">
            <iconify-icon icon="lucide:edit-2" width="20"></iconify-icon>
        </a>
    </header>

    <main class="p-6 space-y-8">
        <!-- Profile Header -->
        <div class="flex flex-col items-center text-center">
            <div class="h-24 w-24 rounded-3xl bg-indigo-600 flex items-center justify-center text-white shadow-2xl shadow-indigo-100 mb-4 rotate-3 hover:rotate-0 transition-transform duration-500">
                <iconify-icon icon="lucide:store" width="40"></iconify-icon>
            </div>
            <h3 class="text-xl font-black text-slate-900">{{ $user->name }}</h3>
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mt-1">{{ $shop->name }} • {{ $shop->area->name ?? 'No Area' }}</p>
        </div>

        <!-- Contact Cards -->
        <div class="grid grid-cols-1 gap-3">
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm">
                    <iconify-icon icon="lucide:phone" width="20"></iconify-icon>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Phone Number</p>
                    <p class="text-sm font-bold text-slate-900">{{ $shop->phone }}</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm">
                    <iconify-icon icon="lucide:mail" width="20"></iconify-icon>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Email Address</p>
                    <p class="text-sm font-bold text-slate-900">{{ $user->email }}</p>
                </div>
            </div>
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center text-slate-400 border border-slate-100 shadow-sm">
                    <iconify-icon icon="lucide:map-pin" width="20"></iconify-icon>
                </div>
                <div>
                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-widest">Shop Address</p>
                    <p class="text-sm font-bold text-slate-900">{{ $shop->address }}</p>
                </div>
            </div>
        </div>

        <!-- Shop Settings List -->
        <div class="space-y-2">
            <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] mb-4">Shop Settings</h4>
            
            <a href="{{ route('shop-owner.wallet.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 hover:border-indigo-100 hover:bg-slate-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <iconify-icon icon="lucide:wallet" width="20"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Wallet & Balance</p>
                        <p class="text-[10px] text-slate-400 font-medium">Manage your credits and payments</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="18" class="text-slate-300 group-hover:text-indigo-400 transition-colors"></iconify-icon>
            </a>

            <a href="{{ route('shop-owner.referral.index') }}" class="flex items-center justify-between p-4 rounded-2xl bg-white border border-slate-100 hover:border-indigo-100 hover:bg-slate-50 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="h-10 w-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <iconify-icon icon="lucide:user-plus" width="20"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900">Refer & Earn</p>
                        <p class="text-[10px] text-slate-400 font-medium">Invite others and get rewards</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="18" class="text-slate-300 group-hover:text-indigo-400 transition-colors"></iconify-icon>
            </a>
        </div>

        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="pt-4">
            @csrf
            <button type="submit" class="w-full h-14 rounded-2xl bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 border border-rose-100 hover:bg-rose-100 transition-all active:scale-95">
                <iconify-icon icon="lucide:log-out" width="18"></iconify-icon>
                Logout Account
            </button>
        </form>

        <p class="text-center text-[9px] text-slate-300 font-bold uppercase tracking-widest">Version 1.0.0 • Vamika Enterprise</p>
    </main>
</div>
@endsection