@extends('layouts.admin')

@section('title', 'Order Details')

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

    /* Status badge */
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Info card */
    .info-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 0.75rem;
        padding: 1.5rem;
    }

    .info-label {
        font-size: 0.875rem;
        color: #64748B;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-size: 1rem;
        font-weight: 500;
        color: #1E293B;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        flex-wrap: wrap;
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
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
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
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-secondary:hover {
        background: #F9FAFB;
    }

    .btn-danger {
        background: #EF4444;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-danger:hover {
        background: #DC2626;
    }

    .btn-success {
        background: #10B981;
        color: white;
        padding: 0.75rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-success:hover {
        background: #059669;
    }

    /* Items list */
    .item-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border: 1px solid #E5E7EB;
        border-radius: 0.5rem;
        background: white;
        transition: all 0.2s ease;
    }

    .item-card:hover {
        border-color: #6366F1;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    /* Invoice styles */
    .invoice-header {
        text-align: center;
        padding: 2rem;
        border-bottom: 2px solid #E5E7EB;
    }

    .invoice-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1E293B;
    }

    .invoice-number {
        font-size: 1.25rem;
        color: #6366F1;
        margin-top: 0.5rem;
    }

    /* Print styles */
    @media print {
        body {
            background: white;
            padding: 0;
        }

        .main-content {
            max-width: 100%;
            margin: 0;
            box-shadow: none;
            border: none;
            border-radius: 0;
        }

        .back-btn,
        .action-buttons,
        .no-print {
            display: none !important;
        }

        .print-only {
            display: block !important;
        }

        .info-card {
            border: 1px solid #000;
            page-break-inside: avoid;
        }

        .item-card {
            border: 1px solid #000;
            page-break-inside: avoid;
        }
    }

    .print-only {
        display: none;
    }
</style>
@endsection

@section('content')
<div class="main-content">
    <!-- Header -->
    <header class="p-6 border-b border-slate-100 bg-white sticky top-0 z-10">
        <div class="flex items-start justify-between gap-4">
            <div class="flex items-start gap-3">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Vamika Enterprise" 
                     class="h-10 w-10 object-contain rounded-md border border-slate-200 mt-1">
                <div>
                    <a href="{{ route('admin.orders.index') }}" class="back-btn mb-1 inline-flex">
                        <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                        Back to Orders
                    </a>
                    <h1 class="text-xl font-semibold text-slate-900 tracking-tight" id="orderNumber">
                        Loading...
                    </h1>
                    <p class="text-sm text-slate-500 mt-0.5">
                        Order details and invoice
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-end gap-2">
                <span id="statusBadge" class="status-badge">Loading...</span>
                <span id="paymentBadge" class="status-badge">Loading...</span>
            </div>
        </div>
    </header>

    <main class="p-6 space-y-6">
        <!-- Print Header -->
        <div class="invoice-header print-only">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">Invoice #{{ $order->id }}</div>
            <div class="text-sm text-slate-500 mt-2">{{ $order->created_at->format('d M Y h:i A') }}</div>
        </div>

        <!-- Order Information -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="info-label">Order Date & Time</div>
                    <div class="info-value">{{ $order->created_at->format('d M Y • h:i A') }}</div>
                </div>
                <div>
                    <div class="info-label">Order Type</div>
                    <div class="info-value">Order #{{ $order->id }}</div>
                </div>
                <div>
                    <div class="info-label">Shop Name</div>
                    <div class="info-value">{{ $order->shop->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="info-label">Shop Owner</div>
                    <div class="info-value">{{ $order->shop->user->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="info-label">Area</div>
                    <div class="info-value">{{ $order->shop->area->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="info-label">Salesperson</div>
                    <div class="info-value">{{ $order->salesperson->name ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="info-label">Payment Status</div>
                    <div class="info-value capitalize">{{ $order->payment_status }}</div>
                </div>
                <div>
                    <div class="info-label">Special Notes</div>
                    <div class="info-value">{{ $order->notes ?? 'None' }}</div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Items</h3>
            <div class="space-y-3">
                @foreach($order->items as $item)
                <div class="item-card">
                    <div class="flex-1">
                        <div class="font-medium text-slate-900">{{ $item->product->name ?? 'Unknown Product' }}</div>
                        <div class="text-sm text-slate-500 mt-1">{{ $item->product->unit ?? '' }}</div>
                    </div>
                    <div class="text-right">
                        <div class="font-semibold text-slate-900">₹{{ number_format($item->subtotal, 2) }}</div>
                        <div class="text-sm text-slate-500">{{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Summary -->
        <div class="info-card">
            <h3 class="font-semibold text-slate-900 mb-4">Order Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Subtotal (Approx)</span>
                    <span class="font-medium text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span> 
                    <!-- Assuming total_amount is final. If subtotal is stored separately use it. -->
                </div>
                <!-- Delivery/Discount logic if stored in DB. For now using Total. -->
                <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                    <span class="text-lg font-semibold text-slate-900">Total Amount</span>
                    <span class="text-2xl font-bold text-emerald-600">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary no-print">
                <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                Back to Orders
            </a>
            <a href="{{ route('admin.orders.update-status.form', $order->id) }}" class="btn-primary no-print">
                <iconify-icon icon="lucide:edit" width="16"></iconify-icon>
                Update Status
            </a>
            <button onclick="window.print()" class="btn-success no-print">
                <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
                Print Invoice
            </button>
            @if($order->status !== 'cancelled')
            <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this order?');" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger no-print">
                    <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                    Delete Order
                </button>
            </form>
            @endif
        </div>

        <!-- Print Footer -->
        <div class="text-center text-sm text-slate-500 print-only mt-8 pt-4 border-t border-slate-200">
            <p>Thank you for your business!</p>
            <p class="mt-1">This is a computer generated invoice. No signature required.</p>
        </div>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Header updates
    document.getElementById('orderNumber').textContent = 'Order #{{ $order->id }}';
    
    // Auto print
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        setTimeout(() => {
            window.print();
        }, 500);
    }
</script>
@endsection