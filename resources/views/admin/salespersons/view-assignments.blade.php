@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Assignments',
    'subtitle' => 'Manage salesperson shop assignments',
    'showBack' => true,
    'backUrl' => route('admin.users.index'),
    'role' => 'Admin Access',
    'showBottomNav' => true
];
@endphp

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

    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }

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

    .assignment-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }

    .assignment-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .status-active {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .status-inactive {
        background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
        color: white;
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
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.4);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }

    .btn-danger {
        background: white;
        color: #EF4444;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid #FCA5A5;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: #FEF2F2;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        background: white;
        color: #1E293B;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        padding-left: 2.5rem;
    }

    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .tabs {
        display: flex;
        overflow-x: auto;
        gap: 1px;
        background: #F1F5F9;
        border-radius: 0.75rem;
        padding: 2px;
        margin-bottom: 1.5rem;
    }

    .tab {
        padding: 0.75rem 1rem;
        background: white;
        border: none;
        color: #64748B;
        font-weight: 500;
        font-size: 0.875rem;
        white-space: nowrap;
        cursor: pointer;
        transition: all 0.2s ease;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .tab:hover {
        color: #334155;
        background: #F8FAFC;
    }

    .tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
    }

    .empty-state {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 4rem 2rem;
        text-align: center;
        color: #6B7280;
    }
</style>

