@extends('layouts.admin')

@section('content')
@php
$areaId = request()->area_id ?? 1;
$removeId = request()->remove_id ?? null;

$pageConfig = [
    'title' => 'Assign Salesperson',
    'subtitle' => 'Manage salespersons for area',
    'showBack' => true,
    'backUrl' => route('admin.areas.index'),
    'role' => 'Admin',
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

    /* Salesperson avatar */
    .salesperson-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: white;
        font-size: 1rem;
    }

    /* Form styles */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: white;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        padding: 0.75rem 2rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    /* Salesperson card */
    .salesperson-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 1px solid #E5E7EB;
        border-radius: 0.5rem;
        background: white;
        transition: all 0.2s ease;
    }

    .salesperson-card:hover {
        border-color: #6366F1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Loading spinner */
    .loading-spinner {
        border: 3px solid #f3f3f3;
        border-top: 3px solid #6366F1;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<div class="main-content">
    <main class="p-6 space-y-8">
        <!-- Area Info -->
        <div class="p-4 bg-slate-50 rounded-lg border border-slate-200">
            <div class="flex items-center gap-3">
                <iconify-icon icon="lucide:map-pin" width="20" class="text-indigo-600"></iconify-icon>
                <div>
                    <h3 class="font-medium text-slate-900" id="areaNameDisplay"></h3>
                    <div class="flex items-center gap-4 text-sm text-slate-500 mt-1">
                        <span class="flex items-center gap-1">
                            <iconify-icon icon="lucide:hash" width="14"></iconify-icon>
                            <span id="areaPincodeDisplay"></span>
                        </span>
                        <span class="flex items-center gap-1">
                            <iconify-icon icon="lucide:store" width="14"></iconify-icon>
                            <span id="areaShopsDisplay"></span> shops
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Salesperson -->
        <div class="form-group">
            <label class="form-label">Assign New Salesperson</label>
            <div class="flex gap-2">
                <select id="salespersonSelect" class="form-select flex-1">
                    <option value="">Select a salesperson...</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
                <button onclick="assignSalesperson()" class="btn-primary">
                    Assign
                </button>
            </div>
        </div>

        <!-- Assigned Salespersons -->
        <div>
            <h3 class="text-sm font-semibold text-slate-900 mb-4">Currently Assigned Salespersons</h3>
            <div id="assignedList" class="space-y-3">
                <!-- Salespersons will be loaded here -->
                <div class="text-center py-8">
                    <div class="loading-spinner"></div>
                    <p class="text-sm text-slate-400 mt-2">Loading assignments...</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const areaId = parseInt(urlParams.get('area_id')) || parseInt("{{ $areaId }}");
    const removeId = urlParams.get('remove_id') || "{{ $removeId }}";

    // Sample data
    const salespersons = [
        { id: 1, name: 'Rajesh Kumar', employeeId: 'EMP001', phone: '9876543210' },
        { id: 2, name: 'Suresh Patel', employeeId: 'EMP002', phone: '9876543211' },
        { id: 3, name: 'Vikram Singh', employeeId: 'EMP003', phone: '9876543212' },
        { id: 4, name: 'Amit Sharma', employeeId: 'EMP004', phone: '9876543213' },
        { id: 5, name: 'Neha Verma', employeeId: 'EMP005', phone: '9876543214' }
    ];

    // Sample area data
    const areasData = [
        { id: 1, name: 'Gandhi Nagar', pincode: '110031', shops: 15 },
        { id: 2, name: 'Laxmi Nagar', pincode: '110092', shops: 12 },
        { id: 3, name: 'Preet Vihar', pincode: '110092', shops: 18 },
        { id: 4, name: 'Shahdara', pincode: '110032', shops: 22 },
        { id: 5, name: 'Mayur Vihar', pincode: '110091', shops: 10 },
        { id: 6, name: 'Karol Bagh', pincode: '110005', shops: 25 }
    ];

    let area = null;
    let assignedSalespersons = [];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        if (areaId) {
            loadAreaData(areaId);
            loadAssignments();
            populateSalespersonSelect();
            
            // Handle remove parameter on page load
            if (removeId) {
                setTimeout(() => {
                    // Remove the remove_id parameter from URL without reloading
                    const url = new URL(window.location);
                    url.searchParams.delete('remove_id');
                    window.history.replaceState({}, '', url);
                }, 100);
            }
        }
    });

    function loadAreaData(id) {
        // Find area data
        area = areasData.find(a => a.id === id);

        if (area) {
            document.getElementById('areaNameDisplay').textContent = area.name;
            document.getElementById('areaPincodeDisplay').textContent = area.pincode;
            document.getElementById('areaShopsDisplay').textContent = area.shops;
        }
    }

    function loadAssignments() {
        // In real app, fetch assignments from API
        // For now, simulate random assignments
        assignedSalespersons = [];

        // Assign 0-2 random salespersons
        const numAssignments = Math.min(Math.floor(Math.random() * 3), salespersons.length);
        for (let i = 0; i < numAssignments; i++) {
            const spIndex = (areaId + i) % salespersons.length;
            if (!assignedSalespersons.some(sp => sp.id === salespersons[spIndex].id)) {
                assignedSalespersons.push({ ...salespersons[spIndex] });
            }
        }

        // If remove_id parameter exists, remove that salesperson
        if (removeId) {
            const removeIdInt = parseInt(removeId);
            const removeIndex = assignedSalespersons.findIndex(sp => sp.id === removeIdInt);
            if (removeIndex !== -1) {
                assignedSalespersons.splice(removeIndex, 1);
                showToast({
                    text: 'Salesperson removed from area',
                    type: 'success'
                });
            }
        }

        renderAssignedList();
    }

    function renderAssignedList() {
        const assignedList = document.getElementById('assignedList');

        if (assignedSalespersons.length === 0) {
            assignedList.innerHTML = `
                <div class="text-center py-8">
                    <iconify-icon icon="lucide:users" width="48" class="text-slate-300 mb-3"></iconify-icon>
                    <p class="text-slate-400">No salespersons assigned to this area</p>
                </div>
            `;
            return;
        }

        assignedList.innerHTML = assignedSalespersons.map(sp => `
            <div class="salesperson-card">
                <div class="flex items-center gap-3">
                    <div class="salesperson-avatar">${sp.name.charAt(0)}</div>
                    <div>
                        <h4 class="font-medium text-slate-900">${sp.name}</h4>
                        <div class="flex items-center gap-3 text-sm text-slate-500 mt-1">
                            <span>${sp.employeeId}</span>
                            <span>${sp.phone}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.salespersons.assign.form') }}?area_id=${areaId}&remove_id=${sp.id}"
                   class="text-rose-500 hover:text-rose-700 text-sm"
                   onclick="return confirm('Remove ${sp.name} from this area?')">
                    Remove
                </a>
            </div>
        `).join('');
    }

    function populateSalespersonSelect() {
        const select = document.getElementById('salespersonSelect');

        // Get salespersons not already assigned
        const availableSalespersons = salespersons.filter(sp =>
            !assignedSalespersons.some(assigned => assigned.id === sp.id)
        );

        if (availableSalespersons.length === 0) {
            select.innerHTML = '<option value="" disabled>All salespersons are already assigned</option>';
            return;
        }

        availableSalespersons.forEach(sp => {
            const option = document.createElement('option');
            option.value = sp.id;
            option.textContent = `${sp.name} (${sp.employeeId})`;
            select.appendChild(option);
        });
    }

    function assignSalesperson() {
        const select = document.getElementById('salespersonSelect');
        const salespersonId = parseInt(select.value);

        if (!salespersonId) {
            showToast({
                text: 'Please select a salesperson',
                type: 'error'
            });
            return;
        }

        const salesperson = salespersons.find(sp => sp.id === salespersonId);

        if (salesperson && !assignedSalespersons.some(sp => sp.id === salespersonId)) {
            assignedSalespersons.push({ ...salesperson });
            renderAssignedList();
            populateSalespersonSelect();
            select.value = '';

            // Show success message
            showToast({
                text: `${salesperson.name} assigned to ${area.name}`,
                type: 'success'
            });
        }
    }

    // Function to show toast messages (compatible with existing toast system)
    function showToast({text, type = 'info'}) {
        // This function should use the existing toast system from footer
        // For now, we'll use a simple alert
        alert(text);
    }
</script>
@endsection