@extends('layouts.shop-owner')

@php
$title = 'My Profile';
$subtitle = 'Shop details & settings';
$role = 'Shop Owner';
$showBack = false;
$headerAction = [
    'id' => 'editProfileBtn',
    'icon' => 'lucide:edit-2',
    'title' => 'Edit Profile'
];
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

    /* Stats hover effect */
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Settings item hover */
    .settings-item:hover {
        background-color: #F9FAFB;
    }

    /* Modal styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
        z-index: 50;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        width: 100%;
        max-width: 28rem;
        max-height: 90vh;
        overflow: hidden;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
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

<main class="p-4 space-y-4">
    <!-- Profile Card -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 animate-slide-up">
        <div class="flex flex-col items-center text-center mb-6">
            <div class="relative mb-4">
                <div
                    class="h-24 w-24 rounded-full bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center text-white text-2xl font-bold">
                    <iconify-icon icon="lucide:store" width="32"></iconify-icon>
                </div>
                <button onclick="changeProfilePicture()"
                    class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm hover:bg-slate-50 transition-colors">
                    <iconify-icon icon="lucide:camera" width="14" class="text-slate-600"></iconify-icon>
                </button>
            </div>
            <h2 id="profileName" class="text-lg font-semibold text-slate-900">Loading...</h2>
            <p id="profileShop" class="text-sm text-slate-500">Shop Owner</p>
            <p id="memberSince" class="text-xs text-slate-400 mt-1">Member since: Loading...</p>
        </div>

        <!-- Contact Info -->
        <div class="space-y-3 border-t border-slate-100 pt-4">
            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                    <iconify-icon icon="lucide:phone" width="16" class="text-slate-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Phone Number</p>
                    <p id="profilePhone" class="text-sm font-medium text-slate-900">Loading...</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                    <iconify-icon icon="lucide:mail" width="16" class="text-slate-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Email Address</p>
                    <p id="profileEmail" class="text-sm font-medium text-slate-900">Loading...</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div
                    class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                    <iconify-icon icon="lucide:map-pin" width="16" class="text-slate-600"></iconify-icon>
                </div>
                <div>
                    <p class="text-xs text-slate-500">Shop Address</p>
                    <p id="profileAddress" class="text-sm font-medium text-slate-900">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Stats -->
    <div class="bg-white rounded-xl border border-slate-200 p-4 animate-slide-up" style="animation-delay: 0.1s;">
        <h3 class="text-sm font-semibold text-slate-900 mb-4">Shop Performance</h3>

        <div class="grid grid-cols-3 gap-3">
            <div class="stat-card p-3 rounded-lg border border-slate-200 bg-white transition-all">
                <p class="text-2xl font-bold text-indigo-600" id="totalOrders">0</p>
                <p class="text-xs text-slate-500">Total Orders</p>
            </div>

            <div class="stat-card p-3 rounded-lg border border-slate-200 bg-white transition-all">
                <p class="text-2xl font-bold text-emerald-600" id="walletBalance">₹0</p>
                <p class="text-xs text-slate-500">Wallet Balance</p>
            </div>

            <div class="stat-card p-3 rounded-lg border border-slate-200 bg-white transition-all">
                <p class="text-2xl font-bold text-amber-600" id="totalReferrals">0</p>
                <p class="text-xs text-slate-500">Referrals</p>
            </div>
        </div>
    </div>

    <!-- Settings Section -->
    <div class="bg-white rounded-xl border border-slate-200 animate-slide-up" style="animation-delay: 0.2s;">
        <div class="p-4 border-b border-slate-100">
            <h3 class="text-sm font-semibold text-slate-900">Shop Settings</h3>
        </div>

        <div class="divide-y divide-slate-100">
            <a href="{{ route('shop-owner.wallet.index') }}" class="settings-item flex items-center justify-between p-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100">
                        <iconify-icon icon="lucide:wallet" width="16" class="text-emerald-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Wallet & Transactions</p>
                        <p class="text-xs text-slate-500">View balance and transaction history</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="16" class="text-slate-400"></iconify-icon>
            </a>

            <a href="{{ route('shop-owner.referral.index') }}" class="settings-item flex items-center justify-between p-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-purple-50 flex items-center justify-center border border-purple-100">
                        <iconify-icon icon="lucide:user-plus" width="16" class="text-purple-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Refer & Earn</p>
                        <p class="text-xs text-slate-500">Invite friends and earn money</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="16" class="text-slate-400"></iconify-icon>
            </a>

            <a href="{{ route('shop-owner.orders.index') }}" class="settings-item flex items-center justify-between p-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-blue-50 flex items-center justify-center border border-blue-100">
                        <iconify-icon icon="lucide:clipboard-list" width="16" class="text-blue-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Order History</p>
                        <p class="text-xs text-slate-500">View all your past orders</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="16" class="text-slate-400"></iconify-icon>
            </a>

            <a href="{{ route('shop-owner.invoices.index') }}" class="settings-item flex items-center justify-between p-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-amber-50 flex items-center justify-center border border-amber-100">
                        <iconify-icon icon="lucide:receipt" width="16" class="text-amber-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Invoices</p>
                        <p class="text-xs text-slate-500">View and download invoices</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="16" class="text-slate-400"></iconify-icon>
            </a>

            <a href="#" class="settings-item flex items-center justify-between p-4 transition-colors">
                <div class="flex items-center gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-100">
                        <iconify-icon icon="lucide:help-circle" width="16" class="text-slate-600"></iconify-icon>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-slate-900">Help & Support</p>
                        <p class="text-xs text-slate-500">FAQs and contact support</p>
                    </div>
                </div>
                <iconify-icon icon="lucide:chevron-right" width="16" class="text-slate-400"></iconify-icon>
            </a>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3 animate-slide-up" style="animation-delay: 0.3s;">
        <button onclick="openAboutApp()"
            class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors flex items-center justify-center gap-2">
            <iconify-icon icon="lucide:info" width="16" class="text-slate-500"></iconify-icon>
            About App & Version
        </button>

        <button onclick="confirmLogout()"
            class="w-full px-4 py-3 rounded-xl border border-red-200 bg-red-50 text-red-600 font-medium hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
            <iconify-icon icon="lucide:log-out" width="16"></iconify-icon>
            Logout
        </button>
    </div>
</main>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="modal-overlay hidden" style="display: none;">
    <div class="modal-content">
        <div class="p-6">
            <div class="flex flex-col items-center text-center mb-4">
                <div class="h-16 w-16 rounded-full bg-red-50 flex items-center justify-center mb-4">
                    <iconify-icon icon="lucide:log-out" width="24" class="text-red-500"></iconify-icon>
                </div>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">Logout</h3>
                <p class="text-sm text-slate-500">Are you sure you want to logout?</p>
                <p class="text-xs text-slate-400 mt-1">You will need to login again to access your account</p>
            </div>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50">
            <div class="flex gap-3">
                <button type="button" onclick="hideLogoutModal()"
                    class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="performLogout()"
                    class="flex-1 px-4 py-3 rounded-xl bg-red-600 text-white font-medium hover:bg-red-700 transition-colors">
                    Logout
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Profile Data - In real app, this would come from Laravel controller
    let shopProfileData = {
        name: "Rajesh Kumar",
        shopName: "Super Mart",
        memberSince: "January 2023",
        phone: "+91 98765 43210",
        email: "rajesh.supermart@email.com",
        address: "123 Main Street, Mumbai",
        businessType: "Kirana Store",
        totalOrders: 47,
        walletBalance: 800,
        totalReferrals: 8
    };

    function initializeProfile() {
        // Try to load from localStorage first
        const savedProfile = localStorage.getItem('shop_profile');
        if (savedProfile) {
            shopProfileData = JSON.parse(savedProfile);
        } else {
            // Load from dummy data and save to localStorage
            localStorage.setItem('shop_profile', JSON.stringify(shopProfileData));
        }

        loadProfileData();
        
        // Set up edit button click - with error handling
        const editBtn = document.getElementById('editProfileBtn');
        if (editBtn) {
            editBtn.addEventListener('click', openEditProfile);
        }
    }

    function loadProfileData() {
        // Load profile data
        document.getElementById('profileName').textContent = shopProfileData.name;
        document.getElementById('profileShop').textContent = shopProfileData.shopName;
        document.getElementById('memberSince').textContent = shopProfileData.memberSince;
        document.getElementById('profilePhone').textContent = shopProfileData.phone;
        document.getElementById('profileEmail').textContent = shopProfileData.email;
        document.getElementById('profileAddress').textContent = shopProfileData.address;

        // Animate counters
        animateCounter('totalOrders', shopProfileData.totalOrders);
        animateCounter('walletBalance', shopProfileData.walletBalance, '₹');
        animateCounter('totalReferrals', shopProfileData.totalReferrals);
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

    function openEditProfile() {
        // Redirect to edit page instead of modal
        window.location.href = '{{ route('shop-owner.profile.edit') }}';
    }

    function closeEditProfile() {
        // Not needed anymore since we redirect to edit page
    }

    function changeProfilePicture() {
        // In a real app, this would open file picker
        alert('Feature to change shop logo would open here');
    }

    function confirmLogout() {
        const modal = document.getElementById('logoutModal');
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }

    function performLogout() {
        // Clear localStorage
        localStorage.removeItem('shop_profile');
        localStorage.removeItem('shop_cart');
        localStorage.removeItem('checkout_details');
        localStorage.removeItem('shop_orders');

        // Show logout message
        showToast({
            text: 'You have been logged out successfully!',
            type: 'success'
        });

        // Redirect to login page after delay
        setTimeout(() => {
            // In Laravel, you would use a POST request to logout route
            // For now, we'll redirect to login page
            window.location.href = '{{ route('login') }}';
        }, 1000);
    }

    function openAboutApp() {
        // In a real app, this would open a modal with app details
        const aboutInfo = `
            Vamika Enterprise Shop App
            Version: 2.1.0
            Build: 2024.12.15
            
            Features:
            • Order Management
            • Product Catalog
            • Wallet System
            • Invoice Generation
            • Real-time Tracking
            
            © 2024 Vamika Enterprise. All rights reserved.
            
            Contact Support:
            Email: support@vamikaenterprise.com
            Phone: +91 9876543210
        `;
        
        alert(aboutInfo);
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

    // Initialize
    document.addEventListener('DOMContentLoaded', initializeProfile);

    // Close modal when clicking outside
    document.addEventListener('click', function (event) {
        const logoutModal = document.getElementById('logoutModal');

        if (event.target === logoutModal) {
            hideLogoutModal();
        }
    });

    // Add keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+E to edit profile
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            openEditProfile();
        }
        // Esc to close modals
        if (e.key === 'Escape') {
            hideLogoutModal();
        }
        // Ctrl+Q to logout
        if (e.ctrlKey && e.key === 'q') {
            e.preventDefault();
            confirmLogout();
        }
        // Ctrl+I for about
        if (e.ctrlKey && e.key === 'i') {
            e.preventDefault();
            openAboutApp();
        }
    });

    // Add profile picture upload handler
    window.addEventListener('load', function() {
        // In a real app, you would add file input for profile picture
        const profilePicBtn = document.querySelector('[onclick="changeProfilePicture()"]');
        if (profilePicBtn) {
            profilePicBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                // Create file input
                const fileInput = document.createElement('input');
                fileInput.type = 'file';
                fileInput.accept = 'image/*';
                fileInput.style.display = 'none';
                fileInput.onchange = function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        if (file.size > 5 * 1024 * 1024) { // 5MB limit
                            showToast({
                                text: 'File size should be less than 5MB',
                                type: 'error'
                            });
                            return;
                        }
                        
                        // In a real app, upload to server
                        // For demo, show success message
                        showToast({
                            text: 'Profile picture updated successfully',
                            type: 'success'
                        });
                        
                        // Simulate preview (in real app, you would show the uploaded image)
                        const profileCircle = document.querySelector('.flex.items-center.justify-center.text-white');
                        if (profileCircle) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                // Would update profile picture here
                                console.log('Image loaded for preview');
                            };
                            reader.readAsDataURL(file);
                        }
                    }
                };
                document.body.appendChild(fileInput);
                fileInput.click();
                document.body.removeChild(fileInput);
            });
        }
    });
</script>
@endsection