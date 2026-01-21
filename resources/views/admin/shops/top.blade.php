@extends('layouts.admin')

@section('title', 'Top Shops')
@section('subtitle', 'Performance ranking of retail shops')

@section('css')
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

    .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #7C2D12; border: 2px solid #FBBF24; }
    .rank-2 { background: linear-gradient(135deg, #C0C0C0, #A0A0A0); color: #374151; border: 2px solid #D1D5DB; }
    .rank-3 { background: linear-gradient(135deg, #CD7F32, #A0522D); color: #7C2D12; border: 2px solid #F59E0B; }
    .rank-4-10 { background: #F3F4F6; color: #4B5563; border: 1px solid #E5E7EB; }

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
        border: 1px solid #D1D5DB;
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

    .shop-status-active {
        color: #10B981;
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .shop-status-inactive {
        color: #EF4444;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }

    .date-input {
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        background-color: white;
        font-size: 0.875rem;
        color: #1E293B;
        width: 100%;
    }

    .date-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-6 space-y-6">
        <!-- Filters -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select class="date-input">
                    <option>All Areas</option>
                    <option>Gandhi Nagar</option>
                    <option>Shahdara</option>
                    <option>Preet Vihar</option>
                    <option>Laxmi Nagar</option>
                </select>
                <select class="date-input">
                    <option>All Shop Types</option>
                    <option>Kirana Store</option>
                    <option>General Store</option>
                    <option>Super Mart</option>
                    <option>Departmental Store</option>
                </select>
                <select class="date-input">
                    <option>This Month</option>
                    <option>Last Month</option>
                    <option>This Quarter</option>
                    <option>This Year</option>
                </select>
            </div>
            <div class="flex gap-3 mt-4">
                <button class="btn-primary px-6">
                    Apply Filters
                </button>
                <button class="btn-secondary" onclick="resetFilters()">
                    Reset
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">42</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Shops</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">₹12,45,670</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Revenue</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">856</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Orders</p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <p class="text-2xl font-semibold text-slate-900">₹29,658</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Avg. per Shop</p>
            </div>
        </div>

        <!-- Shops List -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-slate-900">Shops Performance Ranking</h3>
                <span class="text-xs text-slate-500">Sorted by Revenue</span>
            </div>
            <div class="divide-y divide-slate-100" id="shopsList">
                <!-- Dynamic content will be loaded here -->
            </div>
        </div>

        <!-- Map View Button -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <div class="flex flex-col sm:flex-row gap-3 justify-between items-center">
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 mb-1">View Shops on Map</h4>
                    <p class="text-xs text-slate-500">See geographical distribution of shops</p>
                </div>
                {{-- <a href="{{ route('admin.shops.map') ?? '#' }}" class="btn-primary"> --}}
                    <iconify-icon icon="lucide:map" width="16" class="mr-2"></iconify-icon>
                    Open Map View
                </a>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Sample data for shops
    const shopsData = [
        { id: 1, name: 'Mohan Kirana Store', orders: 45, revenue: 125430, area: 'Gandhi Nagar', type: 'Kirana Store', status: 'active', owner: 'Mohan Sharma', contact: '9876543201' },
        { id: 2, name: 'Gupta General Store', orders: 32, revenue: 108920, area: 'Gandhi Nagar', type: 'General Store', status: 'active', owner: 'Ramesh Gupta', contact: '9876543202' },
        { id: 3, name: 'Bansal Provision', orders: 28, revenue: 95670, area: 'Shahdara', type: 'Kirana Store', status: 'active', owner: 'Sunil Bansal', contact: '9876543203' },
        { id: 4, name: 'Sharma Super Mart', orders: 25, revenue: 78450, area: 'Preet Vihar', type: 'Super Mart', status: 'active', owner: 'Vikram Sharma', contact: '9876543204' },
        { id: 5, name: 'Verma Departmental Store', orders: 19, revenue: 69800, area: 'Laxmi Nagar', type: 'Departmental Store', status: 'active', owner: 'Raj Verma', contact: '9876543205' },
        { id: 6, name: 'Patel Kirana', orders: 18, revenue: 65430, area: 'Gandhi Nagar', type: 'Kirana Store', status: 'active', owner: 'Manoj Patel', contact: '9876543206' },
        { id: 7, name: 'Singh General Store', orders: 16, revenue: 59870, area: 'Shahdara', type: 'General Store', status: 'active', owner: 'Jaspreet Singh', contact: '9876543207' },
        { id: 8, name: 'Jain Provision', orders: 14, revenue: 52340, area: 'Preet Vihar', type: 'Kirana Store', status: 'active', owner: 'Amit Jain', contact: '9876543208' },
        { id: 9, name: 'Yadav Store', orders: 12, revenue: 45670, area: 'Laxmi Nagar', type: 'Kirana Store', status: 'active', owner: 'Deepak Yadav', contact: '9876543209' },
        { id: 10, name: 'Mehta Mart', orders: 10, revenue: 38900, area: 'Gandhi Nagar', type: 'Super Mart', status: 'inactive', owner: 'Rohit Mehta', contact: '9876543210' }
    ];

    function loadShopsPage() {
        loadShopsList();
    }

    function loadShopsList() {
        const listContainer = document.getElementById('shopsList');
        listContainer.innerHTML = '';
        
        shopsData.forEach((shop, index) => {
            const avgOrderValue = Math.round(shop.revenue / shop.orders);
            
            const item = document.createElement('div');
            item.className = 'p-4 hover:bg-slate-50 transition-colors';
            item.innerHTML = `
                <div class="flex items-center gap-4 mb-3">
                    <div class="rank-badge ${index < 3 ? `rank-${index + 1}` : 'rank-4-10'}">
                        ${index + 1}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-semibold text-slate-900">${shop.name}</h4>
                                <div class="flex items-center gap-3 mt-1">
                                    <span class="text-xs text-slate-500">${shop.area}</span>
                                    <span class="text-xs text-slate-500">•</span>
                                    <span class="text-xs text-slate-500">${shop.type}</span>
                                </div>
                            </div>
                            <span class="text-xs px-2 py-1 rounded-full ${shop.status === 'active' ? 'shop-status-active' : 'shop-status-inactive'}">
                                ${shop.status === 'active' ? 'Active' : 'Inactive'}
                            </span>
                        </div>
                        <div class="text-xs text-slate-500 mt-2">
                            Owner: ${shop.owner} • Contact: ${shop.contact}
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">₹${shop.revenue.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">Revenue</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">${shop.orders}</div>
                        <div class="text-xs text-slate-500">Orders</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">₹${avgOrderValue.toLocaleString()}</div>
                        <div class="text-xs text-slate-500">Avg. Order Value</div>
                    </div>
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <div class="text-sm font-semibold text-slate-900">${calculateRating(shop.revenue)}/5</div>
                        <div class="text-xs text-slate-500">Performance Rating</div>
                    </div>
                </div>
                
                <!-- Performance indicators -->
                <div class="flex items-center gap-4 mt-3">
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <span class="text-xs text-slate-600">High Revenue</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full ${shop.orders > 20 ? 'bg-emerald-500' : 'bg-amber-500'}"></div>
                        <span class="text-xs text-slate-600">${shop.orders > 20 ? 'Frequent Orders' : 'Moderate Orders'}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 rounded-full ${avgOrderValue > 2500 ? 'bg-emerald-500' : 'bg-blue-500'}"></div>
                        <span class="text-xs text-slate-600">${avgOrderValue > 2500 ? 'Premium Orders' : 'Regular Orders'}</span>
                    </div>
                </div>
            `;
            listContainer.appendChild(item);
        });
    }

    function calculateRating(revenue) {
        if (revenue >= 100000) return 5;
        if (revenue >= 80000) return 4;
        if (revenue >= 60000) return 3;
        if (revenue >= 40000) return 2;
        return 1;
    }

    function resetFilters() {
        showToast({
            text: 'Filters reset',
            type: 'success'
        });
        loadShopsList();
    }

    function showToast({ text, type }) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg border ${
            type === 'error' ? 'bg-red-50 border-red-200 text-red-800' :
            type === 'success' ? 'bg-green-50 border-green-200 text-green-800' :
            'bg-blue-50 border-blue-200 text-blue-800'
        } animate__animated animate__slideInRight`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:${
                    type === 'error' ? 'x-circle' :
                    type === 'success' ? 'check-circle' : 'info'
                }" width="18"></iconify-icon>
                <span class="text-sm font-medium">${text}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Auto hide after 3 seconds
        setTimeout(() => {
            toast.classList.remove('animate__slideInRight');
            toast.classList.add('animate__slideOutRight');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', loadShopsPage);
</script>
@endsection