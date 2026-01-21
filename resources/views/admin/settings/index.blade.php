@php
$pageConfig = [
    'title' => 'Settings',
    'role' => 'Admin',
    'showBottomNav' => true
];
@endphp

@extends('layouts.admin')

@section('content')
<style>
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
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
            border-radius: 1rem;
            border: 1px solid #E2E8F0;
        }
    }

    .form-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        background: white;
        color: #1E293B;
        font-size: 0.875rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 500;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        border: none;
        cursor: pointer;
        width: 100%;
    }

    .settings-section {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1E293B;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .logo-upload {
        width: 120px;
        height: 120px;
        border: 2px dashed #CBD5E1;
        border-radius: 1rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #F8FAFC;
        margin: 0 auto 2rem;
    }

    .logo-upload:hover {
        border-color: #667eea;
        background: #F1F5F9;
        transform: scale(1.05);
    }
</style>

<div class="main-content">
    <main style="padding: 1.5rem;">
        <!-- Company Logo -->
        <div class="settings-section">
            <h3 class="section-title">
                <iconify-icon icon="lucide:image"></iconify-icon>
                Company Logo
            </h3>
            <div class="logo-upload" onclick="uploadLogo()">
                <iconify-icon icon="lucide:upload" width="32" class="text-slate-400 mb-2"></iconify-icon>
                <span class="text-sm text-slate-500">Upload Logo</span>
                <span class="text-xs text-slate-400 mt-1">JPG, PNG up to 2MB</span>
            </div>
            <p class="text-sm text-slate-500 text-center">Upload your company logo. Recommended size: 400x400px</p>
        </div>

        <!-- Company Information -->
        <div class="settings-section">
            <h3 class="section-title">Company Information</h3>
            
            <div class="form-group">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-input" value="Vamika Enterprise" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Contact Email</label>
                <input type="email" class="form-input" value="info@vamikaenterprise.com" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="tel" class="form-input" value="011-12345678" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">GST Number</label>
                <input type="text" class="form-input" value="07AABCU9603R1ZM" readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">Address</label>
                <textarea class="form-input" rows="3" readonly>Delhi NCR, India</textarea>
            </div>
        </div>

        <!-- Change Password -->
        <div class="settings-section">
            <h3 class="section-title">Change Password</h3>
            
            <div class="form-group">
                <label class="form-label">Current Password</label>
                <input type="password" class="form-input" placeholder="Enter current password">
            </div>
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" class="form-input" placeholder="Enter new password">
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" class="form-input" placeholder="Confirm new password">
            </div>
        </div>

        <!-- Save Button -->
        <button class="btn-primary" onclick="alert('Settings saved!')">Save All Changes</button>
    </main>
</div>

<script>
function uploadLogo() {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    
    input.onchange = function(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                return;
            }
            
            const logoUpload = document.querySelector('.logo-upload');
            logoUpload.innerHTML = `
                <iconify-icon icon="lucide:check-circle" width="32" class="text-green-500 mb-2"></iconify-icon>
                <span class="text-sm text-slate-500">Logo Uploaded</span>
                <span class="text-xs text-slate-400 mt-1">Click to change</span>
            `;
            alert('Logo uploaded successfully!');
        }
    };
    
    input.click();
}
</script>
@endsection