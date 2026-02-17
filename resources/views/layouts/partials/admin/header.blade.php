@php
$pageConfig = $pageConfig ?? [];

$title    = $pageConfig['title'] ?? 'Admin Panel';
$subtitle = $pageConfig['subtitle'] ?? '';
$showBack = $pageConfig['showBack'] ?? false;
$backUrl  = $pageConfig['backUrl'] ?? 'javascript:history.back()';
$role     = $pageConfig['role'] ?? 'Admin';
@endphp

<header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-slate-200">
    <div class="px-6 py-4 flex items-center justify-between">

        <div class="flex items-center gap-4">
            @if ($showBack)
                <a href="{{ $backUrl }}"
                   class="p-2 rounded-xl border border-slate-200 bg-white shadow-sm hover:bg-slate-50 transition-all">
                    <iconify-icon icon="lucide:arrow-left" width="20" class="text-slate-600"></iconify-icon>
                </a>
            @endif

            <div class="relative">
                <img src="{{ asset('assets/images/logo.png') }}"
                     class="h-10 w-10 object-contain rounded-xl border border-slate-100 shadow-sm">
                <div class="absolute -bottom-1 -right-1 h-3 w-3 bg-emerald-500 border-2 border-white rounded-full"></div>
            </div>

            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">
                    {{ $title }}
                </h1>
                @if ($subtitle)
                    <p class="text-[10px] font-medium text-slate-500 uppercase tracking-widest">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="hidden sm:flex flex-col items-end mr-2">
                <span class="text-xs font-bold text-slate-900">{{ $role }}</span>
                <span class="text-[10px] font-medium text-slate-400" id="currentDate"></span>
            </div>
            <div class="h-10 w-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600 shadow-sm">
                <iconify-icon icon="carbon:user-admin" width="22"></iconify-icon>
            </div>
            {!! $pageHeaderRight ?? '' !!}
        </div>

    </div>
</header>