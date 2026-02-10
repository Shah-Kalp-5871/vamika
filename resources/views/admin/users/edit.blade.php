@extends('layouts.admin')

@section('content')
@php
// Create a dummy user for testing if $user is not passed
$testUser = $user ?? (object)[
    'id' => 1,
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '9876543210',
    'type' => 'shop-owner',
    'status' => 'active',
    'bit' => 'Test Bit',
    'address' => 'Test Address',
    'shop_name' => 'Test Shop',
    'employee_id' => null,
    'created_at' => now(),
];

$user = $user ?? $testUser;

$pageConfig = [
    'title' => 'Edit User',
    'subtitle' => 'Update user details',
    'showBack' => true,
    'backUrl' => route('admin.users.index'),
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

    /* User Info Header */
    .user-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        border-bottom: 1px solid #E2E8F0;
        background: white;
    }

    .user-avatar-large {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1.25rem;
        color: white;
    }

    .user-info h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 0.25rem;
    }

    .user-info p {
        font-size: 0.875rem;
        color: #64748B;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: auto;
    }

    .badge-shop {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        color: white;
    }

    .badge-sales {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        color: white;
    }

    /* Form Styles */
    .form-container {
        padding: 1.5rem;
    }

    .form-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .form-grid {
            grid-template-columns: 1fr 1fr;
        }
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
        margin-bottom: 0.5rem;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        color: #1F2937;
        transition: all 0.2s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-input::placeholder {
        color: #9CA3AF;
    }

    .form-input:disabled {
        background-color: #F9FAFB;
        border-color: #E5E7EB;
        color: #6B7280;
        cursor: not-allowed;
    }

    .form-required {
        color: #EF4444;
        margin-left: 2px;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E2E8F0;
    }

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
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
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

    .btn-danger {
        background: white;
        color: #EF4444;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: 1px solid #FCA5A5;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        margin-left: auto;
    }

    .btn-danger:hover {
        background: #FEF2F2;
    }

    /* Status toggle */
    .status-toggle {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-top: 0.5rem;
    }

    .toggle-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 26px;
    }

    .toggle-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 34px;
    }

    .toggle-slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }

    input:checked + .toggle-slider {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    input:checked + .toggle-slider:before {
        transform: translateX(24px);
    }

    .toggle-label {
        font-size: 0.875rem;
        color: #374151;
    }

    /* Password change section */
    .password-section {
        background: #F8FAFC;
        padding: 1.5rem;
        border-radius: 0.75rem;
        border: 1px solid #E2E8F0;
        margin-top: 1.5rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    /* Password Toggle Styles */
    .password-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        background: transparent;
        border: none;
        color: #94A3B8;
        cursor: pointer;
        padding: 0.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        z-index: 10;
    }
    
    .password-toggle:hover {
        color: #6366F1;
    }

    .form-input.pr-10 {
        padding-right: 2.5rem;
    }
</style>

<div class="main-content">
    <!-- User Header -->
    <div class="user-header">
        <div class="user-avatar-large" id="userAvatar" style="background: #4ECDC4;">
            {{ substr($user->name ?? 'User', 0, 1) }}
        </div>
        <div class="user-info">
            <h3 id="userName">{{ $user->name ?? 'User Name' }}</h3>
            <p id="userEmail">{{ $user->email ?? 'user@example.com' }}</p>
        </div>
        <span class="user-badge" id="userTypeBadge">
            <iconify-icon icon="lucide:store" width="12" class="mr-1"></iconify-icon>
            {{ $user->role === 'shop-owner' ? 'Shop Owner' : 'Salesperson' }}
        </span>
    </div>

    <main>
        <!-- Edit Form -->
        <div class="form-container">
            <h2 class="form-title">Edit User Details</h2>
            <form id="editUserForm" method="POST" action="{{ url('/admin/users/' . ($user->id ?? '')) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="user_type" value="{{ $user->role === 'shop-owner' ? 'shop-owner' : 'salesperson' }}">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="form-required">*</span></label>
                        <input type="text" class="form-input" name="name" 
                               value="{{ old('name', $user->name ?? '') }}" 
                               placeholder="Enter full name" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="form-required">*</span></label>
                        <input type="email" class="form-input" name="email" 
                               value="{{ old('email', $user->email ?? '') }}" 
                               placeholder="Enter email address" required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="form-required">*</span></label>
                        <input type="tel" class="form-input" name="phone" 
                               value="{{ old('phone', $user->phone ?? '') }}" 
                               placeholder="Enter phone number" required>
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if($user->role === 'shop-owner')
                        <div class="form-group">
                            <label class="form-label">Shop Name <span class="form-required">*</span></label>
                            <input type="text" class="form-input" name="shop_name" 
                                   value="{{ old('shop_name', $user->shop ? $user->shop->name : '') }}" 
                                   placeholder="Enter shop name" required>
                            @error('shop_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label">Employee ID</label>
                            <input type="text" class="form-input" name="employee_id" 
                                   value="{{ old('employee_id', $user->employee_id ?? '') }}" 
                                   placeholder="Enter employee ID">
                            @error('employee_id')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif

                    @if($user->role === 'shop-owner')
                    <div class="form-group">
                        <label class="form-label">Bit <span class="form-required">*</span></label>
                        <select class="form-input" name="bit_id" required>
                            <option value="">Select bit</option>
                            @foreach($bits as $bit)
                                <option value="{{ $bit->id }}" 
                                    {{ (old('bit_id', $user->shop ? $user->shop->bit_id : '') == $bit->id) ? 'selected' : '' }}>
                                    {{ $bit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bit_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label">Address @if($user->role === 'shop-owner')<span class="form-required">*</span>@endif</label>
                        <input type="text" class="form-input" name="address" 
                               value="{{ old('address', $user->shop ? $user->shop->address : '') }}" 
                               placeholder="Enter address" @if($user->role === 'shop-owner') required @endif>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <div class="status-toggle">
                            <label class="toggle-switch">
                                <input type="checkbox" name="status" value="active" 
                                       {{ (old('status', $user->status ?? 'active') == 'active') ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label" id="statusLabel">
                                {{ (old('status', $user->status ?? 'active') == 'active') ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @error('status')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Password Change Section (Optional) -->
                <div class="password-section">
                    <h4 class="section-title">
                        <iconify-icon icon="lucide:lock" width="16"></iconify-icon>
                        Change Password (Optional)
                    </h4>
                    <p class="text-sm text-slate-500 mb-4">Leave blank if you don't want to change the password</p>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">New Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-input pr-10" name="password" 
                                       placeholder="Enter new password">
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                                </button>
                            </div>
                            @error('password')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm New Password</label>
                            <div class="password-wrapper">
                                <input type="password" class="form-input pr-10" name="password_confirmation" 
                                       placeholder="Confirm new password">
                                <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                    <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="display: flex; flex-wrap: wrap; gap: 0.75rem; justify-content: flex-end;">
                    <a href="{{ route('admin.users.index') }}" class="btn-secondary" style="margin-right: auto;">
                        <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <iconify-icon icon="lucide:save" width="16"></iconify-icon>
                        Update User
                    </button>
                    <button type="button" class="btn-danger" onclick="confirmDelete()">
                        <iconify-icon icon="lucide:trash" width="16"></iconify-icon>
                        Delete User
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    let selectedUserType = '';

    function togglePassword(button) {
        const input = button.parentElement.querySelector('input');
        const icon = button.querySelector('iconify-icon');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.setAttribute('icon', 'lucide:eye-off');
        } else {
            input.type = 'password';
            icon.setAttribute('icon', 'lucide:eye');
        }
    }

    // Status toggle label update
    document.querySelector('input[name="status"]').addEventListener('change', function() {
        document.getElementById('statusLabel').textContent = this.checked ? 'Active' : 'Inactive';
    });

    // Form validation for password match
    document.getElementById('editUserForm').addEventListener('submit', function(e) {
        const password = this.querySelector('input[name="password"]').value;
        const confirmPassword = this.querySelector('input[name="password_confirmation"]').value;
        
        if (password && password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return;
        }
    });

    // Delete confirmation
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            // Create a delete form
            const deleteForm = document.createElement('form');
            deleteForm.method = 'POST';
            deleteForm.action = "{{ url('/admin/users/' . ($user->id ?? '')) }}";
            deleteForm.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = "{{ csrf_token() }}";
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            deleteForm.appendChild(csrfToken);
            deleteForm.appendChild(methodField);
            document.body.appendChild(deleteForm);
            deleteForm.submit();
        }
    }

    // Update avatar color based on user type
    document.addEventListener('DOMContentLoaded', function() {
        const userType = "{{ $user->role }}";
        const avatar = document.getElementById('userAvatar');
        const badge = document.getElementById('userTypeBadge');
        
        if (userType === 'shop-owner') {
            avatar.style.background = 'linear-gradient(135deg, #10B981 0%, #059669 100%)';
            badge.className = 'user-badge badge-shop';
            badge.innerHTML = '<iconify-icon icon="lucide:store" width="12" class="mr-1"></iconify-icon> Shop Owner';
        } else {
            avatar.style.background = 'linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%)';
            badge.className = 'user-badge badge-sales';
            badge.innerHTML = '<iconify-icon icon="lucide:briefcase" width="12" class="mr-1"></iconify-icon> Salesperson';
        }
    });
</script>
@endsection