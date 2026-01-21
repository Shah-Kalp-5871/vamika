@extends('layouts.admin')

@section('title', 'Update Stock - Admin')

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

    /* Product info card */
    .product-info-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
    }

    .info-label {
        color: #64748B;
    }

    .info-value {
        color: #1E293B;
        font-weight: 500;
    }
    
    /* Stock status */
    .stock-status {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .stock-in {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }
    
    .stock-low {
        background: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }
    
    .stock-out {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }
    
    /* Stock indicator */
    .stock-indicator {
        margin-top: 1rem;
        padding: 1rem;
        background: white;
        border: 1px solid #E5E7EB;
        border-radius: 0.5rem;
    }
    
    .stock-bar {
        height: 8px;
        background: #E5E7EB;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .stock-level {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .stock-full {
        background: #10B981;
    }
    
    .stock-medium {
        background: #F59E0B;
    }
    
    .stock-low {
        background: #EF4444;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="flex items-start gap-3">
            <!-- Logo -->
            <img
                src="{{ asset('assets/images/logo.png') }}"
                alt="Vamika Enterprise"
                class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1"
            >

            <!-- Content -->
            <div class="flex-1">
                <a href="{{ route('admin.products.index') }}" class="back-btn mb-3 inline-flex items-center gap-1">
                    <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                    Back to Products
                </a>

                <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
                    Update Stock
                </h1>

                <h2 class="text-lg font-normal text-slate-500 tracking-tight">
                    Manage inventory levels for products
                </h2>
            </div>
        </div>
    </header>

    <main class="p-6">
        <!-- Product Information -->
        <div class="product-info-card">
            <div class="info-row">
                <span class="info-label">Product Name:</span>
                <span class="info-value" id="productName">Loading...</span>
            </div>
            <div class="info-row">
                <span class="info-label">Category:</span>
                <span class="info-value" id="productCategory">Loading...</span>
            </div>
            <div class="info-row">
                <span class="info-label">Unit:</span>
                <span class="info-value" id="productUnit">Loading...</span>
            </div>
            <div class="info-row">
                <span class="info-label">Price:</span>
                <span class="info-value" id="productPrice">Loading...</span>
            </div>
            <div class="info-row">
                <span class="info-label">Current Status:</span>
                <span class="info-value" id="stockStatus">Loading...</span>
            </div>
        </div>
        
        <!-- Current Stock Display -->
        <div class="stock-indicator">
            <div class="flex justify-between items-center">
                <span class="font-medium text-slate-900">Current Stock Level</span>
                <span class="text-2xl font-bold" id="currentStockDisplay">0</span>
            </div>
            <div class="stock-bar">
                <div id="stockLevelBar" class="stock-level" style="width: 0%"></div>
            </div>
            <div class="flex justify-between text-sm text-slate-500 mt-2">
                <span>0</span>
                <span id="maxStock">100</span>
            </div>
        </div>

        <!-- Stock Update Form -->
        <form id="stockForm" class="space-y-6" onsubmit="handleSubmit(event)">
            @csrf
            <input type="hidden" id="productId" value="{{ request('id') }}">
            
            <div class="form-group">
                <label class="form-label" for="newStock">New Stock Quantity *</label>
                <div class="flex items-center gap-3">
                    <button type="button" onclick="adjustStock(-10)" class="px-4 py-2 text-lg font-bold text-slate-600 hover:bg-slate-100 rounded-lg border border-slate-200">
                        -10
                    </button>
                    <button type="button" onclick="adjustStock(-1)" class="px-4 py-2 text-lg font-bold text-slate-600 hover:bg-slate-100 rounded-lg border border-slate-200">
                        -1
                    </button>
                    <input type="number" id="newStock" class="form-input text-center flex-1" required 
                           min="0" placeholder="Enter quantity">
                    <button type="button" onclick="adjustStock(1)" class="px-4 py-2 text-lg font-bold text-slate-600 hover:bg-slate-100 rounded-lg border border-slate-200">
                        +1
                    </button>
                    <button type="button" onclick="adjustStock(10)" class="px-4 py-2 text-lg font-bold text-slate-600 hover:bg-slate-100 rounded-lg border border-slate-200">
                        +10
                    </button>
                </div>
                <div class="helper-text mt-2">Enter the updated stock quantity. Use buttons for quick adjustments.</div>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="stockReason">Reason for Update (Optional)</label>
                <select id="stockReason" class="form-input">
                    <option value="">Select reason</option>
                    <option value="restock">Restock from supplier</option>
                    <option value="sale">Sale/Order fulfillment</option>
                    <option value="damage">Damaged/Expired items</option>
                    <option value="adjustment">Inventory adjustment</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="stockNotes">Notes (Optional)</label>
                <textarea id="stockNotes" class="form-input" rows="3" 
                          placeholder="Add any notes about this stock update"></textarea>
            </div>
            
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                <h3 class="font-semibold text-blue-900 mb-3">Stock Status Guidelines</h3>
                <div class="space-y-2 text-sm text-blue-800">
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:check-circle" width="16" class="text-blue-600 mt-0.5"></iconify-icon>
                        <span><strong>In Stock:</strong> Stock above 20 units - Product is readily available</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:alert-triangle" width="16" class="text-amber-500 mt-0.5"></iconify-icon>
                        <span><strong>Low Stock:</strong> Stock between 1-20 units - Consider restocking soon</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:x-circle" width="16" class="text-rose-500 mt-0.5"></iconify-icon>
                        <span><strong>Out of Stock:</strong> Stock at 0 units - Urgent restock needed</span>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end gap-4 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.products.index') }}" class="btn-secondary no-underline">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    Update Stock
                </button>
            </div>
        </form>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Get product ID from URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = parseInt(urlParams.get('id'));
    
    // Sample products data
    const sampleProducts = [
        {
            id: 1,
            name: 'Aashirvaad Atta',
            category: 'Groceries',
            unit: '5 kg',
            price: 420,
            stock: 150,
            description: 'Premium quality whole wheat atta'
        },
        {
            id: 2,
            name: 'Fortune Oil',
            category: 'Groceries',
            unit: '1 liter',
            price: 210,
            stock: 85,
            description: 'Pure refined sunflower oil'
        },
        {
            id: 3,
            name: 'Maggi Noodles',
            category: 'Food',
            unit: 'Pack of 4',
            price: 70,
            stock: 200,
            description: 'Instant noodles, masala flavor'
        },
        {
            id: 4,
            name: 'Tata Salt',
            category: 'Groceries',
            unit: '1 kg',
            price: 28,
            stock: 5,
            description: 'Iodized salt'
        },
        {
            id: 5,
            name: 'Parle-G Biscuits',
            category: 'Food',
            unit: '300 gm',
            price: 50,
            stock: 0,
            description: 'Glucose biscuits'
        }
    ];
    
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        if (productId) {
            loadProductInfo(productId);
        } else {
            document.getElementById('productName').textContent = 'Product Not Found';
        }
    });
    
    function loadProductInfo(id) {
        const product = sampleProducts.find(p => p.id === id);
        
        if (!product) {
            document.getElementById('productName').textContent = 'Product Not Found';
            return;
        }
        
        // Update product info
        document.getElementById('productName').textContent = product.name;
        document.getElementById('productCategory').textContent = product.category;
        document.getElementById('productUnit').textContent = product.unit;
        document.getElementById('productPrice').textContent = `₹${product.price}`;
        
        // Stock status
        const statusElement = document.getElementById('stockStatus');
        let statusClass, statusText, stockBarClass;
        
        if (product.stock > 20) {
            statusClass = 'stock-status stock-in';
            statusText = 'In Stock';
            stockBarClass = 'stock-full';
        } else if (product.stock > 0 && product.stock <= 20) {
            statusClass = 'stock-status stock-low';
            statusText = 'Low Stock';
            stockBarClass = 'stock-medium';
        } else {
            statusClass = 'stock-status stock-out';
            statusText = 'Out of Stock';
            stockBarClass = 'stock-low';
        }
        
        statusElement.className = statusClass;
        statusElement.innerHTML = `
            <iconify-icon icon="lucide:${product.stock > 20 ? 'check-circle' : product.stock > 0 ? 'alert-triangle' : 'x-circle'}" width="14"></iconify-icon>
            ${statusText}
        `;
        
        // Stock display
        document.getElementById('currentStockDisplay').textContent = `${product.stock} units`;
        document.getElementById('newStock').value = product.stock;
        
        // Stock level bar (assuming max stock of 200 for visualization)
        const maxStock = 200;
        const stockPercentage = Math.min((product.stock / maxStock) * 100, 100);
        document.getElementById('stockLevelBar').className = `stock-level ${stockBarClass}`;
        document.getElementById('stockLevelBar').style.width = `${stockPercentage}%`;
        document.getElementById('maxStock').textContent = maxStock;
    }
    
    function adjustStock(amount) {
        const input = document.getElementById('newStock');
        let currentValue = parseInt(input.value) || 0;
        currentValue += amount;
        
        // Ensure value doesn't go below 0
        if (currentValue < 0) {
            currentValue = 0;
        }
        
        input.value = currentValue;
    }
    
    function handleSubmit(event) {
        event.preventDefault();
        
        const productId = document.getElementById('productId').value;
        const newStock = parseInt(document.getElementById('newStock').value);
        const reason = document.getElementById('stockReason').value;
        const notes = document.getElementById('stockNotes').value;
        
        if (isNaN(newStock) || newStock < 0) {
            alert('Please enter a valid stock quantity');
            return;
        }
        
        // In real app, you would send this data to your backend
        const updateData = {
            productId,
            newStock,
            reason,
            notes,
            updatedAt: new Date().toISOString(),
            updatedBy: 'Admin' // In real app, get from session
        };
        
        console.log('Updating product stock:', updateData);
        
        // Show success message
        alert(`Stock updated to ${newStock} units successfully!`);
        
        // Redirect back to manage products page
        window.location.href = '{{ route("admin.products.index") }}';
    }
</script>
@endsection