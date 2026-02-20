@extends('layouts.admin')

@section('content')
@php
$pageConfig = [
    'title' => 'Add New User',
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

    /* User Type Selection */
    .user-type-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin: 2rem 1.5rem;
    }

    .user-type-card {
        border: 2px solid #E2E8F0;
        border-radius: 1rem;
        padding: 2rem 1.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
    }

    .user-type-card:hover {
        border-color: #667eea;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(102, 126, 234, 0.1);
    }

    .user-type-card.active {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
    }

    .user-type-icon {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
    }

    .user-type-card.shop-owner .user-type-icon {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    .user-type-card.salesperson .user-type-icon {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
    }

    .user-type-card h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 0.5rem;
    }

    .user-type-card p {
        font-size: 0.875rem;
        color: #64748B;
        line-height: 1.5;
    }

    /* Form Styles */
    .form-container {
        padding: 0 1.5rem 2rem;
        display: none;
    }

    .form-container.active {
        display: block;
        animation: slideUp 0.5s ease forwards;
    }

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

    .btn-back {
        background: transparent;
        color: #374151;
        font-weight: 500;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-back:hover {
        background: #F3F4F6;
    }
</style>

<div class="main-content">
    <main>
        <!-- User Type Selection -->
        <div class="user-type-container" id="userTypeSelection">
            <div class="user-type-card shop-owner" onclick="selectUserType('shop-owner', this)">
                <div class="user-type-icon">
                    <iconify-icon icon="lucide:store" width="32" style="color: white;"></iconify-icon>
                </div>
                <h3>Shop Owner</h3>
                <p>Create account for shop owners who manage their stores and inventory</p>
            </div>

            <div class="user-type-card salesperson" onclick="selectUserType('salesperson', this)">
                <div class="user-type-icon">
                    <iconify-icon icon="lucide:briefcase" width="32" style="color: white;"></iconify-icon>
                </div>
                <h3>Salesperson</h3>
                <p>Create account for salespersons who visit shops and manage orders</p>
            </div>
        </div>

        <!-- Shop Owner Form -->
        <div class="form-container" id="shopOwnerForm">
            <h2 class="form-title">Add Shop Owner</h2>
            <form id="shopOwnerFormData" method="POST" action="{{ url('/admin/users') }}">
                @csrf
                <input type="hidden" name="user_type" value="shop-owner">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Bit <span class="form-required">*</span></label>
                        <select class="form-input" name="bit_id" required>
                            <option value="">Select bit</option>
                            @foreach($bits as $bit)
                                <option value="{{ $bit->id }}" {{ old('bit_id') == $bit->id ? 'selected' : '' }}>
                                    {{ $bit->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('bit_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <a href="{{ route('admin.bits.index') }}" class=" text-blue-500 hover:text-blue-600 padding-16px">Add New Bit</a>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Shop Name <span class="form-required">*</span></label>
                        <input type="text" class="form-input" name="shop_name" placeholder="Enter shop name" 
                               value="{{ old('shop_name') }}" required>
                        @error('shop_name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Full Name <span class="form-required">*</span></label>
                        <input type="text" class="form-input" name="name" placeholder="Enter full name" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="form-required">*</span></label>
                        <input type="email" class="form-input" name="email" placeholder="Enter email address" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="form-required">*</span></label>
                        <input type="tel" class="form-input" name="phone" placeholder="Enter 10-digit number" 
                               value="{{ old('phone') }}" maxlength="10" pattern="[0-9]{10}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" required>
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="form-group">
                        <label class="form-label">Address <span class="form-required">*</span></label>
                        <input type="text" class="form-input" name="address" placeholder="Enter full address"
                               value="{{ old('address') }}" required>
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password <span class="form-required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" class="form-input pr-10" name="password" placeholder="Set password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password <span class="form-required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" class="form-input pr-10" name="password_confirmation" 
                                   placeholder="Confirm password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="goBackToSelection()">
                        <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                        Back
                    </button>
                    <button type="submit" class="btn-primary">
                        <iconify-icon icon="lucide:save" width="16"></iconify-icon>
                        Create Shop Owner
                    </button>
                </div>
            </form>
        </div>

        <!-- Salesperson Form -->
        <div class="form-container" id="salespersonForm">
            <h2 class="form-title">Add Salesperson</h2>
            <form id="salespersonFormData" method="POST" action="{{ url('/admin/users') }}">
                @csrf
                <input type="hidden" name="user_type" value="salesperson">
                
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name <span class="form-required">*</span></label>
                        <input type="text" class="form-input" name="name" placeholder="Enter full name" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="form-required">*</span></label>
                        <input type="email" class="form-input" name="email" placeholder="Enter email address" 
                               value="{{ old('email') }}" required>
                        @error('email')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Phone Number <span class="form-required">*</span></label>
                        <input type="tel" class="form-input" name="phone" placeholder="Enter 10-digit number" 
                               value="{{ old('phone') }}" maxlength="10" pattern="[0-9]{10}"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" required>
                        @error('phone')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Employee ID</label>
                        <input type="text" class="form-input" name="employee_id" placeholder="Enter employee ID"
                               value="{{ old('employee_id') }}">
                        @error('employee_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>





                    <div class="form-group">
                        <label class="form-label">Address</label>
                        <input type="text" class="form-input" name="address" placeholder="Enter address"
                               value="{{ old('address') }}">
                        @error('address')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password <span class="form-required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" class="form-input pr-10" name="password" placeholder="Set password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                        @error('password')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password <span class="form-required">*</span></label>
                        <div class="password-wrapper">
                            <input type="password" class="form-input pr-10" name="password_confirmation" 
                                   placeholder="Confirm password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword(this)">
                                <iconify-icon icon="lucide:eye" width="20"></iconify-icon>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn-secondary" onclick="goBackToSelection()">
                        <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                        Back
                    </button>
                    <button type="submit" class="btn-primary">
                        <iconify-icon icon="lucide:save" width="16"></iconify-icon>
                        Create Salesperson
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

    function selectUserType(type, element) {
        selectedUserType = type;
        
        // Remove active class from all cards
        document.querySelectorAll('.user-type-card').forEach(card => {
            card.classList.remove('active');
        });
        
        // Add active class to clicked card
        element.classList.add('active');
        
        // Hide selection and show appropriate form
        document.getElementById('userTypeSelection').style.display = 'none';
        
        if (type === 'shop-owner') {
            document.getElementById('shopOwnerForm').classList.add('active');
            // Set the hidden input value
            document.querySelector('#shopOwnerFormData input[name="user_type"]').value = 'shop-owner';
        } else if (type === 'salesperson') {
            document.getElementById('salespersonForm').classList.add('active');
            // Set the hidden input value
            document.querySelector('#salespersonFormData input[name="user_type"]').value = 'salesperson';
        }
    }

    function goBackToSelection() {
        // Hide all forms
        document.getElementById('shopOwnerForm').classList.remove('active');
        document.getElementById('salespersonForm').classList.remove('active');
        
        // Show selection
        document.getElementById('userTypeSelection').style.display = 'grid';
        
        // Reset selection
        selectedUserType = '';
        document.querySelectorAll('.user-type-card').forEach(card => {
            card.classList.remove('active');
        });
    }

    // Handle shop owner form submission
    document.getElementById('shopOwnerFormData').addEventListener('submit', function(e) {
        // Laravel will handle validation and form submission
        // Client-side validation for password match
        const password = this.querySelector('input[name="password"]').value;
        const confirmPassword = this.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Passwords do not match!'
            });
            return;
        }
    });

    // Handle salesperson form submission
    document.getElementById('salespersonFormData').addEventListener('submit', function(e) {
        // Laravel will handle validation and form submission
        // Client-side validation for password match
        const password = this.querySelector('input[name="password"]').value;
        const confirmPassword = this.querySelector('input[name="password_confirmation"]').value;
        
        if (password !== confirmPassword) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Passwords do not match!'
            });
            return;
        }
    });

    // Check if there are validation errors and show appropriate form
    document.addEventListener('DOMContentLoaded', function() {
        const oldUserType = "{{ old('user_type') }}";
        
        if (oldUserType === 'shop-owner') {
            // Simulate clicking on shop owner card
            const shopOwnerCard = document.querySelector('.user-type-card.shop-owner');
            selectUserType('shop-owner', shopOwnerCard);
        } else if (oldUserType === 'salesperson') {
            // Simulate clicking on salesperson card
            const salespersonCard = document.querySelector('.user-type-card.salesperson');
            selectUserType('salesperson', salespersonCard);
        }
    });
</script>
@endsection