@php
// Set default variables if not set
$title = $title ?? 'Shop Panel';
$role = $role ?? 'Shop Owner';
$showBack = $showBack ?? false;
$backUrl = $backUrl ?? route('shop-owner.dashboard');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title }}</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Common CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bottom-nav.css') }}">
    
    @if (!empty($pageConfig['css'] ?? []))
        @foreach ($pageConfig['css'] as $css)
            <link rel="stylesheet" href="{{ $css }}">
        @endforeach
    @endif
</head>
<body class="text-slate-600 bg-[#FAFAFA]">
    @include('layouts.partials.shop-owner.header')
    
    <main class="min-h-screen pb-20">
        @yield('content')
    </main>
    
    @include('layouts.partials.bottom-nav.shop')

    <!-- Common Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('assets/js/toast.js') }}"></script>
    <!-- Chart.js JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
</body>
</html>