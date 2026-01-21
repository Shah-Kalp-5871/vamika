@extends('layouts.admin')

@section('title', 'Manage Offers')
@section('subtitle', 'Discounts & promotions')

@php
$pageConfig = [
    'showBottomNav' => true,
];
@endphp
@section('css')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

        /* Offer type badge */
        .offer-type-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
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

        /* Tabs */
        .tabs-container {
            display: flex;
            overflow-x: auto;
            border-bottom: 1px solid #E2E8F0;
            margin: 0 -1.5rem;
            padding: 0 1.5rem;
        }

        .tab {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            color: #64748B;
            cursor: pointer;
            white-space: nowrap;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tab:hover {
            color: #475569;
        }

        .tab.active {
            color: #4F46E5;
            border-bottom-color: #4F46E5;
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.05), transparent);
        }

        /* Progress bar */
        .progress-bar {
            height: 6px;
            background-color: #E2E8F0;
            border-radius: 3px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 3px;
            transition: width 0.3s ease;
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
            text-decoration: none;
            color: inherit;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }
    </style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-6 space-y-8">
        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Active Offers -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up">
                <div class="mb-3">
                    <iconify-icon icon="lucide:check-circle" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="activeOffers">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Active Offers</p>
            </div>

            <!-- Total Usage -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.1s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:users" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalUsage">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Usage</p>
            </div>

            <!-- Discount Given -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.2s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:indian-rupee" width="20" class="text-amber-500"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="discountGiven">₹0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Discount Given</p>
            </div>

            <!-- Upcoming Offers -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up"
                style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:clock" width="20" class="text-purple-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="upcomingOffers">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Upcoming Offers</p>
            </div>
        </div>

        <!-- Tabs and Filters -->
        <div class="space-y-4">
            <!-- Tabs -->
            <div class="tabs-container">
                <div class="tab active" onclick="filterOffers('all')">
                    <iconify-icon icon="lucide:layers" width="16"></iconify-icon>
                    All Offers
                </div>
                <div class="tab" onclick="filterOffers('active')">
                    <iconify-icon icon="lucide:check-circle" width="16"></iconify-icon>
                    Active Offers
                </div>
                <div class="tab" onclick="filterOffers('upcoming')">
                    <iconify-icon icon="lucide:clock" width="16"></iconify-icon>
                    Upcoming Offers
                </div>
                <div class="tab" onclick="filterOffers('expired')">
                    <iconify-icon icon="lucide:x-circle" width="16"></iconify-icon>
                    Expired Offers
                </div>
                <div class="tab" onclick="filterOffers('draft')">
                    <iconify-icon icon="lucide:file-text" width="16"></iconify-icon>
                    Drafts Offers
                </div>
            </div>

            <!-- Search and Create -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="relative flex-1">
                    <iconify-icon icon="lucide:search" width="16"
                        class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></iconify-icon>
                    <input type="text" placeholder="Search offers by name or code..."
                        class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        id="offerSearch" oninput="searchOffers(this.value)">
                </div>
                <a href="{{ route('admin.offers.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg transition-colors no-underline">
                    <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                    Create Offer
                </a>
            </div>
        </div>

        <!-- Offers List -->
        <div id="offersList" class="space-y-4">
            <!-- Offers will be loaded here -->
            <div class="flex items-center justify-center py-12">
                <div class="loading-spinner"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="empty-state hidden">
            <div class="flex flex-col items-center justify-center">
                <iconify-icon icon="lucide:gift" width="48" class="text-slate-400 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">No Offers Found</h4>
                <p class="text-sm text-slate-500 mb-6 max-w-md">
                    No offers match your current filters. Try adjusting your search or create a new offer.
                </p>
                <a href="{{ route('admin.offers.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                    <iconify-icon icon="lucide:plus" width="16"></iconify-icon>
                    Create New Offer
                </a>
            </div>
        </div>
    </main>
</div>

<!-- Add Offer Button -->
<a href="{{ route('admin.offers.create') }}" class="add-btn no-underline">
    <iconify-icon icon="lucide:plus"></iconify-icon>
</a>
@endsection

