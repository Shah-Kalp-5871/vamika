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
        <form action="{{ route('admin.settings.update') }}" method="POST">
            @csrf
            
            @if(session('success'))
                <div class="mb-4 p-4 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Salesperson Working Hours -->
            <div class="settings-section">
                <h3 class="section-title">
                    <iconify-icon icon="lucide:clock" class="mr-2"></iconify-icon>
                    Salesperson Working Hours (Global)
                </h3>
                <p class="text-xs text-slate-500 mb-4 italic">These hours will apply to all salespersons for "View-Only" mode enforcement.</p>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Global Start Time (IST)</label>
                        <input type="time" name="salesperson_work_start" class="form-input" 
                               value="{{ $settings['salesperson_work_start'] ?? '09:00' }}">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Global End Time (IST)</label>
                        <input type="time" name="salesperson_work_end" class="form-input" 
                               value="{{ $settings['salesperson_work_end'] ?? '18:00' }}">
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="settings-section">
                <h3 class="section-title">Company Information</h3>
                
                <div class="form-group">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-input" value="{{ $settings['company_name'] ?? 'Vamika Enterprise' }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-input" value="{{ $settings['contact_email'] ?? 'info@vamikaenterprise.com' }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" name="phone_number" class="form-input" value="{{ $settings['phone_number'] ?? '011-12345678' }}">
                </div>
            </div>

            <!-- Save Button -->
            <button type="submit" class="btn-primary">Save All Changes</button>
        </form>
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