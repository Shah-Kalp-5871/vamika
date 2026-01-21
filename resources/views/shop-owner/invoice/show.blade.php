@extends('layouts.shop-owner')

@php
$title = 'Invoice';
$subtitle = 'Order summary';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.orders.index');
$headerAction = [
    'id' => 'print-invoice',
    'icon' => 'lucide:printer',
    'title' => 'Print Invoice'
];
@endphp

@section('content')
<style>
    @media print {
        @page {
            size: A4 landscape;
            margin: 10mm;
        }

        body * {
            visibility: hidden;
        }

        #print-area, #print-area * {
            visibility: visible;
        }

        #print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        .no-print {
            display: none !important;
        }

        .bottom-nav {
            display: none !important;
        }

        header, footer {
            display: none !important;
        }
    }

    .invoice-container {
        max-width: 100%;
        overflow-x: auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .action-bar {
        position: sticky;
        bottom: 80px;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #E2E8F0;
        padding: 16px;
        box-shadow: 0 -4px 6px -1px rgba(0,0,0,0.1);
        z-index: 40;
    }
</style>

<div class="main-content p-4">
    <div id="print-area">
        <div class="max-w-full mx-auto p-3 bg-white border text-[11px] font-mono">

            <!-- HEADER -->
            <div class="flex justify-between border-b pb-2 mb-2">
                <div>
                    <p class="font-bold text-lg">VAMIKA ENTERPRISE</p>
                    <p>Wholesale Distributor</p>
                    <p>Delhi NCR, India</p>
                    <p>GSTIN: 07AABCU9603R1ZM</p>
                    <p>Phone: +91 9876543210</p>
                    <p>Email: info@vamikaenterprise.com</p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-base">TAX INVOICE</p>
                    <p>Invoice No: <span id="invoiceNumber" class="font-semibold"></span></p>
                    <p>Date: <span id="invoiceDate" class="font-semibold"></span></p>
                    <p>Time: <span id="invoiceTime" class="font-semibold"></span></p>
                    <p>Order No: <span id="orderNumber" class="font-semibold"></span></p>
                </div>
            </div>

            <!-- CUSTOMER & SHIP TO -->
            <div class="grid grid-cols-2 gap-4 border-b pb-2 mb-2">
                <div>
                    <p class="font-semibold mb-1">Bill To:</p>
                    <p id="customerName" class="font-medium"></p>
                    <p id="customerShop" class="text-slate-600"></p>
                    <p id="customerAddress" class="text-slate-600"></p>
                    <p id="customerPhone" class="text-slate-600"></p>
                    <p id="customerGST" class="text-slate-600">GSTIN: <span id="gstNumber">N/A</span></p>
                </div>
                <div>
                    <p class="font-semibold mb-1">Ship To:</p>
                    <p id="shipToName" class="font-medium"></p>
                    <p id="shipToAddress" class="text-slate-600"></p>
                    <p id="shipToPhone" class="text-slate-600"></p>
                </div>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border text-[11px] min-w-[1000px]">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="border p-1">#</th>
                            <th class="border p-1 text-left">Item Description</th>
                            <th class="border p-1">HSN/SAC</th>
                            <th class="border p-1">Qty</th>
                            <th class="border p-1">Unit</th>
                            <th class="border p-1">Rate (₹)</th>
                            <th class="border p-1">Taxable Value (₹)</th>
                            <th class="border p-1">CGST %</th>
                            <th class="border p-1">SGST %</th>
                            <th class="border p-1">Total Tax (₹)</th>
                            <th class="border p-1">Amount (₹)</th>
                        </tr>
                    </thead>

                    <tbody id="invoiceItems"></tbody>

                    <!-- TOTAL ROWS -->
                    <tfoot class="bg-slate-50">
                        <tr class="font-semibold">
                            <td class="border p-1 text-center" colspan="3">SUB TOTAL</td>
                            <td class="border p-1 text-center" id="totalQtyCell">0</td>
                            <td class="border p-1" colspan="2"></td>
                            <td class="border p-1 text-right" id="subTotalCell">₹0.00</td>
                            <td class="border p-1 text-center" colspan="2"></td>
                            <td class="border p-1 text-right" id="totalTaxCell">₹0.00</td>
                            <td class="border p-1 text-right" id="totalAmountCell">₹0.00</td>
                        </tr>
                        <tr>
                            <td class="border p-1" colspan="10" class="text-right">Delivery Charges</td>
                            <td class="border p-1 text-right" id="deliveryChargeCell">₹0.00</td>
                        </tr>
                        <tr>
                            <td class="border p-1" colspan="10" class="text-right">Discount</td>
                            <td class="border p-1 text-right text-emerald-600" id="discountCell">-₹0.00</td>
                        </tr>
                        <tr class="font-bold bg-slate-100">
                            <td class="border p-1 text-center" colspan="10">GRAND TOTAL</td>
                            <td class="border p-1 text-right text-lg" id="grandTotalCell">₹0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- TAX SUMMARY -->
            <div class="mt-4 border p-2 text-[10px]">
                <p class="font-semibold mb-1">Tax Summary:</p>
                <div id="taxSummary" class="grid grid-cols-3 gap-2">
                    <!-- Tax summary will be populated here -->
                </div>
            </div>

            <!-- FINAL TOTAL & NOTES -->
            <div class="flex justify-between mt-3">
                <div class="text-[11px] w-2/3">
                    <p class="font-semibold mb-1">Amount in Words :</p>
                    <p id="amountInWords" class="italic border-t pt-1"></p>
                    
                    <div class="mt-3">
                        <p class="font-semibold mb-1">Notes:</p>
                        <ul class="list-disc list-inside text-[10px]">
                            <li>Goods once sold will not be taken back</li>
                            <li>All disputes subject to Delhi jurisdiction only</li>
                            <li>Interest @18% p.a. will be charged on overdue payments</li>
                            <li>E.& O.E.</li>
                        </ul>
                    </div>
                </div>

                <div class="text-right">
                    <div class="mb-4">
                        <p class="text-[11px]">Payment Method:</p>
                        <p id="paymentMethod" class="font-semibold"></p>
                        <p id="paymentStatus" class="text-xs"></p>
                    </div>
                    
                    <div class="mt-6 pt-2 border-t">
                        <p class="text-[11px]">For Vamika Enterprise</p>
                        <div class="mt-4">
                            <p class="text-[10px]">Authorised Signatory</p>
                            <div class="mt-2 border-t w-32 mx-auto"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="mt-4 border-t pt-2 text-[10px] text-center text-slate-500">
                <p>This is a computer generated invoice and does not require signature</p>
                <p>GST No: 07AABCU9603R1ZM | Phone: +91 9876543210 | Email: accounts@vamikaenterprise.com</p>
                <p>Thank you for your business!</p>
            </div>

        </div>
    </div>
</div>

<!-- ACTION BAR -->
<div class="action-bar no-print flex gap-3 justify-end">
    <button onclick="shareInvoice()" 
            class="px-4 py-2 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 text-sm flex items-center gap-2 transition-colors">
        <iconify-icon icon="lucide:share-2" width="16"></iconify-icon>
        Share
    </button>
    <button onclick="printInvoice()" 
            class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 text-sm flex items-center gap-2 transition-colors">
        <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
        Print Invoice
    </button>
    <button onclick="downloadPDF()" 
            class="px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 text-sm flex items-center gap-2 transition-colors">
        <iconify-icon icon="lucide:download" width="16"></iconify-icon>
        Download PDF
    </button>
</div>
@endsection

@section('scripts')
<script>
    let currentInvoice = null;

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadInvoice();
        
        // Set up print button in header
        document.getElementById('print-invoice').addEventListener('click', printInvoice);
    });

    function loadInvoice() {
        // Get invoice number from URL
        const urlParts = window.location.pathname.split('/');
        const invoiceNumber = urlParts[urlParts.length - 1];

        if (!invoiceNumber || invoiceNumber === 'show') {
            showError('Invalid invoice number');
            return;
        }

        // Load orders from localStorage or dummy data
        const savedOrders = localStorage.getItem('shop_orders');
        
        // Dummy data as fallback
        const dummyData = {
            orders: [
                {
                    orderNumber: "ORD00127",
                    invoiceNumber: "INV-2024-00127",
                    date: "Dec 15, 2024",
                    time: "10:30 AM",
                    status: "Delivered",
                    subtotal: 2400,
                    delivery: 50,
                    discount: 100,
                    total: 2350,
                    paymentMethod: "Cash on Delivery",
                    paymentStatus: "Paid",
                    address: "123 Main Street, Mumbai, Maharashtra 400001",
                    phone: "+91 9876543210",
                    items: [
                        { name: "Aashirvaad Atta", unit: "5kg", quantity: 2, price: 250, total: 500, hsn: "1101" },
                        { name: "Fortune Oil", unit: "1L", quantity: 1, price: 190, total: 190, hsn: "1507" },
                        { name: "Tata Salt", unit: "1kg", quantity: 3, price: 25, total: 75, hsn: "2501" },
                        { name: "Coca-Cola", unit: "750ml", quantity: 4, price: 40, total: 160, hsn: "2202" }
                    ]
                }
            ],
            shopOwner: {
                name: "Rajesh Kumar",
                shopName: "Super Mart",
                address: "123 Main Street, Mumbai, Maharashtra 400001",
                phone: "+91 9876543210",
                gstin: "27ABCDE1234F1Z5"
            }
        };

        const orders = savedOrders ? JSON.parse(savedOrders) : dummyData.orders;
        const shopOwner = JSON.parse(localStorage.getItem('shop_owner')) || dummyData.shopOwner;

        currentInvoice = orders.find(o => o.orderNumber === invoiceNumber || o.invoiceNumber === invoiceNumber);

        if (!currentInvoice) {
            showError('Invoice not found');
            return;
        }

        // Populate invoice data
        populateInvoiceData(currentInvoice, shopOwner);
    }

    function populateInvoiceData(invoice, shopOwner) {
        // Header information
        document.getElementById('invoiceNumber').textContent = invoice.invoiceNumber || invoice.orderNumber;
        document.getElementById('orderNumber').textContent = invoice.orderNumber;
        document.getElementById('invoiceDate').textContent = invoice.date;
        document.getElementById('invoiceTime').textContent = invoice.time;
        
        // Customer information
        document.getElementById('customerName').textContent = shopOwner.name;
        document.getElementById('customerShop').textContent = shopOwner.shopName;
        document.getElementById('customerAddress').textContent = shopOwner.address;
        document.getElementById('customerPhone').textContent = shopOwner.phone;
        document.getElementById('gstNumber').textContent = shopOwner.gstin || 'N/A';
        
        // Ship to information (same as customer for now)
        document.getElementById('shipToName').textContent = shopOwner.name;
        document.getElementById('shipToAddress').textContent = shopOwner.address;
        document.getElementById('shipToPhone').textContent = shopOwner.phone;
        
        // Payment information
        document.getElementById('paymentMethod').textContent = invoice.paymentMethod || 'Cash on Delivery';
        document.getElementById('paymentStatus').textContent = invoice.paymentStatus || 'Paid';
        
        // Populate items
        const tbody = document.getElementById('invoiceItems');
        tbody.innerHTML = '';
        
        let totalQty = 0;
        let subtotal = 0;
        let totalTax = 0;
        
        invoice.items.forEach((item, index) => {
            totalQty += item.quantity;
            const taxable = item.total;
            const cgst = (taxable * 0.06); // 6% CGST
            const sgst = (taxable * 0.06); // 6% SGST
            const itemTax = cgst + sgst;
            const itemTotal = taxable + itemTax;
            
            subtotal += taxable;
            totalTax += itemTax;
            
            const row = document.createElement('tr');
            row.className = index % 2 === 0 ? 'bg-white' : 'bg-slate-50';
            row.innerHTML = `
                <td class="border p-1 text-center">${index + 1}</td>
                <td class="border p-1">${item.name}</td>
                <td class="border p-1 text-center">${item.hsn || 'N/A'}</td>
                <td class="border p-1 text-center">${item.quantity}</td>
                <td class="border p-1 text-center">${item.unit}</td>
                <td class="border p-1 text-right">₹${item.price.toFixed(2)}</td>
                <td class="border p-1 text-right">₹${taxable.toFixed(2)}</td>
                <td class="border p-1 text-center">6%</td>
                <td class="border p-1 text-center">6%</td>
                <td class="border p-1 text-right">₹${itemTax.toFixed(2)}</td>
                <td class="border p-1 text-right font-semibold">₹${itemTotal.toFixed(2)}</td>
            `;
            tbody.appendChild(row);
        });
        
        // Calculate totals
        const delivery = invoice.delivery || 0;
        const discount = invoice.discount || 0;
        const grandTotal = Math.max(0, subtotal + totalTax + delivery - discount);
        
        // Update totals display
        document.getElementById('totalQtyCell').textContent = totalQty;
        document.getElementById('subTotalCell').textContent = `₹${subtotal.toFixed(2)}`;
        document.getElementById('totalTaxCell').textContent = `₹${totalTax.toFixed(2)}`;
        document.getElementById('totalAmountCell').textContent = `₹${(subtotal + totalTax).toFixed(2)}`;
        document.getElementById('deliveryChargeCell').textContent = `₹${delivery.toFixed(2)}`;
        document.getElementById('discountCell').textContent = `-₹${discount.toFixed(2)}`;
        document.getElementById('grandTotalCell').textContent = `₹${grandTotal.toFixed(2)}`;
        
        // Update amount in words
        document.getElementById('amountInWords').textContent = 
            'Rupees ' + numberToWords(Math.round(grandTotal)) + ' Only';
        
        // Update tax summary
        updateTaxSummary(subtotal, totalTax);
    }

    function updateTaxSummary(subtotal, totalTax) {
        const taxSummary = document.getElementById('taxSummary');
        taxSummary.innerHTML = `
            <div class="border p-1">
                <p class="font-semibold">Taxable Value:</p>
                <p class="text-right">₹${subtotal.toFixed(2)}</p>
            </div>
            <div class="border p-1">
                <p class="font-semibold">CGST (6%):</p>
                <p class="text-right">₹${(totalTax / 2).toFixed(2)}</p>
            </div>
            <div class="border p-1">
                <p class="font-semibold">SGST (6%):</p>
                <p class="text-right">₹${(totalTax / 2).toFixed(2)}</p>
            </div>
        `;
    }

    function printInvoice() {
        window.print();
    }

    function shareInvoice() {
        if (!currentInvoice) return;
        
        const invoiceData = {
            title: `Invoice ${currentInvoice.invoiceNumber || currentInvoice.orderNumber}`,
            text: `Invoice #${currentInvoice.invoiceNumber || currentInvoice.orderNumber}\n` +
                  `Date: ${currentInvoice.date}\n` +
                  `Total: ₹${currentInvoice.total}\n` +
                  `Status: ${currentInvoice.paymentStatus || 'Paid'}`,
            url: window.location.href
        };
        
        if (navigator.share) {
            navigator.share(invoiceData)
                .then(() => console.log('Invoice shared successfully'))
                .catch(error => console.log('Error sharing:', error));
        } else {
            // Fallback for desktop
            const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(invoiceData.text)}`;
            window.open(whatsappUrl, '_blank');
            
            showToast({
                text: 'Share link copied to clipboard',
                type: 'success'
            });
        }
    }

    function downloadPDF() {
        if (!currentInvoice) return;
        
        // In a real app, this would generate a PDF
        // For demo, we'll use the print function
        printInvoice();
        
        showToast({
            text: 'PDF download would start in production',
            type: 'info'
        });
    }

    function numberToWords(num) {
        const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];
        
        if (num === 0) return 'Zero';
        
        function convertHundreds(n) {
            let result = '';
            if (n >= 100) {
                result += units[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }
            if (n >= 20) {
                result += tens[Math.floor(n / 10)] + ' ';
                n %= 10;
            }
            if (n >= 10) {
                result += teens[n - 10] + ' ';
                n = 0;
            }
            if (n > 0) {
                result += units[n] + ' ';
            }
            return result.trim();
        }
        
        let result = '';
        if (num >= 10000000) {
            result += convertHundreds(Math.floor(num / 10000000)) + ' Crore ';
            num %= 10000000;
        }
        if (num >= 100000) {
            result += convertHundreds(Math.floor(num / 100000)) + ' Lakh ';
            num %= 100000;
        }
        if (num >= 1000) {
            result += convertHundreds(Math.floor(num / 1000)) + ' Thousand ';
            num %= 1000;
        }
        if (num > 0) {
            result += convertHundreds(num);
        }
        
        return result.trim();
    }

    function showError(message) {
        const container = document.querySelector('.main-content');
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-16">
                <iconify-icon icon="lucide:file-x" width="64" class="text-rose-500 mb-4"></iconify-icon>
                <h3 class="text-lg font-semibold text-slate-900 mb-2">${message}</h3>
                <p class="text-sm text-slate-500 mb-6">Unable to load the invoice. Please try again.</p>
                <button onclick="window.location.href='{{ route('shop-owner.invoices.index') }}'"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Back to Invoices
                </button>
            </div>
        `;
        document.querySelector('.action-bar').style.display = 'none';
    }

    function showToast({ text, type = 'info' }) {
        const existingToast = document.querySelector('.toast-notification');
        if (existingToast) existingToast.remove();
        
        const toast = document.createElement('div');
        toast.className = `toast-notification fixed top-6 right-6 z-50 px-4 py-3 rounded-lg shadow-lg transition-all transform translate-x-full opacity-0 ${
            type === 'success' ? 'bg-emerald-500 text-white' : 
            type === 'error' ? 'bg-rose-500 text-white' : 
            'bg-indigo-500 text-white'
        }`;
        toast.textContent = text;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        }, 10);
        
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', function (e) {
        // Ctrl+P to print
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            printInvoice();
        }
        // Ctrl+S to share
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            shareInvoice();
        }
        // Ctrl+D to download
        if (e.ctrlKey && e.key === 'd') {
            e.preventDefault();
            downloadPDF();
        }
    });
</script>
@endsection