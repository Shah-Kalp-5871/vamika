@php
// Set default pageConfig if not set
$pageConfig = $pageConfig ?? [];
$pageConfig['title'] = $pageConfig['title'] ?? 'Sales Panel';
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageConfig['title'] }}</title>

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
    @include('layouts.partials.salesperson.header')
    
    <main class="page-wrapper">
        @yield('content')
    </main>
    
    @if (!empty($pageConfig['showBottomNav']))
        @include('layouts.partials.bottom-nav.sales')
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