@extends('layouts.shop-owner')

@php
$title = 'Invoices';
$subtitle = 'Invoice history';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.profile.index');
@endphp

@section('content')
<style>
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: #64748B;
        background: #F8FAFC;
        border-radius: 0.75rem;
        border: 2px dashed #E2E8F0;
    }

    .invoice-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        padding: 16px;
        transition: all 0.2s ease;
    }

    .invoice-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border-color: #CBD5E1;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
        gap: 4px;
    }

    .status-paid {
        background-color: #DCFCE7;
        color: #166534;
        border: 1px solid #BBF7D0;
    }

    .status-pending {
        background-color: #FEF3C7;
        color: #92400E;
        border: 1px solid #FDE68A;
    }

    .loading-spinner {
        display: inline-block;
        width: 1.5rem;
        height: 1.5rem;
        border: 2px solid #E2E8F0;
        border-top-color: #64748B;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>

<div class="main-content p-4 mb-24">
    <!-- Header Section -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-lg font-semibold text-slate-900">Invoice History</h1>
                <p class="text-sm text-slate-500">View and download your past invoices</p>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="filterInvoices('all')" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors">
                    All
                </button>
                <button onclick="filterInvoices('paid')" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors">
                    Paid
                </button>
                <button onclick="filterInvoices('pending')" class="px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors">
                    Pending
                </button>
            </div>
        </div>

        <!-- Stats Summary -->
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="bg-white rounded-xl border border-slate-200 p-3 text-center">
                <p class="text-lg font-semibold text-slate-900" id="totalInvoices">0</p>
                <p class="text-xs text-slate-500">Total Invoices</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-3 text-center">
                <p class="text-lg font-semibold text-emerald-600" id="totalPaid">₹0</p>
                <p class="text-xs text-slate-500">Total Paid</p>
            </div>
            <div class="bg-white rounded-xl border border-slate-200 p-3 text-center">
                <p class="text-lg font-semibold text-amber-600" id="totalPending">₹0</p>
                <p class="text-xs text-slate-500">Pending Payment</p>
            </div>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="relative mb-4">
        <iconify-icon icon="lucide:search" width="18"
            class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
        <input type="text" id="invoiceSearch" placeholder="Search by invoice number or amount..." 
            class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white
                   focus:outline-none focus:ring-2 focus:ring-indigo-100
                   focus:border-indigo-300 text-sm placeholder-slate-400 transition-all"
            oninput="searchInvoices()">
    </div>

    <!-- Invoice List -->
    <div id="invoiceList" class="space-y-3">
        <div class="flex items-center justify-center py-12">
            <div class="loading-spinner"></div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="empty-state hidden">
        <div class="flex flex-col items-center justify-center">
            <iconify-icon icon="lucide:file-text" width="48" class="text-slate-400 mb-4"></iconify-icon>
            <h4 class="text-lg font-semibold text-slate-700 mb-2">No Invoices Found</h4>
            <p class="text-sm text-slate-500 mb-6 max-w-md">
                No invoices match your current filters. Start shopping to generate your first invoice.
            </p>
            <button onclick="window.location.href='{{ route('shop-owner.products.index') }}'"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors no-underline">
                <iconify-icon icon="lucide:shopping-bag" width="16"></iconify-icon>
                Start Shopping
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let allInvoices = [];
    let currentFilter = 'all';
    let searchTerm = '';

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadInvoices();
    });

    function loadInvoices() {
        // Load orders from localStorage or use dummy data
        const savedOrders = localStorage.getItem('shop_orders');
        
        // Dummy data as fallback
        const dummyData = {
            orders: [
                {
                    orderNumber: "ORD00127",
                    date: "Dec 15, 2024",
                    time: "10:30 AM",
                    status: "Delivered",
                    subtotal: 2400,
                    delivery: 50,
                    discount: 100,
                    total: 2350,
                    paymentStatus: "paid",
                    paymentMethod: "Cash on Delivery",
                    items: [
                        { name: "Aashirvaad Atta", unit: "5kg", quantity: 2, price: 250, total: 500 },
                        { name: "Fortune Oil", unit: "1L", quantity: 1, price: 190, total: 190 },
                        { name: "Tata Salt", unit: "1kg", quantity: 3, price: 25, total: 75 },
                        { name: "Coca-Cola", unit: "750ml", quantity: 4, price: 40, total: 160 }
                    ]
                },
                {
                    orderNumber: "ORD00126",
                    date: "Dec 14, 2024",
                    time: "02:45 PM",
                    status: "Processing",
                    subtotal: 1850,
                    delivery: 50,
                    discount: 0,
                    total: 1900,
                    paymentStatus: "pending",
                    paymentMethod: "Cash on Delivery",
                    items: [
                        { name: "Basmati Rice", unit: "5kg", quantity: 1, price: 450, total: 450 },
                        { name: "Toor Dal", unit: "1kg", quantity: 2, price: 180, total: 360 },
                        { name: "Sugar", unit: "1kg", quantity: 5, price: 45, total: 225 }
                    ]
                },
                {
                    orderNumber: "ORD00125",
                    date: "Dec 12, 2024",
                    time: "09:15 AM",
                    status: "Pending",
                    subtotal: 3200,
                    delivery: 0,
                    discount: 200,
                    total: 3000,
                    paymentStatus: "paid",
                    paymentMethod: "Wallet Balance",
                    items: [
                        { name: "Amul Milk", unit: "500ml", quantity: 10, price: 30, total: 300 },
                        { name: "Lays Chips", unit: "50g", quantity: 20, price: 20, total: 400 },
                        { name: "Parle-G Biscuits", unit: "100g", quantity: 30, price: 10, total: 300 }
                    ]
                },
                {
                    orderNumber: "ORD00124",
                    date: "Dec 10, 2024",
                    time: "04:20 PM",
                    status: "Dispatched",
                    subtotal: 1475,
                    delivery: 50,
                    discount: 75,
                    total: 1450,
                    paymentStatus: "pending",
                    paymentMethod: "Cash on Delivery",
                    items: [
                        { name: "Tea Powder", unit: "250g", quantity: 2, price: 120, total: 240 },
                        { name: "Nescafe Coffee", unit: "50g", quantity: 3, price: 85, total: 255 },
                        { name: "Bournvita", unit: "500g", quantity: 2, price: 190, total: 380 }
                    ]
                }
            ]
        };

        allInvoices = savedOrders ? JSON.parse(savedOrders) : dummyData.orders;

        // Add payment status if not present
        allInvoices.forEach(invoice => {
            if (!invoice.paymentStatus) {
                invoice.paymentStatus = invoice.status === 'Delivered' ? 'paid' : 'pending';
            }
        });

        updateInvoiceStats();
        renderInvoices();
    }

    function filterInvoices(filter) {
        currentFilter = filter;
        
        // Update button styles
        document.querySelectorAll('button[onclick^="filterInvoices"]').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white');
            btn.classList.add('border', 'border-slate-200', 'bg-white', 'text-slate-600', 'hover:bg-slate-50');
        });
        
        // Activate clicked button
        event.target.classList.remove('border', 'border-slate-200', 'bg-white', 'text-slate-600', 'hover:bg-slate-50');
        event.target.classList.add('bg-indigo-600', 'text-white');
        
        renderInvoices();
    }

    function searchInvoices() {
        searchTerm = document.getElementById('invoiceSearch').value.toLowerCase();
        renderInvoices();
    }

    function renderInvoices() {
        const container = document.getElementById('invoiceList');
        const emptyState = document.getElementById('emptyState');

        // Filter invoices
        let filteredInvoices = allInvoices.filter(invoice => {
            // Apply status filter
            if (currentFilter === 'paid' && invoice.paymentStatus !== 'paid') return false;
            if (currentFilter === 'pending' && invoice.paymentStatus !== 'pending') return false;
            
            // Apply search filter
            if (searchTerm) {
                return invoice.orderNumber.toLowerCase().includes(searchTerm) ||
                       invoice.total.toString().includes(searchTerm) ||
                       invoice.paymentMethod.toLowerCase().includes(searchTerm);
            }
            
            return true;
        });

        // Sort by date (newest first)
        filteredInvoices.sort((a, b) => new Date(b.date) - new Date(a.date));

        // Check if empty
        if (filteredInvoices.length === 0) {
            container.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        container.innerHTML = '';

        // Render invoices
        filteredInvoices.forEach((invoice, index) => {
            const card = document.createElement('div');
            card.className = 'invoice-card';
            card.style.animationDelay = `${index * 0.05}s`;
            
            const statusClass = invoice.paymentStatus === 'paid' ? 'status-paid' : 'status-pending';
            const statusText = invoice.paymentStatus === 'paid' ? 'Paid' : 'Pending';

            card.innerHTML = `
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">Invoice #${invoice.orderNumber}</h3>
                        <div class="flex items-center gap-3 mt-1">
                            <span class="text-xs text-slate-500">${invoice.date} • ${invoice.time}</span>
                            <span class="status-badge ${statusClass}">
                                <iconify-icon icon="${invoice.paymentStatus === 'paid' ? 'lucide:check-circle' : 'lucide:clock'}" width="10"></iconify-icon>
                                ${statusText}
                            </span>
                        </div>
                    </div>
                    <span class="text-lg font-bold text-slate-900">₹${invoice.total}</span>
                </div>
                
                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <iconify-icon icon="lucide:shopping-bag" width="14" class="text-slate-400"></iconify-icon>
                        <span class="text-xs text-slate-600">${invoice.items.length} items</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <iconify-icon icon="lucide:credit-card" width="14" class="text-slate-400"></iconify-icon>
                        <span class="text-xs text-slate-600">${invoice.paymentMethod}</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                    <div>
                        <p class="text-xs text-slate-500">Order Status</p>
                        <p class="text-sm font-medium text-slate-900">${invoice.status}</p>
                    </div>
                    
                    <div class="flex gap-2">
                        <button onclick="viewInvoice('${invoice.orderNumber}')"
                                class="px-3 py-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-700 
                                       hover:bg-indigo-50 rounded-lg border border-slate-200 transition-colors 
                                       flex items-center gap-1">
                            <iconify-icon icon="lucide:eye" width="12"></iconify-icon>
                            View
                        </button>
                        <button onclick="downloadInvoice('${invoice.orderNumber}')"
                                class="px-3 py-1.5 text-xs font-medium text-emerald-600 hover:text-emerald-700 
                                       hover:bg-emerald-50 rounded-lg border border-slate-200 transition-colors 
                                       flex items-center gap-1">
                            <iconify-icon icon="lucide:download" width="12"></iconify-icon>
                            Download
                        </button>
                    </div>
                </div>
            `;

            container.appendChild(card);
        });
    }

    function updateInvoiceStats() {
        const totalInvoices = allInvoices.length;
        const totalPaid = allInvoices
            .filter(inv => inv.paymentStatus === 'paid')
            .reduce((sum, inv) => sum + inv.total, 0);
        const totalPending = allInvoices
            .filter(inv => inv.paymentStatus === 'pending')
            .reduce((sum, inv) => sum + inv.total, 0);

        document.getElementById('totalInvoices').textContent = totalInvoices;
        document.getElementById('totalPaid').textContent = '₹' + totalPaid.toLocaleString();
        document.getElementById('totalPending').textContent = '₹' + totalPending.toLocaleString();
    }

    function viewInvoice(orderNumber) {
        window.location.href = '{{ route('shop-owner.invoices.show', ['id' => 'INVOICE_ID']) }}'.replace('INVOICE_ID', orderNumber);
    }

    function downloadInvoice(orderNumber) {
        // In a real app, this would generate and download a PDF
        const invoice = allInvoices.find(inv => inv.orderNumber === orderNumber);
        if (invoice) {
            // Create a simple invoice text for download
            const invoiceContent = `
                INVOICE #${invoice.orderNumber}
                Date: ${invoice.date} ${invoice.time}
                Status: ${invoice.status}
                Payment: ${invoice.paymentMethod} (${invoice.paymentStatus})
                
                ITEMS:
                ${invoice.items.map(item => `${item.name} - ${item.unit} x${item.quantity} = ₹${item.total}`).join('\n')}
                
                SUBTOTAL: ₹${invoice.subtotal || invoice.total}
                DELIVERY: ₹${invoice.delivery || 0}
                DISCOUNT: ₹${invoice.discount || 0}
                TOTAL: ₹${invoice.total}
                
                Thank you for your business!
            `;

            // Create download link
            const blob = new Blob([invoiceContent], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Invoice_${orderNumber}.txt`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);

            showToast({
                text: `Invoice #${orderNumber} downloaded`,
                type: 'success'
            });
        }
    }

    // Toast notification function
    function showToast({ text, type = 'info' }) {
        // Remove existing toast if any
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) {
            existingToast.remove();
        }
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-6 right-6 z-50 px-4 py-3 rounded-lg shadow-lg transition-all transform translate-x-full opacity-0 ${
            type === 'success' ? 'bg-emerald-500 text-white' : 
            type === 'error' ? 'bg-rose-500 text-white' : 
            'bg-indigo-500 text-white'
        }`;
        toast.textContent = text;
        document.body.appendChild(toast);
        
        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 10);
        
        // Animate out after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+F to focus search
        if (e.ctrlKey && e.key === 'f') {
            e.preventDefault();
            document.getElementById('invoiceSearch').focus();
        }
        // Esc to clear search
        if (e.key === 'Escape') {
            document.getElementById('invoiceSearch').value = '';
            searchTerm = '';
            renderInvoices();
        }
    });
</script>
@endsection