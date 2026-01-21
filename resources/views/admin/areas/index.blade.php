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
        gap: 0.25rem;
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
   <header class="p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="flex justify-between items-start">

            <!-- Left: Logo + Content -->
            <div class="flex items-start gap-3">

                <!-- Logo -->
                <img
                    src="{{ asset('assets/images/logo.png') }}"
                    alt="Vamika Enterprise"
                    class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1"
                >

                <!-- Text Content -->
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="back-btn mb-3 inline-flex items-center gap-1">
                        <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                        Back to Dashboard
                    </a>

                    <p class="text-xs font-medium text-slate-400 mb-1 tracking-wide uppercase">
                        Area Management
                    </p>
                    <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
                        Manage Areas
                    </h1>
                    <h2 class="text-lg font-normal text-slate-500 tracking-tight">
                        Delivery areas and salesperson assignments
                    </h2>
                </div>
            </div>

            <!-- Right: Stats -->
            <div class="flex flex-col items-end">
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                    <iconify-icon icon="carbon:location" width="12" class="mr-1.5"></iconify-icon>
                    <span id="totalAreasCount">0</span> Areas
                </span>
                <span class="text-xs text-slate-400 mt-2" id="currentDate"></span>
            </div>

        </div>
    </header>

    <main class="p-6 space-y-8">

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Total Areas -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up">
                <div class="mb-3">
                    <iconify-icon icon="lucide:map-pin" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalAreas">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Areas</p>
            </div>

            <!-- Active Areas -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up" style="animation-delay: 0.1s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:check-circle" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="activeAreas">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Areas</p>
            </div>

            <!-- Total Shops -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up" style="animation-delay: 0.2s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:store" width="20" class="text-amber-500"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalShops">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Shops</p>
            </div>

            <!-- Assigned Salespersons -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up" style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:users" width="20" class="text-purple-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalSalespersons">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Salespersons</p>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h3 class="text-sm font-semibold text-slate-900">Delivery Areas</h3>
            <div class="flex items-center gap-3">
                <div class="relative flex-1 sm:flex-none">
                    <iconify-icon icon="lucide:search" width="16" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" placeholder="Search areas..." 
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
                <a href="{{ route('admin.areas.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                    <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                    Add New Area
                </a>
            </div>
        </div>
    </main>
</div>

