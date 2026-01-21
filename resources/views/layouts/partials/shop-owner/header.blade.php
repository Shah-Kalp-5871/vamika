@php
$title    = $title    ?? '';
$subtitle = $subtitle ?? '';
$role     = $role     ?? '';
$showBack = $showBack ?? false;
$backUrl  = $backUrl  ?? route('shop-owner.dashboard');
@endphp

<header class="sticky top-0 z-20 bg-white border-b border-slate-100">
    <div class="px-6 py-4 flex items-start justify-between">

        <div class="flex items-start gap-3">
            @if ($showBack)
                <a href="{{ $backUrl }}"
                   class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition">
                    <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
                </a>
            @endif

            <img src="{{ asset('assets/images/logo.png') }}"
                 class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1"
                 alt="Vamika Enterprise">

            <div>
                <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
                    {{ $title }}
                </h1>

                @if (!empty($subtitle))
                    <p class="text-xs text-slate-500">{{ $subtitle }}</p>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex flex-col items-end gap-2">
                @if (!empty($role))
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs
                                 bg-indigo-50 text-indigo-600 border border-indigo-100">
                        <iconify-icon icon="carbon:user-admin" width="12" class="mr-1.5"></iconify-icon>
                        {{ $role }}
                    </span>
                @endif

                <span class="text-xs text-slate-400" id="currentDate"></span>
            </div>
            
            @if (!empty($headerAction))
                <button
                    id="{{ $headerAction['id'] }}"
                    class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors"
                >
                    <iconify-icon icon="{{ $headerAction['icon'] }}" width="18"></iconify-icon>
                </button>
            @endif
        </div>

    </div>
</header>