<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Vamika Enterprise</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bottom-nav.css') }}">

    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 10mm;
            }

            body * {
                visibility: hidden;
            }

            #print-area,
            #print-area * {
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
        }
    </style>
</head>

<body>
    <div id="print-area">
        <div class="max-w-full mx-auto p-3 bg-white border text-[11px] font-mono">
            <!-- HEADER -->
            <div class="flex justify-between border-b pb-2 mb-2">
                <div>
                    <p class="font-bold text-lg">VAMIKA ENTERPRISE</p>
                    <p>Wholesale Distributor</p>
                    <p>Delhi NCR, India</p>
                    <p>GSTIN: 07AABCU9603R1ZM</p>
                </div>

                <div class="text-right">
                    <p class="font-bold text-base">TAX INVOICE</p>
                    <p>Invoice No: <span id="invoiceNumber"></span></p>
                    <p>Date: <span id="invoiceDate"></span></p>
                    <p>Time: <span id="invoiceTime"></span></p>
                </div>
            </div>

            <!-- CUSTOMER -->
            <div class="border-b pb-2 mb-2">
                <p class="font-semibold">Bill To:</p>
                <p id="customerName"></p>
                <p id="customerAddress"></p>
                <p id="customerPhone"></p>
            </div>

            <!-- TABLE -->
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border text-[11px] min-w-[1000px]">
                    <thead class="bg-slate-100">
                        <tr>
                            <th class="border p-1">#</th>
                            <th class="border p-1 text-left">Item</th>
                            <th class="border p-1">HSN</th>
                            <th class="border p-1">Qty</th>
                            <th class="border p-1">Rate</th>
                            <th class="border p-1">Taxable</th>
                            <th class="border p-1">CGST</th>
                            <th class="border p-1">SGST</th>
                            <th class="border p-1">Amount</th>
                        </tr>
                    </thead>

                    <tbody id="invoiceItems"></tbody>

                    <!-- TOTAL ROW -->
                    <tfoot>
                        <tr class="font-semibold bg-slate-50">
                            <td class="border p-1 text-center" colspan="3">TOTAL</td>
                            <td class="border p-1 text-center" id="totalQtyCell">0</td>
                            <td class="border p-1"></td>
                            <td class="border p-1"></td>
                            <td class="border p-1"></td>
                            <td class="border p-1"></td>
                            <td class="border p-1 text-right" id="totalAmountCell">₹0</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="flex justify-between mt-3">
                <!-- AMOUNT IN WORDS -->
                <div class="text-[11px] w-2/3">
                    <strong>Amount in Words :</strong>
                    <p id="amountInWords" class="italic mt-1"></p>
                </div>

                <!-- GRAND TOTAL -->
                <table class="text-[11px]">
                    <tr class="font-bold border-t">
                        <td class="pr-6 pt-1">Grand Total</td>
                        <td class="pt-1">₹<span id="invTotal"></span></td>
                    </tr>
                </table>
            </div>

            <!-- FOOTER -->
            <div class="mt-4 border-t pt-2 text-[10px] flex justify-between">
                <div>
                    <p>Goods once sold will not be taken back</p>
                    <p>E & O.E</p>
                </div>
                <div class="text-right">
                    <p>For Vamika Enterprise</p>
                    <div class="mt-6 border-t w-32 text-center">Authorised Signatory</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- ACTION BAR -->
    <div class="no-print sticky bottom-0 bg-white border-t p-3 flex gap-3 justify-end z-50">
        <button onclick="shareInvoice()"
            class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm flex items-center gap-2 hover:bg-indigo-700">
            <iconify-icon icon="lucide:share-2" width="16"></iconify-icon>
            Share
        </button>

        <button onclick="printInvoice()"
            class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm flex items-center gap-2 hover:bg-emerald-700">
            <iconify-icon icon="lucide:printer" width="16"></iconify-icon>
            Print
        </button>
    </div>

    @include('layouts.partials.bottom-nav.sales')

    <script>
        function loadInvoice() {
            const order = JSON.parse(localStorage.getItem('currentOrder'));

            if (!order) {
                alert('No order found!');
                window.location.href = "{{ route('salesperson.dashboard') }}";
                return;
            }

            // Set invoice details
            document.getElementById('invoiceNumber').textContent = order.orderNumber;
            document.getElementById('customerName').textContent = order.shop.name;
            document.getElementById('customerAddress').textContent = order.shop.address || order.area.name;
            document.getElementById('customerPhone').textContent = `Phone: ${order.shop.phone}`;
            document.getElementById('invoiceDate').textContent = order.date;
            document.getElementById('invoiceTime').textContent = order.time;

            // Set items
            const itemsTable = document.getElementById('invoiceItems');
            let totalQty = 0;
            let totalAmount = 0;

            order.items.forEach((item, index) => {
                totalQty += item.quantity;
                totalAmount += item.total;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="border p-1 text-center">${index + 1}</td>
                    <td class="border p-1">${item.name} (${item.unit})</td>
                    <td class="border p-1 text-center">170490</td>
                    <td class="border p-1 text-center">${item.quantity}</td>
                    <td class="border p-1 text-right">₹${item.price}</td>
                    <td class="border p-1 text-right">₹${item.total}</td>
                    <td class="border p-1 text-right">6.5</td>
                    <td class="border p-1 text-right">6.5</td>
                    <td class="border p-1 text-right font-semibold">₹${item.total}</td>
                `;
                itemsTable.appendChild(row);
            });

            // Set totals inside table
            document.getElementById('totalQtyCell').textContent = totalQty;
            document.getElementById('totalAmountCell').textContent = `₹${totalAmount}`;

            // Amount in words
            document.getElementById('amountInWords').textContent =
                'Rupees ' + numberToWords(Math.round(totalAmount)) + ' Only';

            // Final amount (right side)
            document.getElementById('invTotal').textContent = totalAmount;
        }

        function printInvoice() {
            window.print();
        }

        function shareInvoice() {
            // Simulate WhatsApp sharing
            const order = JSON.parse(localStorage.getItem('currentOrder'));
            const message = `Invoice ${order.orderNumber} from Vamika Enterprise\nTotal: ₹${order.total} \nDate: ${order.date} `;

            if (navigator.share) {
                navigator.share({
                    title: 'Invoice from Vamika Enterprise',
                    text: message,
                    url: window.location.href
                });
            } else {
                // Fallback for non-share API browsers
                const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
                window.open(whatsappUrl, '_blank');
            }
        }

        function goToDashboard() {
            // Clear temporary data
            localStorage.removeItem('cartItems');
            localStorage.removeItem('selectedShop');
            localStorage.removeItem('selectedArea');
            localStorage.removeItem('currentOrder');

            window.location.href = "{{ route('salesperson.dashboard') }}";
        }

        function numberToWords(num) {
            const a = [
                '', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine',
                'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen',
                'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'
            ];
            const b = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

            if (num === 0) return 'Zero';

            if (num < 20) return a[num];

            if (num < 100)
                return b[Math.floor(num / 10)] + (num % 10 ? ' ' + a[num % 10] : '');

            if (num < 1000)
                return a[Math.floor(num / 100)] + ' Hundred ' + (num % 100 ? numberToWords(num % 100) : '');

            if (num < 100000)
                return numberToWords(Math.floor(num / 1000)) + ' Thousand ' + (num % 1000 ? numberToWords(num % 1000) : '');

            if (num < 10000000)
                return numberToWords(Math.floor(num / 100000)) + ' Lakh ' + (num % 100000 ? numberToWords(num % 100000) : '');

            return numberToWords(Math.floor(num / 10000000)) + ' Crore ' + (num % 10000000 ? numberToWords(num % 10000000) : '');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', loadInvoice);
    </script>
</body>

</html>