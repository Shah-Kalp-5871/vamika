@extends('layouts.admin')

@section('title', 'Assign Area & Shop - Admin')

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background-color: #FAFAFA;
        -webkit-font-smoothing: antialiased;
        padding-bottom: 80px;
    }

    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: transparent;
    }

    ::-webkit-scrollbar-thumb {
        background-color: #E4E4E7;
        border-radius: 20px;
    }

    /* Main content container */
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

    /* Custom animations */
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

    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }

    /* Badge styles */
    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-sales {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        color: white;
    }

    /* Card styles */
    .info-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    /* Area card */
    .area-card {
        border: 2px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .area-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .area-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    /* Shop card */
    .shop-card {
        border: 2px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .shop-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .shop-card.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }

    /* Button styles */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }

    .btn-secondary {
        background: white;
        color: #374151;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        width: 100%;
        justify-content: center;
    }

    .btn-secondary:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }

    /* Progress steps */
    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin: 2rem 0;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 1rem;
        left: 0;
        right: 0;
        height: 2px;
        background: #E2E8F0;
        z-index: 1;
    }

    .step {
        position: relative;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .step-number {
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: white;
        border: 2px solid #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #94A3B8;
        margin-bottom: 0.5rem;
    }

    .step.active .step-number {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-color: #667eea;
        color: white;
    }

    .step.completed .step-number {
        background: #10B981;
        border-color: #10B981;
        color: white;
    }

    .step-label {
        font-size: 0.75rem;
        color: #94A3B8;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #667eea;
    }

    .step.completed .step-label {
        color: #10B981;
    }

    /* Stats badges */
    .stats-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        color: #64748B;
    }

    .stats-badge .count {
        font-weight: 600;
        color: #334155;
    }

    /* Toast notification */
    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100">
        <div class="px-6 py-4 flex items-center justify-between">
            <!-- Left: Back + Logo + Context -->
            <div class="flex items-center gap-3">
                <!-- Logo -->
                <img src="{{ asset('assets/images/logo.png') }}" alt="Vamika Enterprise"
                    class="h-8 w-8 object-contain rounded-md border border-slate-200">

                <!-- Title -->
                <div class="leading-tight">
                    <h1 class="text-lg font-semibold text-slate-900 tracking-tight">Assign Area & Shop</h1>
                    <p class="text-sm text-slate-500 truncate" id="salespersonName">
                        @if(request()->has('user_id'))
                            Loading...
                        @else
                            Select Salesperson First
                        @endif
                    </p>
                </div>
            </div>

            <!-- Admin Badge -->
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                Admin Panel
            </span>
        </div>
    </header>

    <main class="p-6 space-y-6">
        <!-- Salesperson Info Card -->
        <div class="info-card animate-slide-up">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center">
                    <iconify-icon icon="lucide:user-tie" width="24" class="text-white"></iconify-icon>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-lg" id="salespersonNameInfo">Salesperson Name</h3>
                    <p class="text-white/80 text-sm mt-1" id="salespersonContact">Loading contact info...</p>
                </div>
                <span class="badge badge-sales">
                    <iconify-icon icon="lucide:user-tie" width="12" class="mr-1"></iconify-icon>
                    Salesperson
                </span>
            </div>
        </div>

        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step active" id="step1">
                <div class="step-number">1</div>
                <span class="step-label">Select Area</span>
            </div>
            <div class="step" id="step2">
                <div class="step-number">2</div>
                <span class="step-label">Select Shop</span>
            </div>
            <div class="step" id="step3">
                <div class="step-number">3</div>
                <span class="step-label">Confirm</span>
            </div>
        </div>

        <!-- Currently Assigned -->
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 animate-slide-up" style="display: none;"
            id="currentAssignment">
            <div class="flex items-start gap-3">
                <iconify-icon icon="lucide:info" width="20" class="text-amber-600 mt-0.5"></iconify-icon>
                <div class="flex-1">
                    <h4 class="font-medium text-amber-900">Currently Assigned</h4>
                    <p class="text-sm text-amber-800 mt-1" id="currentAssignmentText"></p>
                </div>
                <button class="text-amber-600 hover:text-amber-800" onclick="removeAssignment()">
                    <iconify-icon icon="lucide:x" width="16"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- Step 1: Select Area -->
        <div id="step1Content" class="animate-slide-up">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Select Delivery Area</h3>
                <p class="text-slate-500 text-sm">Choose the area where this salesperson will operate</p>
            </div>

            <!-- Search Areas -->
            <div class="relative mb-6">
                <iconify-icon icon="lucide:search" width="16"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    id="areaSearch" placeholder="Search areas...">
            </div>

            <!-- Areas Grid -->
            <div class="space-y-3 mb-8" id="areasList">
                <!-- Areas will be loaded here -->
                <div class="text-center py-8 text-slate-500">
                    <div class="loading-spinner inline-block w-8 h-8 border-2 border-slate-200 border-t-slate-600 rounded-full animate-spin mb-4"></div>
                    <p class="font-medium">Loading areas...</p>
                </div>
            </div>

            <!-- Next Button -->
            <button class="btn-primary" onclick="goToStep2()" id="nextToStep2Btn" disabled>
                <iconify-icon icon="lucide:arrow-right" width="16"></iconify-icon>
                Next: Select Shop
            </button>
        </div>

        <!-- Step 2: Select Shop (Hidden Initially) -->
        <div id="step2Content" class="animate-slide-up" style="display: none;">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Select Shop in <span id="selectedAreaName"
                        class="text-indigo-600">Area Name</span></h3>
                <p class="text-slate-500 text-sm">Choose a shop to assign to this salesperson</p>
            </div>

            <!-- Search Shops -->
            <div class="relative mb-6">
                <iconify-icon icon="lucide:search" width="16"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text"
                    class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-500 focus:ring-2 focus:ring-indigo-100"
                    id="shopSearch" placeholder="Search shops by name or owner...">
            </div>

            <!-- Shops Grid -->
            <div class="space-y-3 mb-8" id="shopsList">
                <!-- Shops will be loaded here -->
            </div>

            <!-- Navigation Buttons -->
            <div class="flex gap-3">
                <button class="btn-secondary" onclick="goBackToStep1()">
                    <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                    Back to Areas
                </button>
                <button class="btn-primary" onclick="goToStep3()" id="nextToStep3Btn" disabled>
                    <iconify-icon icon="lucide:arrow-right" width="16"></iconify-icon>
                    Next: Confirm
                </button>
            </div>
        </div>

        <!-- Step 3: Confirmation (Hidden Initially) -->
        <div id="step3Content" class="animate-slide-up" style="display: none;">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Confirm Assignment</h3>
                <p class="text-slate-500 text-sm">Review and confirm the assignment details</p>
            </div>

            <!-- Assignment Summary -->
            <div class="bg-slate-50 border border-slate-200 rounded-xl p-6 mb-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                <iconify-icon icon="lucide:user-tie" width="24" class="text-indigo-600"></iconify-icon>
                            </div>
                            <div>
                                <h4 class="font-semibold text-slate-900" id="confirmSalespersonName"></h4>
                                <p class="text-sm text-slate-500" id="confirmSalespersonContact"></p>
                            </div>
                        </div>
                        <span class="badge badge-sales">Salesperson</span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        <div class="bg-white p-4 rounded-lg border border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <iconify-icon icon="lucide:map-pin" width="16"
                                        class="text-emerald-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Assigned Area</p>
                                    <p class="font-semibold text-slate-900" id="confirmAreaName"></p>
                                    <p class="text-xs text-slate-400 mt-1" id="confirmAreaStats"></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-4 rounded-lg border border-slate-200">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <iconify-icon icon="lucide:store" width="16"
                                        class="text-blue-600"></iconify-icon>
                                </div>
                                <div>
                                    <p class="text-sm text-slate-500">Assigned Shop</p>
                                    <p class="font-semibold text-slate-900" id="confirmShopName"></p>
                                    <p class="text-xs text-slate-400 mt-1" id="confirmShopOwner"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <iconify-icon icon="lucide:calendar" width="16"
                                    class="text-amber-600"></iconify-icon>
                            </div>
                            <div>
                                <p class="text-sm text-slate-500">Assignment Date</p>
                                <p class="font-semibold text-slate-900" id="assignmentDate"></p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-4 rounded-lg border border-slate-200">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center">
                                <iconify-icon icon="lucide:package" width="16"
                                    class="text-purple-600"></iconify-icon>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-slate-500">Access Permissions</p>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                        <iconify-icon icon="lucide:shopping-cart" width="10"
                                            class="mr-1"></iconify-icon>
                                        Take Orders
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                        <iconify-icon icon="lucide:clipboard-list" width="10"
                                            class="mr-1"></iconify-icon>
                                        View Products
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                        <iconify-icon icon="lucide:file-text" width="10"
                                            class="mr-1"></iconify-icon>
                                        Generate Invoices
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Final Actions -->
            <div class="flex gap-3">
                <button class="btn-secondary" onclick="goBackToStep2()">
                    <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                    Back to Shops
                </button>
                <button class="btn-primary" onclick="saveAssignment()">
                    <iconify-icon icon="lucide:save" width="16"></iconify-icon>
                    Save Assignment
                </button>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Dummy data
    const areas = [
        { id: 1, name: 'Gandhi Nagar', shops: 15, activeSalespersons: 3, ordersToday: 24 },
        { id: 2, name: 'Shahdara', shops: 12, activeSalespersons: 2, ordersToday: 18 },
        { id: 3, name: 'Laxmi Nagar', shops: 18, activeSalespersons: 4, ordersToday: 32 },
        { id: 4, name: 'Preet Vihar', shops: 10, activeSalespersons: 1, ordersToday: 15 },
        { id: 5, name: 'Nirman Vihar', shops: 8, activeSalespersons: 2, ordersToday: 12 },
        { id: 6, name: 'Karkardooma', shops: 14, activeSalespersons: 3, ordersToday: 21 },
        { id: 7, name: 'Geeta Colony', shops: 9, activeSalespersons: 1, ordersToday: 14 },
        { id: 8, name: 'Mayur Vihar', shops: 11, activeSalespersons: 2, ordersToday: 19 }
    ];

    const shops = [
        { id: 1, name: 'Mohan Kirana Store', owner: 'Mohan Singh', area: 'Gandhi Nagar', areaId: 1, phone: '9876543210', status: 'active' },
        { id: 2, name: 'Gupta General Store', owner: 'Ramesh Gupta', area: 'Gandhi Nagar', areaId: 1, phone: '9876543211', status: 'active' },
        { id: 3, name: 'Bansal Provision', owner: 'Sunil Bansal', area: 'Shahdara', areaId: 2, phone: '9876543212', status: 'active' },
        { id: 4, name: 'Verma Departmental Store', owner: 'Anil Verma', area: 'Laxmi Nagar', areaId: 3, phone: '9876543214', status: 'inactive' },
        { id: 5, name: 'Sharma Super Mart', owner: 'Rajesh Sharma', area: 'Preet Vihar', areaId: 4, phone: '9876543213', status: 'active' },
        { id: 6, name: 'Modern Kirana', owner: 'Sanjay Kumar', area: 'Gandhi Nagar', areaId: 1, phone: '9876543215', status: 'active' },
        { id: 7, name: 'Daily Needs Mart', owner: 'Vikram Singh', area: 'Nirman Vihar', areaId: 5, phone: '9876543216', status: 'active' },
        { id: 8, name: 'Super Bazaar', owner: 'Rohit Mehta', area: 'Karkardooma', areaId: 6, phone: '9876543217', status: 'active' },
        { id: 9, name: 'City Mart', owner: 'Amit Shah', area: 'Gandhi Nagar', areaId: 1, phone: '9876543218', status: 'active' },
        { id: 10, name: 'Family Store', owner: 'Deepak Jain', area: 'Laxmi Nagar', areaId: 3, phone: '9876543219', status: 'active' },
        { id: 11, name: 'Quick Shop', owner: 'Raj Kumar', area: 'Geeta Colony', areaId: 7, phone: '9876543220', status: 'active' },
        { id: 12, name: '24/7 Mart', owner: 'Ankit Sharma', area: 'Mayur Vihar', areaId: 8, phone: '9876543221', status: 'inactive' }
    ];

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user_id') || '{{ request()->input("user_id") }}';
    const userName = urlParams.get('name') || '{{ request()->input("name") }}' || 'Salesperson';

    // State
    let selectedArea = null;
    let selectedShop = null;
    let currentStep = 1;
    let currentAssignments = [];

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        // Set salesperson info
        document.getElementById('salespersonName').textContent = userName;
        document.getElementById('salespersonNameInfo').textContent = userName;

        // Load salesperson contact info
        const salesperson = getSalespersonInfo(userId);
        if (salesperson) {
            document.getElementById('salespersonContact').textContent =
                `${salesperson.email} • ${salesperson.phone}`;
            document.getElementById('confirmSalespersonContact').textContent =
                `${salesperson.email} • ${salesperson.phone}`;
        }

        document.getElementById('confirmSalespersonName').textContent = userName;

        // Load current assignments
        loadCurrentAssignments();

        // Load areas
        loadAreas();

        // Setup search
        setupAreaSearch();
    });

    function getSalespersonInfo(id) {
        // In real app, fetch from API
        // For now, return dummy data
        return {
            email: 'salesperson@vamika.com',
            phone: '9876543200',
            id: id,
            employeeId: 'EMP00' + id
        };
    }

    function loadCurrentAssignments() {
        // In real app, fetch from API
        const assignments = JSON.parse(localStorage.getItem(`assignments_${userId}`)) || [];
        currentAssignments = assignments;

        if (assignments.length > 0) {
            const current = assignments[0];
            document.getElementById('currentAssignmentText').textContent =
                `${current.shopName} in ${current.areaName}`;
            document.getElementById('currentAssignment').style.display = 'block';

            // If already assigned, pre-select
            selectedArea = areas.find(a => a.id === current.areaId);
            selectedShop = shops.find(s => s.id === current.shopId);

            if (selectedArea) {
                selectArea(selectedArea.id);
            }
        }
    }

    function loadAreas() {
        const areasList = document.getElementById('areasList');
        areasList.innerHTML = '';

        // Simulate API loading
        setTimeout(() => {
            areas.forEach(area => {
                const areaCard = document.createElement('div');
                areaCard.className = 'area-card';
                areaCard.innerHTML = `
                    <div class="flex-1">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-slate-900">${area.name}</h4>
                            <button class="px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg font-medium text-sm hover:bg-indigo-100 transition-colors"
                                    onclick="event.stopPropagation(); selectArea(${area.id})">
                                Select
                            </button>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="stats-badge">
                                <iconify-icon icon="lucide:store" width="14" class="text-slate-400"></iconify-icon>
                                <span class="count">${area.shops}</span>
                                <span class="text">Shops</span>
                            </div>
                            <div class="stats-badge">
                                <iconify-icon icon="lucide:users" width="14" class="text-slate-400"></iconify-icon>
                                <span class="count">${area.activeSalespersons}</span>
                                <span class="text">Active</span>
                            </div>
                            <div class="stats-badge">
                                <iconify-icon icon="lucide:package" width="14" class="text-slate-400"></iconify-icon>
                                <span class="count">${area.ordersToday}</span>
                                <span class="text">Orders Today</span>
                            </div>
                        </div>
                    </div>
                `;

                areaCard.onclick = () => selectArea(area.id);
                areasList.appendChild(areaCard);
            });
        }, 500);
    }

    function setupAreaSearch() {
        const searchInput = document.getElementById('areaSearch');
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            const areaCards = document.querySelectorAll('.area-card');

            areaCards.forEach(card => {
                const areaName = card.querySelector('h4').textContent.toLowerCase();
                const isVisible = areaName.includes(term);
                card.style.display = isVisible ? 'flex' : 'none';
            });
        });
    }

    function selectArea(areaId) {
        selectedArea = areas.find(area => area.id === areaId);

        // Update UI - remove selected class from all cards
        document.querySelectorAll('.area-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Find and select the clicked card
        const areaCards = document.querySelectorAll('.area-card');
        areaCards.forEach((card, index) => {
            const button = card.querySelector('button');
            if (button && button.onclick && button.onclick.toString().includes(areaId)) {
                card.classList.add('selected');
            }
        });

        // Enable next button
        document.getElementById('nextToStep2Btn').disabled = false;
    }

    function goToStep2() {
        if (!selectedArea) {
            showToast('Please select an area first', 'error');
            return;
        }

        // Update steps
        currentStep = 2;
        document.getElementById('step1').classList.remove('active');
        document.getElementById('step1').classList.add('completed');
        document.getElementById('step2').classList.add('active');

        // Hide step 1, show step 2
        document.getElementById('step1Content').style.display = 'none';
        document.getElementById('step2Content').style.display = 'block';

        // Load shops for selected area
        loadShopsByArea(selectedArea.id);

        // Update area name
        document.getElementById('selectedAreaName').textContent = selectedArea.name;

        // Setup shop search
        setupShopSearch();
    }

    function loadShopsByArea(areaId) {
        const shopsList = document.getElementById('shopsList');
        const filteredShops = shops.filter(shop => shop.areaId === areaId && shop.status === 'active');

        shopsList.innerHTML = '';

        if (filteredShops.length === 0) {
            shopsList.innerHTML = `
                <div class="text-center py-8 text-slate-500">
                    <iconify-icon icon="lucide:store" width="48" class="mb-4 text-slate-300"></iconify-icon>
                    <p class="font-medium">No active shops found in this area</p>
                    <p class="text-sm mt-1">Add shops to this area first or activate existing shops</p>
                    <button class="mt-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors"
                            onclick="goToAreaManagement()">
                        <iconify-icon icon="lucide:plus" width="14" class="mr-2"></iconify-icon>
                        Manage Shops
                    </button>
                </div>
            `;
            return;
        }

        filteredShops.forEach(shop => {
            const shopCard = document.createElement('div');
            shopCard.className = 'shop-card';
            shopCard.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-semibold text-slate-900">${shop.name}</h4>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                        <div class="flex items-center gap-4 mt-2">
                            <div class="flex items-center gap-1 text-sm text-slate-500">
                                <iconify-icon icon="lucide:user" width="14"></iconify-icon>
                                <span>${shop.owner}</span>
                            </div>
                            <div class="flex items-center gap-1 text-sm text-slate-500">
                                <iconify-icon icon="lucide:phone" width="14"></iconify-icon>
                                <span>${shop.phone}</span>
                            </div>
                        </div>
                    </div>
                    <button class="ml-4 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-medium transition-colors"
                            onclick="selectShop(${shop.id})">
                        Select
                    </button>
                </div>
            `;
            shopsList.appendChild(shopCard);
        });
    }

    function setupShopSearch() {
        const searchInput = document.getElementById('shopSearch');
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase();
            const shopCards = document.querySelectorAll('.shop-card');

            shopCards.forEach(card => {
                const shopName = card.querySelector('h4').textContent.toLowerCase();
                const ownerName = card.querySelector('.flex.items-center.gap-1').textContent.toLowerCase();
                const isVisible = shopName.includes(term) || ownerName.includes(term);
                card.style.display = isVisible ? 'block' : 'none';
            });
        });
    }

    function selectShop(shopId) {
        selectedShop = shops.find(shop => shop.id === shopId);

        // Update UI - remove selected class from all cards
        document.querySelectorAll('.shop-card').forEach(card => {
            card.classList.remove('selected');
        });

        // Find and select the clicked card
        const shopCards = document.querySelectorAll('.shop-card');
        shopCards.forEach(card => {
            const button = card.querySelector('button');
            if (button && button.onclick && button.onclick.toString().includes(shopId)) {
                card.classList.add('selected');
            }
        });

        // Enable next button
        document.getElementById('nextToStep3Btn').disabled = false;
    }

    function goToStep3() {
        if (!selectedShop) {
            showToast('Please select a shop first', 'error');
            return;
        }

        // Update steps
        currentStep = 3;
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step2').classList.add('completed');
        document.getElementById('step3').classList.add('active');

        // Hide step 2, show step 3
        document.getElementById('step2Content').style.display = 'none';
        document.getElementById('step3Content').style.display = 'block';

        // Set confirmation details
        document.getElementById('confirmAreaName').textContent = selectedArea.name;
        document.getElementById('confirmAreaStats').textContent =
            `${selectedArea.shops} shops • ${selectedArea.activeSalespersons} salespersons`;
        document.getElementById('confirmShopName').textContent = selectedShop.name;
        document.getElementById('confirmShopOwner').textContent = `Owner: ${selectedShop.owner}`;

        // Set current date
        const now = new Date();
        const dateStr = now.toLocaleDateString('en-US', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        document.getElementById('assignmentDate').textContent = dateStr;
    }

    function goBackToStep1() {
        // Update steps
        currentStep = 1;
        document.getElementById('step2').classList.remove('active');
        document.getElementById('step1').classList.add('active');

        // Hide step 2, show step 1
        document.getElementById('step2Content').style.display = 'none';
        document.getElementById('step1Content').style.display = 'block';
    }

    function goBackToStep2() {
        // Update steps
        currentStep = 2;
        document.getElementById('step3').classList.remove('active');
        document.getElementById('step2').classList.add('active');

        // Hide step 3, show step 2
        document.getElementById('step3Content').style.display = 'none';
        document.getElementById('step2Content').style.display = 'block';
    }

    function saveAssignment() {
        // Show loading state
        const saveBtn = document.querySelector('#step3Content .btn-primary');
        const originalText = saveBtn.innerHTML;
        saveBtn.innerHTML = `
            <div class="loading-spinner inline-block w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin mr-2"></div>
            Saving...
        `;
        saveBtn.disabled = true;

        // In real app, save to API
        const assignment = {
            salespersonId: userId,
            salespersonName: userName,
            areaId: selectedArea.id,
            areaName: selectedArea.name,
            shopId: selectedShop.id,
            shopName: selectedShop.name,
            owner: selectedShop.owner,
            shopPhone: selectedShop.phone,
            assignedDate: new Date().toISOString(),
            status: 'active',
            permissions: ['take_orders', 'view_products', 'generate_invoices']
        };

        // Save to localStorage (simulating API call)
        localStorage.setItem(`assignments_${userId}`, JSON.stringify([assignment]));

        // Show success message
        setTimeout(() => {
            showToast('Assignment saved successfully!', 'success');
            
            // Reset button
            saveBtn.innerHTML = originalText;
            saveBtn.disabled = false;
            
            // Redirect back to users page
            setTimeout(() => {
                window.location.href = '{{ route("admin.users.index") }}?message=Assignment+saved+successfully&type=success';
            }, 1500);
        }, 1000);
    }

    function removeAssignment() {
        if (confirm('Are you sure you want to remove this assignment?\n\nThe salesperson will no longer have access to this shop.')) {
            // Remove from localStorage (simulating API call)
            localStorage.removeItem(`assignments_${userId}`);

            // Reset selections
            selectedArea = null;
            selectedShop = null;

            // Hide current assignment display
            document.getElementById('currentAssignment').style.display = 'none';

            // Reset UI
            document.querySelectorAll('.area-card').forEach(card => {
                card.classList.remove('selected');
            });
            document.querySelectorAll('.shop-card').forEach(card => {
                card.classList.remove('selected');
            });

            // Disable buttons
            document.getElementById('nextToStep2Btn').disabled = true;
            document.getElementById('nextToStep3Btn').disabled = true;

            // If on step 2 or 3, go back to step 1
            if (currentStep > 1) {
                goBackToStep1();
            }

            // Reset progress steps
            document.getElementById('step1').classList.add('active');
            document.getElementById('step2').classList.remove('active', 'completed');
            document.getElementById('step3').classList.remove('active', 'completed');

            // Show success message
            showToast('Assignment removed successfully!', 'success');
        }
    }

    function goToAreaManagement() {
        // Redirect to areas management page
        showToast('Redirecting to area management page…', 'info');
        setTimeout(() => {
            window.location.href = '{{ route("admin.areas.index") }}';
        }, 1000);
    }

    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = `toast px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };
        
        const icons = {
            success: 'check-circle',
            error: 'x-circle',
            info: 'info'
        };
        
        toast.classList.add(...colors[type].split(' '));
        
        toast.innerHTML = `
            <iconify-icon icon="lucide:${icons[type]}" width="18"></iconify-icon>
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection