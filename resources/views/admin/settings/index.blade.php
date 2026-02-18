@php
$pageConfig = [
    'title' => 'Settings',
    'role' => 'Admin',
    'showBottomNav' => true
];
@endphp

@extends('layouts.admin')

@section('content')
<div class="max-w-2xl mx-auto p-6">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">System Settings</h1>
        <p class="text-sm text-slate-500 mt-1">Manage global configurations and company details.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-6">
        @csrf
        
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-start gap-3">
                <iconify-icon icon="lucide:check-circle" width="20" class="mt-0.5 flex-shrink-0"></iconify-icon>
                <div class="text-sm font-medium">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Global Configuration -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                        <iconify-icon icon="lucide:clock" width="20"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Working Hours</h3>
                        <p class="text-xs text-slate-500 font-medium">Global configuration for salespersons</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <div class="p-3 rounded-xl bg-amber-50 border border-amber-100 flex items-start gap-3">
                        <iconify-icon icon="lucide:info" width="16" class="mt-0.5 text-amber-600 flex-shrink-0"></iconify-icon>
                        <p class="text-xs text-amber-700 font-medium leading-relaxed">
                            These hours enforce "View-Only" mode for all salespersons outside of the specified window.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Start Time (IST)</label>
                    <div class="relative">
                        <input type="time" name="salesperson_work_start" 
                               value="{{ $settings['salesperson_work_start'] ?? '09:00' }}"
                               class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
                
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">End Time (IST)</label>
                    <div class="relative">
                        <input type="time" name="salesperson_work_end" 
                               value="{{ $settings['salesperson_work_end'] ?? '18:00' }}"
                               class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-indigo-100 focus:border-indigo-500 outline-none transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Company Information -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <iconify-icon icon="lucide:building-2" width="20"></iconify-icon>
                    </div>
                    <div>
                        <h3 class="text-base font-bold text-slate-900">Company Details</h3>
                        <p class="text-xs text-slate-500 font-medium">Information displayed on invoices</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Company Name</label>
                    <input type="text" name="company_name" 
                           value="{{ $settings['company_name'] ?? 'Vamika Enterprise' }}"
                           class="w-full bg-slate-50 border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all"
                           placeholder="Enter company name">
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Contact Email</label>
                        <div class="relative">
                            <iconify-icon icon="lucide:mail" width="16" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                            <input type="email" name="contact_email" 
                                   value="{{ $settings['contact_email'] ?? 'info@vamikaenterprise.com' }}"
                                   class="w-full bg-slate-50 border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Phone Number</label>
                        <div class="relative">
                            <iconify-icon icon="lucide:phone" width="16" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                            <input type="tel" name="phone_number" maxlength="10"
                                   value="{{ $settings['phone_number'] ?? '9212345678' }}"
                                   oninput="this.value = this.value.replace(/[^0-9-]/g, '')"
                                   class="w-full bg-slate-50 border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm font-medium text-slate-700 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">GSTIN</label>
                    <div class="relative">
                        <iconify-icon icon="lucide:receipt-text" width="16" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                        <input type="text" name="gstin"
                               value="{{ $settings['gstin'] ?? '' }}"
                               maxlength="15"
                               oninput="this.value = this.value.toUpperCase()"
                               placeholder="e.g. 24ABCDE1234F1Z5"
                               class="w-full bg-slate-50 border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm font-mono font-medium text-slate-700 focus:ring-2 focus:ring-emerald-100 focus:border-emerald-500 outline-none transition-all tracking-wider">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1.5 pl-1">15-character GST Identification Number. Displayed on all invoices.</p>
                </div>
            </div>
        </div>

        <!-- Save Action -->
        <div class="sticky bottom-6 z-10">
            <button type="submit" 
                    class="w-full bg-slate-900 text-white font-bold py-4 rounded-xl shadow-xl shadow-slate-200 hover:bg-slate-800 hover:shadow-2xl hover:-translate-y-0.5 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:save" width="20"></iconify-icon>
                Save Changes
            </button>
        </div>
    </form>

    <!-- Danger Zone -->
    <div class="mt-8 pt-8 border-t border-slate-200">
        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Danger Zone</h3>
        
        <div class="bg-rose-50 rounded-2xl border border-rose-100 p-6 flex items-center justify-between gap-4">
            <div>
                <h4 class="text-sm font-bold text-rose-700">Sign Out</h4>
                <p class="text-xs text-rose-600/80 mt-1">End your current session securely.</p>
            </div>
            
            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button" onclick="confirmLogout()" 
                        class="bg-white text-rose-600 text-xs font-bold px-4 py-2.5 rounded-lg border border-rose-100 shadow-sm hover:shadow-md hover:border-rose-200 active:scale-95 transition-all flex items-center gap-2">
                    <iconify-icon icon="lucide:log-out" width="14"></iconify-icon>
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Logout?',
            text: "Are you sure you want to end your session?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, Logout'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logout-form').submit();
            }
        });
    }
</script>
@endsection