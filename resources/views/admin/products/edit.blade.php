@extends('layouts.admin')

@section('title', 'Edit Product - Admin')

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

    .btn-danger {
        background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
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

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
        justify-content: space-between;
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

    /* Delete confirmation modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        border-radius: 1rem;
        padding: 2rem;
        max-width: 400px;
        width: 90%;
        animation: slideUp 0.3s ease;
    }

    @keyframes slideUp {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <main class="p-6">
        <form id="productForm" class="space-y-6" method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $product->id }}">

            <!-- Product Header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Edit Product: {{ $product->name }}</h2>
                    <p class="text-slate-500 text-sm">SKU: {{ $product->sku }}</p>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">Edit Product: {{ $product->name }}</h2>
                    <p class="text-slate-500 text-sm">SKU: {{ $product->sku }}</p>
                </div>
            </div>

            <!-- Product Image Upload -->
            <div class="image-upload-container">
                <label class="form-label">Product Image</label>
                <div class="image-upload-box {{ $product->image_path ? 'has-image' : '' }}" id="imageUploadBox">
                    <div class="upload-placeholder" id="uploadPlaceholder" style="{{ $product->image_path ? 'display: none;' : '' }}">
                        <iconify-icon icon="lucide:image" width="48" class="text-slate-400"></iconify-icon>
                        <span>Click to upload product image</span>
                        <span class="text-xs text-slate-500">PNG, JPG, GIF up to 5MB</span>
                    </div>
                    <div class="image-preview-container" id="imagePreviewContainer" style="{{ $product->image_path ? 'display: flex;' : 'display: none;' }}">
                        @if($product->image_path)
                            <img id="imagePreview" class="image-preview" src="{{ asset('storage/' . $product->image_path) }}" alt="Product Preview">
                        @else
                            <img id="imagePreview" class="image-preview" src="" alt="Product Preview" style="display: none;">
                        @endif
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
                <input type="hidden" id="remove_image" name="remove_image" value="0">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="sku">SKU *</label>
                    <input type="text" id="sku" name="sku" class="form-input" required
                        placeholder="Enter product SKU (e.g., SKU001)"
                        value="{{ old('sku', $product->sku) }}">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="name">Product Name *</label>
                    <input type="text" id="name" name="name" class="form-input" required
                        placeholder="Enter product name"
                        value="{{ old('name', $product->name) }}">
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
                        <option value="1" {{ old('category_id', $product->category_id) == '1' ? 'selected' : '' }}>Groceries</option>
                        <option value="2" {{ old('category_id', $product->category_id) == '2' ? 'selected' : '' }}>Food</option>
                        <option value="3" {{ old('category_id', $product->category_id) == '3' ? 'selected' : '' }}>Home Care</option>
                        <option value="4" {{ old('category_id', $product->category_id) == '4' ? 'selected' : '' }}>Personal Care</option>
                        <option value="5" {{ old('category_id', $product->category_id) == '5' ? 'selected' : '' }}>Beverages</option>
                        <option value="6" {{ old('category_id', $product->category_id) == '6' ? 'selected' : '' }}>Dairy</option>
                        <option value="7" {{ old('category_id', $product->category_id) == '7' ? 'selected' : '' }}>Snacks</option>
                        <option value="8" {{ old('category_id', $product->category_id) == '8' ? 'selected' : '' }}>Bakery</option>
                        <option value="9" {{ old('category_id', $product->category_id) == '9' ? 'selected' : '' }}>Biscuits</option>
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="brand">Brand *</label>
                    <input type="text" id="brand" name="brand" class="form-input" required
                        placeholder="Enter brand name"
                        value="{{ old('brand', $product->brand) }}">
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
                        value="{{ old('unit', $product->unit) }}">
                    @error('unit')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="pack_size">Pack Size *</label>
                    <input type="text" id="pack_size" name="pack_size" class="form-input" required
                        placeholder="e.g., 1kg, 500ml, 10 pieces"
                        value="{{ old('pack_size', $product->pack_size) }}">
                    @error('pack_size')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" for="status">Status *</label>
                    <select id="status" name="status" class="form-select" required>
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                            value="{{ old('mrp', $product->mrp) }}">
                        <div class="helper-text">Maximum Retail Price</div>
                        @error('mrp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="price">Selling Price (₹) *</label>
                        <input type="number" id="price" name="price" class="form-input" required step="0.01"
                            placeholder="0.00" min="0"
                            value="{{ old('price', $product->price) }}">
                        <div class="helper-text">Current selling price</div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="discount">Discount (%)</label>
                        <input type="number" id="discount" name="discount" class="form-input" step="0.01"
                            placeholder="0" min="0" max="100" readonly
                            value="{{ old('discount', $product->discount) }}">
                        <div class="helper-text">Auto-calculated from MRP & Price</div>
                    </div>
                </div>
            </div>


            <div class="form-group">
                <label class="form-label" for="description">Product Description</label>
                <textarea id="description" name="description" class="form-textarea" rows="4"
                    placeholder="Enter product description, features, and specifications">{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="action-buttons">
                <div>
                    <button type="button" onclick="showDeleteModal()" class="btn-danger">
                        <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                        Delete Product
                    </button>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Update Product
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal-overlay">
    <div class="modal-content">
        <div class="flex items-center gap-3 mb-4">
            <div class="p-2 bg-rose-100 rounded-lg">
                <iconify-icon icon="lucide:alert-triangle" width="24" class="text-rose-600"></iconify-icon>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-slate-900">Delete Product</h3>
                <p class="text-sm text-slate-500">This action cannot be undone</p>
            </div>
        </div>
        
        <div class="mb-6 p-4 bg-slate-50 rounded-lg border border-slate-200">
            <p class="text-sm text-slate-700 mb-2">You're about to delete:</p>
            <p class="font-medium text-slate-900">{{ $product->name }}</p>
            <p class="text-xs text-slate-500">{{ $product->sku }}</p>
        </div>
        
        <div class="text-sm text-slate-600 mb-6">
            <p class="mb-2"><strong>Warning:</strong> This will permanently delete:</p>
            <ul class="list-disc list-inside space-y-1 text-slate-500">
                <li>Product information</li>
                <li>Pricing history</li>
                <li>Sales data</li>
            </ul>
        </div>
        
        <div class="flex justify-end gap-3">
            <button type="button" onclick="hideDeleteModal()"
                    class="px-4 py-2 text-sm font-medium text-slate-700 hover:text-slate-900 hover:bg-slate-100 rounded-lg border border-slate-200 transition-colors">
                Cancel
            </button>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-rose-600 hover:bg-rose-700 rounded-lg transition-colors">
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        setupFormEventListeners();
        setupImageUpload();
        calculateDiscount(); // Calculate initial discount
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
        document.getElementById('imageUploadBox').addEventListener('click', function(e) {
            if (e.target.closest('.image-actions')) return;
            document.getElementById('imageInput').click();
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
                document.getElementById('imagePreview').style.display = 'block';
                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('imagePreviewContainer').style.display = 'flex';
                document.getElementById('imageUploadBox').classList.add('has-image');
                document.getElementById('remove_image').value = '0'; // Reset remove flag
            };
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('imagePreview').src = '';
        document.getElementById('imagePreview').style.display = 'none';
        document.getElementById('imagePreviewContainer').style.display = 'none';
        document.getElementById('uploadPlaceholder').style.display = 'flex';
        document.getElementById('imageUploadBox').classList.remove('has-image');
        document.getElementById('imageInput').value = '';
        document.getElementById('remove_image').value = '1'; // Set remove flag
    }

    function validateForm() {
        const skuInput = document.getElementById('sku');
        const nameInput = document.getElementById('name');
        const categoryInput = document.getElementById('category_id');
        const brandInput = document.getElementById('brand');
        const mrpInput = document.getElementById('mrp');
        const priceInput = document.getElementById('price');

        if (!skuInput || !nameInput || !categoryInput || !brandInput || !mrpInput || !priceInput) {
            console.error('Some form fields are missing');
            return true; // Allow submission if elements are missing to prevent total lock
        }

        const sku = skuInput.value.trim();
        const name = nameInput.value.trim();
        const category = categoryInput.value;
        const brand = brandInput.value.trim();
        
        const mrp = parseFloat(mrpInput.value) || 0;
        const price = parseFloat(priceInput.value) || 0;

        // Check required fields
        if (!sku || !name || !category || !brand) {
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

        return true;
    }

    function showDeleteModal() {
        document.getElementById('deleteModal').style.display = 'flex';
    }

    function hideDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
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

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (modal && modal.style.display === 'flex') {
            if (event.target === modal) {
                hideDeleteModal();
            }
        }
    });
</script>
@endsection