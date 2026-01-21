@extends('layouts.shop-owner')

@php
$title = 'Wallet';
$subtitle = '';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.profile.index');
@endphp

@section('content')
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

    /* Main content container - MOBILE ONLY */
    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
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

    /* Card hover effects */
    .balance-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .action-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Hide on larger screens */
    @media (min-width: 768px) {
        body::before {
            content: "Mobile Only View - Please view on mobile device";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999999;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            padding: 20px;
        }
    }

    /* Transaction status badges */
    .status-credit {
        background-color: #DCFCE7;
        color: #166534;
        border: 1px solid #BBF7D0;
    }

    .status-debit {
        background-color: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .status-pending {
        background-color: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
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

    /* Empty state */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
        background: #F8FAFC;
        border-radius: 0.75rem;
        border: 2px dashed #E2E8F0;
    }

    /* Filter menu */
    .filter-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 0.25rem;
        background: white;
        border-radius: 0.5rem;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        min-width: 160px;
        z-index: 10;
    }
</style>

<main class="p-4 space-y-4">
    <!-- Balance Card -->
    <div
        class="balance-card bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 shadow-lg text-white transition-all animate-slide-up">
        <div class="flex items-center justify-between mb-4">
            <div class="p-2 bg-white/20 rounded-lg backdrop-blur-sm">
                <iconify-icon icon="lucide:wallet" width="20" class="text-white"></iconify-icon>
            </div>
            <span class="text-xs font-medium bg-white/20 px-3 py-1 rounded-full border border-white/30">
                Available Balance
            </span>
        </div>
        <p class="text-sm text-emerald-100 mb-1">Virtual Wallet</p>
        <h2 class="text-3xl font-bold tracking-tight mb-2" id="walletBalanceAmount">₹0</h2>
        <p class="text-xs text-emerald-100/80 flex items-center gap-1">
            <iconify-icon icon="lucide:clock" width="12"></iconify-icon>
            Last updated: <span id="walletLastUpdated">Just now</span>
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 gap-3 animate-slide-up" style="animation-delay: 0.1s;">
        <div class="stat-card bg-white rounded-xl border border-slate-200 p-4 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100">
                    <iconify-icon icon="lucide:trending-up" width="16" class="text-emerald-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Earned</p>
                    <p class="text-lg font-semibold text-slate-900" id="totalEarned">₹0</p>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-xl border border-slate-200 p-4 transition-all">
            <div class="flex items-center gap-3 mb-3">
                <div
                    class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100">
                    <iconify-icon icon="lucide:shopping-cart" width="16" class="text-indigo-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Total Used</p>
                    <p class="text-lg font-semibold text-slate-900" id="totalUsed">₹0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl border border-slate-200 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">Quick Actions</h3>
        </div>

        <div class="grid grid-cols-3 gap-3 p-4">
            <button onclick="showEarnInfo()"
                class="action-card p-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-all flex flex-col items-center justify-center">
                <div
                    class="h-10 w-10 rounded-lg bg-purple-50 flex items-center justify-center border border-purple-100 mb-2">
                    <iconify-icon icon="lucide:gift" width="16" class="text-purple-600"></iconify-icon>
                </div>
                <span class="text-xs font-medium text-slate-700 text-center">How to Earn</span>
            </button>

            <button onclick="filterTransactions('all')"
                class="action-card p-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-all flex flex-col items-center justify-center">
                <div
                    class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center border border-amber-100 mb-2">
                    <iconify-icon icon="lucide:history" width="16" class="text-amber-600"></iconify-icon>
                </div>
                <span class="text-xs font-medium text-slate-700 text-center">All History</span>
            </button>

            <a href="{{ route('shop-owner.products.index') }}"
                class="action-card p-3 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-all flex flex-col items-center justify-center no-underline">
                <div
                    class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100 mb-2">
                    <iconify-icon icon="lucide:shopping-bag" width="16" class="text-indigo-600"></iconify-icon>
                </div>
                <span class="text-xs font-medium text-slate-700 text-center">Shop Now</span>
            </a>
        </div>
    </div>

    <!-- How to Earn Info Section -->
    <div id="earnInfoSection" class="hidden bg-white rounded-xl border border-slate-200 animate-slide-up"
        style="animation-delay: 0.4s;">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="h-8 w-8 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100">
                    <iconify-icon icon="lucide:gift" width="14" class="text-emerald-600"></iconify-icon>
                </div>
                <h3 class="text-sm font-semibold text-slate-900">How to Earn Virtual Money</h3>
            </div>
            <button onclick="hideEarnInfo()" class="p-1 rounded-lg hover:bg-slate-100 transition-colors">
                <iconify-icon icon="lucide:x" width="16" class="text-slate-500"></iconify-icon>
            </button>
        </div>

        <div class="p-4 space-y-3">
            <div class="flex items-center gap-3 p-3 rounded-lg border border-emerald-100 bg-emerald-50">
                <div
                    class="h-10 w-10 rounded-full bg-white flex items-center justify-center border border-emerald-200">
                    <iconify-icon icon="lucide:user-plus" width="16" class="text-emerald-600"></iconify-icon>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-medium text-slate-900">Signup Bonus</h4>
                    <p class="text-xs text-slate-500">Get bonus when you create your account</p>
                </div>
                <span class="text-sm font-bold text-emerald-600">₹500</span>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50">
            <h4 class="text-sm font-semibold text-slate-900 mb-3 flex items-center gap-2">
                <iconify-icon icon="lucide:info" width="14" class="text-slate-600"></iconify-icon>
                Rules & Information
            </h4>
            <div class="space-y-2">
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14"
                        class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Use virtual money to get discounts on orders (max 50% of
                        order value)</p>
                </div>
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14"
                        class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Virtual money is non-transferable and non-withdrawable</p>
                </div>
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14"
                        class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Balance expires after 1 year of inactivity</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="bg-white rounded-xl border border-slate-200 animate-slide-up" style="animation-delay: 0.5s;">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-semibold text-slate-900">Recent Transactions</h3>
            <div class="relative">
                <button onclick="toggleFilterMenu()"
                    class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center gap-2 text-xs font-medium">
                    <iconify-icon icon="lucide:filter" width="12" class="text-slate-600"></iconify-icon>
                    <span id="currentFilter">All</span>
                    <iconify-icon icon="lucide:chevron-down" width="12" class="text-slate-600"></iconify-icon>
                </button>

                <div id="filterMenu" class="filter-menu hidden">
                    <button onclick="filterTransactions('all')"
                        class="w-full px-4 py-2 text-left text-xs hover:bg-slate-50 transition-colors flex items-center gap-2 rounded-t-lg">
                        <iconify-icon icon="lucide:list" width="12" class="text-slate-600"></iconify-icon>
                        All Transactions
                    </button>
                    <button onclick="filterTransactions('credit')"
                        class="w-full px-4 py-2 text-left text-xs hover:bg-slate-50 transition-colors flex items-center gap-2">
                        <iconify-icon icon="lucide:plus-circle" width="12" class="text-emerald-600"></iconify-icon>
                        Credits Only
                    </button>
                    <button onclick="filterTransactions('debit')"
                        class="w-full px-4 py-2 text-left text-xs hover:bg-slate-50 transition-colors flex items-center gap-2 rounded-b-lg">
                        <iconify-icon icon="lucide:minus-circle" width="12" class="text-red-600"></iconify-icon>
                        Debits Only
                    </button>
                </div>
            </div>
        </div>

        <div id="transactionList" class="divide-y divide-slate-100">
            <!-- Transactions will be loaded here -->
            <div class="flex items-center justify-center py-12">
                <div class="loading-spinner"></div>
            </div>
        </div>

        <div id="emptyTransactions" class="empty-state hidden">
            <iconify-icon icon="lucide:credit-card" width="48" class="text-slate-400 mb-4"></iconify-icon>
            <h4 class="text-lg font-semibold text-slate-700 mb-2">No Transactions Yet</h4>
            <p class="text-sm text-slate-500 mb-6">Your transaction history will appear here</p>
            <button onclick="showEarnInfo()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                <iconify-icon icon="lucide:gift" width="16"></iconify-icon>
                Learn How to Earn
            </button>
        </div>

        <!-- View All Transactions Button -->
        <div class="p-4 border-t border-slate-100 bg-slate-50">
            <button onclick="viewAllTransactions()"
                class="w-full px-4 py-3 rounded-lg border border-slate-300 bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
                <iconify-icon icon="lucide:eye" width="16"></iconify-icon>
                View All Transactions
            </button>
        </div>
    </div>
