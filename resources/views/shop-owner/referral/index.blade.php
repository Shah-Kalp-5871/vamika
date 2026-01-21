@extends('layouts.shop-owner')

@section('title', 'Refer & Earn')
@section('role', 'Shop Owner')
@section('showBack', true)
@php
    $backUrl = route('shop-owner.profile.index');
@endphp

@push('css')
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
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .step-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Status badges */
    .status-active {
        background-color: #DCFCE7;
        color: #166534;
    }

    .status-pending {
        background-color: #FEF3C7;
        color: #92400E;
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

    /* WhatsApp button gradient */
    .whatsapp-gradient {
        background: linear-gradient(135deg, #25D366, #128C7E);
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

    /* Step number circles */
    .step-number {
        width: 32px;
        height: 32px;
        background: #4F46E5;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.875rem;
        flex-shrink: 0;
    }
</style>
@endpush

@section('content')
<!-- Main Content -->
<div class="main-content">
    <main class="p-4 space-y-4">
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-6 text-white shadow-lg animate-slide-up">
            <div class="text-center">
                <div class="h-16 w-16 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center mx-auto mb-4">
                    <iconify-icon icon="lucide:gift" width="28" class="text-white"></iconify-icon>
                </div>
                <h2 class="text-xl font-bold mb-2">Earn up to ₹500</h2>
                <p class="text-emerald-100">For every friend who joins using your code</p>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-3 animate-slide-up" style="animation-delay: 0.1s;">
            <div class="stat-card bg-white rounded-xl border border-slate-200 p-4 transition-all text-center">
                <div class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100 mx-auto mb-2">
                    <iconify-icon icon="lucide:wallet" width="16" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-lg font-semibold text-slate-900" id="totalEarned">₹0</p>
                <p class="text-xs text-slate-500">Total Earned</p>
            </div>

            <div class="stat-card bg-white rounded-xl border border-slate-200 p-4 transition-all text-center">
                <div class="h-10 w-10 rounded-lg bg-purple-50 flex items-center justify-center border border-purple-100 mx-auto mb-2">
                    <iconify-icon icon="lucide:users" width="16" class="text-purple-600"></iconify-icon>
                </div>
                <p class="text-lg font-semibold text-slate-900" id="totalReferrals">0</p>
                <p class="text-xs text-slate-500">Successful Invites</p>
            </div>

            <div class="stat-card bg-white rounded-xl border border-slate-200 p-4 transition-all text-center">
                <div class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center border border-amber-100 mx-auto mb-2">
                    <iconify-icon icon="lucide:banknote" width="16" class="text-amber-600"></iconify-icon>
                </div>
                <p class="text-lg font-semibold text-slate-900" id="availableEarnings">₹0</p>
                <p class="text-xs text-slate-500">Available Now</p>
            </div>
        </div>

        <!-- Referral Code Section -->
        <div class="bg-white rounded-xl border border-slate-200 animate-slide-up" style="animation-delay: 0.2s;">
            <div class="p-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100">
                        <iconify-icon icon="lucide:gift" width="14" class="text-emerald-600"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">Your Referral Code</h3>
                </div>
                <p class="text-xs text-slate-500 mt-1">Share this unique code with other shop owners</p>
            </div>

            <div class="p-4">
                <div class="flex gap-3 mb-3">
                    <div class="flex-1 px-4 py-3 rounded-lg bg-slate-50 border border-slate-200 font-mono font-bold text-slate-900 text-center" id="referralCodeDisplay">
                        VAMIKA-LOADING
                    </div>
                    <button onclick="copyReferralCode()" class="px-4 py-3 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors flex items-center gap-2 whitespace-nowrap">
                        <iconify-icon icon="lucide:copy" width="14"></iconify-icon>
                        Copy
                    </button>
                </div>
            </div>
        </div>

        <!-- WhatsApp Share Section -->
        <div class="whatsapp-gradient rounded-xl p-4 text-white shadow-lg animate-slide-up" style="animation-delay: 0.3s;">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-12 w-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                    <iconify-icon icon="logos:whatsapp-icon" width="24" class="text-white"></iconify-icon>
                </div>
                <div>
                    <h3 class="text-sm font-semibold">Share via WhatsApp</h3>
                    <p class="text-xs text-white/90">The easiest way to invite other shop owners</p>
                </div>
            </div>

            <button onclick="shareViaWhatsApp()" class="w-full mt-2 px-4 py-3 rounded-lg bg-white text-emerald-600 font-semibold hover:bg-gray-100 transition-colors flex items-center justify-center gap-2">
                <iconify-icon icon="logos:whatsapp-icon" width="18" class="text-emerald-600"></iconify-icon>
                Share on WhatsApp
            </button>
        </div>

        <!-- How It Works Section -->
        <div class="bg-white rounded-xl border border-slate-200 animate-slide-up" style="animation-delay: 0.4s;">
            <div class="p-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100">
                        <iconify-icon icon="lucide:info" width="14" class="text-indigo-600"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">How It Works</h3>
                </div>
            </div>

            <div class="p-4 space-y-4">
                <div class="step-card flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 transition-all">
                    <div class="step-number">1</div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-900 mb-1">Share Your Code</h4>
                        <p class="text-xs text-slate-600">Share your unique referral code with other shop owners via WhatsApp</p>
                    </div>
                </div>

                <div class="step-card flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 transition-all">
                    <div class="step-number">2</div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-900 mb-1">They Sign Up</h4>
                        <p class="text-xs text-slate-600">They use your code when registering their shop on our platform</p>
                    </div>
                </div>

                <div class="step-card flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 transition-all">
                    <div class="step-number">3</div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-900 mb-1">You Earn Money</h4>
                        <p class="text-xs text-slate-600">Get ₹300 for 1st referral, ₹200 for 2nd referral (Max 2 referrals)</p>
                    </div>
                </div>

                <div class="step-card flex items-start gap-3 p-3 rounded-lg border border-slate-200 bg-slate-50 transition-all">
                    <div class="step-number">4</div>
                    <div>
                        <h4 class="text-sm font-medium text-slate-900 mb-1">They Get Bonus</h4>
                        <p class="text-xs text-slate-600">They receive ₹500 signup bonus in their wallet upon verification</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Referrals List Section -->
        <div class="bg-white rounded-xl border border-slate-200 animate-slide-up" style="animation-delay: 0.5s;">
            <div class="p-4 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 rounded-lg bg-purple-50 flex items-center justify-center border border-purple-100">
                        <iconify-icon icon="lucide:users" width="14" class="text-purple-600"></iconify-icon>
                    </div>
                    <h3 class="text-sm font-semibold text-slate-900">Your Referrals</h3>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-600 text-white" id="referralsCount">
                    0
                </span>
            </div>

            <div id="referralsListContainer" class="divide-y divide-slate-100">
                <!-- Referrals will be loaded here -->
                <div class="flex items-center justify-center py-12">
                    <div class="loading-spinner"></div>
                </div>
            </div>

            <div id="emptyReferrals" class="empty-state hidden">
                <iconify-icon icon="lucide:user-plus" width="48" class="text-slate-400 mb-4"></iconify-icon>
                <h4 class="text-lg font-semibold text-slate-700 mb-2">No Referrals Yet</h4>
                <p class="text-sm text-slate-500 mb-6">Share your code with other shop owners to start earning</p>
                <button onclick="shareViaWhatsApp()" class="inline-flex items-center gap-2 px-4 py-2 whatsapp-gradient text-white rounded-lg hover:opacity-90 transition-all no-underline">
                    <iconify-icon icon="logos:whatsapp-icon" width="16"></iconify-icon>
                    Invite via WhatsApp
                </button>
            </div>
        </div>

        <!-- Terms Section -->
        <div class="bg-slate-50 rounded-xl border border-slate-200 p-4 animate-slide-up" style="animation-delay: 0.6s;">
            <div class="space-y-3">
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14" class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Maximum 2 referrals per account allowed</p>
                </div>
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14" class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Bonus credited after referred shop is verified</p>
                </div>
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14" class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Virtual money expires after 1 year of inactivity</p>
                </div>
                <div class="flex items-start gap-2">
                    <iconify-icon icon="lucide:check-circle" width="14" class="text-emerald-500 mt-0.5"></iconify-icon>
                    <p class="text-xs text-slate-600">Total maximum earnings: ₹1000 (₹500 signup + ₹500 referrals)</p>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/wjs/dummy-data.js') }}"></script>
<script>
    let referrals = [];

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadReferralPage();
    });

    function loadReferralPage() {
        // Load data
        referrals = dummyData.referrals || [];
        const wallet = dummyData.shopOwner.walletBalance || 0;
        const transactions = dummyData.walletTransactions || [];

        // Calculate total earned from referrals
        const referralEarnings = referrals.reduce((sum, ref) => sum + ref.bonus, 0);
        const totalEarned = transactions
            .filter(t => t.amount > 0)
            .reduce((sum, t) => sum + t.amount, 0);

        // Animate counters
        animateCounter('totalEarned', totalEarned, '₹');
        animateCounter('totalReferrals', referrals.length);
        animateCounter('availableEarnings', wallet, '₹');

        // Update referrals count
        document.getElementById('referralsCount').textContent = referrals.length;

        // Generate and display referral code
        const code = generateReferralCode(dummyData.shopOwner.name);
        document.getElementById('referralCodeDisplay').textContent = code;

        // Load referrals list
        loadReferralsList();
    }

    function animateCounter(elementId, targetValue, prefix = '') {
        const element = document.getElementById(elementId);
        const currentText = element.textContent;
        const currentValue = parseInt(currentText.replace(prefix, '').replace(/,/g, '')) || 0;
        const duration = 800;
        const increment = (targetValue - currentValue) / (duration / 16);
        let current = currentValue;

        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
                current = targetValue;
                clearInterval(timer);
                element.textContent = prefix + current.toLocaleString();
            } else {
                if (prefix) {
                    element.textContent = prefix + Math.round(current).toLocaleString();
                } else {
                    element.textContent = Math.round(current).toLocaleString();
                }
            }
        }, 16);
    }

    function generateReferralCode(name) {
        const prefix = "VAMIKA-";
        const random = Math.random().toString(36).substr(2, 6).toUpperCase();
        return prefix + random;
    }

    function loadReferralsList() {
        const container = document.getElementById('referralsListContainer');
        const emptyState = document.getElementById('emptyReferrals');

        if (referrals.length === 0) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        container.innerHTML = '';

        // Sort by date (newest first)
        referrals.sort((a, b) => new Date(b.date) - new Date(a.date));

        referrals.forEach((ref, index) => {
            const item = document.createElement('div');
            item.className = 'p-4';

            const statusClass = ref.status === 'Active' ? 'status-active' : 'status-pending';

            item.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full ${ref.status === 'Active' ? 'bg-emerald-50' : 'bg-amber-50'} flex items-center justify-center border ${ref.status === 'Active' ? 'border-emerald-100' : 'border-amber-100'}">
                            <iconify-icon icon="lucide:user" width="16" class="${ref.status === 'Active' ? 'text-emerald-600' : 'text-amber-600'}"></iconify-icon>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-slate-900">${ref.name}</h4>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="text-xs text-slate-500 flex items-center gap-1">
                                    <iconify-icon icon="lucide:calendar" width="10"></iconify-icon>
                                    ${ref.date}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                    ${ref.status}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-emerald-600">+₹${ref.bonus}</p>
                        <p class="text-xs text-slate-500 mt-1">Bonus</p>
                    </div>
                </div>
            `;
            container.appendChild(item);
        });
    }

    function copyReferralCode() {
        const code = document.getElementById('referralCodeDisplay').textContent;
        navigator.clipboard.writeText(code)
            .then(() => {
                // Show success feedback
                const copyBtn = event.target;
                const originalHTML = copyBtn.innerHTML;

                copyBtn.innerHTML = '<iconify-icon icon="lucide:check" width="14"></iconify-icon> Copied!';
                copyBtn.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                copyBtn.classList.add('bg-emerald-600');

                setTimeout(() => {
                    copyBtn.innerHTML = originalHTML;
                    copyBtn.classList.remove('bg-emerald-600');
                    copyBtn.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                }, 2000);
            })
            .catch(err => {
                console.error('Failed to copy code:', err);

                showToast({
                    text: 'Failed to copy code. Please try again',
                    type: 'error'
                });
            });
    }

    function shareViaWhatsApp() {
        const code = document.getElementById('referralCodeDisplay').textContent;
        const shopName = dummyData.shopOwner.shopName || "My Shop";
        const ownerName = dummyData.shopOwner.name || "Shop Owner";

        const message = `🚀 *Join Vamika Enterprise Wholesale Platform!* 🚀

💰 *Get ₹500 Signup Bonus!* 💰

Hi shop owner,

Join our wholesale platform and get exclusive benefits:
• *₹500 Signup Bonus* in your wallet
• Access to *wholesale prices*
• *Easy ordering* system
• *Direct delivery* to your shop
• *Virtual wallet* for discounts

Use my referral code: *${code}*

Sign up here: https://vamikaenterprise.com/register

Let's grow our businesses together! 🛍️

Best regards,
${ownerName}
${shopName}`;

        const encodedMessage = encodeURIComponent(message);
        const whatsappURL = `https://wa.me/?text=${encodedMessage}`;

        window.open(whatsappURL, '_blank');
    }

    // Add animation delays for cards
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.animate-slide-up');
        cards.forEach((card, index) => {
            card.style.animationDelay = `${index * 0.1}s`;
        });
    });

    // Keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+C to copy referral code
        if (e.ctrlKey && e.key === 'c') {
            e.preventDefault();
            copyReferralCode();
        }
        // Ctrl+W to share via WhatsApp
        if (e.ctrlKey && e.key === 'w') {
            e.preventDefault();
            shareViaWhatsApp();
        }
    });
</script>
@endpush