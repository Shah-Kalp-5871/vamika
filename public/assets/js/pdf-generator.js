// PDF Generator for Invoices

function generatePDFInvoice(orderData) {
    // Create a new jsPDF instance
    const doc = new jsPDF();

    // Company Header
    doc.setFontSize(20);
    doc.setTextColor(46, 125, 50); // Green color
    doc.text('VAMIKA ENTERPRISE', 105, 20, { align: 'center' });

    doc.setFontSize(10);
    doc.setTextColor(0, 0, 0);
    doc.text('Wholesale Distributor', 105, 27, { align: 'center' });
    doc.text('Delhi NCR, India | GSTIN: ' + (orderData.gstin || window.appGstin || 'N/A'), 105, 32, { align: 'center' });
    doc.text('Phone: 011-12345678 | Email: info@vamikaenterprise.com', 105, 37, { align: 'center' });

    // Invoice Title
    doc.setFontSize(16);
    doc.text('TAX INVOICE', 105, 50, { align: 'center' });

    doc.setFontSize(10);
    doc.text(`Invoice No: ${orderData.orderNumber}`, 15, 60);
    doc.text(`Date: ${orderData.date}`, 15, 65);
    doc.text(`Time: ${orderData.time}`, 15, 70);

    // Customer Details
    doc.setFontSize(12);
    doc.text('Bill To:', 15, 85);
    doc.setFontSize(10);
    doc.text(orderData.shop.name, 15, 92);
    doc.text(orderData.shop.address, 15, 97);
    doc.text(`Phone: ${orderData.shop.phone}`, 15, 102);
    doc.text(`Area: ${orderData.area.name}`, 15, 107);

    // Salesperson Info
    doc.text('Salesperson:', 150, 85);
    doc.text(dummyData.salesperson.name, 150, 92);
    doc.text(`Area: ${dummyData.salesperson.assignedArea}`, 150, 97);

    // Line separator
    doc.setDrawColor(0);
    doc.line(15, 115, 195, 115);

    // Table Header
    doc.setFontSize(11);
    doc.setTextColor(255, 255, 255);
    doc.setFillColor(76, 175, 80);
    doc.rect(15, 120, 180, 10, 'F');

    doc.text('S.No.', 20, 127);
    doc.text('Product Description', 40, 127);
    doc.text('Qty', 120, 127);
    doc.text('Unit Price', 140, 127);
    doc.text('Total', 170, 127);

    // Table Rows
    doc.setTextColor(0, 0, 0);
    let yPos = 135;

    orderData.items.forEach((item, index) => {
        doc.setFontSize(10);
        doc.text((index + 1).toString(), 20, yPos);
        doc.text(item.name, 40, yPos);
        doc.text(item.unit, 40, yPos + 5);
        doc.text(item.quantity.toString(), 120, yPos);
        doc.text(`₹${item.price}`, 140, yPos);
        doc.text(`₹${item.total}`, 170, yPos);
        yPos += 15;
    });

    // Summary
    yPos += 10;
    doc.line(120, yPos, 195, yPos);
    yPos += 10;

    doc.text('Subtotal:', 140, yPos);
    doc.text(`₹${orderData.subtotal}`, 170, yPos);
    yPos += 8;

    doc.text('Delivery Charge:', 140, yPos);
    doc.text(`₹${orderData.delivery}`, 170, yPos);
    yPos += 8;

    doc.setFontSize(12);
    doc.setFont(undefined, 'bold');
    doc.text('Total Payable:', 140, yPos);
    doc.text(`₹${orderData.total}`, 170, yPos);

    // Payment Info
    yPos += 15;
    doc.setFontSize(10);
    doc.setFont(undefined, 'normal');
    doc.text('Payment Method: Cash on Delivery', 15, yPos);
    doc.text('Payment Status: Pending', 15, yPos + 5);

    // Notes
    if (orderData.notes) {
        yPos += 15;
        doc.text('Notes:', 15, yPos);
        doc.text(orderData.notes, 15, yPos + 5);
    }

    // Footer
    yPos += 25;
    doc.setFontSize(9);
    doc.setTextColor(100, 100, 100);
    doc.text('Thank you for your business!', 105, yPos, { align: 'center' });
    doc.text('Goods once sold will not be taken back', 105, yPos + 5, { align: 'center' });
    doc.text('E. & O.E.', 105, yPos + 10, { align: 'center' });

    // Signature
    yPos += 25;
    doc.line(15, yPos, 80, yPos);
    doc.text('Customer Signature', 47, yPos + 5, { align: 'center' });

    doc.line(120, yPos, 185, yPos);
    doc.text('Authorized Signature', 152, yPos + 5, { align: 'center' });

    // Save the PDF
    doc.save(`invoice_${orderData.orderNumber}.pdf`);
}

// Export function for use in other files
window.generatePDFInvoice = generatePDFInvoice;