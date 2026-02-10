@extends('layouts.admin')

@section('title', 'Manage Areas - Admin')
@php
$pageConfig = [
    'showBottomNav' => true
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

    /* Status badge styles */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Side Panel Styles */
    #shopPanel {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    #shopPanelOverlay {
        transition: opacity 0.3s ease;
    }
    .panel-open #shopPanel {
        transform: translateX(0);
    }
    .panel-open #shopPanelOverlay {
        opacity: 1;
        pointer-events: auto;
    }

    /* Salesperson avatar */
    .salesperson-avatar {
        width: 32px;
        height: 32px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        font-size: 0.75rem;
    }

    /* Empty state */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
        background: #F8FAFC;
        border-radius: 0.75rem;
        border: 2px dashed #E2E8F0;
    }

    /* Add button */
    .add-btn {
        position: fixed;
        bottom: 5rem;
        right: 1rem;
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 50%;
        font-size: 1.5rem;
        cursor: pointer;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
        z-index: 50;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1);
    }

    @media (min-width: 640px) {
        .add-btn {
            bottom: 2rem;
            right: calc(50% - 21rem);
        }
    }

    /* Action buttons */
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        border: 1px solid #E2E8F0;
        background: white;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid #E2E8F0;
        border-top-color: #64748B;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    /* Notification */
    .notification {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        animation: slideUp 0.3s ease;
    }
    
    /* Back button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        color: #64748B;
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .back-btn:hover {
        background: #F8FAFC;
        color: #475569;
    }
</style>
@endsection

@section('content')
<div class="main-content">

    <!-- Header -->
    <header class="p-4 sm:p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                
                <!-- Left: Title & Navigation -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all"
                       title="Back to Dashboard">
                        <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
                    </a>
                    
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Manage Bits</h1>
                            <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-600 border border-indigo-100 uppercase tracking-wider">
                                Beta
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">Configure delivery areas and shop availability</p>
                    </div>
                </div>

                <!-- Right: Stats & Info -->
                <div class="flex items-center justify-between sm:justify-end gap-4 border-t sm:border-t-0 pt-4 sm:pt-0 border-slate-50">
                    <div class="flex flex-col items-start sm:items-end">
                        <div class="flex items-center gap-1.5 mt-1 text-sm font-semibold text-slate-900">
                            <iconify-icon icon="lucide:map-pin" width="16" class="text-indigo-600"></iconify-icon>
                            <span id="totalAreasCount">0</span>
                            <span class="text-slate-400 font-medium">Active Bits</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <main class="p-6 space-y-8">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Total Bits -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm animate-slide-up">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                        <iconify-icon icon="lucide:map-pin" width="24"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Total Bits</p>
                        <h3 class="text-2xl font-bold text-slate-900" id="totalAreas">0</h3>
                    </div>
                </div>
            </div>

            <!-- Active Bits -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm animate-slide-up" style="animation-delay: 0.1s;">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600">
                        <iconify-icon icon="lucide:check-circle" width="24"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Active Bits</p>
                        <h3 class="text-2xl font-bold text-slate-900" id="activeAreas">0</h3>
                    </div>
                </div>
            </div>

            <!-- Total Shops -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm animate-slide-up" style="animation-delay: 0.2s;">
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                        <iconify-icon icon="lucide:store" width="24"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Total Shops</p>
                        <h3 class="text-2xl font-bold text-slate-900" id="totalShops">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-sm font-semibold text-slate-900">Delivery Bits</h3>
            <div class="flex items-center gap-3">
                <div class="relative flex-1 sm:flex-none">
                    <iconify-icon icon="lucide:search" width="16" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" placeholder="Search bits..." 
                           class="w-full sm:w-64 pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           id="searchInput">
                </div>
                <select class="text-sm border border-slate-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        id="statusFilter">
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        <!-- Areas List -->
        <div id="areasList" class="space-y-4">
            <!-- Areas will be loaded here -->
            <div class="flex items-center justify-center py-12">
                <div class="loading-spinner"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state hidden">
            <div class="flex flex-col items-center justify-center">
                <iconify-icon icon="lucide:map" width="48" class="text-slate-400 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">No Areas Found</h4>
                <p class="text-sm text-slate-500 mb-6 max-w-md">
                    No delivery areas match your current filters. Try adjusting your search or add a new area.
                </p>
                <a href="{{ route('admin.bits.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                    <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                    Add New Area
                </a>
            </div>
        </div>
    </main>

    <!-- Side Panel Overlay -->
    <div id="shopPanelOverlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[60] opacity-0 pointer-events-none transition-opacity duration-300" onclick="hideShopPanel()"></div>

    <!-- Shop Side Panel -->
    <div id="shopPanel" class="fixed top-0 right-0 h-full w-full sm:w-[500px] bg-white shadow-2xl z-[70] transform translate-x-full transition-transform duration-300 ease-in-out">
        <div class="h-full flex flex-col">
            <!-- Panel Header -->
            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                <div>
                    <h2 id="panelBitName" class="text-xl font-bold text-slate-900">Bit Shops</h2>
                    <p id="panelShopCount" class="text-sm text-slate-500 mt-0.5">0 shops found</p>
                </div>
                <button onclick="hideShopPanel()" class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-white hover:shadow-sm text-slate-400 hover:text-slate-600 transition-all">
                    <iconify-icon icon="lucide:x" width="24"></iconify-icon>
                </button>
            </div>

            <!-- Panel Content -->
            <div class="flex-1 overflow-y-auto p-6">
                <!-- Search within Panel -->
                <div class="mb-6 relative">
                    <iconify-icon icon="lucide:search" width="18" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" id="panelSearch" placeholder="Search shops by name..." 
                           class="w-full pl-10 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 transition-all outline-none"
                           oninput="filterPanelShops(this.value)">
                </div>

                <!-- Shops List Container -->
                <div id="panelShopList" class="space-y-4">
                    <!-- Loading State -->
                    <div id="panelLoading" class="flex flex-col items-center justify-center py-20">
                        <div class="loading-spinner mb-4"></div>
                        <p class="text-sm text-slate-500">Loading shops...</p>
                    </div>

                    <!-- Empty State -->
                    <div id="panelEmpty" class="hidden flex flex-col items-center justify-center py-20 text-center">
                        <div class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                            <iconify-icon icon="lucide:store" width="32" class="text-slate-300"></iconify-icon>
                        </div>
                        <p class="text-slate-900 font-semibold text-lg">No shops found</p>
                        <p class="text-slate-500 text-sm mt-1">There are no shops assigned to this bit.</p>
                    </div>

                    <!-- Dynamic Shops will go here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Area Button -->
<a href="{{ route('admin.bits.create') }}" class="add-btn no-underline">
    <iconify-icon icon="lucide:plus"></iconify-icon>
</a>
@endsection

@section('scripts')
<script>
    let areas = [];
    let originalAreas = [];
    
    // Sample salespersons data
    const salespersons = [
        { id: 1, name: 'Rajesh Kumar', employeeId: 'EMP001', phone: '9876543210' },
        { id: 2, name: 'Suresh Patel', employeeId: 'EMP002', phone: '9876543211' },
        { id: 3, name: 'Vikram Singh', employeeId: 'EMP003', phone: '9876543212' },
        { id: 4, name: 'Amit Sharma', employeeId: 'EMP004', phone: '9876543213' },
        { id: 5, name: 'Neha Verma', employeeId: 'EMP005', phone: '9876543214' }
    ];
    
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        loadAreasPage();
        setupEventListeners();
    });
    
    function setupEventListeners() {
        document.getElementById('searchInput').addEventListener('input', function(e) {
            searchAreas(e.target.value);
        });
        
        document.getElementById('statusFilter').addEventListener('change', function(e) {
            filterByStatus(e.target.value);
        });
    }
    
    function loadAreasPage() {
        // Set current date
        const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', dateOptions);
        
        // Load data from server
        areas = @json($bits);
        originalAreas = [...areas];
        
        // Initialize areas with additional derived data if needed
        areas.forEach((area, index) => {
            // Ensure pincodes is an array (it might be null or JSON string if not cast properly in model)
            // But Laravel 'array' cast should handle it.
            
            // Generate description if not present
            area.description = area.pincodes ? `Serving: ${area.pincodes.join(', ')}` : 'Delivery area';
            
            // Map shops_count to shops
            area.shops = area.shops_count || 0;
            
            // Mock delivery charge
            area.deliveryCharge = 50; 
        });
        
        updateAreaStats();
        renderAreasList();
    }
    
    function getDummyAreasData() {
        return [];
    }
    
    function updateAreaStats() {
        const totalAreas = areas.length;
        const activeAreas = areas.filter(a => a.status === 'active').length;
        const totalShops = areas.reduce((sum, area) => sum + area.shops, 0);
        
        document.getElementById('totalAreas').textContent = totalAreas;
        document.getElementById('totalAreasCount').textContent = totalAreas;
        document.getElementById('activeAreas').textContent = activeAreas;
        document.getElementById('totalShops').textContent = totalShops.toLocaleString();
    }
    
    function renderAreasList() {
        const areasList = document.getElementById('areasList');
        const emptyState = document.getElementById('emptyState');
        
        if (areas.length === 0) {
            areasList.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }
        
        areasList.classList.remove('hidden');
        emptyState.classList.add('hidden');
        
        areasList.innerHTML = '';
        
        areas.forEach((area, index) => {
            const areaCard = document.createElement('div');
            areaCard.className = 'bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow animate-slide-up';
            areaCard.style.animationDelay = `${index * 0.05}s`;
            
            // Status badge
            const statusBadge = area.status === 'active' 
                ? `<span class="status-badge bg-emerald-50 text-emerald-700 border border-emerald-200">
                     <iconify-icon icon="lucide:check-circle" width="12"></iconify-icon> Active
                   </span>`
                : `<span class="status-badge bg-slate-100 text-slate-600 border border-slate-200">
                     <iconify-icon icon="lucide:x-circle" width="12"></iconify-icon> Inactive
                   </span>`;
            
            areaCard.innerHTML = `
                <div class="p-6">
                    <!-- Header with area info and actions -->
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <iconify-icon icon="lucide:map-pin" class="text-indigo-600" width="20"></iconify-icon>
                                <h3 class="text-lg font-semibold text-slate-900">${area.name}</h3>
                                ${statusBadge}
                            </div>
                            
                            <!-- Area details -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 mt-3">
                                <div class="flex items-center gap-1">
                                    <iconify-icon icon="lucide:hash" width="14" class="text-slate-400"></iconify-icon>
                                    <span>${Array.isArray(area.pincodes) ? area.pincodes[0] + (area.pincodes.length > 1 ? ' +' + (area.pincodes.length - 1) : '') : 'N/A'}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <iconify-icon icon="lucide:indian-rupee" width="14" class="text-slate-400"></iconify-icon>
                                    <span>${area.deliveryCharge} delivery charge</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <iconify-icon icon="lucide:store" width="14" class="text-slate-400"></iconify-icon>
                                    <span>${area.shops} shops</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="flex items-center gap-2">
                            <button onclick="showShopPanel(${area.id}, '${area.name}')" 
                                    class="px-3 py-2 text-sm font-semibold text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 rounded-lg transition-all flex items-center gap-2 shadow-sm">
                                <iconify-icon icon="lucide:store" width="16"></iconify-icon>
                                <span>Shops (${area.shops})</span>
                            </button>
                            <a href="${'{{ route('admin.bits.edit', ':id') }}'.replace(':id', area.id)}"
                               class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-slate-50 rounded-lg border border-slate-100 transition-all no-underline"
                               title="Edit Bit">
                                <iconify-icon icon="lucide:edit" width="18"></iconify-icon>
                            </a>
                            <button onclick="deleteArea(${area.id})"
                                    class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg border border-slate-100 transition-all"
                                    title="Delete Bit">
                                <iconify-icon icon="lucide:trash-2" width="18"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    ${area.description ? `
                        <div class="mt-4 p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <p class="text-sm text-slate-600">
                                <iconify-icon icon="lucide:info" width="14" class="text-slate-400 mr-2"></iconify-icon>
                                ${area.description}
                            </p>
                        </div>
                    ` : ''}
                </div>
            `;
            areasList.appendChild(areaCard);
        });
    }
    
    let currentPanelShops = [];

    function showShopPanel(bitId, bitName) {
        document.body.classList.add('panel-open', 'overflow-hidden');
        document.getElementById('panelBitName').textContent = bitName;
        document.getElementById('panelLoading').classList.remove('hidden');
        document.getElementById('panelEmpty').classList.add('hidden');
        document.getElementById('panelSearch').value = '';
        
        const shopListContainer = document.getElementById('panelShopList');
        // Remove old shop cards
        const oldCards = shopListContainer.querySelectorAll('.shop-panel-card');
        oldCards.forEach(c => c.remove());

        fetchPanelShops(bitId);
    }

    function hideShopPanel() {
        document.body.classList.remove('panel-open', 'overflow-hidden');
    }

    async function fetchPanelShops(bitId) {
        try {
            const response = await fetch("{{ route('admin.bits.shops', ':id') }}".replace(':id', bitId));
            const data = await response.json();
            
            document.getElementById('panelLoading').classList.add('hidden');
            document.getElementById('panelShopCount').textContent = `${data.shops ? data.shops.length : 0} shops found`;
            
            if (data.shops && data.shops.length > 0) {
                currentPanelShops = data.shops;
                renderPanelShops(data.shops);
            } else {
                document.getElementById('panelEmpty').classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error fetching shops:', error);
            document.getElementById('panelLoading').classList.add('hidden');
            // Show error state...
        }
    }

    function renderPanelShops(shops) {
        const container = document.getElementById('panelShopList');
        // Remove existing items
        const oldCards = container.querySelectorAll('.shop-panel-card');
        oldCards.forEach(c => c.remove());

        if (shops.length === 0) {
            // If searching and no results, show a tiny message but keep original empty state hidden
            return;
        }

        shops.forEach(shop => {
            const shopDiv = document.createElement('div');
            shopDiv.className = 'shop-panel-card flex items-center justify-between p-4 bg-white rounded-2xl border border-slate-100 hover:border-indigo-200 hover:shadow-md transition-all group animate-slide-up';
            shopDiv.innerHTML = `
                <div class="flex items-center gap-4">
                    <div class="h-12 w-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center border border-slate-100 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-colors font-bold text-lg">
                        ${shop.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="text-sm font-bold text-slate-900 truncate">${shop.name}</div>
                        <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-3 gap-y-1">
                            <span class="flex items-center gap-1">
                                <iconify-icon icon="lucide:user" width="12" class="text-slate-400"></iconify-icon>
                                ${shop.user ? shop.user.name : 'No owner'}
                            </span>
                            <span class="flex items-center gap-1">
                                <iconify-icon icon="lucide:phone" width="12" class="text-slate-400"></iconify-icon>
                                ${shop.phone || 'No phone'}
                            </span>
                        </div>
                    </div>
                </div>
                <a href="/admin/users/${shop.user_id}/edit" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all">
                    <iconify-icon icon="lucide:external-link" width="18"></iconify-icon>
                </a>
            `;
            container.appendChild(shopDiv);
        });
    }

    function filterPanelShops(query) {
        const term = query.toLowerCase().trim();
        const filtered = currentPanelShops.filter(shop => 
            shop.name.toLowerCase().includes(term) ||
            (shop.salesperson && shop.salesperson.name.toLowerCase().includes(term))
        );
        renderPanelShops(filtered);
    }
    
    function deleteArea(areaId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This will affect all shops in this bit. This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "{{ route('admin.bits.destroy', ':id') }}".replace(':id', areaId);
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';
                form.appendChild(methodField);
                
                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    
    function searchAreas(searchTerm) {
        const term = searchTerm.toLowerCase().trim();
        
        if (!term) {
            areas = [...originalAreas];
            renderAreasList();
            return;
        }
        
        areas = originalAreas.filter(area => 
            area.name.toLowerCase().includes(term) ||
            area.pincode.toLowerCase().includes(term) ||
            area.description.toLowerCase().includes(term)
        );
        
        renderAreasList();
    }
    
    function toggleShops(bitId) {
        const shopListContainer = document.getElementById(`shopList-${bitId}`);
        const toggleIcon = document.getElementById(`toggleIcon-${bitId}`);
        
        if (shopListContainer.classList.contains('hidden')) {
            // Show and possibly load
            shopListContainer.classList.remove('hidden');
            toggleIcon.style.transform = 'rotate(90deg)';
            
            // Check if already loaded (except for the loading spinner)
            if (shopListContainer.querySelector('.loading-spinner')) {
                fetchShops(bitId);
            }
        } else {
            // Hide
            shopListContainer.classList.add('hidden');
            toggleIcon.style.transform = 'rotate(0deg)';
        }
    }
    
    async function fetchShops(bitId) {
        const container = document.getElementById(`shopList-${bitId}`);
        
        try {
            const response = await fetch("{{ route('admin.bits.shops', ':id') }}".replace(':id', bitId));
            const data = await response.json();
            
            if (data.shops && data.shops.length > 0) {
                container.innerHTML = data.shops.map(shop => `
                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100 hover:border-indigo-200 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center text-xs font-bold">
                                ${shop.name.charAt(0)}
                            </div>
                            <div>
                                <div class="text-sm font-medium text-slate-900">${shop.name}</div>
                                <div class="text-xs text-slate-500">${shop.user ? 'Owner: ' + shop.user.name : 'No owner'}</div>
                                <div class="text-xs text-slate-500">${shop.salesperson ? 'SP: ' + shop.salesperson.name : 'Unassigned'}</div>
                            </div>
                        </div>
                        <a href="/admin/users/${shop.user_id}/edit" class="text-slate-400 hover:text-indigo-600 transition-colors">
                            <iconify-icon icon="lucide:external-link" width="16"></iconify-icon>
                        </a>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="text-center py-4 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                        <p class="text-xs text-slate-400">No shops found in this bit</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error fetching shops:', error);
            container.innerHTML = `
                <div class="text-center py-4 bg-rose-50 rounded-lg border border-rose-100">
                    <p class="text-xs text-rose-500">Failed to load shops</p>
                </div>
            `;
        }
    }
    
    function filterByStatus(status) {
        if (status === 'all') {
            areas = [...originalAreas];
        } else {
            areas = originalAreas.filter(area => area.status === status);
        }
        
        renderAreasList();
    }
    
    function showNotification(message, type) {
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800',
            warning: 'bg-amber-50 border-amber-200 text-amber-800'
        };
        
        const icons = {
            success: 'check-circle',
            error: 'x-circle',
            info: 'info',
            warning: 'alert-circle'
        };
        
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg border ${colors[type]} animate-slide-up`;
        notification.innerHTML = `
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:${icons[type]}" width="18"></iconify-icon>
                <span class="text-sm font-medium">${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateY(-10px)';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
</script>
@endsection