</main>
@endsection

@section('scripts')
<script>
    let currentTransactionFilter = 'all';
    let filterMenuOpen = false;
    let transactions = [];

    // Dummy data - In real app, this would come from Laravel controller
    const dummyData = {
        shopOwner: {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            walletBalance: 12500,
            totalOrders: 47,
            pendingOrders: 8,
            deliveredOrders: 39
        },
        walletTransactions: [
            {
                id: 1,
                type: 'credit',
                amount: 5000,
                description: 'Wallet Top-up via UPI',
                date: 'Dec 15, 2024',
                time: '10:30 AM',
                balance: 12500,
                reference: 'REF-2024-001'
            },
            {
                id: 2,
                type: 'debit',
                amount: -2400,
                description: 'Order #ORD00127 Payment',
                date: 'Dec 14, 2024',
                time: '02:45 PM',
                balance: 7500,
                reference: 'ORD-00127'
            },
            {
                id: 3,
                type: 'credit',
                amount: 1000,
                description: 'Referral Bonus',
                date: 'Dec 12, 2024',
                time: '09:15 AM',
                balance: 9900,
                reference: 'REF-BONUS-001'
            },
            {
                id: 4,
                type: 'debit',
                amount: -1850,
                description: 'Order #ORD00126 Payment',
                date: 'Dec 10, 2024',
                time: '04:20 PM',
                balance: 8050,
                reference: 'ORD-00126'
            },
            {
                id: 5,
                type: 'credit',
                amount: 500,
                description: 'Signup Bonus',
                date: 'Dec 8, 2024',
                time: '11:00 AM',
                balance: 8550,
                reference: 'SIGNUP-BONUS'
            },
            {
                id: 6,
                type: 'debit',
                amount: -3200,
                description: 'Order #ORD00125 Payment',
                date: 'Dec 5, 2024',
                time: '03:30 PM',
                balance: 5350,
                reference: 'ORD-00125'
            }
        ]
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadWalletPage();
    });

    function loadWalletPage() {
        // Load wallet balance from localStorage or dummy data
        const savedShopOwner = localStorage.getItem('shop_owner');
        let walletBalance = 0;
        
        if (savedShopOwner) {
            const shopOwner = JSON.parse(savedShopOwner);
            walletBalance = shopOwner.walletBalance || 0;
        } else {
            walletBalance = dummyData.shopOwner.walletBalance || 0;
        }
        
        animateWalletBalance(walletBalance);

        // Load transactions from localStorage or dummy data
        const savedTransactions = localStorage.getItem('wallet_transactions');
        if (savedTransactions) {
            transactions = JSON.parse(savedTransactions);
        } else {
            transactions = dummyData.walletTransactions || [];
            // Save to localStorage for persistence
            localStorage.setItem('wallet_transactions', JSON.stringify(transactions));
        }

        // Calculate stats
        calculateStats(transactions);

        // Set last updated time
        updateLastUpdatedTime();

        // Load transactions with current filter
        filterTransactions('all');
    }

    function animateWalletBalance(targetValue) {
        const element = document.getElementById('walletBalanceAmount');
        const currentValue = parseInt(element.textContent.replace('₹', '').replace(/,/g, '')) || 0;
        const duration = 800;
        const increment = (targetValue - currentValue) / (duration / 16);
        let current = currentValue;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
                current = targetValue;
                clearInterval(timer);
                element.textContent = `₹${current.toLocaleString()}`;
            } else {
                element.textContent = `₹${Math.round(current).toLocaleString()}`;
            }
        }, 16);
    }

    function calculateStats(allTransactions) {
        const totalEarned = allTransactions
            .filter(t => t.amount > 0)
            .reduce((sum, t) => sum + t.amount, 0);

        const totalUsed = Math.abs(allTransactions
            .filter(t => t.amount < 0)
            .reduce((sum, t) => sum + t.amount, 0));

        // Animate counters
        animateCounter('totalEarned', totalEarned, '₹');
        animateCounter('totalUsed', totalUsed, '₹');
    }

    function animateCounter(elementId, targetValue, prefix = '') {
        const element = document.getElementById(elementId);
        const currentValue = parseInt(element.textContent.replace(prefix, '').replace(/,/g, '')) || 0;
        const duration = 500;
        const increment = (targetValue - currentValue) / (duration / 16);
        let current = currentValue;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
                current = targetValue;
                clearInterval(timer);
                element.textContent = prefix + current.toLocaleString();
            } else {
                element.textContent = prefix + Math.round(current).toLocaleString();
            }
        }, 16);
    }

    function updateLastUpdatedTime() {
        const now = new Date();
        const formattedTime = now.toLocaleTimeString('en-IN', { 
            hour: '2-digit', 
            minute: '2-digit',
            hour12: true 
        });
        document.getElementById('walletLastUpdated').textContent = formattedTime;
    }

    function filterTransactions(filter) {
        currentTransactionFilter = filter;
        filterMenuOpen = false;
        document.getElementById('filterMenu').classList.add('hidden');

        // Update filter button text
        const filterTexts = {
            'all': 'All',
            'credit': 'Credits',
            'debit': 'Debits'
        };
        document.getElementById('currentFilter').textContent = filterTexts[filter] || 'All';

        // Filter transactions
        let filteredTransactions = [...transactions];

        if (filter === 'credit') {
            filteredTransactions = transactions.filter(t => t.amount > 0);
        } else if (filter === 'debit') {
            filteredTransactions = transactions.filter(t => t.amount < 0);
        }

        // Render transactions
        renderTransactions(filteredTransactions);
    }

    function renderTransactions(transactionList) {
        const container = document.getElementById('transactionList');
        const emptyState = document.getElementById('emptyTransactions');

        if (transactionList.length === 0) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        container.innerHTML = '';

        // Sort by date (newest first)
        transactionList.sort((a, b) => new Date(b.date) - new Date(a.date));

        // Show only last 10 transactions
        const recentTransactions = transactionList.slice(0, 10);

        recentTransactions.forEach((transaction, index) => {
            const item = document.createElement('div');
            item.className = 'p-4 transition-colors hover:bg-slate-50';

            const isCredit = transaction.amount > 0;
            const amountClass = isCredit ? 'text-emerald-600' : 'text-red-600';
            const amountSign = isCredit ? '+' : '-';
            const amountText = `₹${Math.abs(transaction.amount).toLocaleString()}`;
            const statusBadge = isCredit ?
                '<span class="px-2 py-0.5 rounded-full text-xs font-medium status-credit">Credit</span>' :
                '<span class="px-2 py-0.5 rounded-full text-xs font-medium status-debit">Debit</span>';

            // Get appropriate icon based on transaction type
            let icon = isCredit ? 'plus-circle' : 'minus-circle';
            let iconColor = isCredit ? 'text-emerald-600' : 'text-red-600';
            let bgColor = isCredit ? 'bg-emerald-50' : 'bg-red-50';
            let borderColor = isCredit ? 'border-emerald-100' : 'border-red-100';

            // Override for specific transaction types
            if (transaction.description.toLowerCase().includes('referral')) {
                icon = 'user-plus';
                iconColor = 'text-purple-600';
                bgColor = 'bg-purple-50';
                borderColor = 'border-purple-100';
            } else if (transaction.description.toLowerCase().includes('signup')) {
                icon = 'gift';
                iconColor = 'text-amber-600';
                bgColor = 'bg-amber-50';
                borderColor = 'border-amber-100';
            } else if (transaction.description.toLowerCase().includes('order')) {
                icon = isCredit ? 'package' : 'shopping-cart';
            }

            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-lg ${bgColor} flex items-center justify-center border ${borderColor}">
                            <iconify-icon icon="lucide:${icon}" width="16" class="${iconColor}"></iconify-icon>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="text-sm font-medium text-slate-900">${transaction.description}</p>
                                ${statusBadge}
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <p class="text-xs text-slate-500">
                                    <iconify-icon icon="lucide:calendar" width="10"></iconify-icon>
                                    ${transaction.date}
                                    ${transaction.time ? ` • ${transaction.time}` : ''}
                                </p>
                                <span class="text-xs text-slate-400">• ${transaction.reference}</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold ${amountClass}">${amountSign}${amountText}</p>
                        <p class="text-xs text-slate-500 mt-1">₹${transaction.balance.toLocaleString()}</p>
                    </div>
                </div>
            `;
            container.appendChild(item);
        });
    }

    function toggleFilterMenu() {
        filterMenuOpen = !filterMenuOpen;
        const menu = document.getElementById('filterMenu');

        if (filterMenuOpen) {
            menu.classList.remove('hidden');
            // Position the menu
            menu.style.width = document.querySelector('.relative button').offsetWidth + 'px';
        } else {
            menu.classList.add('hidden');
        }
    }

    function showEarnInfo() {
        document.getElementById('earnInfoSection').classList.remove('hidden');

        // Scroll to the section
        document.getElementById('earnInfoSection').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    function hideEarnInfo() {
        document.getElementById('earnInfoSection').classList.add('hidden');
    }

    function viewAllTransactions() {
        // In a real app, this would navigate to a full transactions page
        // For now, we'll show all transactions
        filterTransactions('all');
        
        // Scroll to transactions section
        document.querySelector('.bg-white.rounded-xl.border').scrollIntoView({
            behavior: 'smooth'
        });

        showToast({
            text: 'Showing all transactions',
            type: 'info'
        });
    }

    function refreshWallet() {
        // Show loading state
        const refreshBtn = event?.currentTarget;
        if (refreshBtn) {
            const originalHTML = refreshBtn.innerHTML;
            refreshBtn.innerHTML = '<iconify-icon icon="lucide:loader-2" width="18" class="text-slate-600 animate-spin"></iconify-icon>';
            
            setTimeout(() => {
                refreshBtn.innerHTML = originalHTML;
                loadWalletPage();
                showToast({
                    text: 'Wallet data refreshed',
                    type: 'success'
                });
            }, 800);
        } else {
            loadWalletPage();
        }
    }

    // Toast notification function
    function showToast({ text, type = 'info' }) {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-6 right-6 z-50 px-4 py-3 rounded-lg shadow-lg transition-all transform translate-x-full opacity-0 ${
            type === 'success' ? 'bg-emerald-500 text-white' : 
            type === 'error' ? 'bg-rose-500 text-white' : 
            type === 'info' ? 'bg-indigo-500 text-white' :
            'bg-slate-500 text-white'
        }`;
        toast.textContent = text;
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 10);
        
        // Animate out after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Close filter menu when clicking outside
    document.addEventListener('click', function (event) {
        const filterButton = event.target.closest('button[onclick="toggleFilterMenu()"]');
        const filterMenu = document.getElementById('filterMenu');
        
        if (!filterButton && filterMenuOpen && !event.target.closest('#filterMenu')) {
            filterMenu.classList.add('hidden');
            filterMenuOpen = false;
        }
    });

    // Close earn info when clicking outside
    document.addEventListener('click', function (event) {
        const earnSection = document.getElementById('earnInfoSection');
        const showEarnButtons = document.querySelectorAll('[onclick="showEarnInfo()"]');
        
        if (earnSection && !earnSection.classList.contains('hidden')) {
            const isClickInside = earnSection.contains(event.target);
            const isShowButton = Array.from(showEarnButtons).some(btn => btn.contains(event.target));
            
            if (!isClickInside && !isShowButton) {
                hideEarnInfo();
            }
        }
    });

    // Add refresh button to header if needed
    window.addEventListener('load', function() {
        const header = document.querySelector('header');
        if (header) {
            const refreshBtn = document.createElement('button');
            refreshBtn.className = 'p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors ml-2';
            refreshBtn.innerHTML = '<iconify-icon icon="lucide:refresh-cw" width="18" class="text-slate-600"></iconify-icon>';
            refreshBtn.title = 'Refresh Wallet (Ctrl+R)';
            refreshBtn.onclick = refreshWallet;
            
            const headerRight = header.querySelector('.flex.items-center.gap-2');
            if (headerRight) {
                headerRight.appendChild(refreshBtn);
            }
        }
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+R to refresh wallet
        if (e.ctrlKey && e.key === 'r') {
            e.preventDefault();
            refreshWallet();
        }
        // 1 for all, 2 for credit, 3 for debit filters
        if (e.key === '1' && !e.ctrlKey) {
            filterTransactions('all');
        }
        if (e.key === '2' && !e.ctrlKey) {
            filterTransactions('credit');
        }
        if (e.key === '3' && !e.ctrlKey) {
            filterTransactions('debit');
        }
        // Esc to close modals/filters
        if (e.key === 'Escape') {
            const filterMenu = document.getElementById('filterMenu');
            if (filterMenuOpen) {
                filterMenu.classList.add('hidden');
                filterMenuOpen = false;
            }
            hideEarnInfo();
        }
    });

    // Auto-refresh wallet balance every 30 seconds
    setInterval(() => {
        updateLastUpdatedTime();
    }, 30000);
</script>
@endsection