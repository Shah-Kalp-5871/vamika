@extends('layouts.admin')

@section('title', 'Order Details')

@section('styles')
<style>
    /* Admin UI Styles */
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
        display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem;
        color: #64748B; text-decoration: none; border-radius: 0.5rem; transition: all 0.2s ease;
    }
    .back-btn:hover { background: #F8FAFC; color: #475569; }

    .status-badge {
        padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600;
        display: inline-flex; align-items: center; gap: 0.25rem;
    }

    .info-card {
        background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 0.75rem; padding: 1.5rem;
    }
    .info-label { font-size: 0.875rem; color: #64748B; margin-bottom: 0.25rem; }
    .info-value { font-size: 1rem; font-weight: 500; color: #1E293B; }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;
        padding: 0.75rem 2rem; border: none; border-radius: 0.5rem; font-weight: 500;
        cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1); }

    .btn-secondary {
        background: white; color: #374151; padding: 0.75rem 2rem;
        border: 1px solid #D1D5DB; border-radius: 0.5rem; font-weight: 500;
        cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-secondary:hover { background: #F9FAFB; }

    .btn-danger {
        background: #EF4444; color: white; padding: 0.75rem 2rem;
        border: none; border-radius: 0.5rem; font-weight: 500;
        cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-danger:hover { background: #DC2626; }

    .btn-success {
        background: #10B981; color: white; padding: 0.75rem 2rem;
        border: none; border-radius: 0.5rem; font-weight: 500;
        cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;
    }
    .btn-success:hover { background: #059669; }

    .item-card {
        display: flex; align-items: center; justify-content: space-between; padding: 1rem;
        border: 1px solid #E5E7EB; border-radius: 0.5rem; background: white; transition: all 0.2s ease;
    }
    .item-card:hover { border-color: #6366F1; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }

    .action-buttons { display: flex; gap: 1rem; margin-top: 2rem; flex-wrap: wrap; }

    /* Print Specific Styles */
    @media print {
        @page { size: A4; margin: 0; }
        body { background: white; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        
        .section-no-print { display: none !important; }
        
        /* Show invoice container */
        #invoice-view {
            display: block !important;
            box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; border-radius: 0 !important;
        }
        .main-content { display: none !important; } /* Ensure admin UI is hidden */
        .bg-gray-50 { background: white !important; }
    }
    
    #invoice-view { display: none; } /* Hidden by default */
</style>
@endsection

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

@php
    function numberToWords($number) {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10] . " " .
                    $words[$number % 10] . " " .
                    $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " . 
            $words[$point = $point % 10] : '';
        return $result . "Rupees Only";
    }
    $gstin = \App\Models\Setting::where('key', 'gstin')->value('value') ?? 'N/A';
@endphp

<!-- Main Admin Content (Visible on Screen) -->
<div class="main-content section-no-print">
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
                    <span class="text-slate-600">Subtotal</span>
                    <span class="font-medium text-slate-900">₹{{ number_format($order->total_amount, 2) }}</span> 
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                    <span class="text-lg font-semibold text-slate-900">Total Amount</span>
                    <span class="text-2xl font-bold text-emerald-600">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="{{ route('admin.orders.index') }}" class="btn-secondary">
                <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon>
                Back to Orders
            </a>
            <a href="{{ route('admin.orders.update-status.form', $order->id) }}" class="btn-primary">
                <iconify-icon icon="lucide:edit" width="16"></iconify-icon>
                Update Status
            </a>
            <button onclick="window.print()" class="btn-success">
                <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
                Print Invoice
            </button>
            @if($order->status !== 'cancelled')
            <button type="button" onclick="confirmDeleteOrder('{{ $order->id }}')" class="btn-danger">
                <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                Delete Order
            </button>
            <form id="delete-order-{{ $order->id }}" action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            @endif
        </div>
    </main>
</div>

<!-- Print Invoice Template (Hidden on Screen, Visible on Print) -->
<div id="invoice-view" class="bg-white mx-auto overflow-hidden print-container" style="max-width: 210mm; min-height: 297mm; position: relative;">
    <div class="p-8 md:p-12 relative z-10 flex flex-col h-full justify-between">
        <div>
            <!-- Header -->
            <div class="flex justify-between items-start border-b-2 border-slate-800 pb-6 mb-8">
                <div class="w-1/2">
                    <!-- Company Logo -->
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Vamika Enterprise" class="h-16 w-auto object-contain mb-4">
                    <h1 class="text-3xl font-bold text-slate-900 tracking-tight uppercase">Tax Invoice</h1>
                </div>
                <div class="w-1/2 text-right">
                    <h2 class="text-lg font-bold text-slate-900">VAMIKA ENTERPRISE</h2>
                    <div class="text-xs text-slate-500 mt-1 leading-relaxed">
                        <p>SHYAM SHRUSHTI APPARTMENT, B-BLOCK-1, GROUND FLOOR</p>
                        <p>NR. KUNTESHWAR MAHADEV MANDIR, NEW RANIP, AHMEDABAD-382470</p>
                        <p>GSTIN: <span class="font-mono font-medium text-slate-700">{{ $gstin }}</span></p>
                        <p>Email: info@vamika.com | Ph: +91 98765 43210</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Details & Bill To -->
            <div class="flex flex-col md:flex-row gap-8 mb-8">
                <div class="w-full md:w-1/2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Bill To</h3>
                    <div class="bg-slate-50 p-4 rounded-lg border border-slate-100">
                        <h4 class="text-sm font-bold text-slate-900">{{ $order->shop->name }}</h4>
                        <p class="text-xs text-slate-600 mt-1 whitespace-pre-line">{{ $order->shop->address }}</p>
                        <div class="mt-3 pt-3 border-t border-slate-200">
                            <p class="text-xs text-slate-600"><span class="font-semibold">Phone:</span> {{ $order->shop->phone }}</p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Invoice Details</h3>
                    <div class="bg-white border border-slate-200 rounded-lg overflow-hidden">
                        <table class="w-full text-xs">
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-2 text-slate-500 bg-slate-50 w-1/3">Invoice No.</td>
                                <td class="px-3 py-2 font-bold text-slate-900 font-mono">INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-2 text-slate-500 bg-slate-50">Date</td>
                                <td class="px-3 py-2 font-medium text-slate-900">{{ $order->created_at->format('d-M-Y') }}</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="px-3 py-2 text-slate-500 bg-slate-50">Salesperson</td>
                                <td class="px-3 py-2 font-medium text-slate-900">{{ $order->user->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 text-slate-500 bg-slate-50">Payment Mode</td>
                                <td class="px-3 py-2 font-medium text-slate-900">Cash / Credit</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Items Table -->
            <div class="mb-8 overflow-hidden rounded-lg border border-slate-200">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="bg-slate-800 text-white uppercase tracking-wider font-bold">
                            <th class="px-4 py-3 w-12 text-center">#</th>
                            <th class="px-4 py-3">Item Description</th>
                            <th class="px-4 py-3 text-center">HSN</th>
                            <th class="px-4 py-3 text-center">Qty</th>
                            <th class="px-4 py-3 text-right">Rate</th>
                            <th class="px-4 py-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($order->items as $index => $item)
                        <tr>
                            <td class="px-4 py-3 text-center text-slate-500">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                <div class="font-bold text-slate-800">{{ $item->product->name }}</div>
                                <div class="text-[10px] text-slate-500 mt-0.5">{{ $item->product->unit }}</div>
                            </td>
                            <td class="px-4 py-3 text-center text-slate-500 font-mono">3304</td>
                            <td class="px-4 py-3 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                            <td class="px-4 py-3 text-right text-slate-600 font-mono">{{ number_format($item->price, 2) }}</td>
                            <td class="px-4 py-3 text-right font-bold text-slate-900 font-mono">{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Totals & Amount in Words -->
            <div class="flex flex-col md:flex-row gap-8 mb-8">
                <div class="w-full md:w-3/5">
                    <div class="border border-slate-200 rounded-lg p-4 h-full flex flex-col justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Amount in Words</h4>
                            <p class="text-sm font-bold text-slate-800 italic bg-slate-50 p-2 rounded border border-slate-100">
                                {{ numberToWords($order->total_amount) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-2/5">
                    <div class="bg-slate-50 rounded-lg border border-slate-200 overflow-hidden">
                        <table class="w-full text-sm">
                            <tr>
                                <td class="px-4 py-2 text-slate-600 border-b border-slate-100">Taxable Amount</td>
                                <td class="px-4 py-2 text-right font-bold text-slate-800 font-mono">{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-slate-600 border-b border-slate-100">CGST (0%)</td>
                                <td class="px-4 py-2 text-right font-bold text-slate-800 font-mono">0.00</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-slate-600 border-b border-slate-100">SGST (0%)</td>
                                <td class="px-4 py-2 text-right font-bold text-slate-800 font-mono">0.00</td>
                            </tr>
                            <tr class="bg-indigo-600 text-white">
                                <td class="px-4 py-3 font-bold uppercase text-xs tracking-wider">Grand Total</td>
                                <td class="px-4 py-3 text-right font-black text-lg font-mono">₹{{ number_format($order->total_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Terms -->
            <div class="mb-4">
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Terms & Conditions</h4>
                <ul class="text-[10px] text-slate-500 list-disc pl-3 space-y-0.5">
                    <li>Goods once sold will not be taken back.</li>
                    <li>Interest @ 18% p.a. will be charged if payment is not made within the due date.</li>
                    <li>Subject to Ahmedabad Jurisdiction only.</li>
                </ul>
            </div>
        </div>

        <!-- Authorised Signatory -->
        <div class="flex justify-end mt-8 pt-8 relative">
            <div class="text-center w-40">
                <div class="h-12 w-full"></div>
                <p class="text-xs font-bold text-slate-900">VAMIKA ENTERPRISE</p>
                <p class="text-[9px] text-slate-400 uppercase tracking-wider mt-1 border-t border-slate-300 pt-1">Authorised Signatory</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Header updates
    document.getElementById('orderNumber').textContent = 'Order #{{ $order->id }}';
    
    // Status and Payment badges
    const status = '{{ $order->status }}';
    const paymentStatus = '{{ $order->payment_status }}';
    const statusBadge = document.getElementById('statusBadge');
    const paymentBadge = document.getElementById('paymentBadge');

    // Helper to format text
    const formatText = (text) => text.charAt(0).toUpperCase() + text.slice(1);

    // Update status badge
    statusBadge.textContent = formatText(status);
    if (status === 'pending') statusBadge.className = 'status-badge bg-amber-50 text-amber-600';
    else if (status === 'confirmed') statusBadge.className = 'status-badge bg-blue-50 text-blue-600';
    else if (status === 'delivered') statusBadge.className = 'status-badge bg-emerald-50 text-emerald-600';
    else if (status === 'cancelled') statusBadge.className = 'status-badge bg-rose-50 text-rose-600';
    else statusBadge.className = 'status-badge bg-slate-50 text-slate-600';

    // Update payment badge
    paymentBadge.textContent = formatText(paymentStatus);
    if (paymentStatus === 'paid') paymentBadge.className = 'status-badge bg-emerald-50 text-emerald-600';
    else if (paymentStatus === 'cancelled') paymentBadge.className = 'status-badge bg-rose-50 text-rose-600';
    else paymentBadge.className = 'status-badge bg-amber-50 text-amber-600';
    
    // Auto print
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('print') === '1') {
        setTimeout(() => {
            window.print();
        }, 500);
    }

    function confirmDeleteOrder(orderId) {
        Swal.fire({
            title: 'Delete Order?',
            text: "Are you sure you want to delete this order? This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#E11D48',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-order-' + orderId).submit();
            }
        });
    }
</script>
@endsection