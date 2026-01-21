@extends('layouts.salesperson')

@section('content')
<style>
    .profile-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    .settings-item:hover {
        background-color: #F9FAFB;
    }
</style>

<!-- Mobile/Tablet View -->
<div class="block">
    <div class="min-h-screen pb-24">
        <!-- Main Content - Mobile -->
        <main class="p-4 space-y-4">
            <!-- Profile Card -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex flex-col items-center text-center mb-6">
                    <div class="relative mb-4">
                        <div
                            class="h-24 w-24 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-2xl font-bold">
                            RS
                        </div>
                        <button onclick="openEditProfile()"
                            class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm hover:bg-slate-50 transition-colors">
                            <iconify-icon icon="lucide:camera" width="14" class="text-slate-600"></iconify-icon>
                        </button>
                    </div>
                    <h2 id="profileNameMobile" class="text-lg font-semibold text-slate-900">Rahul Sharma</h2>
                    <p id="profileRoleMobile" class="text-sm text-slate-500">Sales Executive</p>
                    <p id="profileAreaMobile" class="text-xs text-slate-400 mt-1">South Mumbai • Joined Feb 2023</p>
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
                            <p id="profilePhoneMobile" class="text-sm font-medium text-slate-900">+91 98765 43210</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div
                            class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                            <iconify-icon icon="lucide:mail" width="16" class="text-slate-600"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Email Address</p>
                            <p id="profileEmailMobile" class="text-sm font-medium text-slate-900">
                                rahul.sharma@vamika.com</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div
                            class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200">
                            <iconify-icon icon="lucide:id-card" width="16" class="text-slate-600"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500">Employee ID</p>
                            <p id="profileIdMobile" class="text-sm font-medium text-slate-900">SE-2023-045</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance Stats -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Performance This Month</h3>

                <div class="grid grid-cols-2 gap-3">
                    <div class="profile-stat p-3 rounded-lg border border-slate-200 bg-white transition-all">
                        <p class="text-2xl font-bold text-indigo-600" id="monthSalesMobile">₹42,500</p>
                        <p class="text-xs text-slate-500">Total Sales</p>
                    </div>

                    <div class="profile-stat p-3 rounded-lg border border-slate-200 bg-white transition-all">
                        <p class="text-2xl font-bold text-emerald-600" id="completedOrdersMobile">34</p>
                        <p class="text-xs text-slate-500">Completed Orders</p>
                    </div>

                    <div class="profile-stat p-3 rounded-lg border border-slate-200 bg-white transition-all">
                        <p class="text-2xl font-bold text-amber-600" id="activeShopsMobile">12</p>
                        <p class="text-xs text-slate-500">Active Shops</p>
                    </div>

                    <div class="profile-stat p-3 rounded-lg border border-slate-200 bg-white transition-all">
                        <p class="text-2xl font-bold text-blue-600" id="successRateMobile">94%</p>
                        <p class="text-xs text-slate-500">Success Rate</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                <button onclick="confirmLogout()"
                    class="w-full px-4 py-3 rounded-xl border border-red-200 bg-red-50 text-red-600 font-medium hover:bg-red-100 transition-colors flex items-center justify-center gap-2">
                    <iconify-icon icon="lucide:log-out" width="16"></iconify-icon>
                    Logout
                </button>
            </div>
        </main>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] flex flex-col shadow-xl">
        <!-- Header -->
        <div class="p-6 border-b border-slate-100 flex-shrink-0">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Edit Profile</h2>
                <button onclick="closeEditProfile()" class="p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <iconify-icon icon="lucide:x" width="20" class="text-slate-500"></iconify-icon>
                </button>
            </div>
        </div>

        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto p-6">
            <div class="flex flex-col items-center mb-6">
                <div class="relative mb-4">
                    <div
                        class="h-20 w-20 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-bold mb-2">
                        RS
                    </div>
                    <button onclick="changeProfilePicture()"
                        class="absolute bottom-0 right-0 h-8 w-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm hover:bg-slate-50 transition-colors">
                        <iconify-icon icon="lucide:camera" width="14" class="text-slate-600"></iconify-icon>
                    </button>
                </div>
                <p class="text-xs text-slate-500">Tap to change profile picture</p>
            </div>

            <form id="editProfileForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Full Name</label>
                    <input type="text" id="editName" value="Rahul Sharma"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Phone Number</label>
                    <input type="tel" id="editPhone" value="+91 98765 43210"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Email Address</label>
                    <input type="email" id="editEmail" value="rahul.sharma@vamika.com"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Assigned Area</label>
                    <input type="text" id="editArea" value="South Mumbai, Bandra"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                </div>
            </form>
        </div>

        <!-- Fixed Footer -->
        <div class="p-6 border-t border-slate-100 bg-slate-50 flex-shrink-0">
            <div class="flex gap-3">
                <button type="button" onclick="closeEditProfile()"
                    class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="saveProfile()"
                    class="flex-1 px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logoutModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-sm shadow-xl">
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

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'My Profile',
        'subtitle' => 'Account settings & information',
        'showBack' => true,
        'backUrl' => route('salesperson.dashboard'),
        'showBottomNav' => true,
        'headerRight' => 'edit'
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Profile Data
    const profileData = {
        name: "Rahul Sharma",
        role: "Sales Executive",
        area: "South Mumbai",
        phone: "+91 98765 43210",
        email: "rahul.sharma@vamika.com",
        employeeId: "SE-2023-045",
        joinDate: "February 2023",
        territories: "South Mumbai, Bandra",
        monthlySales: "₹42,500",
        totalOrders: 156,
        completedOrders: 34,
        activeShops: 12,
        successRate: "94%",
        achievements: [
            "Top Seller - March 2024",
            "Fastest Delivery - 98%",
            "New Shop Acquisition - 5"
        ]
    };

    function initializeProfile() {
        loadProfileData();
    }

    function loadProfileData() {
        // Load profile data for mobile
        document.getElementById('profileNameMobile').textContent = profileData.name;
        document.getElementById('profileRoleMobile').textContent = profileData.role;
        document.getElementById('profileAreaMobile').textContent = `${profileData.area} • Joined ${profileData.joinDate}`;

        document.getElementById('profilePhoneMobile').textContent = profileData.phone;
        document.getElementById('profileEmailMobile').textContent = profileData.email;
        document.getElementById('profileIdMobile').textContent = profileData.employeeId;

        document.getElementById('monthSalesMobile').textContent = profileData.monthlySales;
        document.getElementById('activeShopsMobile').textContent = profileData.activeShops;
        document.getElementById('successRateMobile').textContent = profileData.successRate;
        document.getElementById('completedOrdersMobile').textContent = profileData.completedOrders;
    }

    function openEditProfile() {
        // Load current data into form
        document.getElementById('editName').value = profileData.name;
        document.getElementById('editPhone').value = profileData.phone;
        document.getElementById('editEmail').value = profileData.email;
        document.getElementById('editArea').value = profileData.territories;

        // Show modal
        document.getElementById('editProfileModal').classList.remove('hidden');
    }

    function closeEditProfile() {
        document.getElementById('editProfileModal').classList.add('hidden');
    }

    function changeProfilePicture() {
        showToast({
            text: 'Opening profile picture uploader…',
            type: 'info'
        });

        // Later: open file picker / modal
    }

    function saveProfile() {
        // Get form values
        const newName = document.getElementById('editName').value;
        const newPhone = document.getElementById('editPhone').value;
        const newEmail = document.getElementById('editEmail').value;
        const newArea = document.getElementById('editArea').value;

        // Validate
        if (!newName.trim() || !newPhone.trim() || !newEmail.trim()) {
            showToast({
                text: 'Please fill in all required fields',
                type: 'error'
            });
            return;
        }

        // Update profile data
        profileData.name = newName;
        profileData.phone = newPhone;
        profileData.email = newEmail;
        profileData.territories = newArea;

        // Reload profile
        loadProfileData();

        // Show success message
        showToast({
            text: 'Profile updated successfully',
            type: 'success'
        });

        // Close modal
        closeEditProfile();
    }

    function confirmLogout() {
        document.getElementById('logoutModal').classList.remove('hidden');
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').classList.add('hidden');
    }

    function performLogout() {
        // Clear localStorage
        localStorage.clear();

        // Show logout message
        showToast({
            text: 'You have been logged out',
            type: 'info'
        });

        // Redirect to login page
        window.location.href = "{{ route('login') }}";
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', initializeProfile);
</script>
@endsection