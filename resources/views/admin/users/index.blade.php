@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Manage Users',
    'subtitle' => 'Admins, salespersons & shop owners',
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
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="shopOwners">0</p>
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
                        <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="salespersons">0</p>
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
                <iconify-icon icon="lucide:search" width="16"
                    class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text" class="search-input" id="userSearch"
                    placeholder="Search by name, email, phone, or shop...">
            </div>
            <a href="{{ route('admin.users.create') }}" class="btn-primary">
                <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                Add New User
            </a>
        </div>

        <!-- User Tabs - Removed Admin tab -->
        <div class="user-tabs">
            <button class="user-tab" data-type="all" onclick="filterUsers('all', this)">
                <iconify-icon icon="lucide:users"></iconify-icon>
                All Users
            </button>
            <button class="user-tab" data-type="shop-owner" onclick="filterUsers('shop-owner', this)">
                <iconify-icon icon="lucide:store"></iconify-icon>
                Shop Owners
            </button>
            <button class="user-tab" data-type="salesperson" onclick="filterUsers('salesperson', this)">
                <iconify-icon icon="lucide:briefcase"></iconify-icon>
                Salespersons
            </button>
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
                            <th>Area</th>
                            <th>Status</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Users will be loaded here -->
                    </tbody>
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
        <div class="pagination" id="pagination">
            <!-- Pagination buttons will be loaded here -->
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    let currentTab = 'all';
    let currentPage = 1;
    const usersPerPage = 10;
    let allUsers = [];

    // Sample user data with shopId for shop owners
    const userData = [
        {
            id: 1,
            name: 'Mohan Singh',
            email: 'shop@demo.com',
            phone: '9876543210',
            type: 'shop-owner',
            status: 'active',
            joinDate: '2023-06-15',
            area: 'Gandhi Nagar',
            shopName: 'Mohan Kirana Store',
            shopId: 101,
            avatar: 'M',
            avatarColor: '#4ECDC4'
        },
        {
            id: 2,
            name: 'Ramesh Gupta',
            email: 'ramesh@shop.com',
            phone: '9876543211',
            type: 'shop-owner',
            status: 'active',
            joinDate: '2023-07-20',
            area: 'Gandhi Nagar',
            shopName: 'Gupta General Store',
            shopId: 102,
            avatar: 'R',
            avatarColor: '#45B7D1'
        },
        {
            id: 3,
            name: 'Rajesh Kumar',
            email: 'sales@demo.com',
            phone: '9876543200',
            type: 'salesperson',
            status: 'active',
            joinDate: '2023-08-10',
            area: 'Gandhi Nagar',
            employeeId: 'EMP001',
            avatar: 'R',
            avatarColor: '#96CEB4'
        },
        {
            id: 4,
            name: 'Sunil Bansal',
            email: 'sunil@shop.com',
            phone: '9876543212',
            type: 'shop-owner',
            status: 'active',
            joinDate: '2023-08-22',
            area: 'Shahdara',
            shopName: 'Bansal Provision',
            shopId: 103,
            avatar: 'S',
            avatarColor: '#FFEAA7'
        },
        {
            id: 5,
            name: 'Anil Verma',
            email: 'anil@shop.com',
            phone: '9876543214',
            type: 'shop-owner',
            status: 'inactive',
            joinDate: '2023-09-05',
            area: 'Laxmi Nagar',
            shopName: 'Verma Departmental Store',
            shopId: 104,
            avatar: 'A',
            avatarColor: '#DDA0DD'
        },
        {
            id: 6,
            name: 'Suresh Patel',
            email: 'suresh@sales.com',
            phone: '9876543201',
            type: 'salesperson',
            status: 'active',
            joinDate: '2023-09-15',
            area: 'Preet Vihar',
            employeeId: 'EMP002',
            avatar: 'S',
            avatarColor: '#98D8C8'
        },
        {
            id: 7,
            name: 'Rajesh Sharma',
            email: 'rajesh@shop.com',
            phone: '9876543213',
            type: 'shop-owner',
            status: 'active',
            joinDate: '2023-10-01',
            area: 'Preet Vihar',
            shopName: 'Sharma Super Mart',
            shopId: 105,
            avatar: 'R',
            avatarColor: '#F7DC6F'
        },
        {
            id: 8,
            name: 'Vikram Singh',
            email: 'vikram@sales.com',
            phone: '9876543202',
            type: 'salesperson',
            status: 'inactive',
            joinDate: '2023-10-10',
            area: 'Laxmi Nagar',
            employeeId: 'EMP003',
            avatar: 'V',
            avatarColor: '#BB8FCE'
        },
        {
            id: 9,
            name: 'Amit Kumar',
            email: 'amit@shop.com',
            phone: '9876543215',
            type: 'shop-owner',
            status: 'active',
            joinDate: '2023-11-05',
            area: 'Mayur Vihar',
            shopName: 'Amit Grocery Store',
            shopId: 106,
            avatar: 'A',
            avatarColor: '#FF9F1C'
        },
        {
            id: 10,
            name: 'Deepak Sharma',
            email: 'deepak@sales.com',
            phone: '9876543203',
            type: 'salesperson',
            status: 'active',
            joinDate: '2023-11-20',
            area: 'Mayur Vihar',
            employeeId: 'EMP004',
            avatar: 'D',
            avatarColor: '#6A0572'
        },
        {
            id: 11,
            name: 'Sanjay Mehta',
            email: 'sanjay@shop.com',
            phone: '9876543216',
            type: 'shop-owner',
            status: 'inactive',
            joinDate: '2023-12-01',
            area: 'Karol Bagh',
            shopName: 'Mehta Super Store',
            shopId: 107,
            avatar: 'S',
            avatarColor: '#118AB2'
        },
        {
            id: 12,
            name: 'Rahul Verma',
            email: 'rahul@sales.com',
            phone: '9876543204',
            type: 'salesperson',
            status: 'active',
            joinDate: '2023-12-15',
            area: 'Karol Bagh',
            employeeId: 'EMP005',
            avatar: 'R',
            avatarColor: '#06D6A0'
        }
    ];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        // Set current date
        const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', dateOptions);

        // Animate cards
        document.querySelectorAll('.animate-slide-up').forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });

        loadUsersPage();
        setupSearch();
    });

    function loadUsersPage() {
        allUsers = userData;
        updateStats();
        filterUsers('all');
    }

    function updateStats() {
        const shopOwners = allUsers.filter(u => u.type === 'shop-owner').length;
        const salespersons = allUsers.filter(u => u.type === 'salesperson').length;

        document.getElementById('shopOwners').textContent = shopOwners;
        document.getElementById('salespersons').textContent = salespersons;
    }

    function filterUsers(type, el = null) {
        currentTab = type;
        currentPage = 1;

        document.querySelectorAll('.user-tab').forEach(tab => {
            tab.classList.remove('active');
        });

        if (el) {
            el.classList.add('active');
        } else {
            // Default case (page load)
            document.querySelector('.user-tab[data-type="all"]').classList.add('active');
        }

        renderUsersTable();
    }

    function setupSearch() {
        const searchInput = document.getElementById('userSearch');
        let searchTimeout;

        searchInput.addEventListener('input', function () {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentPage = 1;
                renderUsersTable();
            }, 300);
        });
    }

    function renderUsersTable() {
        // Filter users
        let filteredUsers = allUsers;
        if (currentTab !== 'all') {
            filteredUsers = allUsers.filter(user => user.type === currentTab);
        }

        // Apply search filter
        const searchTerm = document.getElementById('userSearch').value.toLowerCase();
        if (searchTerm) {
            filteredUsers = filteredUsers.filter(user =>
                user.name.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.phone.includes(searchTerm) ||
                (user.shopName && user.shopName.toLowerCase().includes(searchTerm))
            );
        }

        // Calculate pagination
        const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
        const startIndex = (currentPage - 1) * usersPerPage;
        const endIndex = startIndex + usersPerPage;
        const pageUsers = filteredUsers.slice(startIndex, endIndex);

        // Show/hide empty state
        const emptyState = document.getElementById('emptyState');
        if (filteredUsers.length === 0) {
            emptyState.style.display = 'flex';
            document.getElementById('pagination').innerHTML = '';
            document.getElementById('usersTableBody').innerHTML = '';
            return;
        } else {
            emptyState.style.display = 'none';
        }

        // Render table
        const tableBody = document.getElementById('usersTableBody');
        tableBody.innerHTML = '';

        pageUsers.forEach((user, index) => {
            const row = document.createElement('tr');
            row.style.animationDelay = `${index * 0.05}s`;
            row.style.opacity = '0';
            row.style.animation = 'slideUp 0.3s ease forwards';

            row.innerHTML = `
                <td>
                    <div class="flex items-center gap-3">
                        <div class="user-avatar" style="background: ${user.avatarColor}">
                            ${user.avatar}
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900">${user.name}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">${user.email}</p>
                            ${user.shopName ? `<p class="text-xs text-slate-500 mt-1"><iconify-icon icon="lucide:store" width="12" class="mr-1"></iconify-icon> ${user.shopName}</p>` : ''}
                            ${user.employeeId ? `<p class="text-xs text-slate-500 mt-1"><iconify-icon icon="lucide:id-card" width="12" class="mr-1"></iconify-icon> ${user.employeeId}</p>` : ''}
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge badge-${user.type}">
                        <iconify-icon icon="${user.type === 'shop-owner' ? 'lucide:store' : 'lucide:briefcase'}" 
                            width="12" class="mr-1"></iconify-icon>
                        ${user.type === 'shop-owner' ? 'Shop Owner' : 'Salesperson'}
                    </span>
                </td>
                <td>
                    <div class="space-y-1">
                        <div class="flex items-center gap-1 text-sm">
                            <iconify-icon icon="lucide:phone" width="14" class="text-slate-400"></iconify-icon>
                            <span>${user.phone}</span>
                        </div>
                        <div class="flex items-center gap-1 text-sm">
                            <iconify-icon icon="lucide:mail" width="14" class="text-slate-400"></iconify-icon>
                            <span>${user.email}</span>
                        </div>
                    </div>
                </td>
                <td>
                    ${user.area ?
                    `<div class="flex items-center gap-1 text-sm">
                        <iconify-icon icon="lucide:map-pin" width="14" class="text-slate-400"></iconify-icon>
                        <span>${user.area}</span>
                    </div>` :
                    '<span class="text-slate-400">-</span>'
                }
                </td>
                <td>
                    <span class="badge badge-${user.status}">
                        <iconify-icon icon="lucide:${user.status === 'active' ? 'check-circle' : 'x-circle'}" 
                            width="12" class="mr-1"></iconify-icon>
                        ${user.status.charAt(0).toUpperCase() + user.status.slice(1)}
                    </span>
                </td>
                <td>
                    <div class="flex items-center gap-1 text-sm">
                        <iconify-icon icon="lucide:calendar" width="14" class="text-slate-400"></iconify-icon>
                        <span>${user.joinDate}</span>
                    </div>
                </td>
                <td>
                    <div class="flex gap-2">
                        <button class="btn-secondary" onclick="editUser(${user.id})">
                            <iconify-icon icon="lucide:edit" width="14"></iconify-icon>
                            Edit
                        </button>
                        ${user.type === 'shop-owner' && user.shopId ?
                            `<a href="{{ route('admin.shops.analysis') }}?shop_id=${user.shopId}" class="btn-analyze">
                                <iconify-icon icon="lucide:bar-chart-3" width="14"></iconify-icon>
                                Analyze
                            </a>` : ''
                        }
                        ${user.type === 'salesperson' ?
                            `<button class="btn-secondary" onclick="assignAreaShop(${user.id}, '${user.name}')" style="background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); color: white; border: none;">
                                <iconify-icon icon="lucide:map-pin" width="14"></iconify-icon>
                                Assign
                            </button>` : ''
                        }
                        <button class="btn-danger" onclick="deleteUser(${user.id})">
                            <iconify-icon icon="lucide:trash" width="14"></iconify-icon>
                            Delete
                        </button>
                    </div>
                </td>
            `;
            tableBody.appendChild(row);
        });

        // Render pagination
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        if (totalPages <= 1) return;

        // Previous button
        const prevBtn = document.createElement('button');
        prevBtn.className = 'page-btn';
        prevBtn.innerHTML = '<iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.onclick = () => {
            if (currentPage > 1) {
                currentPage--;
                renderUsersTable();
            }
        };
        pagination.appendChild(prevBtn);

        // Page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);

        if (endPage - startPage + 1 < maxVisiblePages) {
            startPage = Math.max(1, endPage - maxVisiblePages + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const pageBtn = document.createElement('button');
            pageBtn.className = `page-btn ${i === currentPage ? 'active' : ''}`;
            pageBtn.textContent = i;
            pageBtn.onclick = () => {
                currentPage = i;
                renderUsersTable();
            };
            pagination.appendChild(pageBtn);
        }

        // Next button
        const nextBtn = document.createElement('button');
        nextBtn.className = 'page-btn';
        nextBtn.innerHTML = '<iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.onclick = () => {
            if (currentPage < totalPages) {
                currentPage++;
                renderUsersTable();
            }
        };
        pagination.appendChild(nextBtn);
    }

    function editUser(userId) {
        const user = allUsers.find(u => u.id === userId);
        if (user) {
            // Pass the userId as a parameter to the route helper
            window.location.href = `{{ route('admin.users.edit', ':id') }}`.replace(':id', userId);
        }
    }

    function deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            // In real app, this would call API
            allUsers = allUsers.filter(u => u.id !== userId);
            renderUsersTable();
            updateStats();
            showToast({
                text: 'User deleted successfully',
                type: 'success'
            });
        }
    }

    function assignAreaShop(userId, userName) {
        // Redirect to assign area/shop page
        window.location.href = "{{ route('admin.areas.assign.form') }}?user_id=" + userId + "&name=" + encodeURIComponent(userName);
    }
</script>
@endsection