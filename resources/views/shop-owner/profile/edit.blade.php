@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.profile.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Edit Profile</h2>
    </header>

    <main class="p-6">
        <form action="{{ route('shop-owner.profile.update') }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <!-- Personal Info -->
            <section class="space-y-4">
                <h3 class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Personal Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-900 mb-2 block">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="h-14 w-full rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-600 px-6 font-bold text-slate-900 placeholder:text-slate-300 transition-all">
                        @error('name') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-900 mb-2 block">Email Address</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="h-14 w-full rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-600 px-6 font-bold text-slate-900 placeholder:text-slate-300 transition-all">
                        @error('email') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <!-- Shop Info -->
            <section class="space-y-4">
                <h3 class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em]">Shop Details</h3>
                <div class="space-y-4">
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-900 mb-2 block">Shop Name</label>
                        <input type="text" name="shop_name" value="{{ old('shop_name', $shop->name ?? '') }}" class="h-14 w-full rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-600 px-6 font-bold text-slate-900 placeholder:text-slate-300 transition-all">
                        @error('shop_name') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-900 mb-2 block">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone', $shop->phone ?? '') }}" class="h-14 w-full rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-600 px-6 font-bold text-slate-900 placeholder:text-slate-300 transition-all">
                        @error('phone') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-900 mb-2 block">Full Address</label>
                        <textarea name="address" rows="3" class="w-full rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-600 p-6 font-bold text-slate-900 placeholder:text-slate-300 transition-all">{{ old('address', $shop->address ?? '') }}</textarea>
                        @error('address') <p class="text-rose-500 text-[10px] font-bold mt-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>
                </div>
            </section>

            <button type="submit" class="h-16 w-full rounded-2xl bg-slate-900 text-white text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-slate-800 transition-all active:scale-95 shadow-xl shadow-slate-100">
                Update Profile
                <iconify-icon icon="lucide:save" width="18"></iconify-icon>
            </button>
        </form>
    </main>
</div>
@endsection