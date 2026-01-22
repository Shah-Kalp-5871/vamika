@extends('layouts.admin')

@section('title', 'Order Consolidation')

@php
$pageConfig = [
    'showBottomNav' => true,
    'showBack' => true,
    'backUrl' => route('admin.dashboard'),
];
@endphp

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        background-color: #F8FAFC;
        -webkit-font-smoothing: antialiased;
        padding-bottom: 80px;
    }

    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FFFFFF;
    }

    @media (min-width: 1024px) {
        .main-content {
            max-width: 1280px;
            margin: 1rem auto;
            min-height: calc(100vh - 2rem);
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 1rem;
            border: 1px solid #E2E8F0;
            overflow: hidden;
        }
    }

    /* Animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }

    .animate-fade-in {
        animation: fadeIn 0.3s ease forwards;
    }

    /* Form Elements */
    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.75rem;
        background: white;
        color: #1E293B;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        font-family: inherit;
    }

    .form-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        background: #FFFFFF;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364758B' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px;
        padding-right: 2.5rem;
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
    }

    .btn-primary:active {
        transform: translateY(0);
    }

    .btn-success {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
    }

    .btn-secondary {
        background: #FFFFFF;
        color: #475569;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        border: 1.5px solid #E2E8F0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        line-height: 1.25rem;
    }

    .btn-secondary:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
    }

    /* Cards */
    .product-card {
        background: white;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .product-card:hover {
        border-color: #6366F1;
        box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
    }

    .stat-card {
        background: white;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    /* Tabs */
    .tab-container {
        display: flex;
        overflow-x: auto;
        background: #F8FAFC;
        border-radius: 0.75rem;
        padding: 0.5rem;
        gap: 0.25rem;
        scrollbar-width: none;
        /* Firefox */
    }

    .tab-container::-webkit-scrollbar {
        display: none;
        /* Chrome, Safari, Edge */
    }

    .tab {
        padding: 0.75rem 1.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #64748B;
        cursor: pointer;
        white-space: nowrap;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: transparent;
        border: none;
        font-family: inherit;
    }

    .tab:hover {
        color: #475569;
        background: rgba(99, 102, 241, 0.05);
    }

    .tab.active {
        color: #6366F1;
        background: white;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0 / 0.1), 0 1px 2px -1px rgba(0, 0, 0 / 0.1);
    }

    /* Category Badges */
    .category-badge {
        padding: 0.375rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        line-height: 1;
    }

    .category-groceries {
        background-color: #ECFDF5;
        color: #065F46;
        border: 1px solid #D1FAE5;
    }

    .category-food {
        background-color: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .category-personal-care {
        background-color: #FCE7F3;
        color: #BE185D;
        border: 1px solid #FBCFE8;
    }

    .category-beverages {
        background-color: #E0E7FF;
        color: #3730A3;
        border: 1px solid #C7D2FE;
    }

    .category-home-care {
        background-color: #EDE9FE;
        color: #5B21B6;
        border: 1px solid #DDD6FE;
    }

    .category-dairy {
        background-color: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    /* Table Styles */
    .table-container {
        position: relative;
        overflow: hidden;
        border-radius: 0.75rem;
        border: 1.5px solid #E2E8F0;
    }

    .table-header {
        background: #F8FAFC;
        border-bottom: 1.5px solid #E2E8F0;
        padding: 1rem 1.5rem;
    }

    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.875rem;
    }

    .table th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 600;
        color: #475569;
        background: #F8FAFC;
        border-bottom: 1.5px solid #E2E8F0;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .table td {
        padding: 1rem 1.5rem;
        color: #1E293B;
        border-bottom: 1px solid #E2E8F0;
        vertical-align: top;
    }

    .table tbody tr {
        transition: background-color 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #F8FAFC;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Pagination */
    .pagination-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.5rem;
        border-top: 1.5px solid #E2E8F0;
        background: white;
    }

    .pagination-info {
        font-size: 0.875rem;
        color: #64748B;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .pagination-btn {
        padding: 0.5rem 0.75rem;
        border: 1.5px solid #E2E8F0;
        background: white;
        color: #475569;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        min-width: 2.5rem;
        justify-content: center;
    }

    .pagination-btn:hover:not(:disabled) {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: #1E293B;
    }

    .pagination-btn.active {
        background: #6366F1;
        color: white;
        border-color: #6366F1;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .page-input {
        width: 4rem;
        text-align: center;
        padding: 0.5rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        color: #475569;
    }

    .page-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* Search Bar */
    .search-container {
        position: relative;
        width: 100%;
    }

    .search-input {
        padding-left: 2.75rem;
        padding-right: 2.75rem;
        border: 1.5px solid #E2E8F0;
        border-radius: 0.75rem;
        background: white;
        color: #1E293B;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        width: 100%;
        font-family: inherit;
    }

    .search-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        pointer-events: none;
    }

    .search-clear {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        cursor: pointer;
        background: none;
        border: none;
        padding: 0.25rem;
        border-radius: 0.375rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .search-clear:hover {
        color: #64748B;
        background: #F1F5F9;
    }

    /* Filter Badges */
    .filter-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .filter-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.375rem 0.75rem;
        background: #E0E7FF;
        color: #3730A3;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .filter-badge:hover {
        background: #C7D2FE;
    }

    .filter-badge .close {
        font-size: 0.875rem;
        line-height: 1;
    }

    /* Loading States */
    .loading-spinner {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid #E2E8F0;
        border-top-color: #6366F1;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .skeleton {
        background: linear-gradient(90deg, #F1F5F9 25%, #E2E8F0 50%, #F1F5F9 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.5s ease-in-out infinite;
        border-radius: 0.375rem;
    }

    @keyframes skeleton-loading {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* Empty States */
    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
    }

    .empty-state-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        color: #CBD5E1;
    }

    .empty-state-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .empty-state-description {
        font-size: 0.875rem;
        color: #64748B;
        max-width: 20rem;
        margin-bottom: 1.5rem;
    }

    /* Truncated Text with Tooltip */
    .truncate-text {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        display: block;
    }

    .truncate-text:hover {
        overflow: visible;
        white-space: normal;
        word-break: break-word;
        position: relative;
        z-index: 100;
        background: white;
        padding: 0.5rem;
        border-radius: 0.375rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        border: 1px solid #E2E8F0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .table-container {
            overflow-x: auto;
        }

        .table {
            min-width: 768px;
        }

        .pagination-container {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .pagination-controls {
            justify-content: center;
        }
    }

    /* Scrollbar Styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #F1F5F9;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #CBD5E1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #94A3B8;
    }

    /* Progress Indicators */
    .progress-bar {
        width: 100%;
        height: 0.375rem;
        background: #E2E8F0;
        border-radius: 9999px;
        overflow: hidden;
        margin: 0.5rem 0;
    }

    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, #6366F1, #8B5CF6);
        border-radius: 9999px;
        transition: width 0.3s ease;
    }

    /* Modal Styles (for product details) */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        z-index: 1000;
        animation: fadeIn 0.2s ease;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        width: 100%;
        max-width: 48rem;
        max-height: 90vh;
        overflow: hidden;
        animation: slideUp 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1.5px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #F8FAFC;
    }

    .modal-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1E293B;
    }

    .modal-close {
        background: none;
        border: none;
        color: #64748B;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-close:hover {
        background: #F1F5F9;
        color: #475569;
    }

    .modal-body {
        padding: 1.5rem;
        overflow-y: auto;
        flex: 1;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1.5px solid #E2E8F0;
        display: flex;
        justify-content: flex-end;
        gap: 0.75rem;
        background: #F8FAFC;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-4 lg:p-6 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 tracking-tight">Order Consolidation</h1>
                <p class="text-slate-500 mt-1">Aggregated product requirements across all sales orders</p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="exportConsolidatedReport()" class="btn-success">
                    <iconify-icon icon="lucide:download" width="16"></iconify-icon>
                    Export Report
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Products -->
            <div class="stat-card animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Products</p>
                        <p class="text-2xl lg:text-3xl font-bold text-slate-900" id="totalProducts">0</p>
                    </div>
                    <div class="p-3 bg-indigo-50 rounded-xl border border-indigo-100">
                        <iconify-icon icon="lucide:package" width="24" class="text-indigo-600"></iconify-icon>
                    </div>
                </div>
                <div class="progress-bar">
                    <div id="productsProgress" class="progress-fill" style="width: 0%"></div>
                </div>
            </div>

            <!-- Total Quantity -->
            <div class="stat-card animate-slide-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Quantity</p>
                        <p class="text-2xl lg:text-3xl font-bold text-slate-900" id="totalQuantity">0</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                        <iconify-icon icon="lucide:shopping-cart" width="24"
                            class="text-emerald-600"></iconify-icon>
                    </div>
                </div>
                <div class="progress-bar">
                    <div id="quantityProgress" class="progress-fill" style="width: 0%"></div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="stat-card animate-slide-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Orders</p>
                        <p class="text-2xl lg:text-3xl font-bold text-slate-900" id="totalOrders">0</p>
                    </div>
                    <div class="p-3 bg-blue-50 rounded-xl border border-blue-100">
                        <iconify-icon icon="lucide:clipboard-list" width="24" class="text-blue-600"></iconify-icon>
                    </div>
                </div>
                <div class="progress-bar">
                    <div id="ordersProgress" class="progress-fill" style="width: 0%"></div>
                </div>
            </div>

            <!-- Total Value -->
            <div class="stat-card animate-slide-up" style="animation-delay: 0.3s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-500 mb-1">Total Value</p>
                        <p class="text-2xl lg:text-3xl font-bold text-slate-900" id="totalValue">₹0</p>
                    </div>
                    <div class="p-3 bg-amber-50 rounded-xl border border-amber-100">
                        <iconify-icon icon="lucide:indian-rupee" width="24" class="text-amber-600"></iconify-icon>
                    </div>
                </div>
                <div class="progress-bar">
                    <div id="valueProgress" class="progress-fill" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Search and Filters Section -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 lg:p-6 animate-slide-up"
            style="animation-delay: 0.4s;">
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="search-container">
                    <iconify-icon icon="lucide:search" width="20" class="search-icon"></iconify-icon>
                    <input type="text" id="searchInput"
                        placeholder="Search products by name, category, or salesperson..." class="search-input py-3"
                        oninput="handleSearch()">
                    <button id="clearSearch" class="search-clear hidden">
                        <iconify-icon icon="lucide:x" width="18"></iconify-icon>
                    </button>
                </div>

                <!-- Active Filters -->
                <div id="activeFilters" class="filter-badges hidden">
                    <!-- Filters will be dynamically added here -->
                </div>
            </div>

            <!-- Filter Controls -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Date Range</label>
                    <select class="form-input form-select" id="dateFilter" onchange="applyFilters()">
                        <option value="today">Today's Orders</option>
                        <option value="tomorrow">Tomorrow's Delivery</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="year">This Year</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Salesperson</label>
                    <select class="form-input form-select" id="salespersonFilter" onchange="applyFilters()">
                        <option value="all">All Salespersons</option>
                        <option value="Rajesh Kumar">Rajesh Kumar</option>
                        <option value="Suresh Patel">Suresh Patel</option>
                        <option value="Vikram Singh">Vikram Singh</option>
                        <option value="Anjali Sharma">Anjali Sharma</option>
                        <option value="Mohammed Ali">Mohammed Ali</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Category</label>
                    <select class="form-input form-select" id="categoryFilter" onchange="applyFilters()">
                        <option value="all">All Categories</option>
                        <option value="Groceries">Groceries</option>
                        <option value="Food">Food</option>
                        <option value="Home Care">Home Care</option>
                        <option value="Personal Care">Personal Care</option>
                        <option value="Beverages">Beverages</option>
                        <option value="Dairy">Dairy</option>
                    </select>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap items-center justify-between gap-4 mt-6 pt-6 border-t border-slate-200">
                <div class="text-sm text-slate-500" id="resultsInfo">
                    Showing 0 of 0 products
                </div>
                <div class="flex items-center gap-3">
                    <button onclick="clearAllFilters()" class="btn-secondary">
                        <iconify-icon icon="lucide:filter-x" width="16"></iconify-icon>
                        Clear Filters
                    </button>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-600">Show:</span>
                        <select class="form-input form-select py-2" id="itemsPerPage"
                            onchange="changeItemsPerPage()">
                            <option value="10">10</option>
                            <option value="20" selected>20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tab-container">
            <button class="tab active" onclick="showTab('summary')">
                <iconify-icon icon="lucide:layers"></iconify-icon>
                Product Summary
            </button>
            <button class="tab" onclick="showTab('cards')">
                <iconify-icon icon="lucide:grid-3x3"></iconify-icon>
                Product Cards
            </button>
            <button class="tab" onclick="showTab('details')">
                <iconify-icon icon="lucide:file-text"></iconify-icon>
                Order Details
            </button>
            <button class="tab" onclick="showTab('analysis')">
                <iconify-icon icon="lucide:bar-chart-3"></iconify-icon>
                Analysis
            </button>
        </div>

        <!-- Summary Tab Content -->
        <div id="summaryTab" class="tab-content">
            <div class="table-container animate-slide-up">
                <div class="table-header">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-semibold text-slate-900">Consolidated Product Requirements</h3>
                        <span class="text-sm text-slate-500" id="tableInfo">Loading products...</span>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="tableLoading" class="p-8">
                    <div class="flex flex-col items-center justify-center">
                        <div class="loading-spinner mb-3"></div>
                        <p class="text-sm text-slate-500">Loading consolidated data...</p>
                    </div>
                </div>

                <!-- Table -->
                <div id="tableContent" class="hidden">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-8">#</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th class="text-right">Unit Price</th>
                                <th class="text-right">Total Required</th>
                                <th class="text-right">Total Value</th>
                                <th class="text-right">Orders</th>
                                <th class="w-24">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="summaryTable">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>

                    <!-- Empty State -->
                    <div id="emptyTable" class="empty-state hidden">
                        <div class="empty-state-icon">
                            <iconify-icon icon="lucide:package-open" width="48"></iconify-icon>
                        </div>
                        <h3 class="empty-state-title">No products found</h3>
                        <p class="empty-state-description">
                            No products match your current search or filter criteria. Try adjusting your filters.
                        </p>
                        <button onclick="clearAllFilters()" class="btn-secondary">
                            Clear All Filters
                        </button>
                    </div>

                    <!-- Pagination -->
                    <div id="pagination" class="pagination-container hidden">
                        <div class="pagination-info">
                            Showing <span id="startIndex">0</span>-<span id="endIndex">0</span> of <span
                                id="totalItems">0</span> products
                        </div>
                        <div class="pagination-controls">
                            <button onclick="changePage('first')" class="pagination-btn">
                                <iconify-icon icon="lucide:chevrons-left" width="16"></iconify-icon>
                            </button>
                            <button onclick="changePage('prev')" class="pagination-btn">
                                <iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>
                            </button>

                            <div class="flex items-center gap-1">
                                <input type="number" id="pageInput" min="1" max="1" value="1" class="page-input"
                                    onchange="goToPage()">
                                <span class="text-sm text-slate-500">of</span>
                                <span id="totalPages" class="text-sm font-medium text-slate-700">1</span>
                            </div>

                            <button onclick="changePage('next')" class="pagination-btn">
                                <iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>
                            </button>
                            <button onclick="changePage('last')" class="pagination-btn">
                                <iconify-icon icon="lucide:chevrons-right" width="16"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Cards Tab Content -->
        <div id="cardsTab" class="tab-content hidden">
            <div id="cardsContent" class="hidden">
                <!-- Cards will be loaded here -->
            </div>

            <!-- Cards Loading State -->
            <div id="cardsLoading" class="p-8">
                <div class="flex flex-col items-center justify-center">
                    <div class="loading-spinner mb-3"></div>
                    <p class="text-sm text-slate-500">Loading product cards...</p>
                </div>
            </div>

            <!-- Cards Empty State -->
            <div id="emptyCards" class="empty-state hidden">
                <div class="empty-state-icon">
                    <iconify-icon icon="lucide:package-open" width="48"></iconify-icon>
                </div>
                <h3 class="empty-state-title">No products found</h3>
                <p class="empty-state-description">
                    No products match your current search or filter criteria.
                </p>
            </div>

            <!-- Cards Pagination -->
            <div id="cardsPagination" class="pagination-container hidden mt-4">
                <div class="pagination-info">
                    Showing <span id="cardsStart">0</span>-<span id="cardsEnd">0</span> of <span
                        id="cardsTotal">0</span> products
                </div>
                <div class="pagination-controls">
                    <button onclick="changeCardsPage('first')" class="pagination-btn">
                        <iconify-icon icon="lucide:chevrons-left" width="16"></iconify-icon>
                    </button>
                    <button onclick="changeCardsPage('prev')" class="pagination-btn">
                        <iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>
                    </button>

                    <div class="flex items-center gap-1">
                        <span class="text-sm font-medium text-slate-700">Page</span>
                        <input type="number" id="cardsPageInput" min="1" max="1" value="1" class="page-input"
                            onchange="goToCardsPage()">
                        <span class="text-sm text-slate-500">of</span>
                        <span id="cardsTotalPages" class="text-sm font-medium text-slate-700">1</span>
                    </div>

                    <button onclick="changeCardsPage('next')" class="pagination-btn">
                        <iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>
                    </button>
                    <button onclick="changeCardsPage('last')" class="pagination-btn">
                        <iconify-icon icon="lucide:chevrons-right" width="16"></iconify-icon>
                    </button>
                </div>
            </div>
        </div>

        <!-- Details Tab Content -->
        <div id="detailsTab" class="tab-content hidden">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Detailed Order Breakdown</h3>
                <div id="detailsContent">
                    <!-- Details will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Analysis Tab Content -->
        <div id="analysisTab" class="tab-content hidden">
            <div class="bg-white rounded-xl border border-slate-200 p-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-4">Consolidation Analysis</h3>
                <div id="analysisContent">
                    <!-- Analysis will be loaded here -->
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Product Details Modal -->
<div id="productModal" class="modal-overlay hidden">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Product Details</h2>
            <button onclick="closeModal()" class="modal-close">
                <iconify-icon icon="lucide:x" width="20"></iconify-icon>
            </button>
        </div>
        <div class="modal-body" id="modalBody">
            <!-- Modal content will be loaded here -->
        </div>
        <div class="modal-footer">
            <button onclick="closeModal()" class="btn-secondary">Close</button>
            <button onclick="printProductReport()" class="btn-primary">
                <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
                Print Report
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Extended sample data with more realistic information


    // Application State
    const state = {
        allProducts: @json($products),
        filteredProducts: [],
        currentTab: 'summary',
        currentPage: 1,
        currentCardsPage: 1,
        itemsPerPage: 20,
        cardsPerPage: 12,
        searchQuery: '',
        filters: {
            dateRange: 'month',
            salesperson: 'all',
            category: 'all'
        },
        sortBy: 'quantity',
        sortDirection: 'desc'
    };

    // Initialize application
    document.addEventListener('DOMContentLoaded', function () {
        initializeApp();
    });

    function initializeApp() {
        // Initialize products
        state.filteredProducts = [...state.allProducts];

        // Set up event listeners
        setupEventListeners();

        // Initial render
        applyFilters();
        updateStats();
        renderSummaryTable();

        // Animate content
        animateContent();
    }

    function setupEventListeners() {
        // Search input with debounce
        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function (e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                handleSearch();
            }, 300);
        });

        // Clear search button
        document.getElementById('clearSearch').addEventListener('click', function () {
            document.getElementById('searchInput').value = '';
            handleSearch();
        });

        // Page input
        document.getElementById('pageInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                goToPage();
            }
        });

        document.getElementById('cardsPageInput').addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                goToCardsPage();
            }
        });

        // Modal close with escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    }

    function animateContent() {
        const elements = document.querySelectorAll('.animate-slide-up');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.1}s`;
        });
    }

    function handleSearch() {
        const searchInput = document.getElementById('searchInput');
        const clearButton = document.getElementById('clearSearch');
        state.searchQuery = searchInput.value.toLowerCase().trim();

        if (state.searchQuery) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }

        state.currentPage = 1;
        state.currentCardsPage = 1;
        applyFilters();
    }

    function applyFilters() {
        // Start with all products
        let filtered = [...state.allProducts];

        // Apply search filter
        if (state.searchQuery) {
            filtered = filtered.filter(product => {
                const searchableText = [
                    product.name,
                    product.category,
                    product.brand,
                    product.description,
                    product.sku
                ].join(' ').toLowerCase();

                return searchableText.includes(state.searchQuery) ||
                    product.currentOrders.some(order =>
                        order.salesperson.toLowerCase().includes(state.searchQuery) ||
                        order.shop.toLowerCase().includes(state.searchQuery)
                    );
            });
        }

        // Apply category filter
        if (state.filters.category !== 'all') {
            filtered = filtered.filter(product => product.category === state.filters.category);
        }

        // Apply salesperson filter
        if (state.filters.salesperson !== 'all') {
            filtered = filtered.filter(product =>
                product.currentOrders.some(order => order.salesperson === state.filters.salesperson)
            );
        }

        // Apply date range filter (simplified for demo)
        if (state.filters.dateRange !== 'all') {
            // In real app, filter by actual dates
            filtered = filtered.filter(product => product.currentOrders.length > 0);
        }

        // Sort products
        filtered.sort((a, b) => {
            let aValue, bValue;

            switch (state.sortBy) {
                case 'quantity':
                    aValue = a.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                    bValue = b.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                    break;
                case 'value':
                    const aTotalQuantity = a.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                    const bTotalQuantity = b.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                    aValue = aTotalQuantity * a.price;
                    bValue = bTotalQuantity * b.price;
                    break;
                case 'orders':
                    aValue = a.currentOrders.length;
                    bValue = b.currentOrders.length;
                    break;
                case 'name':
                    aValue = a.name.toLowerCase();
                    bValue = b.name.toLowerCase();
                    break;
                default:
                    aValue = a.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                    bValue = b.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
            }

            if (state.sortDirection === 'asc') {
                return aValue > bValue ? 1 : -1;
            } else {
                return aValue < bValue ? 1 : -1;
            }
        });

        state.filteredProducts = filtered;
        updateStats();
        updateActiveFilters();

        // Update current tab content
        switch (state.currentTab) {
            case 'summary':
                renderSummaryTable();
                break;
            case 'cards':
                renderProductCards();
                break;
            case 'details':
                renderOrderDetails();
                break;
            case 'analysis':
                renderAnalysis();
                break;
        }
    }

    function updateStats() {
        const totalProducts = state.filteredProducts.length;
        let totalQuantity = 0;
        let totalOrders = 0;
        let totalValue = 0;

        state.filteredProducts.forEach(product => {
            const productQuantity = product.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
            totalQuantity += productQuantity;
            totalOrders += product.currentOrders.length;
            totalValue += productQuantity * product.price;
        });

        // Update stats display
        document.getElementById('totalProducts').textContent = totalProducts.toLocaleString();
        document.getElementById('totalQuantity').textContent = totalQuantity.toLocaleString();
        document.getElementById('totalOrders').textContent = totalOrders.toLocaleString();
        document.getElementById('totalValue').textContent = `₹${totalValue.toLocaleString()}`;

        // Update progress bars (relative to all products)
        const allProductsTotal = state.allProducts.length;
        const allProductsQuantity = state.allProducts.reduce((sum, product) =>
            sum + product.currentOrders.reduce((sum2, order) => sum2 + order.quantity, 0), 0);
        const allProductsOrders = state.allProducts.reduce((sum, product) => sum + product.currentOrders.length, 0);
        const allProductsValue = state.allProducts.reduce((sum, product) =>
            sum + (product.currentOrders.reduce((sum2, order) => sum2 + order.quantity, 0) * product.price), 0);

        document.getElementById('productsProgress').style.width = `${(totalProducts / allProductsTotal) * 100}%`;
        document.getElementById('quantityProgress').style.width = `${(totalQuantity / allProductsQuantity) * 100}%`;
        document.getElementById('ordersProgress').style.width = `${(totalOrders / allProductsOrders) * 100}%`;
        document.getElementById('valueProgress').style.width = `${(totalValue / allProductsValue) * 100}%`;

        // Update results info
        document.getElementById('resultsInfo').textContent =
            `Showing ${totalProducts} of ${state.allProducts.length} products`;
    }

    function updateActiveFilters() {
        const container = document.getElementById('activeFilters');
        container.innerHTML = '';

        let hasFilters = false;

        // Search filter
        if (state.searchQuery) {
            hasFilters = true;
            const badge = document.createElement('button');
            badge.className = 'filter-badge';
            badge.innerHTML = `
                Search: "${state.searchQuery}"
                <span class="close" onclick="clearSearch()">&times;</span>
            `;
            badge.onclick = clearSearch;
            container.appendChild(badge);
        }

        // Category filter
        if (state.filters.category !== 'all') {
            hasFilters = true;
            const badge = document.createElement('button');
            badge.className = 'filter-badge';
            badge.innerHTML = `
                Category: ${state.filters.category}
                <span class="close" onclick="clearFilter('category')">&times;</span>
            `;
            badge.onclick = () => clearFilter('category');
            container.appendChild(badge);
        }

        // Salesperson filter
        if (state.filters.salesperson !== 'all') {
            hasFilters = true;
            const badge = document.createElement('button');
            badge.className = 'filter-badge';
            badge.innerHTML = `
                Salesperson: ${state.filters.salesperson}
                <span class="close" onclick="clearFilter('salesperson')">&times;</span>
            `;
            badge.onclick = () => clearFilter('salesperson');
            container.appendChild(badge);
        }

        // Date range filter
        if (state.filters.dateRange !== 'month') {
            hasFilters = true;
            const badge = document.createElement('button');
            badge.className = 'filter-badge';
            badge.innerHTML = `
                Date: ${document.getElementById('dateFilter').options[document.getElementById('dateFilter').selectedIndex].text}
                <span class="close" onclick="clearFilter('dateRange')">&times;</span>
            `;
            badge.onclick = () => clearFilter('dateRange');
            container.appendChild(badge);
        }

        container.classList.toggle('hidden', !hasFilters);
    }

    function clearSearch() {
        document.getElementById('searchInput').value = '';
        state.searchQuery = '';
        document.getElementById('clearSearch').classList.add('hidden');
        applyFilters();
    }

    function clearFilter(filterName) {
        switch (filterName) {
            case 'category':
                state.filters.category = 'all';
                document.getElementById('categoryFilter').value = 'all';
                break;
            case 'salesperson':
                state.filters.salesperson = 'all';
                document.getElementById('salespersonFilter').value = 'all';
                break;
            case 'dateRange':
                state.filters.dateRange = 'month';
                document.getElementById('dateFilter').value = 'month';
                break;
        }
        applyFilters();
    }

    function clearAllFilters() {
        state.searchQuery = '';
        state.filters = {
            dateRange: 'month',
            salesperson: 'all',
            category: 'all'
        };

        document.getElementById('searchInput').value = '';
        document.getElementById('clearSearch').classList.add('hidden');
        document.getElementById('dateFilter').value = 'month';
        document.getElementById('salespersonFilter').value = 'all';
        document.getElementById('categoryFilter').value = 'all';

        applyFilters();
    }

    function changeItemsPerPage() {
        state.itemsPerPage = parseInt(document.getElementById('itemsPerPage').value);
        state.currentPage = 1;
        renderSummaryTable();
    }

    function renderSummaryTable() {
        const loadingElement = document.getElementById('tableLoading');
        const contentElement = document.getElementById('tableContent');
        const tableBody = document.getElementById('summaryTable');
        const emptyElement = document.getElementById('emptyTable');
        const paginationElement = document.getElementById('pagination');
        const tableInfoElement = document.getElementById('tableInfo');

        // Show loading
        loadingElement.classList.remove('hidden');
        contentElement.classList.add('hidden');

        setTimeout(() => {
            const totalItems = state.filteredProducts.length;

            if (totalItems === 0) {
                loadingElement.classList.add('hidden');
                contentElement.classList.remove('hidden');
                emptyElement.classList.remove('hidden');
                paginationElement.classList.add('hidden');
                tableInfoElement.textContent = 'No products found';
                return;
            }

            // Calculate pagination
            const totalPages = Math.ceil(totalItems / state.itemsPerPage);
            const startIndex = (state.currentPage - 1) * state.itemsPerPage;
            const endIndex = Math.min(startIndex + state.itemsPerPage, totalItems);
            const currentItems = state.filteredProducts.slice(startIndex, endIndex);

            // Update table info
            tableInfoElement.textContent = `Showing ${startIndex + 1}-${endIndex} of ${totalItems} products`;

            // Render table rows
            tableBody.innerHTML = '';
            currentItems.forEach((product, index) => {
                const totalQuantity = product.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                const totalValue = totalQuantity * product.price;
                const categoryClass = getCategoryClass(product.category);

                const row = document.createElement('tr');
                row.className = 'animate-fade-in';
                row.style.animationDelay = `${index * 0.05}s`;

                row.innerHTML = `
                    <td class="text-sm text-slate-500 font-medium">${startIndex + index + 1}</td>
                    <td>
                        <div class="flex flex-col">
                            <span class="font-medium text-slate-900 truncate-text" title="${product.name}">${product.name}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-slate-500">SKU: ${product.sku}</span>
                                <span class="text-xs text-slate-400">•</span>
                                <span class="text-xs text-slate-500">${product.brand}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="category-badge ${categoryClass}">
                            ${product.category}
                        </span>
                    </td>
                    <td class="text-right">
                        <div class="font-semibold text-slate-900">₹${product.price}</div>
                        <div class="text-xs text-slate-500">per ${product.unit}</div>
                    </td>
                    <td class="text-right">
                        <div class="font-bold text-slate-900 text-lg">${totalQuantity}</div>
                        <div class="text-xs text-slate-500">${product.unit}</div>
                    </td>
                    <td class="text-right">
                        <div class="font-bold text-emerald-700 text-lg">₹${totalValue.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">@ ₹${product.price}</div>
                    </td>
                    <td class="text-right">
                        <div class="flex flex-col items-end">
                            <span class="font-semibold text-slate-900">${product.currentOrders.length}</span>
                            <span class="text-xs text-slate-500">orders</span>
                        </div>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <button onclick="viewProductDetails(${product.id})" 
                                    class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                    title="View Details">
                                <iconify-icon icon="lucide:eye" width="16"></iconify-icon>
                            </button>
                            <button onclick="exportProductReport(${product.id})" 
                                    class="p-2 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                                    title="Export Report">
                                <iconify-icon icon="lucide:download" width="16"></iconify-icon>
                            </button>
                        </div>
                    </td>
                `;
                tableBody.appendChild(row);
            });

            // Update pagination
            updatePagination(totalItems, totalPages, startIndex, endIndex);

            // Hide loading, show content
            loadingElement.classList.add('hidden');
            contentElement.classList.remove('hidden');
            emptyElement.classList.add('hidden');
            paginationElement.classList.remove('hidden');

        }, 300); // Simulate loading
    }

    function updatePagination(totalItems, totalPages, startIndex, endIndex) {
        document.getElementById('startIndex').textContent = (startIndex + 1).toLocaleString();
        document.getElementById('endIndex').textContent = endIndex.toLocaleString();
        document.getElementById('totalItems').textContent = totalItems.toLocaleString();
        document.getElementById('totalPages').textContent = totalPages.toLocaleString();

        const pageInput = document.getElementById('pageInput');
        pageInput.value = state.currentPage;
        pageInput.max = totalPages;

        // Update button states
        document.querySelectorAll('[onclick*="changePage"]').forEach(btn => {
            const text = btn.getAttribute('onclick');
            if (text.includes("'first'") || text.includes("'prev'")) {
                btn.disabled = state.currentPage === 1;
            } else if (text.includes("'last'") || text.includes("'next'")) {
                btn.disabled = state.currentPage === totalPages;
            }
        });
    }

    function changePage(action) {
        const totalPages = Math.ceil(state.filteredProducts.length / state.itemsPerPage);

        switch (action) {
            case 'first':
                state.currentPage = 1;
                break;
            case 'prev':
                if (state.currentPage > 1) state.currentPage--;
                break;
            case 'next':
                if (state.currentPage < totalPages) state.currentPage++;
                break;
            case 'last':
                state.currentPage = totalPages;
                break;
        }

        renderSummaryTable();

        // Scroll to top of table
        document.getElementById('summaryTab').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function goToPage() {
        const pageInput = document.getElementById('pageInput');
        const page = parseInt(pageInput.value);
        const totalPages = Math.ceil(state.filteredProducts.length / state.itemsPerPage);

        if (page >= 1 && page <= totalPages) {
            state.currentPage = page;
            renderSummaryTable();
        } else {
            pageInput.value = state.currentPage;
        }
    }

    function getCategoryClass(category) {
        const classes = {
            'Groceries': 'category-groceries',
            'Food': 'category-food',
            'Personal Care': 'category-personal-care',
            'Beverages': 'category-beverages',
            'Home Care': 'category-home-care',
            'Dairy': 'category-dairy'
        };
        return classes[category] || 'category-groceries';
    }

    function showTab(tabName) {
        state.currentTab = tabName;

        // Update tab UI
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.currentTarget.classList.add('active');

        // Hide all tabs
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });

        // Show selected tab
        document.getElementById(tabName + 'Tab').classList.remove('hidden');

        // Load tab content if needed
        switch (tabName) {
            case 'summary':
                renderSummaryTable();
                break;
            case 'cards':
                renderProductCards();
                break;
            case 'details':
                renderOrderDetails();
                break;
            case 'analysis':
                renderAnalysis();
                break;
        }
    }

    function renderProductCards() {
        const contentElement = document.getElementById('cardsContent');
        const loadingElement = document.getElementById('cardsLoading');
        const emptyElement = document.getElementById('emptyCards');
        const paginationElement = document.getElementById('cardsPagination');

        // Show loading
        contentElement.classList.add('hidden');
        loadingElement.classList.remove('hidden');
        emptyElement.classList.add('hidden');
        paginationElement.classList.add('hidden');

        setTimeout(() => {
            const totalItems = state.filteredProducts.length;

            if (totalItems === 0) {
                loadingElement.classList.add('hidden');
                emptyElement.classList.remove('hidden');
                return;
            }

            // Calculate pagination
            const totalPages = Math.ceil(totalItems / state.cardsPerPage);
            const startIndex = (state.currentCardsPage - 1) * state.cardsPerPage;
            const endIndex = Math.min(startIndex + state.cardsPerPage, totalItems);
            const currentItems = state.filteredProducts.slice(startIndex, endIndex);

            // Render cards
            contentElement.innerHTML = '';

            currentItems.forEach((product, index) => {
                const totalQuantity = product.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
                const totalValue = totalQuantity * product.price;
                const categoryClass = getCategoryClass(product.category);

                // Calculate salesperson distribution
                const salespersonMap = {};
                product.currentOrders.forEach(order => {
                    if (!salespersonMap[order.salesperson]) {
                        salespersonMap[order.salesperson] = 0;
                    }
                    salespersonMap[order.salesperson] += order.quantity;
                });

                const salespersonHTML = Object.entries(salespersonMap)
                    .map(([name, quantity]) => `
                        <div class="flex justify-between items-center py-2 px-3 bg-slate-50 rounded-lg">
                            <span class="text-sm font-medium text-slate-700 truncate">${name}</span>
                            <span class="text-sm font-semibold text-slate-900">${quantity}</span>
                        </div>
                    `).join('');

                const card = document.createElement('div');
                card.className = 'product-card animate-fade-in';
                card.style.animationDelay = `${index * 0.05}s`;

                card.innerHTML = `
                    <div class="p-5">
                        <!-- Card Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-base font-semibold text-slate-900 truncate-text" title="${product.name}">
                                    ${product.name}
                                </h3>
                                <div class="flex items-center gap-2 mt-2">
                                    <span class="category-badge ${categoryClass}">${product.category}</span>
                                    <span class="text-xs text-slate-500">${product.brand}</span>
                                </div>
                            </div>
                            <div class="p-2 bg-indigo-50 rounded-lg border border-indigo-100 ml-2">
                                <iconify-icon icon="lucide:package" width="18" class="text-indigo-600"></iconify-icon>
                            </div>
                        </div>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-slate-50 p-3 rounded-lg">
                                <div class="text-xs font-medium text-slate-500 mb-1">Total Required</div>
                                <div class="text-lg font-bold text-slate-900">${totalQuantity}</div>
                                <div class="text-xs text-slate-500">${product.unit}</div>
                            </div>
                            <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-100">
                                <div class="text-xs font-medium text-emerald-600 mb-1">Total Value</div>
                                <div class="text-lg font-bold text-emerald-700">₹${totalValue.toLocaleString()}</div>
                                <div class="text-xs text-slate-500">@ ₹${product.price}</div>
                            </div>
                        </div>
                        
                        <!-- Salesperson Breakdown -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2 mb-2">
                                <iconify-icon icon="lucide:users" width="14" class="text-slate-400"></iconify-icon>
                                <span class="text-sm font-medium text-slate-700">Salesperson Distribution</span>
                            </div>
                            <div class="space-y-1.5">
                                ${salespersonHTML}
                            </div>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="bg-slate-50 rounded-lg p-3">
                            <div class="flex justify-between text-sm">
                                <div class="text-slate-600">Orders: <span class="font-semibold">${product.currentOrders.length}</span></div>
                                <div class="text-slate-600">SKU: <span class="font-semibold">${product.sku}</span></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="px-5 pb-5 pt-0">
                        <button onclick="viewProductDetails(${product.id})" 
                                class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                            <iconify-icon icon="lucide:eye" width="16"></iconify-icon>
                            View Details
                        </button>
                    </div>
                `;

                contentElement.appendChild(card);
            });

            // Update cards pagination
            updateCardsPagination(totalItems, totalPages, startIndex, endIndex);

            // Show content
            loadingElement.classList.add('hidden');
            contentElement.classList.remove('hidden');
            paginationElement.classList.remove('hidden');

        }, 300);
    }

    function updateCardsPagination(totalItems, totalPages, startIndex, endIndex) {
        document.getElementById('cardsStart').textContent = (startIndex + 1).toLocaleString();
        document.getElementById('cardsEnd').textContent = endIndex.toLocaleString();
        document.getElementById('cardsTotal').textContent = totalItems.toLocaleString();
        document.getElementById('cardsTotalPages').textContent = totalPages.toLocaleString();

        const pageInput = document.getElementById('cardsPageInput');
        pageInput.value = state.currentCardsPage;
        pageInput.max = totalPages;

        // Update button states
        document.querySelectorAll('[onclick*="changeCardsPage"]').forEach(btn => {
            const text = btn.getAttribute('onclick');
            if (text.includes("'first'") || text.includes("'prev'")) {
                btn.disabled = state.currentCardsPage === 1;
            } else if (text.includes("'last'") || text.includes("'next'")) {
                btn.disabled = state.currentCardsPage === totalPages;
            }
        });
    }

    function changeCardsPage(action) {
        const totalPages = Math.ceil(state.filteredProducts.length / state.cardsPerPage);

        switch (action) {
            case 'first':
                state.currentCardsPage = 1;
                break;
            case 'prev':
                if (state.currentCardsPage > 1) state.currentCardsPage--;
                break;
            case 'next':
                if (state.currentCardsPage < totalPages) state.currentCardsPage++;
                break;
            case 'last':
                state.currentCardsPage = totalPages;
                break;
        }

        renderProductCards();
    }

    function goToCardsPage() {
        const pageInput = document.getElementById('cardsPageInput');
        const page = parseInt(pageInput.value);
        const totalPages = Math.ceil(state.filteredProducts.length / state.cardsPerPage);

        if (page >= 1 && page <= totalPages) {
            state.currentCardsPage = page;
            renderProductCards();
        } else {
            pageInput.value = state.currentCardsPage;
        }
    }

    function viewProductDetails(productId) {
        const product = state.allProducts.find(p => p.id === productId);
        if (!product) return;

        const totalQuantity = product.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
        const totalValue = totalQuantity * product.price;
        const categoryClass = getCategoryClass(product.category);

        let ordersHTML = '';
        product.currentOrders.forEach((order, index) => {
            ordersHTML += `
                <div class="border border-slate-200 rounded-lg p-4 ${index !== 0 ? 'mt-3' : ''}">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-1">
                            <div class="font-medium text-slate-900">${order.shop}</div>
                            <div class="text-sm text-slate-500 mt-1">Order ID: ${order.orderId}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-slate-900 text-lg">${order.quantity} ${product.unit}</div>
                            <div class="text-sm text-emerald-600 font-medium">₹${order.quantity * product.price}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <div class="text-slate-500">Salesperson</div>
                            <div class="font-medium text-slate-900">${order.salesperson}</div>
                        </div>
                        <div>
                            <div class="text-slate-500">Order Date</div>
                            <div class="font-medium text-slate-900">${order.orderDate}</div>
                        </div>
                        <div>
                            <div class="text-slate-500">Delivery Date</div>
                            <div class="font-medium text-slate-900">${order.deliveryDate}</div>
                        </div>
                        <div>
                            <div class="text-slate-500">Unit Price</div>
                            <div class="font-medium text-slate-900">₹${product.price}</div>
                        </div>
                    </div>
                </div>
            `;
        });

        const modalBody = document.getElementById('modalBody');
        modalBody.innerHTML = `
            <!-- Product Header -->
            <div class="border-b border-slate-200 pb-6 mb-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-slate-900 mb-2">${product.name}</h3>
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="category-badge ${categoryClass}">${product.category}</span>
                            <span class="text-sm text-slate-600">SKU: ${product.sku}</span>
                            <span class="text-sm text-slate-600">Brand: ${product.brand}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-2xl font-bold text-slate-900">₹${product.price}</div>
                        <div class="text-sm text-slate-500">per ${product.unit}</div>
                    </div>
                </div>
                <p class="text-slate-600">${product.description}</p>
            </div>
            
            <!-- Product Stats -->
            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="text-center p-4 bg-slate-50 rounded-lg">
                    <div class="text-2xl font-bold text-slate-900 mb-1">${totalQuantity}</div>
                    <div class="text-sm text-slate-500">Total Quantity</div>
                </div>
                <div class="text-center p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                    <div class="text-2xl font-bold text-emerald-700 mb-1">₹${totalValue.toLocaleString()}</div>
                    <div class="text-sm text-emerald-600">Total Value</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="text-2xl font-bold text-blue-700 mb-1">${product.currentOrders.length}</div>
                    <div class="text-sm text-blue-600">Total Orders</div>
                </div>
            </div>
            
            <!-- Orders List -->
            <div class="mb-6">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">Order Details (${product.currentOrders.length} orders)</h4>
                <div class="max-h-96 overflow-y-auto pr-2">
                    ${ordersHTML}
                </div>
            </div>
            
            <!-- Summary -->
            <div class="bg-slate-50 rounded-lg p-4">
                <h4 class="font-semibold text-slate-900 mb-2">Consolidation Summary</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-slate-500">Average per order</div>
                        <div class="font-medium text-slate-900">${(totalQuantity / product.currentOrders.length).toFixed(1)} ${product.unit}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Average order value</div>
                        <div class="font-medium text-slate-900">₹${(totalValue / product.currentOrders.length).toFixed(2)}</div>
                    </div>
                    <div>
                        <div class="text-slate-500">Total shops</div>
                        <div class="font-medium text-slate-900">
                            ${[...new Set(product.currentOrders.map(order => order.shop))].length}
                        </div>
                    </div>
                    <div>
                        <div class="text-slate-500">Total salespersons</div>
                        <div class="font-medium text-slate-900">
                            ${[...new Set(product.currentOrders.map(order => order.salesperson))].length}
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Show modal
        document.getElementById('productModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('productModal').classList.add('hidden');
    }

    function exportProductReport(productId) {
        const product = state.allProducts.find(p => p.id === productId);
        if (!product) return;

        const totalQuantity = product.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
        const totalValue = totalQuantity * product.price;

        let csv = `Product Report: ${product.name}\n`;
        csv += `Category: ${product.category}\n`;
        csv += `SKU: ${product.sku}\n`;
        csv += `Brand: ${product.brand}\n`;
        csv += `Unit: ${product.unit}\n`;
        csv += `Unit Price: ₹${product.price}\n`;
        csv += `Total Required: ${totalQuantity}\n`;
        csv += `Total Value: ₹${totalValue}\n`;
        csv += `Number of Orders: ${product.currentOrders.length}\n\n`;
        csv += 'Order Details:\n';
        csv += 'Shop,Order ID,Salesperson,Order Date,Delivery Date,Quantity,Value\n';

        product.currentOrders.forEach(order => {
            csv += `"${order.shop}","${order.orderId}","${order.salesperson}","${order.orderDate}","${order.deliveryDate}",${order.quantity},₹${order.quantity * product.price}\n`;
        });

        // Create and trigger download
        const BOM = '\uFEFF'; // UTF-8 BOM
        const blob = new Blob([BOM + csv], { type: 'text/csv;charset=utf-8;' });

        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `product-report-${product.sku}-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        showToast('Product report exported successfully!', 'success');
    }

    function printProductReport() {
        window.print();
    }

    function renderOrderDetails() {
        const container = document.getElementById('detailsContent');
        container.innerHTML = `
            <div class="text-center py-8">
                <iconify-icon icon="lucide:file-text" width="48" class="text-slate-300 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">Order Details View</h4>
                <p class="text-slate-500">Detailed breakdown of all orders by product</p>
            </div>
        `;
        // Implement detailed order view here
    }

    function renderAnalysis() {
        const container = document.getElementById('analysisContent');
        container.innerHTML = `
            <div class="text-center py-8">
                <iconify-icon icon="lucide:bar-chart-3" width="48" class="text-slate-300 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">Consolidation Analysis</h4>
                <p class="text-slate-500">Visual analysis of order consolidation data</p>
            </div>
        `;
        // Implement analysis charts here
    }

    function exportConsolidatedReport() {
        let csv = 'Consolidated Order Report\n';
        csv += `Generated: ${new Date().toLocaleString()}\n`;
        csv += `Filters: ${Object.entries(state.filters).filter(([k, v]) => v !== 'all').map(([k, v]) => `${k}: ${v}`).join(', ')}\n`;
        csv += `Search: ${state.searchQuery || 'None'}\n\n`;

        csv += 'Product Name,Category,Brand,SKU,Unit,Unit Price,Total Quantity,Total Value,Orders Count\n';

        state.filteredProducts.forEach(product => {
            const totalQuantity = product.currentOrders.reduce((sum, order) => sum + order.quantity, 0);
            const totalValue = totalQuantity * product.price;

            csv += `"${product.name}","${product.category}","${product.brand}","${product.sku}","${product.unit}",₹${product.price},${totalQuantity},₹${totalValue},${product.currentOrders.length}\n`;
        });

        // Create and trigger download
        const BOM = '\uFEFF'; // UTF-8 BOM
        const blob = new Blob([BOM + csv], { type: 'text/csv;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `consolidated-report-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);

        showToast('Consolidated report exported successfully!', 'success');
    }

    function consolidateOrders() {
        if (state.filteredProducts.length === 0) {
            showToast('No products to consolidate!', 'error');
            return;
        }

        const totalQuantity = state.filteredProducts.reduce((sum, product) =>
            sum + product.currentOrders.reduce((sum2, order) => sum2 + order.quantity, 0), 0);
        const totalValue = state.filteredProducts.reduce((sum, product) =>
            sum + (product.currentOrders.reduce((sum2, order) => sum2 + order.quantity, 0) * product.price), 0);

        const summary = `
            Order Consolidation Summary
            ============================
            
            Date: ${new Date().toLocaleDateString()}
            Time: ${new Date().toLocaleTimeString()}
            
            Statistics:
            • Total Products: ${state.filteredProducts.length}
            • Total Quantity: ${totalQuantity} units
            • Total Value: ₹${totalValue.toLocaleString()}
            • Total Orders: ${state.filteredProducts.reduce((sum, product) => sum + product.currentOrders.length, 0)}
            
            Action Items:
            1. Generate purchase orders for consolidated quantities
            2. Schedule deliveries based on order dates
            3. Allocate inventory to salespersons
            4. Update stock levels
            
            Status: Ready for processing
        `;

        alert(summary);
        showToast('Orders consolidated successfully!', 'success');
    }

    function showToast(message, type = 'success') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 px-4 py-3 rounded-lg text-white font-medium animate-slide-up z-50 ${type === 'success' ? 'bg-emerald-500' :
                type === 'error' ? 'bg-red-500' :
                    'bg-blue-500'
            }`;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }

    // Sort functionality
    function sortBy(column) {
        if (state.sortBy === column) {
            state.sortDirection = state.sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            state.sortBy = column;
            state.sortDirection = 'desc';
        }
        applyFilters();
    }
</script>
@endsection