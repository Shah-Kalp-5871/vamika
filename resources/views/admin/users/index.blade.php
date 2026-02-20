@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Manage Users',
    'showBack' => true,
    'backUrl' => route('admin.dashboard'),
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

    /* Main content container similar to reference */
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

    /* User avatar */
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        color: white;
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

    .badge-shop {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .badge-sales {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        color: white;
    }

    .badge-admin {
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        color: white;
    }

    .badge-active {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .badge-inactive {
        background: linear-gradient(135deg, #6B7280 0%, #4B5563 100%);
        color: white;
    }

    /* Tabs */
    .user-tabs {
        display: flex;
        overflow-x: auto;
        gap: 1px;
        background: #F1F5F9;
        border-radius: 0.75rem;
        padding: 2px;
        margin: 0 1.5rem;
    }

    .user-tab {
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

    .user-tab:hover {
        color: #334155;
        background: #F8FAFC;
    }

    .user-tab.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 2px 10px rgba(102, 126, 234, 0.2);
    }

    /* Search input */
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
        text-decoration: none;
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
        text-decoration: none;
    }

    .btn-secondary:hover {
        border-color: #D1D5DB;
        background: #F9FAFB;
    }

    .btn-analyze {
        background: linear-gradient(135deg, #8B5CF6 0%, #7C3AED 100%);
        color: white;
        font-weight: 500;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-analyze:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
        background: linear-gradient(135deg, #7C3AED 0%, #6D28D9 100%);
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

    /* Table styles */
    .users-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 0.75rem;
        overflow: hidden;
        border: 1px solid #E2E8F0;
    }

    .users-table th {
        background: #F8FAFC;
        padding: 0.75rem 1rem;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #E2E8F0;
    }

    .users-table td {
        padding: 1rem;
        border-bottom: 1px solid #E2E8F0;
        color: #374151;
        font-size: 0.875rem;
        vertical-align: middle;
    }

    .users-table tr:last-child td {
        border-bottom: none;
    }

    .users-table tr:hover {
        background: #F8FAFC;
    }

    /* Pagination */
    .pagination {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .page-btn {
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        border: 1px solid #E2E8F0;
        background: white;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 500;
        color: #4B5563;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .page-btn:hover:not(.active):not(:disabled) {
        background: #F3F4F6;
        border-color: #9CA3AF;
        color: #1F2937;
    }

    .page-btn.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-color: transparent;
    }

    .page-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Empty state */
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
        <!-- Stats Grid - Only 2 cards now -->
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 animate-slide-up"
                style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $totalShopOwners }}</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Shop Owners</p>
                    </div>
                    <div class="p-2 bg-emerald-50 rounded-lg border border-emerald-100">
                        <iconify-icon icon="lucide:store" width="20" class="text-emerald-600"></iconify-icon>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4 animate-slide-up"
                style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight">{{ $totalSalespersons }}</p>
                        <p class="text-xs text-slate-500 font-medium mt-1">Salespersons</p>
                    </div>
                    <div class="p-2 bg-blue-50 rounded-lg border border-blue-100">
                        <iconify-icon icon="lucide:briefcase" width="20" class="text-blue-600"></iconify-icon>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Add Bar -->
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="relative flex-1">
                <form action="{{ route('admin.users.index') }}" method="GET" class="w-full">
                    <iconify-icon icon="lucide:search" width="16"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" name="search" class="search-input" value="{{ request('search') }}"
                        placeholder="Search by name, email, phone, or shop..." onchange="this.form.submit()">
                    @if(request('role'))
                        <input type="hidden" name="role" value="{{ request('role') }}">
                    @endif
                </form>
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                Add New User
            </a>
        </div>

        <!-- User Tabs -->
        <div class="user-tabs">
            <a href="{{ route('admin.users.index') }}" class="user-tab {{ !request('role') ? 'active' : '' }}">
                <iconify-icon icon="lucide:users"></iconify-icon>
                All Users
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'shop-owner']) }}" class="user-tab {{ request('role') == 'shop-owner' ? 'active' : '' }}">
                <iconify-icon icon="lucide:store"></iconify-icon>
                Shop Owners
            </a>
            <a href="{{ route('admin.users.index', ['role' => 'salesperson']) }}" class="user-tab {{ request('role') == 'salesperson' ? 'active' : '' }}">
                <iconify-icon icon="lucide:briefcase"></iconify-icon>
                Salespersons
            </a>
        </div>

        <!-- Users Table -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="users-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Type</th>
                            <th>Contact</th>
                            <th>Bit</th>
                            <th>Status</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                        @forelse($users as $user)
                        <tr class="animate-slide-up" style="animation-delay: 0.1s;">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="user-avatar" style="background: {{ $user->role == 'shop-owner' ? 'linear-gradient(135deg, #10B981 0%, #059669 100%)' : 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)' }}">
                                        {{ substr($user->role == 'shop-owner' && $user->shop ? $user->shop->name : $user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        @if($user->role == 'shop-owner' && $user->shop)
                                            <h4 class="text-sm font-semibold text-slate-900">{{ $user->shop->name }}</h4>
                                            <p class="text-xs text-slate-600 font-medium mt-0.5">{{ $user->name }}</p>
                                        @else
                                            <h4 class="text-sm font-semibold text-slate-900">{{ $user->name }}</h4>
                                        @endif
                                        
                                        <p class="text-[10px] text-slate-400 mt-1">{{ $user->email }}</p>

                                        @if($user->role == 'salesperson' && $user->employee_id)
                                            <p class="text-xs text-slate-500 mt-1"><iconify-icon icon="lucide:id-card" width="12" class="mr-1"></iconify-icon> {{ $user->employee_id }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $user->role == 'shop-owner' ? 'badge-shop' : 'badge-sales' }}">
                                    <iconify-icon icon="{{ $user->role == 'shop-owner' ? 'lucide:store' : 'lucide:briefcase' }}" 
                                        width="12" class="mr-1"></iconify-icon>
                                    {{ $user->role == 'shop-owner' ? 'Shop Owner' : 'Salesperson' }}
                                </span>
                            </td>
                            <td>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-1 text-sm">
                                        <iconify-icon icon="lucide:phone" width="14" class="text-slate-400"></iconify-icon>
                                        <span>{{ $user->phone ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->bit)
                                <div class="flex items-center gap-1 text-sm">
                                    <iconify-icon icon="lucide:map-pin" width="14" class="text-slate-400"></iconify-icon>
                                    <span>{{ $user->bit->name }}</span>
                                </div>
                                @elseif($user->shop && $user->shop->bit)
                                <div class="flex items-center gap-1 text-sm">
                                    <iconify-icon icon="lucide:map-pin" width="14" class="text-slate-400"></iconify-icon>
                                    <span>{{ $user->shop->bit->name }}</span>
                                </div>
                                @else
                                <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->status == 'active' ? 'badge-active' : 'badge-inactive' }}">
                                    <iconify-icon icon="lucide:{{ $user->status == 'active' ? 'check-circle' : 'x-circle' }}" 
                                        width="12" class="mr-1"></iconify-icon>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center gap-1 text-sm">
                                    <iconify-icon icon="lucide:calendar" width="14" class="text-slate-400"></iconify-icon>
                                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-secondary">
                                        <iconify-icon icon="lucide:edit" width="14"></iconify-icon>
                                        Edit
                                    </a>
                                    
                                    @if($user->role === 'shop-owner')
                                    <a href="{{ route('admin.shops.analysis', ['shop_id' => $user->shop->id ?? 0]) }}" class="btn-analyze">
                                        <iconify-icon icon="lucide:bar-chart-2" width="14"></iconify-icon>
                                        Analyze
                                    </a>
                                    @elseif($user->role === 'salesperson')
                                    <a href="{{ route('admin.salespersons.details', $user->id) }}" class="btn-analyze" style="background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);">
                                        <iconify-icon icon="lucide:bar-chart-2" width="14"></iconify-icon>
                                        Report
                                    </a>
                                    @endif

                                    <form id="delete-user-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDeleteUser({{ $user->id }}, '{{ $user->name }}')" class="btn-danger">
                                            <iconify-icon icon="lucide:trash" width="14"></iconify-icon>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center p-4">
                                <div class="empty-state">
                                    <iconify-icon icon="lucide:users" width="48" class="mb-4 text-slate-300"></iconify-icon>
                                    <h4 class="font-medium text-slate-900 mb-2">No Users Found</h4>
                                    <p class="text-sm text-slate-500 mb-4">Try adjusting your search or add a new user</p>
                                    <a href="{{ route('admin.users.create') }}" class="btn-primary">
                                        <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                                        Add New User
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                </table>
            </div>

            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <iconify-icon icon="lucide:users" width="48" class="mb-4 text-slate-300"></iconify-icon>
                <h4 class="font-medium text-slate-900 mb-2">No Users Found</h4>
                <p class="text-sm text-slate-500 mb-4">Try adjusting your search or add a new user</p>
                <a href="{{ route('admin.users.create') }}" class="btn-primary">
                    <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                    Add New User
                </a>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t border-slate-100 pt-6">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                Showing {{ $users->firstItem() ?? 0 }}-{{ $users->lastItem() ?? 0 }} of {{ $users->total() }} Users
            </p>
            <div class="pagination-wrapper">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Animations
    document.addEventListener('DOMContentLoaded', function () {
        // Set current date
        const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
        const currentDateElement = document.getElementById('currentDate');
        if (currentDateElement) {
             currentDateElement.textContent = new Date().toLocaleDateString('en-US', dateOptions);
        }

        // Animate cards
        document.querySelectorAll('.animate-slide-up').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });

    function confirmDeleteUser(userId, userName) {
        Swal.fire({
            title: 'Delete User?',
            text: `Are you sure you want to delete ${userName}? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, delete user!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-user-${userId}`).submit();
            }
        });
    }
</script>
@endsection