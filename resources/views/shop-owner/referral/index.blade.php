@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Refer & Earn</h2>
    </header>

    <main class="p-6 space-y-8">
        <!-- Hero -->
        <div class="relative overflow-hidden rounded-3xl bg-indigo-600 p-8 text-white shadow-2xl shadow-indigo-100">
            <div class="relative z-10">
                <h3 class="text-2xl font-black tracking-tight mb-2">Invite Friends, Earn Credits!</h3>
                <p class="text-xs text-indigo-100 font-bold uppercase tracking-widest opacity-80">Get ₹500 for every successful referral</p>
            </div>
            <div class="absolute -top-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Referral Code -->
        <section class="space-y-4">
            <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">Your Unique Code</h4>
            <div class="flex items-center gap-3">
                <div class="flex-1 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center font-black text-slate-900 tracking-[0.3em] uppercase">
                    VAMIKA-{{ strtoupper(substr($user->name, 0, 3)) }}{{ $user->id }}
                </div>
                <button onclick="copyCode()" class="h-14 w-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-100 hover:scale-105 active:scale-95 transition-all">
                    <iconify-icon icon="lucide:copy" width="20"></iconify-icon>
                </button>
            </div>
        </section>

        <!-- Steps -->
        <section class="space-y-4">
            <h4 class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em]">How it works</h4>
            <div class="space-y-3">
                <div class="p-4 rounded-2xl border border-slate-100 flex gap-4">
                    <div class="h-8 w-8 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-black text-xs">01</div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider leading-relaxed">Share your code with other shop owners.</p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 flex gap-4">
                    <div class="h-8 w-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-xs">02</div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider leading-relaxed">They sign up using your referral link.</p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 flex gap-4">
                    <div class="h-8 w-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-black text-xs">03</div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider leading-relaxed">Both get ₹500 credits in your wallets!</p>
                </div>
            </div>
        </section>

        <button class="h-16 w-full rounded-2xl bg-[#25D366] text-white text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-xl shadow-emerald-50 hover:bg-[#128C7E] transition-all">
            <iconify-icon icon="ic:baseline-whatsapp" width="24"></iconify-icon>
            Invite via WhatsApp
        </button>
    </main>
</div>

<script>
    function copyCode() {
        const code = "VAMIKA-{{ strtoupper(substr($user->name, 0, 3)) }}{{ $user->id }}";
        navigator.clipboard.writeText(code);
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Referral code copied to clipboard',
            timer: 1500,
            showConfirmButton: false
        });
    }
</script>
@endsection