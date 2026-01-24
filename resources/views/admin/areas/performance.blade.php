@extends('layouts.admin')

@section('title', $area->name . ' Performance - Admin')

@section('styles')
<style>
    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
    }

    @media (min-width: 640px) {
        .main-content {
            max-width: 42rem;
            margin: 2rem auto;
            min-height: calc(100vh - 4rem);
            background-color: white;
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            border-radius: 1rem;
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .shop-card {
        border-radius: 0.75rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
        background: white;
        border: 1px solid #E2E8F0;
    }

    .shop-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .trend-up {
        background: #D1FAE5;
        color: #065F46;
    }

    .trend-down {
        background: #FEE2E2;
        color: #991B1B;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100">
        <div class="px-6 py-4 space-y-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.areas.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 transition-colors">
                    <iconify-icon icon="lucide:arrow-left" width="18"></iconify-icon>
                    Back to Areas
                </a>
            </div>

            <div>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">
                    {{ $area->name }} Performance
                </h1>
                <p class="text-sm text-slate-500">
                    Detailed metrics for {{ $area->code }}
                </p>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-6">
        <!-- Stats Summary -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_shops'] }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Shops</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-bold text-slate-900">₹{{ number_format($stats['total_revenue']) }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Revenue</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_orders'] }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Orders</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-bold text-slate-900">₹{{ number_format($stats['avg_order_value']) }}</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Avg. Order Value</p>
            </div>
        </div>

        <!-- Assignments Summary -->
        <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white rounded-lg text-indigo-600 shadow-sm border border-indigo-100">
                    <iconify-icon icon="lucide:users" width="20"></iconify-icon>
                </div>
                <div>
                    <p class="text-sm font-semibold text-slate-900">{{ $stats['salespersons_count'] }} Salespersons</p>
                    <p class="text-xs text-slate-500">Currently assigned to shops in this area</p>
                </div>
            </div>
            <a href="{{ route('admin.salespersons.assign.form', ['area_id' => $area->id]) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">
                Manage
            </a>
        </div>

        <!-- Top Shops in Area -->
        <div class="space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider">Top Shops by Revenue</h3>
                <span class="text-xs text-slate-500">{{ $stats['shops_performance']->count() }} shops listed</span>
            </div>

            <div class="space-y-3">
                @foreach($stats['shops_performance'] as $shop)
                <div class="shop-card">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h4 class="text-sm font-bold text-slate-900">{{ $shop['name'] }}</h4>
                            <p class="text-xs text-slate-500">Owner: {{ $shop['owner'] }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-slate-900">₹{{ number_format($shop['revenue']) }}</p>
                            <p class="text-xs text-slate-500">{{ $shop['orders'] }} orders</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-3 border-t border-slate-50">
                        <span class="text-xs text-slate-400">
                            Last Order: {{ $shop['last_order'] ? $shop['last_order']->diffForHumans() : 'No orders' }}
                        </span>
                        <a href="#" class="text-xs font-bold text-indigo-600 hover:text-indigo-700">View Shop</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Export -->
        <button class="btn-primary w-full" onclick="alert('Exporting area report...')">
            <iconify-icon icon="lucide:download" width="16" class="mr-2"></iconify-icon>
            Export Area Performance
        </button>
    </main>
</div>
@endsection
