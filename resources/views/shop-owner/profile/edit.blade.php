@extends('layouts.shop-owner')

@php
$title = 'Edit Profile';
$subtitle = 'Update shop information';
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

    /* Input styles */
    .form-input {
        width: 100%;
        padding: 0.625rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid #E2E8F0;
        background-color: white;
        font-size: 0.875rem;
        color: #1F2937;
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #4F46E5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-input::placeholder {
        color: #9CA3AF;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.375rem;
    }

    .form-textarea {
        resize: vertical;
        min-height: 5rem;
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236B7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.5rem center;
        background-repeat: no-repeat;
        background-size: 1.5em 1.5em;
        padding-right: 2.5rem;
    }

    /* Profile picture */
    .profile-picture-container {
        position: relative;
        display: inline-block;
    }

    .profile-picture {
        width: 6rem;
        height: 6rem;
        border-radius: 50%;
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        border: 4px solid white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    .picture-upload-btn {
        position: absolute;
        bottom: 0;
        right: 0;
        width: 2rem;
        height: 2rem;
        border-radius: 50%;
        background: white;
        border: 2px solid #E5E7EB;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .picture-upload-btn:hover {
        background: #F9FAFB;
        border-color: #D1D5DB;
        transform: scale(1.05);
    }

    .picture-upload-btn:active {
        transform: scale(0.95);
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

<div class="main-content p-4">
    <form id="editProfileForm" class="space-y-6" onsubmit="return false;">

        <!-- Shop Logo -->
        <div class="bg-white rounded-xl border border-slate-200 p-6 text-center animate-slide-up">
            <div class="profile-picture-container mb-3">
                <div class="profile-picture">
                    <iconify-icon icon="lucide:store" width="32"></iconify-icon>
                </div>
                <button type="button" onclick="changeProfilePicture()" class="picture-upload-btn">
                    <iconify-icon icon="lucide:camera" width="14" class="text-slate-600"></iconify-icon>
                </button>
            </div>
            <p class="text-xs text-slate-500">Tap to change shop logo (Max 5MB, JPG/PNG)</p>
        </div>

        <!-- Form Fields -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-4 animate-slide-up" style="animation-delay: 0.1s;">
            <!-- Shop Name -->
            <div>
                <label for="editShopName" class="form-label">
                    Shop Name
                    <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="editShopName" name="shop_name" class="form-input" 
                       placeholder="Enter your shop name" required>
                <p class="text-xs text-slate-400 mt-1">This will be displayed on your invoices</p>
            </div>

            <!-- Owner Name -->
            <div>
                <label for="editOwnerName" class="form-label">
                    Owner Name
                    <span class="text-rose-500">*</span>
                </label>
                <input type="text" id="editOwnerName" name="owner_name" class="form-input" 
                       placeholder="Enter owner's full name" required>
            </div>

            <!-- Phone Number -->
            <div>
                <label for="editPhone" class="form-label">
                    Phone Number
                    <span class="text-rose-500">*</span>
                </label>
                <input type="tel" id="editPhone" name="phone" class="form-input" 
                       placeholder="+91 98765 43210" pattern="^[\d\s+\-()]{10,15}$" required>
                <p class="text-xs text-slate-400 mt-1">We'll use this for order updates and notifications</p>
            </div>

            <!-- Email Address -->
            <div>
                <label for="editEmail" class="form-label">
                    Email Address
                    <span class="text-rose-500">*</span>
                </label>
                <input type="email" id="editEmail" name="email" class="form-input" 
                       placeholder="shop@example.com" required>
                <p class="text-xs text-slate-400 mt-1">For invoices and important communications</p>
            </div>

            <!-- Shop Address -->
            <div>
                <label for="editAddress" class="form-label">
                    Shop Address
                    <span class="text-rose-500">*</span>
                </label>
                <textarea id="editAddress" name="address" rows="3" class="form-input form-textarea" 
                          placeholder="Full shop address including landmark" required></textarea>
                <p class="text-xs text-slate-400 mt-1">Used for delivery and billing</p>
            </div>

            <!-- Business Type -->
            <div>
                <label for="editBusinessType" class="form-label">
                    Business Type
                </label>
                <select id="editBusinessType" name="business_type" class="form-input form-select">
                    <option value="kirana">Kirana Store</option>
                    <option value="general">General Store</option>
                    <option value="medical">Medical Store</option>
                    <option value="supermarket">Supermarket</option>
                    <option value="restaurant">Restaurant</option>
                    <option value="bakery">Bakery</option>
                    <option value="beverage">Beverage Shop</option>
                    <option value="other">Other</option>
                </select>
                <p class="text-xs text-slate-400 mt-1">Helps us provide better recommendations</p>
            </div>

            <!-- GST Number (Optional) -->
            <div>
                <label for="editGST" class="form-label">GST Number (Optional)</label>
                <input type="text" id="editGST" name="gst_number" class="form-input" 
                       placeholder="07AABCU9603R1ZM" pattern="^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$">
                <p class="text-xs text-slate-400 mt-1">For GST-compliant invoices</p>
            </div>
        </div>

        <!-- Shop Preferences -->
        <div class="bg-white rounded-xl border border-slate-200 p-4 space-y-4 animate-slide-up" style="animation-delay: 0.2s;">
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Shop Preferences</h3>
            
            <!-- Preferred Delivery Time -->
            <div>
                <label for="preferredTime" class="form-label">Preferred Delivery Time</label>
                <select id="preferredTime" name="preferred_time" class="form-input form-select">
                    <option value="morning">Morning (9 AM - 12 PM)</option>
                    <option value="afternoon">Afternoon (12 PM - 4 PM)</option>
                    <option value="evening">Evening (4 PM - 7 PM)</option>
                    <option value="anytime">Anytime</option>
                </select>
            </div>

            <!-- Minimum Order Value -->
            <div>
                <label for="minOrderValue" class="form-label">Minimum Order Value (Optional)</label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-500">₹</span>
                    <input type="number" id="minOrderValue" name="min_order_value" class="form-input pl-8" 
                           placeholder="0" min="0" step="100">
                </div>
                <p class="text-xs text-slate-400 mt-1">Set 0 for no minimum</p>
            </div>

            <!-- Shop Opening Hours -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="openingTime" class="form-label">Opening Time</label>
                    <input type="time" id="openingTime" name="opening_time" class="form-input">
                </div>
                <div>
                    <label for="closingTime" class="form-label">Closing Time</label>
                    <input type="time" id="closingTime" name="closing_time" class="form-input">
                </div>
            </div>

            <!-- Communication Preferences -->
            <div class="space-y-2">
                <p class="text-sm font-medium text-slate-900">Communication Preferences</p>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="smsNotifications" name="sms_notifications" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="smsNotifications" class="text-sm text-slate-700">SMS Notifications</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="emailNotifications" name="email_notifications" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    <label for="emailNotifications" class="text-sm text-slate-700">Email Notifications</label>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="orderUpdates" name="order_updates" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" checked>
                    <label for="orderUpdates" class="text-sm text-slate-700">Order Status Updates</label>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-3 animate-slide-up" style="animation-delay: 0.3s;">
            <button type="button" onclick="goBack()"
                class="flex-1 px-4 py-3 border border-slate-300 rounded-xl bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                Cancel
            </button>
            <button type="submit" id="saveButton"
                class="flex-1 px-4 py-3 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="saveText">Save Changes</span>
                <span id="savingText" class="hidden">Saving...</span>
            </button>
        </div>

    </form>
</div>
@endsection

@section('scripts')
<script>
    let shopProfileData = {};

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadProfileData();
        setupFormValidation();
    });

    function loadProfileData() {
        // Try to load from localStorage first
        const savedProfile = localStorage.getItem('shop_profile');
        
        // Default profile data
        const defaultProfile = {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            phone: "+91 98765 43210",
            email: "rajesh.supermart@email.com",
            address: "123 Main Street, Mumbai, Maharashtra 400001",
            businessType: "kirana",
            gstNumber: "",
            preferredTime: "morning",
            minOrderValue: 0,
            openingTime: "08:00",
            closingTime: "20:00",
            smsNotifications: true,
            emailNotifications: true,
            orderUpdates: true
        };

        shopProfileData = savedProfile ? JSON.parse(savedProfile) : defaultProfile;

        // Populate form fields
        document.getElementById('editShopName').value = shopProfileData.shopName || '';
        document.getElementById('editOwnerName').value = shopProfileData.name || '';
        document.getElementById('editPhone').value = shopProfileData.phone || '';
        document.getElementById('editEmail').value = shopProfileData.email || '';
        document.getElementById('editAddress').value = shopProfileData.address || '';
        document.getElementById('editBusinessType').value = shopProfileData.businessType || 'kirana';
        document.getElementById('editGST').value = shopProfileData.gstNumber || '';
        document.getElementById('preferredTime').value = shopProfileData.preferredTime || 'morning';
        document.getElementById('minOrderValue').value = shopProfileData.minOrderValue || 0;
        document.getElementById('openingTime').value = shopProfileData.openingTime || '08:00';
        document.getElementById('closingTime').value = shopProfileData.closingTime || '20:00';
        document.getElementById('smsNotifications').checked = shopProfileData.smsNotifications !== false;
        document.getElementById('emailNotifications').checked = shopProfileData.emailNotifications !== false;
        document.getElementById('orderUpdates').checked = shopProfileData.orderUpdates !== false;
    }

    function setupFormValidation() {
        const form = document.getElementById('editProfileForm');
        
        // Phone validation
        const phoneInput = document.getElementById('editPhone');
        phoneInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                value = '+91 ' + value;
                if (value.length > 6) {
                    value = value.slice(0, 6) + ' ' + value.slice(6);
                }
                if (value.length > 11) {
                    value = value.slice(0, 11) + ' ' + value.slice(11);
                }
            }
            e.target.value = value;
        });

        // Form submission
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            saveProfile();
        });

        // Real-time validation
        const requiredFields = ['editShopName', 'editOwnerName', 'editPhone', 'editEmail', 'editAddress'];
        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.addEventListener('blur', validateField);
            field.addEventListener('input', validateField);
        });
    }

    function goBack() {
        window.location.href = '{{ route('shop-owner.profile.index') }}';
    }

    function validateField(e) {
        const field = e.target;
        const value = field.value.trim();
        const fieldId = field.id;
        
        let isValid = true;
        let errorMessage = '';

        switch(fieldId) {
            case 'editPhone':
                const phoneDigits = value.replace(/\D/g, '');
                isValid = phoneDigits.length >= 10;
                errorMessage = 'Please enter a valid 10-digit phone number';
                break;
            case 'editEmail':
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                isValid = emailRegex.test(value);
                errorMessage = 'Please enter a valid email address';
                break;
            case 'editGST':
                if (value) {
                    const gstRegex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;
                    isValid = gstRegex.test(value.toUpperCase());
                    errorMessage = 'Please enter a valid GST number';
                }
                break;
            default:
                isValid = value.length > 0;
                errorMessage = 'This field is required';
        }

        // Update field styling
        if (isValid || (fieldId === 'editGST' && !value)) {
            field.classList.remove('border-rose-300', 'bg-rose-50');
            field.classList.add('border-slate-200', 'bg-white');
            
            // Remove error message
            const errorElement = document.getElementById(fieldId + 'Error');
            if (errorElement) {
                errorElement.remove();
            }
        } else {
            field.classList.remove('border-slate-200', 'bg-white');
            field.classList.add('border-rose-300', 'bg-rose-50');
            
            // Add error message
            const errorElement = document.getElementById(fieldId + 'Error');
            if (!errorElement) {
                const errorDiv = document.createElement('p');
                errorDiv.id = fieldId + 'Error';
                errorDiv.className = 'text-xs text-rose-600 mt-1';
                errorDiv.textContent = errorMessage;
                field.parentNode.appendChild(errorDiv);
            }
        }

        return isValid;
    }

    function validateForm() {
        const requiredFields = ['editShopName', 'editOwnerName', 'editPhone', 'editEmail', 'editAddress'];
        let isValid = true;

        requiredFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!validateField({ target: field })) {
                isValid = false;
            }
        });

        return isValid;
    }

    async function saveProfile() {
        // Validate form
        if (!validateForm()) {
            showToast({
                text: 'Please fix the errors in the form',
                type: 'error'
            });
            return;
        }

        // Disable save button
        const saveButton = document.getElementById('saveButton');
        const saveText = document.getElementById('saveText');
        const savingText = document.getElementById('savingText');
        
        saveButton.disabled = true;
        saveText.classList.add('hidden');
        savingText.classList.remove('hidden');

        // Collect form data
        const updatedProfile = {
            name: document.getElementById('editOwnerName').value.trim(),
            shopName: document.getElementById('editShopName').value.trim(),
            phone: document.getElementById('editPhone').value.trim(),
            email: document.getElementById('editEmail').value.trim(),
            address: document.getElementById('editAddress').value.trim(),
            businessType: document.getElementById('editBusinessType').value,
            gstNumber: document.getElementById('editGST').value.trim().toUpperCase(),
            preferredTime: document.getElementById('preferredTime').value,
            minOrderValue: parseInt(document.getElementById('minOrderValue').value) || 0,
            openingTime: document.getElementById('openingTime').value,
            closingTime: document.getElementById('closingTime').value,
            smsNotifications: document.getElementById('smsNotifications').checked,
            emailNotifications: document.getElementById('emailNotifications').checked,
            orderUpdates: document.getElementById('orderUpdates').checked,
            lastUpdated: new Date().toISOString()
        };

        try {
            // Simulate API call delay
            await new Promise(resolve => setTimeout(resolve, 800));

            // Save to localStorage
            localStorage.setItem('shop_profile', JSON.stringify(updatedProfile));

            // Show success message
            showToast({
                text: 'Profile updated successfully!',
                type: 'success'
            });

            // Update shop owner data in localStorage (for dashboard)
            const shopOwnerData = JSON.parse(localStorage.getItem('shop_owner')) || {};
            const mergedData = { ...shopOwnerData, ...updatedProfile };
            localStorage.setItem('shop_owner', JSON.stringify(mergedData));

            // Redirect back to profile page after delay
            setTimeout(() => {
                window.location.href = '{{ route('shop-owner.profile.index') }}';
            }, 1000);

        } catch (error) {
            console.error('Error saving profile:', error);
            showToast({
                text: 'Failed to save profile. Please try again.',
                type: 'error'
            });
            
            // Re-enable save button
            saveButton.disabled = false;
            saveText.classList.remove('hidden');
            savingText.classList.add('hidden');
        }
    }

    function changeProfilePicture() {
        // In a real app, this would open file picker
        const fileInput = document.createElement('input');
        fileInput.type = 'file';
        fileInput.accept = 'image/*';
        fileInput.style.display = 'none';
        
        fileInput.onchange = function(event) {
            const file = event.target.files[0];
            if (file) {
                // Check file size (max 5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showToast({
                        text: 'File size should be less than 5MB',
                        type: 'error'
                    });
                    return;
                }

                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                if (!validTypes.includes(file.type)) {
                    showToast({
                        text: 'Please upload JPG or PNG files only',
                        type: 'error'
                    });
                    return;
                }

                // In a real app, upload to server
                // For demo, simulate upload
                showToast({
                    text: 'Uploading profile picture...',
                    type: 'info'
                });

                // Simulate upload delay
                setTimeout(() => {
                    showToast({
                        text: 'Profile picture updated successfully!',
                        type: 'success'
                    });
                    
                    // Update profile picture preview (in real app, this would be the uploaded image URL)
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const profileCircle = document.querySelector('.profile-picture');
                        // In a real app, you would set background-image
                        console.log('Profile picture updated:', e.target.result);
                    };
                    reader.readAsDataURL(file);
                }, 1500);
            }
        };
        
        document.body.appendChild(fileInput);
        fileInput.click();
        document.body.removeChild(fileInput);
    }

    function goBack() {
        if (hasUnsavedChanges()) {
            if (confirm('You have unsaved changes. Are you sure you want to leave?')) {
                window.location.href = '{{ route('shop-owner.profile.index') }}';
            }
        } else {
            window.location.href = '{{ route('shop-owner.profile.index') }}';
        }
    }

    function hasUnsavedChanges() {
        const currentData = {
            name: document.getElementById('editOwnerName').value.trim(),
            shopName: document.getElementById('editShopName').value.trim(),
            phone: document.getElementById('editPhone').value.trim(),
            email: document.getElementById('editEmail').value.trim(),
            address: document.getElementById('editAddress').value.trim()
        };

        const originalData = {
            name: shopProfileData.name || '',
            shopName: shopProfileData.shopName || '',
            phone: shopProfileData.phone || '',
            email: shopProfileData.email || '',
            address: shopProfileData.address || ''
        };

        return JSON.stringify(currentData) !== JSON.stringify(originalData);
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

    // Add keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+S to save
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            saveProfile();
        }
        // Esc to go back
        if (e.key === 'Escape') {
            e.preventDefault();
            goBack();
        }
    });

    // Warn about unsaved changes
    window.addEventListener('beforeunload', function (e) {
        if (hasUnsavedChanges()) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    // Add character counter for address
    document.getElementById('editAddress').addEventListener('input', function(e) {
        const charCount = e.target.value.length;
        const maxLength = 200;
        
        let counter = document.getElementById('addressCounter');
        if (!counter) {
            counter = document.createElement('p');
            counter.id = 'addressCounter';
            counter.className = 'text-xs text-slate-400 text-right mt-1';
            e.target.parentNode.appendChild(counter);
        }
        
        counter.textContent = `${charCount}/${maxLength} characters`;
        
        if (charCount > maxLength) {
            counter.classList.add('text-rose-600');
            counter.classList.remove('text-slate-400');
        } else {
            counter.classList.remove('text-rose-600');
            counter.classList.add('text-slate-400');
        }
    });
</script>
@endsection