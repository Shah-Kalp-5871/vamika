@extends('layouts.admin')

@section('title', 'Product Details - Admin')

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
    
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-active {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }
    
    .status-inactive {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    .image-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }

    .product-image {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 1px solid #E2E8F0;
    }

    .btn-edit {
        background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <header class="p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="flex items-start gap-3">
            <div class="flex-1">
                <a href="{{ route('admin.products.index') }}" class="back-btn mb-3 inline-flex items-center gap-1">
                    <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                    Back to Products
                </a>
                <h1 class="text-xl font-semibold text-slate-900 tracking-tight">
                    Product Details
                </h1>
            </div>
            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn-edit">
                <iconify-icon icon="lucide:edit" width="18"></iconify-icon>
                Edit
            </a>
        </div>
    </header>

    <main class="p-6">
        <div class="product-info-card">
            <div class="info-row">
                <span class="info-label">Product Name</span>
                <span class="info-value">{{ $product->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">SKU</span>
                <span class="info-value">{{ $product->sku }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Category</span>
                <span class="info-value">{{ $product->category }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Price</span>
                <span class="info-value">₹{{ number_format($product->price, 2) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Current Stock</span>
                <span class="info-value">{{ $product->stock }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="status-badge {{ $product->status === 'active' ? 'status-active' : 'status-inactive' }}">
                    <iconify-icon icon="lucide:{{ $product->status === 'active' ? 'check-circle' : 'x-circle' }}" width="14"></iconify-icon>
                    {{ ucfirst($product->status) }}
                </span>
            </div>
        </div>

        @if($product->description)
        <div class="mb-6">
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Description</h3>
            <div class="p-4 bg-white border border-slate-200 rounded-lg text-sm text-slate-600">
                {{ $product->description }}
            </div>
        </div>
        @endif

        @if($product->images && count($product->images) > 0)
        <div>
            <h3 class="text-sm font-semibold text-slate-900 mb-2">Product Images</h3>
            <div class="image-grid">
                @foreach($product->images as $image)
                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image" class="product-image">
                @endforeach
            </div>
        </div>
        @endif
    </main>
</div>
@endsection