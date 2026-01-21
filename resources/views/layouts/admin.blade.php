@php
// Set default pageConfig if not set
$pageConfig = $pageConfig ?? [];
$pageConfig['title'] = $pageConfig['title'] ?? 'Admin Panel';
$pageConfig['role'] = $pageConfig['role'] ?? 'Admin';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $pageConfig['title'] }}</title>

    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Common CSS -->
    <link rel="stylesheet" href="{{ asset('assets/admin/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bottom-nav.css') }}">
    
    @yield('styles') 
    {{-- maadar css BSDK--}}

    {!! $pageConfig['extraHead'] ?? '' !!}
</head>
<body class="text-slate-600">
    @include('layouts.partials.admin.header')
    
    <main class="min-h-screen pb-20">
        @yield('content')
    </main>
    
    @if (!empty($pageConfig['showBottomNav']))
        @include('layouts.partials.bottom-nav.admin')
    @endif

    <!-- Common Scripts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="{{ asset('assets/js/toast.js') }}"></script>
    <!-- Chart.js JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @yield('scripts')
</body>
</html>