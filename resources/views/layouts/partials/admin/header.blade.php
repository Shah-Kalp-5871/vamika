@php
$pageConfig = $pageConfig ?? [];

$title    = $pageConfig['title'] ?? 'Admin Panel';
$subtitle = $pageConfig['subtitle'] ?? '';
$showBack = $pageConfig['showBack'] ?? false;
$backUrl  = $pageConfig['backUrl'] ?? 'javascript:history.back()';
$role     = $pageConfig['role'] ?? 'Admin';
@endphp

<header class="sticky top-0 z-20 bg-white border-b border-slate-100">
    <div class="px-6 py-4 flex items-start justify-between">

        <div class="flex items-start gap-3">
            @if ($showBack)
                <a href="{{ $backUrl }}"
                   class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50">
                    <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
                </a>
            @endif

            <img src="{{ asset('assets/images/logo.png') }}"
                 class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1">

            <div>
                <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
                    {{ $title }}
                </h1>
                @if ($subtitle)
                    <p class="text-xs text-slate-500">{{ $subtitle }}</p>
                @endif
                {!! $pageHeaderLeft ?? '' !!}
            </div>
        </div>

        <div class="flex flex-col items-end gap-2">
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs
                         bg-indigo-50 text-indigo-600 border border-indigo-100">
                <iconify-icon icon="carbon:user-admin" width="12" class="mr-1.5"></iconify-icon>
                {{ $role }}
            </span>
            <span class="text-xs text-slate-400" id="currentDate"></span>
            {!! $pageHeaderRight ?? '' !!}
        </div>

    </div>
</header>