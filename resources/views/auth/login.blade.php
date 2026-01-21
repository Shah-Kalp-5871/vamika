@extends('layouts.guest')

@section('title', 'Login | Vamika Enterprise')

@section('content')
<div class="login-container">
    <!-- Right side - Login Form -->
    <div class="login-right">
        <div class="login-header">
            <h2>Welcome Back</h2>
            <p class="login-subtitle">Sign in to your account to continue</p>
        </div>
        
        <form class="login-form" method="POST" action="{{ route('login.submit') }}">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <input type="email" class="form-input" id="email" name="email"
                           value="{{ old('email') }}" 
                           placeholder="Enter your email address" required>
                </div>
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-input" id="password" name="password"
                           placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" id="togglePassword">👁️</button>
                </div>
                @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-options">
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            </div>
            
            <button type="submit" class="btn-login">Sign In</button>
            
            <!-- Demo Accounts Section -->
            <div class="demo-section">
                <div class="demo-title">Demo Accounts</div>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 10px;">Use these credentials for testing (Password: demo123)</p>
                
                <div class="demo-accounts">
                    <div class="demo-account">
                        <strong>Admin:</strong><br>
                        admin@vamika.com
                    </div>
                    <div class="demo-account">
                        <strong>Salesperson:</strong><br>
                        sales@vamika.com
                    </div>
                    <div class="demo-account">
                        <strong>Shop Owner:</strong><br>
                        shop@vamika.com
                    </div>
                </div>
            </div>
            
            <div class="login-footer">
                <p>Need an account? <a href="{{ route('register') }}">Contact Administrator</a></p>
            </div>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    
    .login-container {
        display: flex;
        width: 100%;
        max-width: 1000px;
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
        min-height: 600px;
    }
    
    .login-left {
        flex: 1;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    
    .login-left::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -20%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 20px 20px;
        opacity: 0.3;
    }
    
    .brand {
        position: relative;
        z-index: 2;
    }
    
    .brand h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
        font-weight: 700;
    }
    
    .brand-tagline {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 300;
    }
    
    .features-list {
        position: relative;
        z-index: 2;
        margin-top: 40px;
    }
    
    .feature-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .feature-icon {
        background: rgba(255,255,255,0.2);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    
    .feature-text h4 {
        font-size: 1rem;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .feature-text p {
        font-size: 0.9rem;
        opacity: 0.8;
    }
    
    .login-right {
        flex: 1;
        padding: 60px 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .login-header {
        margin-bottom: 40px;
    }
    
    .login-header h2 {
        font-size: 2rem;
        color: #333;
        margin-bottom: 10px;
    }
    
    .login-subtitle {
        color: #666;
        font-size: 1rem;
    }
    
    .login-form {
        width: 100%;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
    }
    
    .input-group {
        position: relative;
    }
    
    .form-input {
        width: 100%;
        padding: 15px 20px;
        border: 2px solid #e1e5e9;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
        background: #f8f9fa;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #667eea;
        background: white;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
    }
    
    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #666;
        cursor: pointer;
        font-size: 18px;
        padding: 5px;
    }
    
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }
    
    .remember-me {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .remember-me input {
        width: 18px;
        height: 18px;
    }
    
    .remember-me label {
        color: #555;
        font-size: 0.95rem;
        cursor: pointer;
    }
    
    .forgot-link {
        color: #667eea;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .forgot-link:hover {
        text-decoration: underline;
    }
    
    .btn-login {
        width: 100%;
        padding: 16px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-login:active {
        transform: translateY(0);
    }
    
    .demo-section {
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 10px;
        border-left: 4px solid #667eea;
    }
    
    .demo-title {
        color: #333;
        margin-bottom: 10px;
        font-weight: 600;
    }
    
    .demo-accounts {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 15px;
    }
    
    .demo-account {
        background: white;
        padding: 12px;
        border-radius: 8px;
        font-size: 0.9rem;
        border: 1px solid #e1e5e9;
    }
    
    .demo-account strong {
        color: #667eea;
    }
    
    .login-footer {
        margin-top: 30px;
        text-align: center;
        color: #666;
        font-size: 0.95rem;
    }
    
    .login-footer a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .login-container {
            flex-direction: column;
            max-width: 450px;
        }
        
        .login-left {
            padding: 40px 30px;
        }
        
        .login-right {
            padding: 40px 30px;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    // Toggle password visibility
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    }
    
    // Check if email was remembered from session
    window.addEventListener('DOMContentLoaded', () => {
        const rememberedEmail = localStorage.getItem('rememberEmail');
        if (rememberedEmail) {
            document.getElementById('email').value = rememberedEmail;
            document.getElementById('remember').checked = true;
        }
    });
</script>
@endsection