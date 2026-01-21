@extends('layouts.shop-owner')

@php
$title = 'Dashboard';
$subtitle = 'Welcome back';
$role = 'Shop Owner';
$showBack = false;
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
        overflow: hidden;
        /* Prevent scrolling during intro */
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

    /* Intro overlay styles - MATCHING THEME */
    .intro-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: white;
        z-index: 9999999;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .intro-content {
        text-align: center;
        color: white;
        animation: fadeInUp 0.8s ease-out;
    }

    .logo-container {
        margin-bottom: 2rem;
        position: relative;
    }

    .logo-circle {
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .loading-bar {
        width: 200px;
        height: 3px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
        overflow: hidden;
        margin: 2rem auto 0;
    }

    .loading-progress {
        width: 0%;
        height: 100%;
        background: white;
        border-radius: 10px;
        animation: loading 2.5s ease-out forwards;
    }

    .loading-text {
        margin-top: 1rem;
        font-size: 0.875rem;
        opacity: 0.8;
        animation: pulse 1.5s infinite;
    }

    @keyframes loading {
        0% {
            width: 0%;
        }

        100% {
            width: 100%;
        }
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {

        0%,
        100% {
            opacity: 0.6;
        }

        50% {
            opacity: 1;
        }
    }

    /* Main content container - MOBILE ONLY */
    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
    }

    /* Custom animation for cards */
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

    /* Offer Popup Styles */
    .offer-popup-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 999999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .offer-popup-overlay.active {
        opacity: 1;
        visibility: visible;
    }

    .offer-popup {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        width: 100%;
        max-width: 350px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transform: translateY(20px);
        transition: transform 0.4s ease;
    }

    .offer-popup-overlay.active .offer-popup {
        transform: translateY(0);
    }

    .offer-popup-header {
        padding: 20px;
        text-align: center;
        background: rgba(255, 255, 255, 0.1);
        position: relative;
    }

    .offer-popup-close {
        position: absolute;
        top: 15px;
        right: 15px;
        background: rgba(255, 255, 255, 0.2);
        border: none;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        color: white;
        font-size: 18px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.3s ease;
    }

    .offer-popup-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .offer-popup-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 1px;
        margin-bottom: 15px;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .offer-popup-title {
        color: white;
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .offer-popup-subtitle {
        color: rgba(255, 255, 255, 0.9);
        font-size: 14px;
        margin-bottom: 15px;
    }

    .offer-popup-code {
        background: white;
        color: #667eea;
        padding: 12px 20px;
        border-radius: 12px;
        font-size: 18px;
        font-weight: 700;
        letter-spacing: 2px;
        display: inline-block;
        margin: 10px 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .offer-popup-body {
        padding: 25px;
        background: white;
        color: #333;
    }

    .offer-feature {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        font-size: 14px;
    }

    .offer-feature-icon {
        background: #667eea;
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .offer-validity {
        background: rgba(102, 126, 234, 0.1);
        padding: 10px 15px;
        border-radius: 10px;
        text-align: center;
        margin-top: 20px;
        font-size: 12px;
        color: #667eea;
        font-weight: 600;
    }

    .offer-popup-footer {
        padding: 15px 25px 25px;
        background: white;
        text-align: center;
        border-top: 1px solid #eee;
    }

    .btn-apply-offer {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 14px 30px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        width: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-apply-offer:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }

    .offer-popup-terms {
        font-size: 11px;
        color: #999;
        margin-top: 10px;
        line-height: 1.4;
    }

    .offer-countdown {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        padding: 10px;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
    }

    .countdown-item {
        text-align: center;
        min-width: 40px;
    }

    .countdown-number {
        background: #667eea;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        margin: 0 auto 5px;
    }

    .countdown-label {
        font-size: 10px;
        color: #666;
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
</style>

<!-- Intro Overlay - MATCHING THEME -->
<div id="introOverlay" class="intro-overlay">
    <div class="intro-content">
        <div class="logo-container">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo" style="width: 90vh; height: auto;">
        </div>
    </div>
</div>

<!-- Offer Popup -->
<div id="offerPopup" class="offer-popup-overlay">
    <div class="offer-popup">
        <div class="offer-popup-header">
            <button class="offer-popup-close" onclick="closeOfferPopup()">
                <iconify-icon icon="lucide:x" width="16"></iconify-icon>
            </button>
            <div class="offer-popup-badge">SPECIAL OFFER</div>
            <h2 class="offer-popup-title" id="offerTitle">Festival Special</h2>
            <p class="offer-popup-subtitle" id="offerDescription">Exclusive discount for shop owners</p>
            <div class="offer-popup-code" id="offerCode">FESTIVAL20</div>
        </div>
        
        <div class="offer-popup-body">
            <div class="offer-feature">
                <div class="offer-feature-icon">
                    <iconify-icon icon="lucide:percent" width="12"></iconify-icon>
                </div>
                <span>Get <strong id="offerDiscount">20%</strong> discount on your next order</span>
            </div>
            
            <div class="offer-feature">
                <div class="offer-feature-icon">
                    <iconify-icon icon="lucide:shopping-bag" width="12"></iconify-icon>
                </div>
                <span>Minimum order: <strong>₹<span id="offerMinOrder">1000</span></strong></span>
            </div>
            
            <div class="offer-feature">
                <div class="offer-feature-icon">
                    <iconify-icon icon="lucide:calendar" width="12"></iconify-icon>
                </div>
                <span>Valid till: <strong id="offerValidTill">31 Dec 2024</strong></span>
            </div>
            
            <div class="offer-countdown" id="offerCountdown">
                <div class="countdown-item">
                    <div class="countdown-number" id="days">00</div>
                    <div class="countdown-label">Days</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="hours">00</div>
                    <div class="countdown-label">Hours</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="minutes">00</div>
                    <div class="countdown-label">Mins</div>
                </div>
                <div class="countdown-item">
                    <div class="countdown-number" id="seconds">00</div>
                    <div class="countdown-label">Secs</div>
                </div>
            </div>
            
            <div class="offer-validity">
                <iconify-icon icon="lucide:clock" width="12"></iconify-icon>
                Limited time offer
            </div>
        </div>
        
        <div class="offer-popup-footer">
            <button class="btn-apply-offer" onclick="applyOffer()">
                Apply Offer Now
            </button>
            <p class="offer-popup-terms">
                * Offer valid for first 100 redemptions only. Cannot be combined with other offers.
            </p>
        </div>
    </div>
</div>

<!-- Main Content -->
<div id="mainContent" class="main-content" style="display: none;">
    <main class="p-6 space-y-8">
        <h2 id="shopOwnerName" class="text-lg font-semibold text-slate-900"></h2>
        <span id="shopName" class="text-sm text-slate-600"></span>

        <div class="relative overflow-hidden rounded-xl bg-gradient-to-br from-emerald-600 to-emerald-800 p-6 text-white shadow-lg">
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="p-2 bg-white/10 rounded-lg border border-white/20">
                        <iconify-icon icon="lucide:wallet" width="20" class="text-white"></iconify-icon>
                    </div>
                    <span class="text-xs font-medium text-emerald-200 bg-white/10 px-2 py-1 rounded border border-white/20">Available Balance</span>
                </div>
                <p class="text-sm text-emerald-100 font-medium">Virtual Wallet</p>
                <h3 id="walletBalance" class="text-3xl font-semibold tracking-tight mt-1 text-white">₹0</h3>
            </div>
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-400 rounded-full blur-3xl opacity-20"></div>
        </div>

        <!-- Secondary Stats Grid -->
        <div class="grid grid-cols-3 gap-4">
            <!-- Total Orders -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up" style="animation-delay: 0.1s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:package" width="20" class="text-indigo-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="totalOrders">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Total Orders</p>
            </div>

            <!-- Pending Orders -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up" style="animation-delay: 0.2s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:clock" width="20" class="text-amber-500"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="pendingOrders">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Pending</p>
            </div>

            <!-- Delivered Orders -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm hover:border-slate-300 transition-colors animate-slide-up" style="animation-delay: 0.3s;">
                <div class="mb-3">
                    <iconify-icon icon="lucide:check-circle" width="20" class="text-emerald-600"></iconify-icon>
                </div>
                <p class="text-2xl font-semibold text-slate-900 tracking-tight" id="deliveredOrders">0</p>
                <p class="text-xs text-slate-500 font-medium mt-1">Delivered</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-2 gap-3">
            <button onclick="window.location.href='{{ route('shop-owner.products.index') }}'" class="flex items-center justify-center gap-2 p-3 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:shopping-cart" width="16" class="text-slate-400 group-hover:text-indigo-600 transition-colors"></iconify-icon>
                Shop Now
            </button>
            <button onclick="window.location.href='{{ route('shop-owner.invoices.index') }}'" class="flex items-center justify-center gap-2 p-3 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:receipt" width="16" class="text-slate-400 group-hover:text-indigo-600 transition-colors"></iconify-icon>
                Invoices
            </button>
            <button onclick="window.location.href='{{ route('shop-owner.orders.index') }}'" class="flex items-center justify-center gap-2 p-3 rounded-lg border border-slate-200 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all active:scale-95 shadow-sm group">
                <iconify-icon icon="lucide:clipboard-list" width="16" class="text-slate-400 group-hover:text-indigo-600 transition-colors"></iconify-icon>
                My Orders
            </button>
        </div>

        <!-- Recent Activity -->
        <section>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-900">Recent Activity</h3>
                <a href="#" class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center">
                    View all <iconify-icon icon="lucide:chevron-right" width="12" class="ml-1"></iconify-icon>
                </a>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div id="activityList" class="divide-y divide-slate-100">
                    <!-- Activity items will be dynamically added here -->
                    <div class="p-8 text-center">
                        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full" role="status" aria-label="loading"></div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Dummy data for shop owner - In real app, this would come from Laravel controller
    const shopOwnerData = {
        shopOwner: {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            walletBalance: 12500,
            totalOrders: 47,
            pendingOrders: 8,
            deliveredOrders: 39
        },
        recentActivities: [
            { type: 'order', title: 'New Order Placed', description: 'Order #ORD00127 for ₹2,400', time: '10 minutes ago', icon: 'shopping-bag' },
            { type: 'payment', title: 'Payment Received', description: '₹5,000 credited to wallet', time: '2 hours ago', icon: 'dollar-sign' },
            { type: 'delivery', title: 'Order Delivered', description: 'Order #ORD00126 delivered', time: '5 hours ago', icon: 'package-check' },
            { type: 'stock', title: 'Stock Alert', description: 'Low stock for Aashirvaad Atta', time: '1 day ago', icon: 'alert-triangle' },
            { type: 'offer', title: 'New Offer Available', description: '20% discount on beverages', time: '2 days ago', icon: 'tag' }
        ]
    };

    // Sample offers data - In real app, this would come from API
    const availableOffers = [
        {
            id: 1,
            title: "Festival Special",
            description: "Exclusive discount for shop owners",
            code: "FESTIVAL20",
            discount: "20%",
            minOrder: 1000,
            validTill: "2024-12-31",
            type: "percentage",
            isActive: true
        },
        {
            id: 2,
            title: "Welcome Bonus",
            description: "New shop owner welcome offer",
            code: "WELCOME500",
            discount: "₹500",
            minOrder: 0,
            validTill: "2024-12-31",
            type: "fixed",
            isActive: true
        },
        {
            id: 3,
            title: "Season Sale",
            description: "End of season clearance",
            code: "SEASON30",
            discount: "30%",
            minOrder: 2000,
            validTill: "2024-12-25",
            type: "percentage",
            isActive: true
        }
    ];

    // Function to get icon based on activity type
    function getActivityIcon(type) {
        const icons = {
            'order': 'shopping-bag',
            'payment': 'dollar-sign',
            'delivery': 'package-check',
            'stock': 'alert-triangle',
            'offer': 'tag',
            'invoice': 'receipt'
        };
        return icons[type] || 'activity';
    }

    // Function to get activity color
    function getActivityColor(type) {
        const colors = {
            'order': 'text-blue-600 bg-blue-50 border-blue-100',
            'payment': 'text-emerald-600 bg-emerald-50 border-emerald-100',
            'delivery': 'text-purple-600 bg-purple-50 border-purple-100',
            'stock': 'text-amber-600 bg-amber-50 border-amber-100',
            'offer': 'text-rose-600 bg-rose-50 border-rose-100',
            'invoice': 'text-indigo-600 bg-indigo-50 border-indigo-100'
        };
        return colors[type] || 'text-slate-600 bg-slate-50 border-slate-100';
    }

    // Load dashboard data
    function loadDashboard() {
        // Set current date
        const dateOptions = { weekday: 'long', month: 'short', day: 'numeric' };
        document.getElementById('currentDate').textContent = new Date().toLocaleDateString('en-US', dateOptions);

        // Set user info
        const owner = shopOwnerData.shopOwner;
        document.getElementById('shopOwnerName').textContent = owner.name;
        document.getElementById('shopName').textContent = owner.shopName;

        // Load wallet balance with animation
        animateCounter('walletBalance', owner.walletBalance, '₹');

        // Load stats
        document.getElementById('totalOrders').textContent = owner.totalOrders;
        document.getElementById('pendingOrders').textContent = owner.pendingOrders;
        document.getElementById('deliveredOrders').textContent = owner.deliveredOrders;

        // Load recent activities
        loadRecentActivities();
    }

    function animateCounter(elementId, targetValue, prefix = '') {
        const element = document.getElementById(elementId);
        const currentValue = parseInt(element.textContent.replace(prefix, '').replace(/,/g, '')) || 0;
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
                element.textContent = prefix + Math.round(current).toLocaleString();
            }
        }, 16);
    }

    function loadRecentActivities() {
        const activityList = document.getElementById('activityList');
        activityList.innerHTML = '';

        if (shopOwnerData.recentActivities.length === 0) {
            activityList.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <iconify-icon icon="lucide:clipboard-list" width="32" class="mb-2 opacity-50"></iconify-icon>
                        <p class="text-sm">No recent activities</p>
                    </div>
                `;
            return;
        }

        shopOwnerData.recentActivities.forEach(activity => {
            const activityDiv = document.createElement('div');
            activityDiv.className = 'group p-4 hover:bg-slate-50 transition-colors cursor-pointer flex items-center justify-between';

            const iconClass = getActivityColor(activity.type);
            const iconName = getActivityIcon(activity.type);

            activityDiv.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full flex items-center justify-center ${iconClass.split(' ')[1]} border ${iconClass.split(' ')[2]}">
                            <iconify-icon icon="lucide:${iconName}" width="16" class="${iconClass.split(' ')[0]}"></iconify-icon>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-slate-900">${activity.title}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">${activity.description}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[10px] text-slate-400">${activity.time}</span>
                    </div>
                `;
            activityList.appendChild(activityDiv);
        });
    }

    // Offer Popup Functions
    function showOfferPopup() {
        const offerPopup = document.getElementById('offerPopup');
        const activeOffers = availableOffers.filter(offer => offer.isActive);
        
        if (activeOffers.length > 0) {
            // Select a random offer or the first active one
            const randomIndex = Math.floor(Math.random() * activeOffers.length);
            const selectedOffer = activeOffers[randomIndex];
            
            // Update popup content
            document.getElementById('offerTitle').textContent = selectedOffer.title;
            document.getElementById('offerDescription').textContent = selectedOffer.description;
            document.getElementById('offerCode').textContent = selectedOffer.code;
            document.getElementById('offerDiscount').textContent = selectedOffer.discount;
            document.getElementById('offerMinOrder').textContent = selectedOffer.minOrder;
            
            // Format date
            const validDate = new Date(selectedOffer.validTill);
            const formattedDate = validDate.toLocaleDateString('en-US', {
                day: 'numeric',
                month: 'short',
                year: 'numeric'
            });
            document.getElementById('offerValidTill').textContent = formattedDate;
            
            // Start countdown
            startCountdown(selectedOffer.validTill);
            
            // Show popup after a short delay
            setTimeout(() => {
                offerPopup.classList.add('active');
            }, 500);
        }
    }

    function closeOfferPopup() {
        const offerPopup = document.getElementById('offerPopup');
        offerPopup.classList.remove('active');
        
        // Optionally set a cookie to not show again for today
        const today = new Date().toDateString();
        localStorage.setItem('lastOfferShown', today);
    }

    function applyOffer() {
        const offerCode = document.getElementById('offerCode').textContent;
        
        // Show success message
        showToast({
            text: `Offer ${offerCode} copied to clipboard! Apply it during checkout.`,
            type: 'success'
        });
        
        // Copy to clipboard
        navigator.clipboard.writeText(offerCode).then(() => {
            console.log('Offer code copied to clipboard');
        });
        
        // Close popup
        closeOfferPopup();
        
        // Optionally redirect to products page
        // window.location.href = '{{ route('shop-owner.products.index') }}';
    }

    function startCountdown(endDate) {
        const countdownEnd = new Date(endDate).getTime();
        
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = countdownEnd - now;
            
            if (distance < 0) {
                document.getElementById('days').textContent = '00';
                document.getElementById('hours').textContent = '00';
                document.getElementById('minutes').textContent = '00';
                document.getElementById('seconds').textContent = '00';
                return;
            }
            
            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            document.getElementById('days').textContent = days.toString().padStart(2, '0');
            document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
            document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
            document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
        }
        
        // Update immediately and then every second
        updateCountdown();
        const countdownInterval = setInterval(updateCountdown, 1000);
        
        // Store interval ID to clear if needed
        window.offerCountdownInterval = countdownInterval;
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
            'bg-indigo-500 text-white'
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

    // Show intro overlay and set timeout to hide it after animation
    document.addEventListener('DOMContentLoaded', function () {
        const introOverlay = document.getElementById('introOverlay');
        const mainContent = document.getElementById('mainContent');

        // After 0.5 seconds, hide intro and show main content
        setTimeout(function () {
            introOverlay.style.opacity = '0';
            introOverlay.style.transform = 'scale(1.1)';

            // After fade out animation completes, remove overlay and show content
            setTimeout(function () {
                introOverlay.style.display = 'none';
                mainContent.style.display = 'block';

                // Enable scrolling again
                document.body.style.overflow = 'auto';

                // Load dashboard data
                loadDashboard();

                // Animate cards
                document.querySelectorAll('.animate-slide-up').forEach((card, index) => {
                    card.style.animationDelay = `${index * 0.1}s`;
                });
                
                // Check if we should show offer popup
                const lastOfferShown = localStorage.getItem('lastOfferShown');
                const today = new Date().toDateString();
                
                // Show offer popup if not shown today (or always for demo)
                if (!lastOfferShown || lastOfferShown !== today) {
                    // Small delay before showing offer popup
                    setTimeout(() => {
                        showOfferPopup();
                    }, 1000);
                }
            }, 500);
        }, 500);
    });
</script>
@endsection