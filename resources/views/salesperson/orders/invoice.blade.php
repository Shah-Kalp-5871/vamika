@extends('layouts.salesperson')

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
@endphp

<div class="max-w-3xl mx-auto min-h-screen pb-24 bg-gray-50 sm:py-8">
    <!-- Actions Bar -->
    <div class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-gray-200 px-4 py-3 mb-6 flex items-center justify-between sm:rounded-xl sm:mx-4 sm:top-4 section-no-print">
        <div class="flex items-center gap-3">
            <a href="{{ route('salesperson.orders.review', $order->id) }}" class="p-2 -ml-2 rounded-full hover:bg-gray-100 text-gray-600 transition-colors">
                <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
            </a>
            <span class="font-bold text-gray-800">Invoice #{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="shareInvoice()" class="flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all font-medium text-sm shadow-sm md:shadow-md active:scale-95">
                <iconify-icon icon="logos:whatsapp-icon" width="18"></iconify-icon>
                <span class="hidden sm:inline">Share on WhatsApp</span>
                <span class="sm:hidden">Share</span>
            </button>
            <button onclick="window.print()" class="p-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                <iconify-icon icon="lucide:printer" width="20"></iconify-icon>
            </button>
        </div>
    </div>

    <!-- Invoice Content -->
    <div id="invoice-content" class="bg-white mx-auto shadow-sm sm:shadow-lg sm:rounded-xl overflow-hidden print-container" style="max-width: 210mm; min-height: 297mm; position: relative;">
        <!-- Watermark -->
        <!-- <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none z-0">
            <img src="{{ asset('assets/images/logo.png') }}" class="w-[500px] h-[500px] object-contain grayscale">
        </div> -->

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
                            <p>123, Business Park, Main Road</p>
                            <p>Ahmedabad, Gujarat - 380001</p>
                            <p>GSTIN: <span class="font-mono font-medium text-slate-700">24ABCDE1234F1Z5</span></p>
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
                                <!-- <p class="text-xs text-slate-600 mt-0.5"><span class="font-semibold">GSTIN:</span> Unregistered</p> -->
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
                                <td class="px-4 py-3 text-center text-slate-500 font-mono">3304</td> <!-- Placeholder HSN -->
                                <td class="px-4 py-3 text-center font-bold text-slate-900">{{ $item->quantity }}</td>
                                <td class="px-4 py-3 text-right text-slate-600 font-mono">{{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-3 text-right font-bold text-slate-900 font-mono">{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                            @endforeach
                            <!-- Fill empty rows to maintain height if needed, or just leave as is -->
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
                    <!-- <img src="" class="h-12 w-auto mx-auto mb-2 opacity-80" alt="Signature"> -->
                    <div class="h-12 w-full"></div> <!-- Placeholder for branding/signature -->
                    <p class="text-xs font-bold text-slate-900">VAMIKA ENTERPRISE</p>
                    <p class="text-[9px] text-slate-400 uppercase tracking-wider mt-1 border-t border-slate-300 pt-1">Authorised Signatory</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        @page { size: A4; margin: 0; }
        body { background: white; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        .section-no-print { display: none !important; }
        .print-container { box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: none !important; border-radius: 0 !important; }
        .bg-gray-50 { background: white !important; }
    }
</style>

<script>
    async function shareInvoice() {
        const element = document.getElementById('invoice-content');
        const invoiceNo = "INV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}";
        const shopName = "{{ $order->shop->name }}";
        
        // Show loading state
        const btn = document.querySelector('button[onclick="shareInvoice()"]');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<iconify-icon icon="lucide:loader-2" width="18" class="animate-spin"></iconify-icon> Generating...';
        btn.disabled = true;

        try {
            // Options for html2pdf
            const opt = {
                margin: 0,
                filename: `Invoice_${invoiceNo}.pdf`,
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, scrollY: 0 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
            };

            // Generate blob
            const pdfBlob = await html2pdf().set(opt).from(element).output('blob');
            const file = new File([pdfBlob], `Invoice_${invoiceNo}.pdf`, { type: 'application/pdf' });

            // Web Share API
            if (navigator.share && navigator.canShare({ files: [file] })) {
                await navigator.share({
                    files: [file],
                    title: `Invoice ${invoiceNo} - Vamika Enterprise`,
                    text: `Hello ${shopName}, Here is your invoice ${invoiceNo} from Vamika Enterprise.`
                });
                
                Swal.fire({
                    icon: 'success',
                    title: 'Shared!',
                    text: 'Invoice shared successfully',
                    timer: 1500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'bottom-end'
                });
            } else {
                // Fallback: Download and open WhatsApp with text
                const url = window.URL.createObjectURL(pdfBlob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Invoice_${invoiceNo}.pdf`;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                window.URL.revokeObjectURL(url);

                Swal.fire({
                    icon: 'info',
                    title: 'Downloaded',
                    text: 'PDF downloaded. Sharing files directly is not supported on this browser/device.',
                    confirmButtonText: 'Open WhatsApp',
                    showCancelButton: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const text = encodeURIComponent(`Hello ${shopName}, Here is your invoice ${invoiceNo} from Vamika Enterprise. Please find the attached file.`);
                        window.open(`https://wa.me/?text=${text}`, '_blank');
                    }
                });
            }
        } catch (error) {
            console.error('Sharing failed:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to generate or share invoice.',
            });
        } finally {
            btn.innerHTML = originalContent;
            btn.disabled = false;
        }
    }
</script>
@endsection