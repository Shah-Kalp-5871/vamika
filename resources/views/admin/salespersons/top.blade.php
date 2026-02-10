@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Top Salespersons',
    'subtitle' => 'Performance ranking of sales team members',
    'showBack' => true,
    'backUrl' => route('admin.reports.index'),
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

    .rank-badge {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
        flex-shrink: 0;
    }

    .rank-1 {
        background: linear-gradient(135deg, #FFD700, #FFA500);
        color: #7C2D12;
        border: 2px solid #FBBF24;
    }

    .rank-2 {
        background: linear-gradient(135deg, #C0C0C0, #A0A0A0);
        color: #374151;
        border: 2px solid #D1D5DB;
    }

    .rank-3 {
        background: linear-gradient(135deg, #CD7F32, #A0522D);
        color: #7C2D12;
        border: 2px solid #F59E0B;
    }

    .rank-4-10 {
        background: #F3F4F6;
        color: #4B5563;
        border: 1px solid #E5E7EB;
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

    .btn-secondary {
        background: white;
        color: #374151;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: 1px solid #E5E7EB;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    .back-link {
        color: #667eea;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .back-link:hover {
        color: #764ba2;
        gap: 0.75rem;
    }

    .date-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: white;
        transition: all 0.2s ease;
    }

    .date-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>

<div class="main-content">
    <main class="p-6 space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <div class="flex flex-col md:flex-row gap-3 w-full">
                    <select class="date-input w-full md:w-40">
                        <option>All Areas</option>
                        <option>Gandhi Nagar</option>
                        <option>Shahdara</option>
                        <option>Preet Vihar</option>
                        <option>Laxmi Nagar</option>
                    </select>
                    <select class="date-input w-full md:w-40">
                        <option>This Month</option>
                        <option>Last Month</option>
                        <option>This Quarter</option>
                        <option>This Year</option>
                        <option>Custom Range</option>
                    </select>
                </div>
                <button class="btn-primary px-6 w-full md:w-auto">
                    Apply Filters
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="totalSalespersons">15</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Salespersons</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="totalRevenue">₹8,42,150</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Revenue</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="totalOrders">342</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Orders</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900" id="avgPerSalesperson">₹2,461</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Avg. per Salesperson</p>
            </div>
        </div>

        <!-- Salespersons List -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50">
                <h3 class="text-sm font-semibold text-slate-900">Salespersons Performance</h3>
            </div>
            <div class="divide-y divide-slate-100" id="salespersonsList">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                <p class="text-sm text-slate-600">Export this data for further analysis</p>
                <div class="flex gap-3">
                    <button class="btn-primary" onclick="exportData()">
                        <iconify-icon icon="lucide:download" width="16" class="mr-2"></iconify-icon>
                        Export as Excel
                    </button>
                    <button class="btn-secondary" onclick="printPage()">
                        <iconify-icon icon="lucide:printer" width="16" class="mr-2"></iconify-icon>
                        Print
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Sample data for salespersons
    const salespersonsData = [
        { id: 1, name: 'Rajesh Kumar', orders: 45, revenue: 125430, target: 120000, area: 'Gandhi Nagar', commission: 6271, contact: '9876543210' },
        { id: 2, name: 'Suresh Patel', orders: 38, revenue: 108920, target: 110000, area: 'Shahdara', commission: 5446, contact: '9876543211' },
        { id: 3, name: 'Vikram Singh', orders: 32, revenue: 95670, target: 100000, area: 'Preet Vihar', commission: 4783, contact: '9876543212' },
        { id: 4, name: 'Amit Sharma', orders: 28, revenue: 78450, target: 90000, area: 'Laxmi Nagar', commission: 3922, contact: '9876543213' },
        { id: 5, name: 'Ravi Verma', orders: 25, revenue: 69800, target: 80000, area: 'Gandhi Nagar', commission: 3490, contact: '9876543214' },
        { id: 6, name: 'Sunil Gupta', orders: 22, revenue: 65430, target: 75000, area: 'Shahdara', commission: 3271, contact: '9876543215' },
        { id: 7, name: 'Manoj Yadav', orders: 20, revenue: 59870, target: 70000, area: 'Preet Vihar', commission: 2993, contact: '9876543216' },
        { id: 8, name: 'Deepak Jain', orders: 18, revenue: 52340, target: 65000, area: 'Laxmi Nagar', commission: 2617, contact: '9876543217' },
        { id: 9, name: 'Ankit Sharma', orders: 15, revenue: 45670, target: 60000, area: 'Gandhi Nagar', commission: 2283, contact: '9876543218' },
        { id: 10, name: 'Rohit Mehta', orders: 12, revenue: 38900, target: 55000, area: 'Shahdara', commission: 1945, contact: '9876543219' }
    ];

    function loadSalespersonsPage() {
        updateSummaryCards();
        loadSalespersonsList();
    }

    function updateSummaryCards() {
        const totalRevenue = salespersonsData.reduce((sum, sp) => sum + sp.revenue, 0);
        const totalOrders = salespersonsData.reduce((sum, sp) => sum + sp.orders, 0);
        const avgPerSalesperson = Math.round(totalRevenue / salespersonsData.length);
        
        document.getElementById('totalSalespersons').textContent = salespersonsData.length;
        document.getElementById('totalRevenue').textContent = '₹' + totalRevenue.toLocaleString();
        document.getElementById('totalOrders').textContent = totalOrders.toLocaleString();
        document.getElementById('avgPerSalesperson').textContent = '₹' + avgPerSalesperson.toLocaleString();
    }

    function loadSalespersonsList() {
        const listContainer = document.getElementById('salespersonsList');
        listContainer.innerHTML = '';
        
        salespersonsData.forEach((sp, index) => {
            const completionPercentage = Math.min(100, (sp.revenue / sp.target) * 100);
            const isTargetMet = sp.revenue >= sp.target;
            
            const item = document.createElement('div');
            item.className = 'p-4 hover:bg-slate-50 transition-colors';
            item.innerHTML = `
                <div class="flex items-center gap-4 mb-3">
                    <div class="rank-badge ${index < 3 ? `rank-${index + 1}` : 'rank-4-10'}">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-slate-900">${sp.name}</h4>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-slate-500">${sp.area}</span>
                            <span class="text-xs text-slate-500">•</span>
                            <span class="text-xs text-slate-500">${sp.contact}</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-sm font-semibold ${isTargetMet ? 'text-emerald-600' : 'text-rose-600'}">
                            ${isTargetMet ? 'Target Achieved' : 'Target Pending'}
                        </div>
                        <div class="text-xs text-slate-500">${completionPercentage.toFixed(1)}% completed</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">₹${sp.revenue.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">Revenue</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">${sp.orders}</div>
                        <div class="text-xs text-slate-500">Orders</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">₹${sp.commission.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">Commission</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">₹${sp.target.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">Target</div>
                    </div>
                </div>
                
                <!-- Progress bar -->
                <div class="mt-3">
                    <div class="flex justify-between text-xs text-slate-500 mb-1">
                        <span>Progress</span>
                        <span>${completionPercentage.toFixed(1)}%</span>
                    </div>
                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full ${isTargetMet ? 'bg-emerald-500' : 'bg-amber-500'}" 
                             style="width: ${completionPercentage}%"></div>
                    </div>
                </div>
            `;
            listContainer.appendChild(item);
        });
    }

    function exportData() {
        // Show toast notification
        if (typeof showToast === 'function') {
            showToast({
                text: 'Exporting salespersons data…',
                type: 'info'
            });
        } else {
            Swal.fire({
                icon: 'info',
                title: 'Exporting...',
                text: 'Exporting salespersons data…',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
        }
        
        // In real implementation, this would trigger a file download
        // For now, we'll simulate with a timeout
        setTimeout(() => {
            if (typeof showToast === 'function') {
                showToast({
                    text: 'Data exported successfully!',
                    type: 'success'
                });
            } else {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Data exported successfully!',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }, 1500);
    }

    function printPage() {
        window.print();
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', loadSalespersonsPage);
</script>
@endsection