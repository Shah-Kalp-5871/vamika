@extends('layouts.admin')

@section('title', 'Offer details')
@section('subtitle', 'Offer details and statistics')

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

    /* Status badge */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Info card */
    .info-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        color: #64748B;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1E293B;
    }

    /* Usage stats */
    .usage-stats {
        display: flex;
        gap: 1rem;
        margin-top: 1rem;
    }

    .stat-item {
        flex: 1;
        text-align: center;
        padding: 1rem;
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 0.5rem;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 600;
        color: #4F46E5;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #64748B;
        margin-top: 0.25rem;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    .btn-danger {
        background: #EF4444;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: #DC2626;
    }
</style>
@endsection

@section('content')
@php
    $id = request()->get('id');
@endphp

<div class="main-content">
    <!-- Header-controlled title (JS needs this) -->
    <div class="px-6 py-4 border-b border-slate-100 bg-white -mt-px">
        <h1 id="offerName" class="text-xl font-semibold text-slate-900 tracking-tight">
            Loading...
        </h1>
    </div>

    <main class="p-6 space-y-6">
        <!-- Status and Type -->
        <div class="flex flex-wrap items-center gap-3">
            <span id="statusBadge" class="status-badge">Loading...</span>
            <span id="typeBadge" class="status-badge">Loading...</span>
            <span id="codeBadge" class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-sm font-mono">
                Code: <span id="offerCode"></span>
            </span>
        </div>

        <!-- Basic Info -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Offer Details</h3>
            <div class="space-y-4">
                <div>
                    <div class="info-label">Description</div>
                    <div class="info-value" id="offerDescription">Loading...</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <div class="info-label">Discount Value</div>
                        <div class="info-value" id="discountValue">Loading...</div>
                    </div>
                    <div>
                        <div class="info-label">Minimum Order</div>
                        <div class="info-value" id="minOrder">Loading...</div>
                    </div>
                    <div>
                        <div class="info-label">Validity Period</div>
                        <div class="info-value" id="validityPeriod">Loading...</div>
                    </div>
                    <div>
                        <div class="info-label">Maximum Usage</div>
                        <div class="info-value" id="maxUses">Loading...</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Usage Statistics -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Usage Statistics</h3>
            <div class="usage-stats">
                <div class="stat-item">
                    <div class="stat-value" id="usedCount">0</div>
                    <div class="stat-label">Times Used</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="remainingCount">0</div>
                    <div class="stat-label">Remaining Uses</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="successRate">0%</div>
                    <div class="stat-label">Success Rate</div>
                </div>
            </div>

            <!-- Progress bar -->
            <div class="mt-4">
                <div class="flex justify-between text-sm text-slate-600 mb-2">
                    <span>Usage Progress</span>
                    <span id="usagePercentage">0%</span>
                </div>
                <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                    <div id="progressBar" class="h-full bg-indigo-600 rounded-full" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Timeline</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Created</span>
                    <span class="text-sm font-medium" id="createdDate">Loading...</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Started</span>
                    <span class="text-sm font-medium" id="startDate">Loading...</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Expires</span>
                    <span class="text-sm font-medium" id="endDate">Loading...</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.offers.create') }}?action=edit&id={{ $id }}" class="btn-primary">
                <iconify-icon icon="lucide:edit" width="16"></iconify-icon>
                Edit Offer
            </a>
            <button onclick="deleteOffer()" class="btn-danger">
                <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                Delete Offer
            </button>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Get offer ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const offerId = parseInt(urlParams.get('id'));

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
            status: 'active',
            createdDate: '2023-12-20'
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
            status: 'active',
            createdDate: '2024-01-15'
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
            status: 'active',
            createdDate: '2024-01-10'
        }
    ];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        if (offerId) {
            loadOfferDetails(offerId);
        } else {
            document.getElementById('offerName').textContent = 'Offer Not Found';
        }
    });

    function loadOfferDetails(id) {
        const offer = sampleOffers.find(o => o.id === id);

        if (!offer) {
            document.getElementById('offerName').textContent = 'Offer Not Found';
            return;
        }

        // Update header
        document.getElementById('offerName').textContent = offer.name;
        document.getElementById('offerCode').textContent = offer.code;

        // Status badge
        const statusBadge = document.getElementById('statusBadge');
        const statusConfig = {
            active: { color: 'bg-emerald-50 text-emerald-700 border-emerald-200', text: 'Active' },
            upcoming: { color: 'bg-blue-50 text-blue-700 border-blue-200', text: 'Upcoming' },
            expired: { color: 'bg-slate-100 text-slate-600 border-slate-200', text: 'Expired' },
            draft: { color: 'bg-amber-50 text-amber-700 border-amber-200', text: 'Draft' }
        };

        const status = statusConfig[offer.status] || statusConfig.draft;
        statusBadge.className = `status-badge ${status.color}`;
        statusBadge.innerHTML = `<iconify-icon icon="lucide:${offer.status === 'active' ? 'check-circle' : 'clock'}" width="12"></iconify-icon> ${status.text}`;

        // Type badge
        const typeBadge = document.getElementById('typeBadge');
        const typeConfig = {
            percentage: { color: 'bg-orange-50 text-orange-700 border-orange-200', text: `${offer.value}% OFF` },
            fixed: { color: 'bg-green-50 text-green-700 border-green-200', text: `₹${offer.value} OFF` },
            bogo: { color: 'bg-purple-50 text-purple-700 border-purple-200', text: 'BUY 1 GET 1' }
        };

        const type = typeConfig[offer.type] || typeConfig.fixed;
        typeBadge.className = `status-badge ${type.color}`;
        typeBadge.innerHTML = `<iconify-icon icon="lucide:${offer.type === 'percentage' ? 'percent' : 'indian-rupee'}" width="12"></iconify-icon> ${type.text}`;

        // Basic info
        document.getElementById('offerDescription').textContent = offer.description;

        const discountValue = offer.type === 'percentage' ? `${offer.value}% discount` :
            offer.type === 'fixed' ? `₹${offer.value} off` :
                `Buy 1 Get 1 (Value: ₹${offer.value})`;
        document.getElementById('discountValue').textContent = discountValue;

        document.getElementById('minOrder').textContent = offer.minOrder > 0 ? `₹${offer.minOrder}` : 'No minimum';
        document.getElementById('maxUses').textContent = offer.maxUses ? `${offer.maxUses} times` : 'Unlimited';

        const formatDate = (dateStr) => {
            const date = new Date(dateStr);
            return date.toLocaleDateString('en-IN', { day: 'numeric', month: 'long', year: 'numeric' });
        };

        document.getElementById('validityPeriod').textContent = `${formatDate(offer.startDate)} to ${formatDate(offer.endDate)}`;

        // Usage stats
        document.getElementById('usedCount').textContent = offer.used;

        const remaining = offer.maxUses ? offer.maxUses - offer.used : '∞';
        document.getElementById('remainingCount').textContent = remaining;

        const successRate = offer.used > 0 ? '92%' : '0%';
        document.getElementById('successRate').textContent = successRate;

        const usagePercentage = offer.maxUses ? Math.min((offer.used / offer.maxUses) * 100, 100) : 0;
        document.getElementById('usagePercentage').textContent = `${usagePercentage.toFixed(1)}%`;
        document.getElementById('progressBar').style.width = `${usagePercentage}%`;

        // Timeline
        document.getElementById('createdDate').textContent = formatDate(offer.createdDate);
        document.getElementById('startDate').textContent = formatDate(offer.startDate);
        document.getElementById('endDate').textContent = formatDate(offer.endDate);
    }

    function deleteOffer() {
        if (confirm('Are you sure you want to delete this offer? This action cannot be undone.')) {
            // In real app, you would send delete request to API
            showToast({
                text: 'Offer deleted successfully',
                type: 'success'
            });
            window.location.href = '{{ route('admin.offers.index') }}';
        }
    }

    function showToast({ text, type }) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg border ${
            type === 'error' ? 'bg-red-50 border-red-200 text-red-800' :
            type === 'success' ? 'bg-green-50 border-green-200 text-green-800' :
            'bg-blue-50 border-blue-200 text-blue-800'
        }`;
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

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endsection