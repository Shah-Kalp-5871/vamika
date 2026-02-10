@extends('layouts.admin')

@section('title', 'Add New Bit - Admin')

@section('styles')
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

    /* Main content container */
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

    /* Back button */
    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        color: #64748B;
        text-decoration: none;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .back-btn:hover {
        background: #F8FAFC;
        color: #475569;
    }

    /* Form styles */
    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #374151;
    }

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: white;
        transition: all 0.2s ease;
        color: #374151;
    }

    .form-input:focus {
        outline: none;
        border-color: #8B5CF6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
    }

    .form-input:hover {
        border-color: #9CA3AF;
    }

    .form-input::placeholder {
        color: #9CA3AF;
    }

    /* Select dropdown styling */
    select.form-input {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 0.75rem center;
        background-repeat: no-repeat;
        background-size: 1rem 1rem;
        padding-right: 2.5rem;
        appearance: none;
    }

    /* Textarea specific styling */
    textarea.form-input {
        resize: vertical;
        min-height: 100px;
    }

    .btn-primary {
        background: #8B5CF6;
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background: #7C3AED;
    }

    .btn-secondary {
        background: white;
        color: #6B7280;
        padding: 0.75rem 1.5rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
        border-color: #9CA3AF;
        text-decoration: none;
    }

    /* Form container styling */
    .form-container {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid #E5E7EB;
        max-width: 600px;
        margin: 0 auto;
    }

    /* Toast notification */
    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="sticky top-0 z-20 bg-white border-b border-slate-100">
        <div class="px-6 py-4 flex items-center gap-4">
            <!-- Back Button -->
            <a href="{{ route('admin.bits.index') }}"
               class="p-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-colors flex items-center justify-center">
                <iconify-icon icon="lucide:arrow-left" width="18" class="text-slate-600"></iconify-icon>
            </a>

            <!-- Logo -->
            <div class="flex items-center gap-2 shrink-0">
                <img 
                    src="{{ asset('assets/images/logo.png') }}" 
                    alt="Vamika Enterprise"
                    class="h-9 w-9 object-contain rounded-md border border-slate-200"
                >
            </div>

            <!-- Title + Subtitle -->
            <div class="flex-1 min-w-0">
                <h1 class="text-lg font-semibold text-slate-900 tracking-tight">
                    {{ isset($bit) ? 'Edit Bit' : 'Add New Bit' }}
                </h1>
                <p class="text-sm text-slate-500">
                    {{ isset($bit) ? 'Update delivery bit details' : 'Configure delivery bit details' }}
                </p>
            </div>

            <!-- Admin Badge -->
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                Admin Panel
            </span>
        </div>
    </header>

    <main class="p-6">
        <div class="form-container">
            <form action="{{ isset($bit) ? route('admin.bits.update', $bit->id) : route('admin.bits.store') }}" method="POST" class="space-y-6">
                @csrf
                @if(isset($bit))
                    @method('PUT')
                @endif
                
                @if ($errors->any())
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <div class="form-group">
                    <label class="form-label" for="bitName">Bit Name *</label>
                    <input type="text" name="name" id="bitName" class="form-input" required 
                           value="{{ old('name', $bit->name ?? '') }}"
                           placeholder="Enter bit name (e.g., Downtown District)">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="form-group">
                        <label class="form-label" for="bitCode">Bit Code *</label>
                        <input type="text" name="code" id="bitCode" class="form-input" required 
                               value="{{ old('code', $bit->code ?? '') }}"
                               placeholder="Enter unique code (e.g., BIT-001)">
                         @error('code') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="bitStatus">Status *</label>
                        <select name="status" id="bitStatus" class="form-input">
                            <option value="active" {{ (old('status', $bit->status ?? '') == 'active') ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ (old('status', $bit->status ?? '') == 'inactive') ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                 <!-- Pincodes (Handling Array as Comma Separated for Simplicity) -->
                <div class="form-group">
                    <label class="form-label" for="pincodes">Pincodes (Comma Separated) *</label>
                     @php
                        $pincodesString = '';
                        if(isset($bit) && is_array($bit->pincodes)) {
                             $pincodesString = implode(', ', $bit->pincodes);
                        }
                     @endphp
                    <input type="text" name="pincodes_string" id="pincodes" class="form-input" 
                           value="{{ old('pincodes_string', $pincodesString) }}"
                           placeholder="e.g., 400001, 400002, 400003">
                    <p class="text-xs text-slate-500 mt-1">Enter valid 6-digit pincodes separated by commas.</p>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 mt-6">
                    <button type="button" onclick="window.location.href='{{ route('admin.bits.index') }}'" class="btn-secondary">
                        Cancel
                    </button>
                    <button type="submit" class="btn-primary">
                        {{ isset($bit) ? 'Update Bit' : 'Save Bit' }}
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    function handleSubmit(event) {
        event.preventDefault();
        
        const name = document.getElementById('areaName').value;
        const pincode = document.getElementById('areaPincode').value;
        const deliveryCharge = document.getElementById('areaDeliveryCharge').value;
        const status = document.getElementById('areaStatus').value;
        const description = document.getElementById('areaDescription').value;
        
        // Validation
        if (!name || !pincode || !deliveryCharge) {
            showToast({
                text: 'Please fill in all required fields',
                type: 'error'
            });
            return;
        }
        
        // Show success message
        showToast({
            text: 'Area saved successfully!',
            type: 'success'
        });
        
        // Redirect back to areas page
        setTimeout(() => {
            window.location.href = '{{ route("admin.bits.index") }}';
        }, 1500);
    }

    function showToast({text, type = 'info'}) {
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `toast px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2`;
        
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };
        
        const icons = {
            success: 'check-circle',
            error: 'x-circle',
            info: 'info'
        };
        
        toast.classList.add(...colors[type].split(' '));
        
        toast.innerHTML = `
            <iconify-icon icon="lucide:${icons[type]}" width="18"></iconify-icon>
            <span>${text}</span>
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
         on type
        const colors = {
            success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
            error: 'bg-rose-50 border-rose-200 text-rose-800',
            info: 'bg-blue-50 border-blue-200 text-blue-800'
        };
        
        const icons = {
            success: 'check-circle',
            error: 'x-circle',
            info: 'info'
        };
        
        toast.classList.add(...colors[type].split(' '));
        
        toast.innerHTML = `
            <iconify-icon icon="lucide:${icons[type]}" width="18"></iconify-icon>
            <span>${text}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection