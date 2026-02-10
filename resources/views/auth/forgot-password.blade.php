@extends('layouts.guest')

@section('title', 'Forgot Password - Vamika Enterprise')

@section('styles')
<style>
    .forgot-container {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 20px;
        background: #f5f5f5;
    }
    
    .forgot-card {
        background: white;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        max-width: 400px;
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
    
    .forgot-card h1 {
        margin-bottom: 20px;
        color: #333;
    }
    
    .instruction-box {
        background: #e3f2fd;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
        color: #333;
    }
    
    .form-group {
        margin-bottom: 20px;
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
        width: 100%;
    }
    
    .btn-primary:hover {
        background: #45a049;
    }
    
    .otp-section {
        margin-top: 20px;
    }
    
    .success-message {
        background: #e8f5e8;
        color: #2E7D32;
        padding: 15px;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .otp-inputs {
        display: flex;
        gap: 10px;
        margin: 20px 0;
        justify-content: center;
    }
    
    .otp-input {
        width: 50px;
        height: 60px;
        text-align: center;
        font-size: 24px;
        border: 2px solid #ddd;
        border-radius: 8px;
    }
    
    .otp-input:focus {
        border-color: #4CAF50;
        outline: none;
    }
    
    .resend-otp {
        text-align: center;
        margin: 20px 0;
        font-size: 14px;
        color: #666;
    }
    
    .resend-otp a {
        color: #4CAF50;
        text-decoration: none;
        font-weight: 500;
    }
    
    .resend-otp a:hover {
        text-decoration: underline;
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
<div class="forgot-container">
    <div class="forgot-card">
        <a href="{{ url('/') }}" class="back-btn">← Back to Home</a>
        
        <h1>Reset Password</h1>
        
        <div class="instruction-box">
            <p>Enter your email address and we'll send you an OTP to reset your password.</p>
        </div>
        
        @if (session('status'))
            <div class="success-message">
                {{ session('status') }}
            </div>
        @endif
        
        <form id="forgotForm" method="POST" action="{{ route('password.email') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" name="email" id="resetEmail" 
                       value="{{ old('email') }}" 
                       placeholder="Enter your registered email" required>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <button type="submit" class="btn-primary">
                Send Password Reset Link
            </button>
        </form>
        
        <!-- OTP Section (for demo/alternative implementation) -->
        <div class="otp-section" id="otpSection" style="display: none;">
            <div class="success-message" id="successMessage">
                OTP sent to your email. Enter it below.
            </div>
            
            <div class="form-group">
                <label class="form-label">Enter OTP</label>
                <div class="otp-inputs">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                    <input type="text" class="otp-input" maxlength="1">
                </div>
            </div>
            
            <div class="resend-otp">
                Didn't receive OTP? <a href="#" id="resendLink">Resend</a>
                <br>
                <span id="timer">(00:60)</span>
            </div>
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" class="form-input" id="newPassword">
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-input" id="confirmPassword">
            </div>
            
            <button type="button" class="btn-primary" onclick="resetPassword()">
                Reset Password
            </button>
        </div>
        
        <div class="login-footer">
            <p>Remember your password? <a href="{{ route('login') }}">Login here</a></p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Demo functionality - only for demonstration
    // In production, use Laravel's built-in password reset
    @if(config('app.debug'))
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            // For demo purposes only, show OTP section
            e.preventDefault();
            
            const email = document.getElementById('resetEmail').value;
            
            if (email) {
                // Show OTP section for demo
                document.getElementById('successMessage').style.display = 'block';
                document.getElementById('otpSection').style.display = 'block';
                
                // Start timer
                startTimer();
                
                // In real app, this would be handled by Laravel's password reset
                console.log('Password reset requested for:', email);
            }
        });
        
        function startTimer() {
            let time = 60;
            const timerElement = document.getElementById('timer');
            const resendLink = document.getElementById('resendLink');
            
            resendLink.style.pointerEvents = 'none';
            resendLink.style.opacity = '0.5';
            
            const timer = setInterval(() => {
                const minutes = Math.floor(time / 60);
                const seconds = time % 60;
                
                timerElement.textContent = `(${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')})`;
                
                if (time <= 0) {
                    clearInterval(timer);
                    resendLink.style.pointerEvents = 'auto';
                    resendLink.style.opacity = '1';
                    timerElement.textContent = '';
                }
                
                time--;
            }, 1000);
            
            // Resend OTP functionality
            resendLink.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Resent',
                    text: 'OTP resent to your email.',
                    timer: 2000,
                    showConfirmButton: false
                });
                startTimer();
            });
        }
        
        function resetPassword() {
            Swal.fire({
                icon: 'success',
                title: 'Password Reset',
                text: 'Password reset successfully!',
                showConfirmButton: false,
                timer: 1500
            }).then(() => {
                window.location.href = "{{ route('login') }}";
            });
        }
        
        // Auto-focus next OTP input
        document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });
            
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '' && index > 0) {
                    inputs[index - 1].focus();
                }
            });
        });
    @endif
</script>
@endsection