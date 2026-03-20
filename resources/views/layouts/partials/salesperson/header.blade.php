<header class="sticky top-0 z-30 bg-white border-b border-slate-200">
    <div class="px-4 py-3 flex items-center gap-3">
        <!-- Back Button -->
        @if (!empty($pageConfig['showBack']))
            <a href="{{ $pageConfig['backUrl'] ?? route('salesperson.dashboard') }}"
                class="p-2 rounded-lg border border-slate-200 bg-white active:scale-95 transition-all">
                <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
            </a>
        @endif

        <!-- Logo -->
        <img src="{{ asset('assets/images/logo.png') }}" class="h-9 w-9 rounded-md border border-slate-100 object-contain bg-white">

        <!-- Title -->
        <h1 class="flex-1 text-base font-semibold text-slate-900 truncate">
            {{ $pageConfig['title'] ?? 'Sales Panel' }}
        </h1>

        <!-- Right Action -->
        @if (($pageConfig['headerRight'] ?? '') === 'search')
            <iconify-icon icon="lucide:search" width="18" class="text-slate-400"></iconify-icon>
        @elseif (($pageConfig['headerRight'] ?? '') === 'profile')
            <a href="{{ route('salesperson.profile.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors">
                <iconify-icon icon="lucide:user" width="18"></iconify-icon>
            </a>
        @elseif (($pageConfig['headerRight'] ?? '') === 'add-shop')
            @if($is_off_hours ?? false)
                <button disabled 
                   class="p-2 rounded-lg bg-indigo-600 text-white opacity-50 cursor-not-allowed shadow-sm shadow-indigo-100">
                    <iconify-icon icon="lucide:plus" width="18"></iconify-icon>
                </button>
            @else
                <a href="{{ route('salesperson.shops.create') }}" 
                   class="p-2 rounded-lg bg-indigo-600 text-white active:scale-95 shadow-sm shadow-indigo-100 hover:bg-indigo-700 transition-all">
                    <iconify-icon icon="lucide:plus" width="18"></iconify-icon>
                </a>
            @endif
        @endif
        @if (($pageConfig['headerRight'] ?? '') === 'edit')
            <button onclick="openEditProfile()"
                class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors active:scale-95">
                <iconify-icon icon="lucide:edit-2" width="18" class="text-slate-600"></iconify-icon>
            </button>
        @endif

    </div>
</header>