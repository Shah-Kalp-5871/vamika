@extends('layouts.admin')

@section('title', 'Edit Area - Admin')

@php
// Create a dummy area if $area is not passed
$dummyArea = $area ?? (object)[
    'id' => request()->route('id') ?? 1,
    'name' => 'Test Area',
    'pincode' => '400001',
    'delivery_charge' => 50,
    'status' => 'active',
    'description' => 'Test area description',
    'shops_count' => 0,
    'salespersons_count' => 0,
];

$area = $area ?? $dummyArea;

// If ID is still ":id", set it to 1
if ($area->id === ':id') {
    $area->id = 1;
}
@endphp

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
        transition: all 0.2s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
        background: white;
        color: #374151;
        padding: 0.75rem 2rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
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
            <a href="{{ route('admin.areas.index') }}"
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
                    Edit Area
                </h1>
                <p class="text-sm text-slate-500">
                    Update delivery area details
                </p>
            </div>

            <!-- Admin Badge -->
            <span class="hidden sm:inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-50 text-indigo-600 border border-indigo-100">
                Admin Panel
            </span>
        </div>
    </header>

    <main class="p-6">
        <form id="areaForm" class="space-y-6" action="{{ url('/admin/areas/' . ($area->id ?? '')) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label" for="areaName">Area Name *</label>
                <input type="text" id="areaName" name="name" class="form-input" required 
                       placeholder="Enter area name (e.g., Downtown District)"
                       value="{{ old('name', $area->name) }}">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div class="form-group">
                    <label class="form-label" for="areaPincode">Pincode *</label>
                    <input type="text" id="areaPincode" name="pincode" class="form-input" required 
                           placeholder="Enter pincode (e.g., 400001)"
                           value="{{ old('pincode', $area->pincode) }}">
                    @error('pincode')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="areaDeliveryCharge">Delivery Charge (₹) *</label>
                    <input type="number" id="areaDeliveryCharge" name="delivery_charge" class="form-input" required 
                           step="0.01" placeholder="0.00"
                           value="{{ old('delivery_charge', $area->delivery_charge) }}">
                    @error('delivery_charge')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="areaStatus">Status</label>
                <select id="areaStatus" name="status" class="form-input">
                    <option value="active" {{ old('status', $area->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $area->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label" for="areaDescription">Description (Optional)</label>
                <textarea id="areaDescription" name="description" class="form-input" rows="4" 
                          placeholder="Enter area description (e.g., Covers major shopping malls and residential complexes)">{{ old('description', $area->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Additional Stats (Read-only) -->
            <div class="bg-slate-50 border border-slate-200 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Area Statistics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-slate-900">{{ $area->shops_count ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Total Shops</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-slate-900">{{ $area->salespersons_count ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Assigned Salespersons</div>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-between gap-4 pt-6 border-t border-slate-200">
                <div>
                    <button type="button" onclick="deleteArea()" 
                            class="px-4 py-2 text-sm font-medium text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-rose-200 transition-colors flex items-center gap-2">
                        <iconify-icon icon="lucide:trash-2" width="14"></iconify-icon>
                        Delete Area
                    </button>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('admin.areas.index') }}" class="btn-secondary no-underline">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Update Area
                    </button>
                </div>
            </div>
        </form>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 z-50 hidden">
            <div class="fixed inset-0 bg-black/50"></div>
            <div class="fixed inset-0 flex items-center justify-center p-4">
                <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-2 bg-rose-50 rounded-lg">
                            <iconify-icon icon="lucide:alert-triangle" width="24" class="text-rose-600"></iconify-icon>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Delete Area</h3>
                            <p class="text-sm text-slate-500">This action cannot be undone</p>
                        </div>
                    </div>
                    
                    <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
                        <p class="text-sm text-slate-700 mb-2">You're about to delete:</p>
                        <p class="font-medium text-slate-900">{{ $area->name }}</p>
                        <p class="text-xs text-slate-500">{{ $area->pincode }}</p>
                    </div>
                    
                    <div class="text-sm text-slate-600 mb-6">
                        <p class="mb-2"><strong>Warning:</strong> This will affect:</p>
                        <ul class="list-disc list-inside space-y-1 text-slate-500">
                            <li>All shops in this area</li>
                            <li>Salesperson assignments</li>
                            <li>Delivery schedules</li>
                            <li>Historical data</li>
                        </ul>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                                class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg border border-slate-200 transition-colors">
                            Cancel
                        </button>
                            <form id="deleteForm" action="{{ url('/admin/areas/' . ($area->id ?? '')) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition-colors">
                                Delete Area
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('areaForm');
        
        form.addEventListener('submit', function(event) {
            // Client-side validation
            const name = document.getElementById('areaName').value.trim();
            const pincode = document.getElementById('areaPincode').value.trim();
            const deliveryCharge = document.getElementById('areaDeliveryCharge').value;
            
            if (!name || !pincode || !deliveryCharge) {
                event.preventDefault();
                showToast('Please fill in all required fields', 'error');
                return false;
            }
            
            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Updating...';
            submitBtn.disabled = true;
            
            // The form will submit normally via POST/PUT
            // Add a small delay to show loading state
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }, 2000);
        });
    });
    
    function deleteArea() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
    
    function showToast(message, type = 'info') {
        // Remove existing toast
        const existingToast = document.querySelector('.toast');
        if (existingToast) {
            existingToast.remove();
        }
        
        // Create new toast
        const toast = document.createElement('div');
        toast.className = `toast px-4 py-3 rounded-lg border text-sm font-medium flex items-center gap-2`;
        
        // Set colors based on type
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
            <span>${message}</span>
        `;
        
        document.body.appendChild(toast);
        
        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (modal && !modal.classList.contains('hidden')) {
            if (event.target === modal || event.target.classList.contains('fixed')) {
                closeDeleteModal();
            }
        }
    });
</script>
@endsection