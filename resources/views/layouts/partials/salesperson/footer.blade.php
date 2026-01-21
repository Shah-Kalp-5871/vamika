</main>

@if (!empty($pageConfig['showBottomNav']))
    @include('layouts.partials.bottom-nav.sales')
@endif

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="{{ asset('assets/js/toast.js') }}"></script>
<!-- Chart.js JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>