<!-- Add Area Button -->
<a href="{{ route('admin.areas.create') }}" class="add-btn no-underline">
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
        
        // Sample dummy data - replace with actual API call
        areas = getDummyAreasData();
        originalAreas = [...areas];
        
        // Initialize areas with additional data
        areas.forEach((area, index) => {
            area.assignedSalespersons = [];
            
            // Assign random salespersons (0-2 per area)
            const numAssignments = Math.min(Math.floor(Math.random() * 3), salespersons.length);
            for (let i = 0; i < numAssignments; i++) {
                const spIndex = (index + i) % salespersons.length;
                if (!area.assignedSalespersons.some(sp => sp.id === salespersons[spIndex].id)) {
                    area.assignedSalespersons.push({...salespersons[spIndex]});
                }
            }
            
            area.status = index % 5 === 0 ? 'inactive' : 'active';
            area.description = area.description || `Delivery area for ${area.name} with ${area.shops} shops`;
            area.deliveryCharge = area.deliveryCharge || (50 + Math.floor(Math.random() * 50));
        });
        
        updateAreaStats();
        renderAreasList();
    }
    
    function getDummyAreasData() {
        return [
            { id: 1, name: 'Mumbai Central', pincode: '400008', shops: 150, description: 'Central Mumbai area including business districts', deliveryCharge: 75 },
            { id: 2, name: 'Andheri East', pincode: '400069', shops: 200, description: 'Commercial and residential area near airport', deliveryCharge: 65 },
            { id: 3, name: 'Bandra West', pincode: '400050', shops: 120, description: 'Upscale residential and shopping area', deliveryCharge: 80 },
            { id: 4, name: 'Powai', pincode: '400076', shops: 180, description: 'IT hub with residential complexes', deliveryCharge: 70 },
            { id: 5, name: 'Thane West', pincode: '400601', shops: 220, description: 'Rapidly developing suburban area', deliveryCharge: 60 },
            { id: 6, name: 'Vashi', pincode: '400703', shops: 190, description: 'Navi Mumbai business district', deliveryCharge: 55 },
            { id: 7, name: 'Chembur', pincode: '400071', shops: 140, description: 'Established residential area', deliveryCharge: 70 },
            { id: 8, name: 'Ghatkopar', pincode: '400077', shops: 210, description: 'Major suburban commercial center', deliveryCharge: 65 }
        ];
    }
    
    function updateAreaStats() {
        const totalAreas = areas.length;
        const activeAreas = areas.filter(a => a.status === 'active').length;
        const totalShops = areas.reduce((sum, area) => sum + area.shops, 0);
        const totalSalespersons = new Set(areas.flatMap(a => a.assignedSalespersons.map(s => s.id))).size;
        
        document.getElementById('totalAreas').textContent = totalAreas;
        document.getElementById('totalAreasCount').textContent = totalAreas;
        document.getElementById('activeAreas').textContent = activeAreas;
        document.getElementById('totalShops').textContent = totalShops.toLocaleString();
        document.getElementById('totalSalespersons').textContent = totalSalespersons;
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
                                    <span>${area.pincode}</span>
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
                            <a href="{{ route('admin.areas.edit', ':id') }}".replace(':id', area.id)
                               class="px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg border border-slate-200 transition-colors flex items-center gap-2 no-underline">
                                <iconify-icon icon="lucide:edit" width="14"></iconify-icon>
                                Edit
                            </a>
                            <button onclick="deleteArea(${area.id})"
                                    class="px-3 py-2 text-sm font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-slate-200 transition-colors flex items-center gap-2">
                                <iconify-icon icon="lucide:trash-2" width="14"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    ${area.description ? `
                        <div class="mb-4 p-3 bg-slate-50 rounded-lg border border-slate-100">
                            <p class="text-sm text-slate-600">
                                <iconify-icon icon="lucide:info" width="14" class="text-slate-400 mr-2"></iconify-icon>
                                ${area.description}
                            </p>
                        </div>
                    ` : ''}
                    
                    <!-- Salespersons section -->
                    <div class="mt-6 pt-6 border-t border-slate-100">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900 mb-1">Assigned Salespersons</h4>
                                <p class="text-xs text-slate-500">Manage salesperson assignments for this area</p>
                            </div>
                            <a href="{{ route('admin.salespersons.assign.form') }}?area_id=${area.id}"
                               class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors no-underline">
                                <iconify-icon icon="lucide:user-plus" width="14"></iconify-icon>
                                Manage Assignments
                            </a>
                        </div>
                        
                        <!-- Salespersons list -->
                        <div class="space-y-2">
                            ${area.assignedSalespersons.length > 0 
                                ? area.assignedSalespersons.map(sp => `
                                    <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg border border-slate-100">
                                        <div class="flex items-center gap-3">
                                            <div class="salesperson-avatar">${sp.name.charAt(0)}</div>
                                            <div>
                                                <div class="text-sm font-medium text-slate-900">${sp.name}</div>
                                                <div class="text-xs text-slate-500">${sp.employeeId}</div>
                                            </div>
                                        </div>
                                        <a href="{{ route('admin.salespersons.assign.form') }}?area_id=${area.id}&remove_id=${sp.id}"
                                           class="text-slate-400 hover:text-rose-500 transition-colors p-1"
                                           onclick="return confirm('Remove ${sp.name} from this area?')">
                                            <iconify-icon icon="lucide:x" width="16"></iconify-icon>
                                        </a>
                                    </div>
                                `).join('')
                                : `<div class="text-center py-4">
                                    <p class="text-sm text-slate-400 italic">No salespersons assigned to this area</p>
                                </div>`
                            }
                        </div>
                    </div>
                </div>
            `;
            areasList.appendChild(areaCard);
        });
    }
    
    function deleteArea(areaId) {
        if (confirm('Are you sure you want to delete this area?\n\nThis will affect all shops in this area.')) {
            areas = areas.filter(a => a.id !== areaId);
            originalAreas = originalAreas.filter(a => a.id !== areaId);
            updateAreaStats();
            renderAreasList();
            showNotification('Area deleted successfully!', 'info');
        }
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