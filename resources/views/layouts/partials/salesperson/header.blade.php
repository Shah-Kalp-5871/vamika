<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageConfig['title'] ?? 'Sales Panel' }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!-- Common CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/salesperson-app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bottom-nav.css') }}">
</head>

<body class="text-slate-600">

    <header class="sticky top-0 z-30 bg-white border-b border-slate-200">

        <div class="px-4 py-3 flex items-center gap-3">

            <!-- Back Button -->
            @if (!empty($pageConfig['showBack']))
                <a href="{{ $pageConfig['backUrl'] ?? route('salesperson.dashboard') }}"
                    class="p-2 rounded-lg border border-slate-200 bg-white active:scale-95">
                    <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
                </a>
            @endif

            <!-- Logo -->
            <img src="{{ asset('assets/images/logo.png') }}" class="h-9 w-9 rounded-md border object-contain">

            <!-- Title -->
            <h1 class="flex-1 text-base font-semibold text-slate-900 truncate">
                {{ $pageConfig['title'] ?? 'Sales Panel' }}
            </h1>


            <!-- Right Action -->
            @if (($pageConfig['headerRight'] ?? '') === 'search')
                <iconify-icon icon="lucide:search" width="18"></iconify-icon>
            @elseif (($pageConfig['headerRight'] ?? '') === 'profile')
                <a href="{{ route('salesperson.profile.index') }}">
                    <iconify-icon icon="lucide:user" width="18"></iconify-icon>
                </a>
            @elseif (($pageConfig['headerRight'] ?? '') === 'add-shop')
                @if($is_off_hours ?? false)
                    <button disabled 
                       class="p-2 rounded-lg bg-indigo-600 text-white opacity-50 cursor-not-allowed shadow-sm">
                        <iconify-icon icon="lucide:plus" width="18"></iconify-icon>
                    </button>
                @else
                    <a href="{{ route('salesperson.shops.create') }}" 
                       class="p-2 rounded-lg bg-indigo-600 text-white active:scale-95 shadow-sm">
                        <iconify-icon icon="lucide:plus" width="18"></iconify-icon>
                    </a>
                @endif
            @endif
            @if (($pageConfig['headerRight'] ?? '') === 'edit')
                <button onclick="openEditProfile()"
                    class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="lucide:edit-2" width="18"></iconify-icon>
                </button>
            @endif

        </div>
    </header>
    <main class="page-wrapper">