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
    @if($is_off_hours ?? false)
        <div class="bg-amber-50 border-b border-amber-200 px-4 py-2 sticky top-0 z-[100] flex items-center gap-3">
            <div class="h-8 w-8 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 shrink-0">
                <iconify-icon icon="lucide:clock" width="18"></iconify-icon>
            </div>
            <div class="flex-1">
                <p class="text-xs font-bold text-amber-900 leading-tight">View-Only Mode</p>
                <p class="text-[10px] text-amber-700 leading-tight mt-0.5">Off-duty. Working hours: {{ $work_hours_string ?? 'N/A' }} (IST)</p>
            </div>
        </div>
    @endif
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