@section('scripts')
<script>
    let offers = [];
    let currentFilter = 'all';
    let originalOffers = [];

    // Sample offers data
    const sampleOffers = [
        {
            id: 1,
            name: 'Welcome Bonus',
            code: 'WELCOME500',
            type: 'fixed',
            value: 500,
            description: '₹500 welcome bonus for new shop owners',
            minOrder: 0,
            maxUses: 1000,
            used: 245,
            startDate: '2024-01-01',
            endDate: '2024-12-31',
            status: 'active'
        },
        {
            id: 2,
            name: 'Festival Discount',
            code: 'FEST20',
            type: 'percentage',
            value: 20,
            description: '20% off on orders above ₹1000',
            minOrder: 1000,
            maxUses: 500,
            used: 189,
            startDate: '2024-01-20',
            endDate: '2024-01-31',
            status: 'active'
        },
        {
            id: 3,
            name: 'Buy 1 Get 1',
            code: 'BOGOATTA',
            type: 'bogo',
            value: 100,
            description: 'Buy 1 Get 1 free on Aashirvaad Atta',
            minOrder: 0,
            maxUses: 200,
            used: 156,
            startDate: '2024-01-15',
            endDate: '2024-01-25',
            status: 'active'
        },
        {
            id: 4,
            name: 'Free Delivery',
            code: 'FREEDEL',
            type: 'fixed',
            value: 50,
            description: 'Free delivery on orders above ₹2000',
            minOrder: 2000,
            maxUses: 300,
            used: 0,
            startDate: '2024-02-01',
            endDate: '2024-02-15',
            status: 'upcoming'
        },
        {
            id: 5,
            name: 'New Year Sale',
            code: 'NEWYEAR25',
            type: 'percentage',
            value: 25,
            description: '25% off on New Year orders',
            minOrder: 1500,
            maxUses: 400,
            used: 400,
            startDate: '2023-12-25',
            endDate: '2023-12-31',
            status: 'expired'
        },
        {
            id: 6,
            name: 'Referral Bonus',
            code: 'REFER300',
            type: 'fixed',
            value: 300,
            description: '₹300 for successful referrals',
            minOrder: 0,
            maxUses: 1000,
            used: 89,
            startDate: '2024-01-01',
            endDate: '2024-12-31',
            status: 'active'
        },
        {
            id: 7,
            name: 'Summer Special',
            code: 'SUMMER15',
            type: 'percentage',
            value: 15,
            description: '15% off on all beverages',
            minOrder: 500,
            maxUses: 250,
            used: 72,
            startDate: '2024-06-01',
            endDate: '2024-06-30',
            status: 'draft'
        }
    ];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadOffersPage();
    });

    function loadOffersPage() {
        // Load offers (in real app, this would come from API/localStorage)
        offers = [...sampleOffers];
        originalOffers = [...offers];

        updateOfferStats();
        renderOffersList();
    }

    function updateOfferStats() {
        const activeOffers = offers.filter(o => o.status === 'active').length;
        const totalUsage = offers.reduce((sum, offer) => sum + offer.used, 0);
        const discountGiven = offers.reduce((sum, offer) => {
            if (offer.type === 'percentage') {
                return sum + (offer.used * (offer.value * 10)); // Assuming average order of 500
            }
            return sum + (offer.used * offer.value);
        }, 0);
        const upcomingOffers = offers.filter(o => o.status === 'upcoming').length;

        document.getElementById('activeOffers').textContent = activeOffers;
        document.getElementById('totalUsage').textContent = totalUsage.toLocaleString();
        document.getElementById('discountGiven').textContent = '₹' + discountGiven.toLocaleString();
        document.getElementById('upcomingOffers').textContent = upcomingOffers;
    }

    function filterOffers(filter) {
        currentFilter = filter;

        // Update tabs
        document.querySelectorAll('.tab').forEach(tab => {
            tab.classList.remove('active');
        });
        event.currentTarget.classList.add('active');

        if (filter === 'all') {
            offers = [...originalOffers];
        } else {
            offers = originalOffers.filter(offer => offer.status === filter);
        }

        renderOffersList();
    }

    function searchOffers(searchTerm) {
        const term = searchTerm.toLowerCase().trim();

        if (!term) {
            if (currentFilter === 'all') {
                offers = [...originalOffers];
            } else {
                offers = originalOffers.filter(offer => offer.status === currentFilter);
            }
            renderOffersList();
            return;
        }

        let filteredOffers;
        if (currentFilter === 'all') {
            filteredOffers = originalOffers;
        } else {
            filteredOffers = originalOffers.filter(offer => offer.status === currentFilter);
        }

        offers = filteredOffers.filter(offer =>
            offer.name.toLowerCase().includes(term) ||
            offer.code.toLowerCase().includes(term) ||
            offer.description.toLowerCase().includes(term)
        );

        renderOffersList();
    }

    function renderOffersList() {
        const offersList = document.getElementById('offersList');
        const emptyState = document.getElementById('emptyState');

        if (offers.length === 0) {
            offersList.classList.add('hidden');
            emptyState.classList.remove('hidden');
            return;
        }

        offersList.classList.remove('hidden');
        emptyState.classList.add('hidden');

        offersList.innerHTML = '';

        offers.forEach((offer, index) => {
            const offerCard = document.createElement('div');
            offerCard.className = 'bg-white rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow animate-slide-up';
            offerCard.style.animationDelay = `${index * 0.05}s`;
            offerCard.style.cursor = 'pointer';

            offerCard.addEventListener('click', () => {
                window.location.href = '{{ route("admin.offers.show", ":id") }}'.replace(':id', offer.id);
            });

            // Status badge
            const statusBadge = getStatusBadge(offer.status);

            // Type badge
            const typeBadge = getTypeBadge(offer.type, offer.value);

            // Usage percentage
            const usagePercentage = offer.maxUses ? Math.min((offer.used / offer.maxUses) * 100, 100) : 0;
            const progressColor = usagePercentage >= 90 ? 'bg-rose-500' :
                usagePercentage >= 70 ? 'bg-amber-500' : 'bg-emerald-500';

            offerCard.innerHTML = `
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <iconify-icon icon="lucide:tag" class="text-indigo-600" width="20"></iconify-icon>
                                <h3 class="text-lg font-semibold text-slate-900">${offer.name}</h3>
                                ${statusBadge}
                                ${typeBadge}
                            </div>
                            
                            <!-- Offer details -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-slate-600 mt-3">
                                <div class="flex items-center gap-1">
                                    <iconify-icon icon="lucide:hash" width="14" class="text-slate-400"></iconify-icon>
                                    <span class="font-mono font-medium">${offer.code}</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <iconify-icon icon="lucide:calendar" width="14" class="text-slate-400"></iconify-icon>
                                    <span>${formatDate(offer.startDate)} - ${formatDate(offer.endDate)}</span>
                                </div>
                                ${offer.minOrder > 0 ? `
                                    <div class="flex items-center gap-1">
                                        <iconify-icon icon="lucide:indian-rupee" width="14" class="text-slate-400"></iconify-icon>
                                        <span>Min. ₹${offer.minOrder}</span>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <!-- Action buttons -->
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.offers.create') }}?action=edit&id=${offer.id}"
                               onclick="event.stopPropagation()"
                               class="px-3 py-2 text-sm font-medium text-indigo-600 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg border border-slate-200 transition-colors flex items-center gap-2 no-underline">
                                <iconify-icon icon="lucide:edit" width="14"></iconify-icon>
                                Edit
                            </a>
                            <button onclick="event.stopPropagation(); deleteOffer(${offer.id})"
                                    class="px-3 py-2 text-sm font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-slate-200 transition-colors flex items-center gap-2">
                                <iconify-icon icon="lucide:trash-2" width="14"></iconify-icon>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <p class="text-sm text-slate-600">
                            <iconify-icon icon="lucide:info" width="14" class="text-slate-400 mr-2"></iconify-icon>
                            ${offer.description}
                        </p>
                    </div>
                    
                    <!-- Usage stats -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600">Usage</span>
                            <span class="font-medium text-slate-900">${offer.used}${offer.maxUses ? `/${offer.maxUses}` : ''}${offer.maxUses ? ' times' : ''}</span>
                        </div>
                        ${offer.maxUses ? `
                            <div class="progress-bar">
                                <div class="progress-fill ${progressColor}" style="width: ${usagePercentage}%"></div>
                            </div>
                        ` : ''}
                    </div>
                </div>
            `;
            offersList.appendChild(offerCard);
        });
    }

    function getStatusBadge(status) {
        const badges = {
            active: {
                icon: 'check-circle',
                color: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                text: 'Active'
            },
            upcoming: {
                icon: 'clock',
                color: 'bg-blue-50 text-blue-700 border-blue-200',
                text: 'Upcoming'
            },
            expired: {
                icon: 'x-circle',
                color: 'bg-slate-100 text-slate-600 border-slate-200',
                text: 'Expired'
            },
            draft: {
                icon: 'file-text',
                color: 'bg-amber-50 text-amber-700 border-amber-200',
                text: 'Draft'
            }
        };

        const badge = badges[status] || badges.draft;
        return `
            <span class="status-badge ${badge.color}">
                <iconify-icon icon="lucide:${badge.icon}" width="12"></iconify-icon>
                ${badge.text}
            </span>
        `;
    }

    function getTypeBadge(type, value) {
        const badges = {
            percentage: {
                icon: 'percent',
                color: 'bg-orange-50 text-orange-700 border-orange-200',
                text: `${value}% OFF`
            },
            fixed: {
                icon: 'indian-rupee',
                color: 'bg-green-50 text-green-700 border-green-200',
                text: `₹${value} OFF`
            },
            bogo: {
                icon: 'gift',
                color: 'bg-purple-50 text-purple-700 border-purple-200',
                text: 'BUY 1 GET 1'
            }
        };

        const badge = badges[type] || badges.fixed;
        return `
            <span class="offer-type-badge ${badge.color}">
                <iconify-icon icon="lucide:${badge.icon}" width="12"></iconify-icon>
                ${badge.text}
            </span>
        `;
    }

    function formatDate(dateStr) {
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-IN', { day: 'numeric', month: 'short' });
    }

    function deleteOffer(offerId) {
        if (confirm('Are you sure you want to delete this offer?')) {
            offers = offers.filter(o => o.id !== offerId);
            originalOffers = originalOffers.filter(o => o.id !== offerId);
            updateOfferStats();
            renderOffersList();
            showNotification('Offer deleted successfully!', 'info');
        }
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