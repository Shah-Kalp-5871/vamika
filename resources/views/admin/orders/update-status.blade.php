@extends('layouts.admin')

@section('title', 'Update Order Status')

@section('styles')
<style>
    /* Main content container */
    .main-content-card {
        max-width: 100%;
        margin: 0 auto;
        background-color: #FAFAFA;
    }

    @media (min-width: 640px) {
        .main-content-card {
            max-width: 42rem;
            margin: 2rem auto;
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

    /* Status indicator */
    .status-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        margin-bottom: 1rem;
    }

    .status-active {
        background-color: #F0F9FF;
        border: 1px solid #BAE6FD;
        color: #0369A1;
    }

    /* Order info card */
    .order-info-card {
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
</style>
@endsection

@section('content')
<div class="main-content-card">
    <div class="p-6">
        <!-- Current Order Info -->
        <h2 class="text-xl font-bold text-slate-800 mb-6">Update Order #{{ $order->id }}</h2>
        
        <div class="order-info-card">
            <div class="info-row">
                <span class="info-label">Order Number:</span>
                <span class="info-value">Order #{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Shop Name:</span>
                <span class="info-value">{{ $order->shop->name ?? 'N/A' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Current Status:</span>
                <span class="info-value uppercase font-bold">{{ $order->status }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Amount:</span>
                <span class="info-value">₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Status Update Form -->
        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="space-y-6">
            @csrf
            {{-- Method spoofing not needed if route is POST, usually update status is PUT/PATCH but route definition says POST --}}
            {{-- Checking route definition from previous turn: Route::post('/orders/{id}/update-status', ...) --}}
            
            <div class="form-group">
                <label class="form-label" for="statusSelect">Order Status *</label>
                <select id="statusSelect" name="status" class="form-select" required>
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="dispatched" {{ $order->status == 'dispatched' ? 'selected' : '' }}>Dispatched</option>
                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="paymentStatusSelect">Payment Status *</label>
                <select id="paymentStatusSelect" name="payment_status" class="form-select" required>
                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="cancelled" {{ $order->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="statusNotes">Update Notes (Optional)</label>
                <textarea id="statusNotes" name="notes" class="form-input" rows="4"
                    placeholder="Add any notes about this status update (e.g., reason for change, delivery instructions, etc.)">{{ $order->notes }}</textarea>
            </div>

            <!-- Status Descriptions -->
            <div class="bg-slate-50 rounded-lg border border-slate-200 p-4">
                <h3 class="font-semibold text-slate-900 mb-3">Status Descriptions</h3>
                <div class="space-y-2 text-sm text-slate-600">
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:clock" width="14" class="text-amber-500 mt-0.5"></iconify-icon>
                        <span><strong>Pending:</strong> Order received but not yet processed</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:check" width="14" class="text-blue-500 mt-0.5"></iconify-icon>
                        <span><strong>Confirmed:</strong> Order verified and confirmed</span>
                    </div>
                        <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:settings" width="14"
                            class="text-purple-500 mt-0.5"></iconify-icon>
                        <span><strong>Processing:</strong> Order is being prepared for dispatch</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:truck" width="14" class="text-indigo-500 mt-0.5"></iconify-icon>
                        <span><strong>Dispatched:</strong> Order is out for delivery</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:check-circle" width="14"
                            class="text-emerald-500 mt-0.5"></iconify-icon>
                        <span><strong>Delivered:</strong> Order successfully delivered to customer</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <iconify-icon icon="lucide:x-circle" width="14" class="text-rose-500 mt-0.5"></iconify-icon>
                        <span><strong>Cancelled:</strong> Order has been cancelled</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-secondary no-underline">
                    Cancel
                </a>
                <button href="{{ route('admin.orders.show', $order->id) }}" type="submit" class="btn-primary">
                    Update Status
                </button>
            </div>
        </form>
    </div>
</div>
@endsection