<div class="main-content">
    <main class="p-6 space-y-6">
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalAssignments">0</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Total Assignments</p>
                    </div>
                    <div class="p-2 bg-indigo-50 rounded-lg border border-indigo-100">
                        <iconify-icon icon="lucide:link" width="20" class="text-indigo-600"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="activeAssignments">0</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Active</p>
                    </div>
                    <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                        <iconify-icon icon="lucide:check-circle" width="20" class="text-emerald-600"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="assignedAreas">0</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Areas Covered</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg border border-blue-100">
                        <iconify-icon icon="lucide:map-pin" width="20" class="text-blue-600"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 animate-slide-up">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="assignedShops">0</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Shops Assigned</p>
                    </div>
                    <div class="p-2 bg-purple-50 rounded-lg border border-purple-100">
                        <iconify-icon icon="lucide:store" width="20" class="text-purple-600"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-1">
                <iconify-icon icon="lucide:search" width="16" 
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text" class="search-input" id="assignmentSearch" 
                       placeholder="Search by salesperson, shop, or area...">
            </div>
            <div class="flex gap-2">
                <button class="btn-secondary" onclick="exportAssignments()">
                    <iconify-icon icon="lucide:download" width="16"></iconify-icon>
                    Export
                </button>
                <button class="btn-primary" onclick="viewReport()">
                    <iconify-icon icon="lucide:bar-chart" width="16"></iconify-icon>
                    Report
                </button>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="filterAssignments('all', this)">
                <iconify-icon icon="lucide:list"></iconify-icon>
                All Assignments
            </button>
            <button class="tab" onclick="filterAssignments('active', this)">
                <iconify-icon icon="lucide:check-circle"></iconify-icon>
                Active
            </button>
            <button class="tab" onclick="filterAssignments('inactive', this)">
                <iconify-icon icon="lucide:x-circle"></iconify-icon>
                Inactive
            </button>
            <button class="tab" onclick="filterAssignments('recent', this)">
                <iconify-icon icon="lucide:clock"></iconify-icon>
                Recent
            </button>
        </div>

        <!-- Assignments List -->
        <div id="assignmentsList">
            <!-- Assignments will be loaded here -->
        </div>

        <!-- Empty State -->
        <div class="empty-state" id="emptyState" style="display: none;">
            <iconify-icon icon="lucide:link" width="48" class="mb-4 text-slate-300"></iconify-icon>
            <h4 class="font-medium text-slate-900 mb-2">No Assignments Found</h4>
            <p class="text-sm text-slate-500 mb-4">Start by assigning shops to salespersons</p>
            <a href="{{ route('admin.users.index') }}" class="btn-primary">
                <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                Go to Users
            </a>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Dummy data for assignments
    let assignmentsData = [
        {
            id: 1,
            salespersonId: 4,
            salespersonName: 'Rajesh Kumar',
            salespersonEmail: 'sales@demo.com',
            salespersonPhone: '9876543200',
            areaId: 1,
            areaName: 'Gandhi Nagar',
            shopId: 1,
            shopName: 'Mohan Kirana Store',
            shopOwner: 'Mohan Singh',
            assignedDate: '2023-11-15',
            lastOrderDate: '2023-12-20',
            status: 'active',
            totalOrders: 24,
            totalAmount: 18500
        },
        {
            id: 2,
            salespersonId: 7,
            salespersonName: 'Suresh Patel',
            salespersonEmail: 'suresh@sales.com',
            salespersonPhone: '9876543201',
            areaId: 4,
            areaName: 'Preet Vihar',
            shopId: 5,
            shopName: 'Sharma Super Mart',
            shopOwner: 'Rajesh Sharma',
            assignedDate: '2023-11-20',
            lastOrderDate: '2023-12-19',
            status: 'active',
            totalOrders: 18,
            totalAmount: 14200
        },
        {
            id: 3,
            salespersonId: 4,
            salespersonName: 'Rajesh Kumar',
            salespersonEmail: 'sales@demo.com',
            salespersonPhone: '9876543200',
            areaId: 1,
            areaName: 'Gandhi Nagar',
            shopId: 2,
            shopName: 'Gupta General Store',
            shopOwner: 'Ramesh Gupta',
            assignedDate: '2023-11-10',
            lastOrderDate: '2023-12-18',
            status: 'active',
            totalOrders: 32,
            totalAmount: 24500
        },
        {
            id: 4,
            salespersonId: 9,
            salespersonName: 'Vikram Singh',
            salespersonEmail: 'vikram@sales.com',
            salespersonPhone: '9876543202',
            areaId: 3,
            areaName: 'Laxmi Nagar',
            shopId: 4,
            shopName: 'Verma Departmental Store',
            shopOwner: 'Anil Verma',
            assignedDate: '2023-10-15',
            lastOrderDate: '2023-11-05',
            status: 'inactive',
            totalOrders: 8,
            totalAmount: 6200
        },
        {
            id: 5,
            salespersonId: 7,
            salespersonName: 'Suresh Patel',
            salespersonEmail: 'suresh@sales.com',
            salespersonPhone: '9876543201',
            areaId: 2,
            areaName: 'Shahdara',
            shopId: 3,
            shopName: 'Bansal Provision',
            shopOwner: 'Sunil Bansal',
            assignedDate: '2023-12-01',
            lastOrderDate: '2023-12-21',
            status: 'active',
            totalOrders: 12,
            totalAmount: 9800
        }
    ];

    let assignments = [...assignmentsData];
    let currentFilter = 'all';

    // Initialize
    document.addEventListener('DOMContentLoaded', function () {
        loadAssignments();
        setupSearch();
        updateStats();
    });

    function loadAssignments() {
        filterAssignments(currentFilter);
    }

    function updateStats() {
        const totalAssignments = assignments.length;
        const activeAssignments = assignments.filter(a => a.status === 'active').length;
        const assignedAreas = [...new Set(assignments.map(a => a.areaName))].length;
        const assignedShops = [...new Set(assignments.map(a => a.shopName))].length;

        document.getElementById('totalAssignments').textContent = totalAssignments;
        document.getElementById('activeAssignments').textContent = activeAssignments;
        document.getElementById('assignedAreas').textContent = assignedAreas;
        document.getElementById('assignedShops').textContent = assignedShops;
    }

    function setupSearch() {
        const searchInput = document.getElementById('assignmentSearch');
        let searchTimeout;
        
        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const term = this.value.toLowerCase();
                filterAssignments(currentFilter, term);
            }, 300);
        });
    }

    function filterAssignments(filter, searchTerm = '', element = null) {
        currentFilter = filter;
        
        // Update tabs
        if (element) {
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            element.classList.add('active');
        }

        let filtered = assignmentsData;

        // Apply filter
        if (filter === 'active') {
            filtered = filtered.filter(a => a.status === 'active');
        } else if (filter === 'inactive') {
            filtered = filtered.filter(a => a.status === 'inactive');
        } else if (filter === 'recent') {
            // Last 7 days
            const weekAgo = new Date();
            weekAgo.setDate(weekAgo.getDate() - 7);
            filtered = filtered.filter(a => new Date(a.assignedDate) >= weekAgo);
        }

        // Apply search
        if (searchTerm) {
            filtered = filtered.filter(a =>
                a.salespersonName.toLowerCase().includes(searchTerm) ||
                a.shopName.toLowerCase().includes(searchTerm) ||
                a.areaName.toLowerCase().includes(searchTerm) ||
                a.shopOwner.toLowerCase().includes(searchTerm)
            );
        }

        assignments = filtered;
        renderAssignments();
        updateStats();
    }

    function renderAssignments() {
        const container = document.getElementById('assignmentsList');
        const emptyState = document.getElementById('emptyState');

        if (assignments.length === 0) {
            container.innerHTML = '';
            emptyState.style.display = 'flex';
            return;
        }

        emptyState.style.display = 'none';
        container.innerHTML = '';

        // Group by salesperson
        const bySalesperson = {};
        assignments.forEach(assignment => {
            if (!bySalesperson[assignment.salespersonId]) {
                bySalesperson[assignment.salespersonId] = {
                    salesperson: {
                        id: assignment.salespersonId,
                        name: assignment.salespersonName,
                        email: assignment.salespersonEmail,
                        phone: assignment.salespersonPhone
                    },
                    assignments: []
                };
            }
            bySalesperson[assignment.salespersonId].assignments.push(assignment);
        });

        // Render grouped assignments
        Object.values(bySalesperson).forEach((group, index) => {
            const groupCard = document.createElement('div');
            groupCard.className = 'assignment-card animate-slide-up';
            groupCard.style.animationDelay = `${index * 0.1}s`;

            // Calculate totals for this salesperson
            const totalShops = group.assignments.length;
            const activeShops = group.assignments.filter(a => a.status === 'active').length;
            const totalOrders = group.assignments.reduce((sum, a) => sum + a.totalOrders, 0);
            const totalAmount = group.assignments.reduce((sum, a) => sum + a.totalAmount, 0);

            groupCard.innerHTML = `
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div>
                            <h3 class="font-semibold text-slate-900">${group.salesperson.name}</h3>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-sm text-slate-500">${group.salesperson.email}</span>
                                <span class="text-sm text-slate-500">•</span>
                                <span class="text-sm text-slate-500">${group.salesperson.phone}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-slate-500">${activeShops}/${totalShops} shops active</span>
                    </div>
                </div>

                <div class="space-y-3">
                    ${group.assignments.map(assignment => `
                        <div class="bg-slate-50 rounded-lg p-4 border border-slate-200">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-medium text-slate-900">${assignment.shopName}</h4>
                                    <span class="status-badge ${assignment.status === 'active' ? 'status-active' : 'status-inactive'}">
                                        <iconify-icon icon="lucide:${assignment.status === 'active' ? 'check-circle' : 'x-circle'}" 
                                            width="12" class="mr-1"></iconify-icon>
                                        ${assignment.status.charAt(0).toUpperCase() + assignment.status.slice(1)}
                                    </span>
                                </div>
                                <span class="text-sm text-slate-500">${assignment.areaName}</span>
                            </div>
                            
                            <div class="flex items-center justify-between text-sm text-slate-600 mb-3">
                                <span>Owner: ${assignment.shopOwner}</span>
                                <span>Assigned: ${assignment.assignedDate}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-4 mb-3">
                                <div class="bg-white p-3 rounded border border-slate-200">
                                    <div class="text-xs text-slate-500 mb-1">Total Orders</div>
                                    <div class="font-semibold text-slate-900">${assignment.totalOrders}</div>
                                </div>
                                <div class="bg-white p-3 rounded border border-slate-200">
                                    <div class="text-xs text-slate-500 mb-1">Total Amount</div>
                                    <div class="font-semibold text-slate-900">₹${assignment.totalAmount.toLocaleString()}</div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <!-- Row 1 -->
                                <div class="flex gap-2">
                                    <button class="btn-secondary flex-1" onclick="editAssignment(${assignment.id})">
                                        <iconify-icon icon="lucide:edit" width="14"></iconify-icon>
                                        Edit
                                    </button>

                                    <button class="btn-danger flex-1" onclick="removeAssignment(${assignment.id})">
                                        <iconify-icon icon="lucide:trash" width="14"></iconify-icon>
                                        Remove
                                    </button>
                                </div>

                                <!-- Row 2 -->
                                <button class="btn-secondary w-full" onclick="viewOrders(${assignment.id})">
                                    <iconify-icon icon="lucide:file-text" width="14"></iconify-icon>
                                    Orders
                                </button>
                            </div>
                        </div>
                    `).join('')}
                </div>

                <div class="mt-4 pt-4 border-t border-slate-200">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-4">
                            <span class="text-slate-600">
                                <iconify-icon icon="lucide:package" width="14" class="mr-1"></iconify-icon>
                                ${totalOrders} total orders
                            </span>
                            <span class="text-slate-600">
                                <iconify-icon icon="lucide:indian-rupee" width="14" class="mr-1"></iconify-icon>
                                ₹${totalAmount.toLocaleString()} total
                            </span>
                        </div>
                        <button class="text-indigo-600 hover:text-indigo-800 font-medium" 
                                onclick="assignMoreShops(${group.salesperson.id}, '${group.salesperson.name}')">
                            <iconify-icon icon="lucide:plus" width="14" class="mr-1"></iconify-icon>
                            Assign More Shops
                        </button>
                    </div>
                </div>
            `;
            
            container.appendChild(groupCard);
        });
    }

    function editAssignment(assignmentId) {
        const assignment = assignments.find(a => a.id === assignmentId);
        if (assignment) {
            // In real app, open edit form or redirect to edit page
            alert(`Edit assignment: ${assignment.shopName}\nSalesperson: ${assignment.salespersonName}`);
        }
    }

    function removeAssignment(assignmentId) {
        if (confirm('Are you sure you want to remove this assignment?\n\nThis will prevent the salesperson from taking orders from this shop.')) {
            // In real app, call API to delete assignment
            assignmentsData = assignmentsData.filter(a => a.id !== assignmentId);
            assignments = assignments.filter(a => a.id !== assignmentId);
            renderAssignments();
            updateStats();
            
            // Show success message
            if (typeof showToast === 'function') {
                showToast({
                    text: 'Assignment removed successfully',
                    type: 'success'
                });
            } else {
                alert('Assignment removed successfully');
            }
        }
    }

    function viewOrders(assignmentId) {
        const assignment = assignments.find(a => a.id === assignmentId);
        if (assignment) {
            // In real app, redirect to orders page filtered by this assignment
            window.location.href = "{{ route('admin.orders.index') }}?salesperson_id=" + assignment.salespersonId + "&shop_id=" + assignment.shopId;
        }
    }

    function assignMoreShops(salespersonId, salespersonName) {
        // Redirect to assign area page for this salesperson
        window.location.href = "{{ route('admin.areas.assign.form') }}?user_id=" + salespersonId + "&name=" + encodeURIComponent(salespersonName);
    }

    function exportAssignments() {
        // Show toast notification
        if (typeof showToast === 'function') {
            showToast({
                text: 'Exporting assignments report...',
                type: 'info'
            });
        } else {
            alert('Exporting assignments report...');
        }
        
        // In real app, generate and download CSV/PDF
        setTimeout(() => {
            if (typeof showToast === 'function') {
                showToast({
                    text: 'Assignments report exported successfully!',
                    type: 'success'
                });
            } else {
                alert('Assignments report exported successfully!');
            }
        }, 1500);
    }

    function viewReport() {
        // In real app, redirect to detailed report page
        window.location.href = "{{ route('admin.reports.index') }}?type=assignments";
    }
</script>
@endsection