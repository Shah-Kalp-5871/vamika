@extends('layouts.admin')

@section('title', 'Add New Product - Admin')

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
            max-width: 48rem;
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

    .form-input, .form-textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        background-color: white;
    }

    .form-input:focus, .form-textarea:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #D1D5DB;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        background-color: white;
        transition: all 0.2s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: #6366F1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* Buttons */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
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
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    /* Grid layout */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .grid-2 {
            grid-template-columns: 1fr 1fr;
        }
    }

    .grid-3 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .grid-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }
    }

    /* Helper text */
    .helper-text {
        font-size: 0.75rem;
        color: #6B7280;
        margin-top: 0.25rem;
    }

    /* Image upload styles */
    .image-upload-container {
        margin-bottom: 1.5rem;
    }

    .image-upload-box {
        border: 2px dashed #D1D5DB;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        background-color: #F9FAFB;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-upload-box:hover {
        border-color: #6366F1;
        background-color: #F8FAFC;
    }

    .image-upload-box.has-image {
        border-style: solid;
        border-color: #10B981;
        background-color: #F0FDF4;
        padding: 1rem;
    }

    .upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        color: #6B7280;
    }

    .upload-placeholder svg {
        width: 3rem;
        height: 3rem;
        color: #9CA3AF;
    }

    .image-preview-container {
        display: none;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
        width: 100%;
    }

    .image-preview {
        max-width: 300px;
        max-height: 300px;
        object-fit: contain;
        border-radius: 0.5rem;
        border: 1px solid #E5E7EB;
        background-color: white;
        padding: 0.5rem;
    }

    .image-actions {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .change-image-btn {
        background: #3B82F6;
        color: white;
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .change-image-btn:hover {
        background: #2563EB;
    }

    .remove-image-btn {
        background: #EF4444;
        color: white;
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .remove-image-btn:hover {
        background: #DC2626;
    }

    /* Product details sections */
    .details-section {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 1rem;
    }

    .details-section-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: #1F2937;
        margin-bottom: 1rem;
    }

    /* Action buttons container */
    .action-buttons {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        padding-top: 1.5rem;
        border-top: 1px solid #E5E7EB;
    }

    /* Toast notification */
    .toast {
        position: fixed;
        top: 1rem;
        right: 1rem;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        color: white;
        font-weight: 500;
        z-index: 1000;
        animation: slideIn 0.3s ease-out;
    }

    .toast-success {
        background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    }

    .toast-error {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    }

    .toast-info {
        background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
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

    /* Responsive adjustments */
    @media (max-width: 640px) {
        .main-content {
            margin: 0;
            border-radius: 0;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons a,
        .action-buttons button {
            width: 100%;
            text-align: center;
        }
        
        .image-preview {
            max-width: 200px;
            max-height: 200px;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-6">
        <form id="productForm" class="space-y-6" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <!-- Product Image Upload -->
            <div class="image-upload-container">
                <label class="form-label">Product Image</label>
                <div class="image-upload-box" id="imageUploadBox">
                    <div class="upload-placeholder" id="uploadPlaceholder">
                        <iconify-icon icon="lucide:image" width="48" class="text-slate-400"></iconify-icon>
                        <span>Click to upload product image</span>
                        <span class="text-xs text-slate-500">PNG, JPG, GIF up to 5MB</span>
                    </div>
                    <div class="image-preview-container" id="imagePreviewContainer">
                        <img id="imagePreview" class="image-preview" src="" alt="Product Preview">
                        <div class="image-actions">
                            <button type="button" class="change-image-btn" onclick="document.getElementById('imageInput').click()">
                                Change Image
                            </button>
                            <button type="button" class="remove-image-btn" onclick="removeImage()">
                                Remove
                            </button>
                        </div>
                    </div>
                </div>
                <input type="file" id="imageInput" name="image" accept="image/*" style="display: none;" onchange="handleImageUpload(event)">
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="sku">SKU *</label>
                    <input type="text" id="sku" name="sku" class="form-input" required
                        placeholder="Enter product SKU (e.g., SKU001)"
                        value="{{ old('sku') }}">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="form-input" required
                        placeholder="Enter product name"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="category_id">Category *</label>
                    <select id="category_id" name="category_id" class="form-select" required>
                        <option value="">Select Category</option>
                        <option value="1" {{ old('category_id') == '1' ? 'selected' : '' }}>Groceries</option>
                        <option value="2" {{ old('category_id') == '2' ? 'selected' : '' }}>Food</option>
                        <option value="3" {{ old('category_id') == '3' ? 'selected' : '' }}>Home Care</option>
                        <option value="4" {{ old('category_id') == '4' ? 'selected' : '' }}>Personal Care</option>
                        <option value="5" {{ old('category_id') == '5' ? 'selected' : '' }}>Beverages</option>
                        <option value="6" {{ old('category_id') == '6' ? 'selected' : '' }}>Dairy</option>
                        <option value="7" {{ old('category_id') == '7' ? 'selected' : '' }}>Snacks</option>
                        <option value="8" {{ old('category_id') == '8' ? 'selected' : '' }}>Bakery</option>
                        <option value="9" {{ old('category_id') == '9' ? 'selected' : '' }}>Biscuits</option>
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="brand">Brand *</label>
                    <input type="text" id="brand" name="brand" class="form-input" required
                        placeholder="Enter brand name"
                        value="{{ old('brand') }}">
                    @error('brand')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label" for="unit">Unit *</label>
                    <input type="text" id="unit" name="unit" class="form-input" required
                        placeholder="e.g., kg, L, piece"
                        value="{{ old('unit') }}">
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="pack_size">Pack Size *</label>
                    <input type="text" id="pack_size" name="pack_size" class="form-input" required
                        placeholder="e.g., 1kg, 500ml, 10 pieces"
                        value="{{ old('pack_size') }}">
                    @error('pack_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="status">Status *</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : 'selected' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="details-section">
                <h3 class="details-section-title">Pricing</h3>
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label" for="mrp">MRP (₹) *</label>
                        <input type="number" id="mrp" name="mrp" class="form-input" required step="0.01"
                            placeholder="0.00" min="0"
                            value="{{ old('mrp') }}">
                        <div class="helper-text">Maximum Retail Price</div>
                        @error('mrp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="price">Selling Price (₹) *</label>
                        <input type="number" id="price" name="price" class="form-input" required step="0.01"
                            placeholder="0.00" min="0"
                            value="{{ old('price') }}">
                        <div class="helper-text">Current selling price</div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="discount">Discount (%)</label>
                        <input type="number" id="discount" name="discount" class="form-input" step="0.01"
                            placeholder="0" min="0" max="100" readonly
                            value="{{ old('discount') }}">
                        <div class="helper-text">Auto-calculated from MRP & Price</div>
                    </div>
                </div>
            </div>

            <div class="details-section">
                <h3 class="details-section-title">Stock Information</h3>
                <div class="grid-3">
                    <div class="form-group">
                        <label class="form-label" for="stock">Current Stock *</label>
                        <input type="number" id="stock" name="stock" class="form-input" required min="0"
                            placeholder="0"
                            value="{{ old('stock') }}">
                        <div class="helper-text">Available quantity</div>
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="min_stock">Minimum Stock</label>
                        <input type="number" id="min_stock" name="min_stock" class="form-input" min="0"
                            placeholder="0" value="{{ old('min_stock', 10) }}">
                        <div class="helper-text">Low stock threshold</div>
                        @error('min_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="max_stock">Maximum Stock</label>
                        <input type="number" id="max_stock" name="max_stock" class="form-input" min="0"
                            placeholder="0" value="{{ old('max_stock', 100) }}">
                        <div class="helper-text">Stock capacity</div>
                        @error('max_stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Product Description</label>
                <textarea id="description" name="description" class="form-textarea" rows="4"
                    placeholder="Enter product description, features, and specifications">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="action-buttons">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Save Product
                </button>
            </div>
        </form>
    </main>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupFormEventListeners();
        setupImageUpload();
    });

    function setupFormEventListeners() {
        // Auto-calculate discount when MRP or Price changes
        document.getElementById('mrp').addEventListener('input', calculateDiscount);
        document.getElementById('price').addEventListener('input', calculateDiscount);

        // Form validation before submission
        document.getElementById('productForm').addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
    }

    function setupImageUpload() {
        document.getElementById('imageUploadBox').addEventListener('click', function() {
            if (!document.getElementById('imagePreviewContainer').style.display || 
                document.getElementById('imagePreviewContainer').style.display === 'none') {
                document.getElementById('imageInput').click();
            }
        });
    }

    function calculateDiscount() {
        const mrp = parseFloat(document.getElementById('mrp').value) || 0;
        const price = parseFloat(document.getElementById('price').value) || 0;
        
        if (mrp > 0 && price <= mrp) {
            const discount = Math.round(((mrp - price) / mrp) * 100);
            document.getElementById('discount').value = discount;
        } else if (price > mrp && mrp > 0) {
            document.getElementById('price').value = mrp;
            document.getElementById('discount').value = 0;
            showToast('Selling price cannot be higher than MRP', 'error');
        } else {
            document.getElementById('discount').value = 0;
        }
    }

    function handleImageUpload(event) {
        const file = event.target.files[0];
        if (file) {
            // Check file size (5MB limit)
            if (file.size > 5 * 1024 * 1024) {
                showToast('File size should be less than 5MB', 'error');
                document.getElementById('imageInput').value = '';
                return;
            }

            // Check file type
            if (!file.type.match('image.*')) {
                showToast('Please select an image file', 'error');
                document.getElementById('imageInput').value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const imageData = e.target.result;
                document.getElementById('imagePreview').src = imageData;
                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('imagePreviewContainer').style.display = 'flex';
                document.getElementById('imageUploadBox').classList.add('has-image');
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('imagePreview').src = '';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('uploadPlaceholder').style.display = 'flex';
        document.getElementById('imageUploadBox').classList.remove('has-image');
        document.getElementById('imageInput').value = '';
    }

    function validateForm() {
        const sku = document.getElementById('sku').value.trim();
        const name = document.getElementById('name').value.trim();
        const category = document.getElementById('category_id').value;
        const brand = document.getElementById('brand').value.trim();
        const unit = document.getElementById('unit').value.trim();
        const packSize = document.getElementById('pack_size').value.trim();
        const mrp = parseFloat(document.getElementById('mrp').value) || 0;
        const price = parseFloat(document.getElementById('price').value) || 0;
        const stock = parseInt(document.getElementById('stock').value) || 0;

        // Check required fields
        if (!sku || !name || !category || !brand || !unit || !packSize) {
            showToast('Please fill in all required fields', 'error');
            return false;
        }

        // Check pricing
        if (mrp <= 0 || price <= 0) {
            showToast('MRP and Selling Price must be greater than 0', 'error');
            return false;
        }

        if (price > mrp) {
            showToast('Selling price cannot be higher than MRP', 'error');
            return false;
        }

        // Check stock
        if (stock < 0) {
            showToast('Stock cannot be negative', 'error');
            return false;
        }

        return true;
    }

    function showToast(message, type = 'info') {
        // Remove existing toasts
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        // Create new toast
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        
        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-20px)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>
@endsection