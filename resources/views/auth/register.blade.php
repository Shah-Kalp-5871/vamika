@extends('layouts.guest')

@section('title', 'Register - Vamika Enterprise')

@section('styles')
<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 20px;
        background: #f5f5f5;
    }
    
    .register-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        max-width: 500px;
        margin: 0 auto;
        width: 100%;
    }
    
    .back-btn {
        display: inline-block;
        color: #666;
        text-decoration: none;
        margin-bottom: 20px;
    }
    
    .back-btn:hover {
        color: #333;
    }
    
    .register-card h1 {
        margin-bottom: 10px;
        color: #333;
    }
    
    .tagline {
        color: #666;
        margin-bottom: 30px;
    }
    
    .step-indicator {
        display: flex;
        margin-bottom: 30px;
        position: relative;
    }
    
    .step {
        flex: 1;
        text-align: center;
        padding: 10px;
        position: relative;
        z-index: 1;
    }
    
    .step-number {
        width: 30px;
        height: 30px;
        background: #e0e0e0;
        color: #666;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 5px;
    }
    
    .step.active .step-number {
        background: #4CAF50;
        color: white;
    }
    
    .step-line {
        position: absolute;
        top: 25px;
        left: 50px;
        right: 50px;
        height: 2px;
        background: #e0e0e0;
        z-index: 0;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 5px;
        color: #333;
        font-weight: 500;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }
    
    .form-row {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }
    
    .form-row .form-group {
        flex: 1;
        margin-bottom: 0;
    }
    
    .benefits-list {
        background: #e8f5e8;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .benefits-list h4 {
        margin-bottom: 10px;
        color: #333;
    }
    
    .benefit-item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 10px 0;
    }
    
    .referral-section {
        background: #fff8e1;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .referral-section h4 {
        margin-bottom: 5px;
        color: #333;
    }
    
    .referral-section p {
        color: #666;
        margin-bottom: 10px;
        font-size: 14px;
    }
    
    .referral-code {
        display: flex;
        gap: 10px;
        margin: 10px 0;
    }
    
    .referral-input {
        flex: 1;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    
    .copy-btn {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .btn-primary {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 15px;
        border-radius: 5px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .btn-primary:hover {
        background: #45a049;
    }
    
    .login-footer {
        margin-top: 20px;
        text-align: center;
        color: #666;
    }
    
    .login-footer a {
        color: #4CAF50;
        text-decoration: none;
        font-weight: 500;
    }
    
    .login-footer a:hover {
        text-decoration: underline;
    }
</style>
@endsection

@section('content')
<div class="register-container">
    <div class="register-card">
        <a href="{{ url('/') }}" class="back-btn">← Back to Home</a>
        
        <h1>Create Shop Owner Account</h1>
        <p class="tagline">Join Vamika Enterprise and start ordering</p>
        
        <div class="step-indicator">
            <div class="step-line"></div>
            <div class="step active">
                <div class="step-number">1</div>
                <div>Account</div>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <div>Shop Details</div>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <div>Verification</div>
            </div>
        </div>
        
        <div class="benefits-list">
            <h4>🎁 Signup Benefits:</h4>
            <div class="benefit-item">
                <span>✅</span>
                <span>₹500 virtual money on signup</span>
            </div>
            <div class="benefit-item">
                <span>✅</span>
                <span>Earn ₹300 for 1st referral</span>
            </div>
            <div class="benefit-item">
                <span>✅</span>
                <span>Earn ₹200 for 2nd referral</span>
            </div>
            <div class="benefit-item">
                <span>✅</span>
                <span>Access to wholesale prices</span>
            </div>
        </div>
        
        <form id="registerForm" method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" class="form-input" name="first_name" 
                           value="{{ old('first_name') }}" required>
                    @error('first_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" class="form-input" name="last_name" 
                           value="{{ old('last_name') }}" required>
                    @error('last_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" name="email" 
                       value="{{ old('email') }}" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-input" name="phone" 
                       value="{{ old('phone') }}" required>
                @error('phone')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-input" name="password" required>
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" class="form-input" name="password_confirmation" required>
                </div>
            </div>
            
            <div class="referral-section">
                <h4>Have a referral code? (Optional)</h4>
                <p>Enter code to give your friend ₹300 bonus</p>
                <div class="referral-code">
                    <input type="text" class="referral-input" name="referral_code"
                           placeholder="Enter referral code" value="{{ old('referral_code') }}">
                    <button type="button" class="copy-btn">Apply</button>
                </div>
                @error('referral_code')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" name="terms" required {{ old('terms') ? 'checked' : '' }}>
                    I agree to Terms & Conditions and Privacy Policy
                </label>
                @error('terms')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn-primary" style="width: 100%;">
                Create Account & Get ₹500 Bonus
            </button>
        </form>
        
        <div class="login-footer">
            <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        // Laravel will handle the form submission and validation
        // You can add additional client-side validation here if needed
        
        // For demo purposes only (remove in production)
        // This simulates the demo behavior from the original code
        if (window.location.href.includes('demo=true')) {
            e.preventDefault();
            Swal.fire({
                icon: 'success',
                title: 'Account Created',
                text: 'Account created successfully! ₹500 credited to your wallet.',
                confirmButtonText: 'Login Now'
            }).then(() => {
                window.location.href = "{{ route('login') }}?role=shop-owner";
            });
        }
    });
    
    // Apply referral code button
    document.querySelector('.copy-btn').addEventListener('click', function() {
        const referralInput = document.querySelector('.referral-input');
        if (referralInput.value.trim()) {
            Swal.fire({
                icon: 'success',
                title: 'Applied',
                text: 'Referral code applied successfully!',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
</script>
@endsection