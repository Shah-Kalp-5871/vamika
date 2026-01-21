@extends('layouts.shop-owner')

@php
$title = 'Checkout';
$subtitle = 'Confirm your order';
$role = 'Shop Owner';
$showBack = true;
$backUrl = route('shop-owner.cart.index');
@endphp

@section('content')
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
        padding-bottom: 140px;
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

    /* Main content container - MOBILE ONLY */
    .main-content {
        max-width: 100%;
        margin: 0 auto;
        min-height: 100vh;
        background-color: #FAFAFA;
    }

    /* Checkout footer - fixed at bottom */
    .checkout-footer {
        position: fixed;
        bottom: 80px;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #E2E8F0;
        padding: 16px;
        box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.1);
        z-index: 40;
    }

    /* Custom animations */
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-up {
        animation: slideUp 0.5s ease forwards;
    }

    /* Progress steps */
    .progress-steps {
        display: flex;
        justify-content: space-between;
        padding: 20px;
        background: white;
        position: relative;
    }

    .progress-steps::before {
        content: '';
        position: absolute;
        top: 30px;
        left: 20%;
        right: 20%;
        height: 2px;
        background: #E2E8F0;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        z-index: 2;
        position: relative;
    }

    .step-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-weight: 600;
        color: #64748B;
        margin-bottom: 8px;
        transition: all 0.3s ease;
    }

    .step.active .step-icon {
        background: #4F46E5;
        color: white;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
    }

    .step-label {
        font-size: 12px;
        font-weight: 500;
        color: #64748B;
    }

    .step.active .step-label {
        color: #4F46E5;
        font-weight: 600;
    }

    /* Checkout section cards */
    .checkout-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #E2E8F0;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .checkout-card:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    /* Payment option */
    .payment-option {
        border: 2px solid #E2E8F0;
        border-radius: 8px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .payment-option.selected {
        border-color: #4F46E5;
        background: #F5F3FF;
    }

    .payment-option:hover:not(.selected) {
        border-color: #CBD5E1;
    }

    /* Terms checkbox */
    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px;
        background: #F8FAFC;
        border-radius: 8px;
        margin: 16px 0;
    }

    /* Hide on larger screens */
    @media (min-width: 768px) {
        body::before {
            content: "Mobile Only View - Please view on mobile device";
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999999;
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            text-align: center;
            padding: 20px;
        }
    }

    /* Loading spinner */
    .loading-spinner {
        display: inline-block;
        width: 1rem;
        height: 1rem;
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

<!-- Main Content -->
<div class="main-content">
    <!-- Progress Steps -->
    <div class="progress-steps">
        <div class="step active">
            <div class="step-icon">1</div>
            <div class="step-label">Delivery</div>
        </div>
        <div class="step">
            <div class="step-icon">2</div>
            <div class="step-label">Payment</div>
        </div>
        <div class="step">
            <div class="step-icon">3</div>
            <div class="step-label">Confirm</div>
        </div>
    </div>

    <main class="p-4 space-y-4">
        <!-- Delivery Address -->
        <div class="checkout-card animate-slide-up">
            <div class="p-4 border-b border-slate-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-slate-900">Delivery Address</h3>
                    <button onclick="editAddress()"
                        class="text-xs font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1">
                        <iconify-icon icon="lucide:edit-2" width="12"></iconify-icon>
                        Edit
                    </button>
                </div>
            </div>
            <div class="p-4">
                <div class="flex items-start gap-3">
                    <div
                        class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100">
                        <iconify-icon icon="lucide:home" width="16" class="text-indigo-600"></iconify-icon>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-slate-900" id="deliveryName">Loading...</h4>
                        <p class="text-xs text-slate-500 mt-1" id="deliveryAddress">Loading...</p>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-xs text-slate-400 flex items-center gap-1">
                                <iconify-icon icon="lucide:phone" width="10"></iconify-icon>
                                <span id="deliveryPhone">Loading...</span>
                            </span>
                            <span class="text-xs text-slate-400 flex items-center gap-1">
                                <iconify-icon icon="lucide:map-pin" width="10"></iconify-icon>
                                <span id="deliveryArea">Loading...</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method -->
        <div class="checkout-card animate-slide-up" style="animation-delay: 0.1s;">
            <div class="p-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Payment Method</h3>
            </div>
            <div class="p-4 space-y-3">
                <!-- COD Option -->
                <div class="payment-option selected" onclick="selectPayment('cod')" id="codOption">
                    <div
                        class="h-10 w-10 rounded-lg bg-emerald-50 flex items-center justify-center border border-emerald-100">
                        <iconify-icon icon="lucide:banknote" width="16" class="text-emerald-600"></iconify-icon>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-slate-900">Cash on Delivery</h4>
                        <p class="text-xs text-slate-500">Pay when you receive the order</p>
                    </div>
                </div>

                <!-- Wallet Option -->
                <div class="payment-option" onclick="selectPayment('wallet')" id="walletOption">
                    <div
                        class="h-10 w-10 rounded-lg bg-indigo-50 flex items-center justify-center border border-indigo-100">
                        <iconify-icon icon="lucide:wallet" width="16" class="text-indigo-600"></iconify-icon>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-slate-900">Wallet Balance</h4>
                        <p class="text-xs text-slate-500">Available: ₹<span id="checkoutWalletBalance">0</span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="checkout-card animate-slide-up" style="animation-delay: 0.2s;">
            <div class="p-4 border-b border-slate-100">
                <h3 class="text-sm font-semibold text-slate-900">Order Summary</h3>
            </div>
            <div class="p-4">
                <!-- Items -->
                <div id="orderItems" class="space-y-3 mb-4">
                    <!-- Items will be loaded here -->
                    <div class="flex items-center justify-center py-4">
                        <div class="loading-spinner"></div>
                    </div>
                </div>

                <!-- Price Breakdown -->
                <div class="space-y-2 pt-4 border-t border-slate-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-medium text-slate-900">₹<span id="checkoutSubtotal">0</span></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Delivery Charge</span>
                        <span class="font-medium text-slate-900">₹<span id="checkoutDelivery">0</span></span>
                    </div>
                    <div class="flex justify-between text-sm hidden" id="walletDiscountRow">
                        <span class="text-slate-500">Wallet Discount</span>
                        <span class="font-medium text-emerald-600">-₹<span id="checkoutDiscount">0</span></span>
                    </div>
                    <div class="flex justify-between text-sm pt-2 border-t border-slate-100">
                        <span class="font-semibold text-slate-900">Total Payable</span>
                        <span class="text-lg font-bold text-emerald-600">₹<span id="checkoutTotal">0</span></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="terms-checkbox animate-slide-up" style="animation-delay: 0.3s;">
            <input type="checkbox" id="acceptTerms" class="mt-0.5">
            <label for="acceptTerms" class="text-sm text-slate-600">
                I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-700">Terms & Conditions</a> and
                confirm this order
            </label>
        </div>
    </main>
</div>

<!-- Checkout Footer - Fixed at Bottom -->
<div class="checkout-footer">
    <button id="placeOrderButton" onclick="placeOrder()"
        class="w-full px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
        <iconify-icon icon="lucide:check-circle" width="18"></iconify-icon>
        Place Order
    </button>
</div>
@endsection

@section('scripts')
<script>
    let checkoutData = {};
    let selectedPayment = 'cod';

    // Dummy data - In real app, this would come from Laravel controller
    const dummyData = {
        shopOwner: {
            name: "Rajesh Kumar",
            shopName: "Super Mart",
            address: "123 Main Street, Mumbai, Maharashtra 400001",
            phone: "+91 9876543210",
            walletBalance: 12500
        }
    };

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        loadCheckoutPage();
    });

    function loadCheckoutPage() {
        // Load checkout data from localStorage
        const savedCheckout = localStorage.getItem('checkout_details');
        if (!savedCheckout) {
            showToast({
                text: 'No checkout data found. Redirecting to cart…',
                type: 'info'
            });

            setTimeout(() => {
                window.location.href = '{{ route('shop-owner.cart.index') }}';
            }, 1500);
            return;
        }

        checkoutData = JSON.parse(savedCheckout);

        if (!checkoutData.items || checkoutData.items.length === 0) {
            showToast({
                text: 'No items in cart. Redirecting to shop…',
                type: 'info'
            });

            setTimeout(() => {
                window.location.href = '{{ route('shop-owner.products.index') }}';
            }, 1500);
            return;
        }

        // Load delivery address
        loadDeliveryAddress();

        // Load wallet balance
        const walletBalance = dummyData.shopOwner.walletBalance || 0;
        document.getElementById('checkoutWalletBalance').textContent = walletBalance.toLocaleString();

        // Load order summary
        loadOrderSummary();

        // Enable/disable wallet option based on balance
        if (walletBalance <= 0) {
            document.getElementById('walletOption').style.opacity = '0.5';
            document.getElementById('walletOption').style.cursor = 'not-allowed';
        }
    }

    function loadDeliveryAddress() {
        const shop = dummyData.shopOwner;
        document.getElementById('deliveryName').textContent = shop.name;
        document.getElementById('deliveryAddress').textContent = shop.address;
        document.getElementById('deliveryPhone').textContent = shop.phone;

        // Determine area from address
        const area = shop.address.includes('Mumbai') ? 'Mumbai Central' :
                    shop.address.includes('Delhi') ? 'Delhi NCR' :
                    shop.address.includes('Bangalore') ? 'Bangalore South' : 'Local Area';
        document.getElementById('deliveryArea').textContent = area;
    }

    function loadOrderSummary() {
        const itemsContainer = document.getElementById('orderItems');
        itemsContainer.innerHTML = '';

        // Add items
        checkoutData.items.forEach((item, index) => {
            const itemRow = document.createElement('div');
            itemRow.className = 'flex items-center justify-between';
            itemRow.innerHTML = `
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded bg-slate-50 flex items-center justify-center border border-slate-200">
                            <iconify-icon icon="lucide:package" width="12" class="text-slate-600"></iconify-icon>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-slate-900">${item.name}</p>
                            <p class="text-[10px] text-slate-500">${item.unit} • Qty: ${item.quantity}</p>
                        </div>
                    </div>
                    <span class="text-sm font-medium text-slate-900">₹${item.total}</span>
                `;
            itemsContainer.appendChild(itemRow);
        });

        // Update prices
        document.getElementById('checkoutSubtotal').textContent = checkoutData.subtotal.toLocaleString();
        document.getElementById('checkoutDelivery').textContent = checkoutData.delivery.toLocaleString();
        document.getElementById('checkoutTotal').textContent = checkoutData.total.toLocaleString();

        // Show/hide wallet discount
        const walletDiscountRow = document.getElementById('walletDiscountRow');
        if (checkoutData.walletDiscount > 0) {
            document.getElementById('checkoutDiscount').textContent = checkoutData.walletDiscount.toLocaleString();
            walletDiscountRow.classList.remove('hidden');
        } else {
            walletDiscountRow.classList.add('hidden');
        }
    }

    function editAddress() {
        // In a real app, this would open an address editor modal
        showToast({
            text: 'Address editor would open here in production',
            type: 'info'
        });
        
        // Example modal implementation
        const newAddress = prompt('Enter new delivery address:', dummyData.shopOwner.address);
        if (newAddress) {
            dummyData.shopOwner.address = newAddress;
            loadDeliveryAddress();
            showToast({
                text: 'Delivery address updated',
                type: 'success'
            });
        }
    }

    function selectPayment(method) {
        // Don't allow selecting wallet if balance is zero
        if (method === 'wallet' && dummyData.shopOwner.walletBalance <= 0) {
            showToast({
                text: 'Insufficient wallet balance. Please choose another payment method.',
                type: 'error'
            });
            return;
        }

        selectedPayment = method;

        // Update UI
        document.getElementById('codOption').classList.remove('selected');
        document.getElementById('walletOption').classList.remove('selected');

        if (method === 'cod') {
            document.getElementById('codOption').classList.add('selected');
            // Remove wallet discount
            checkoutData.walletDiscount = 0;
        } else {
            document.getElementById('walletOption').classList.add('selected');
            // Apply wallet discount (up to 50% of subtotal or wallet balance)
            const maxDiscount = Math.min(checkoutData.subtotal * 0.5, dummyData.shopOwner.walletBalance);
            checkoutData.walletDiscount = Math.min(maxDiscount, checkoutData.subtotal);
        }

        // Recalculate total
        checkoutData.total = Math.max(0, checkoutData.subtotal + checkoutData.delivery - checkoutData.walletDiscount);

        // Update display
        loadOrderSummary();
        
        showToast({
            text: `Payment method set to ${method === 'cod' ? 'Cash on Delivery' : 'Wallet Balance'}`,
            type: 'success'
        });
    }

    function placeOrder() {
        // Validate terms acceptance
        if (!document.getElementById('acceptTerms').checked) {
            showToast({
                text: 'Please accept the terms and conditions to proceed',
                type: 'error'
            });
            return;
        }

        // Validate payment method selection
        if (!selectedPayment) {
            showToast({
                text: 'Please select a payment method',
                type: 'error'
            });
            return;
        }

        // Generate order number
        const timestamp = Date.now();
        const orderNumber = 'ORD' + timestamp.toString().slice(-8);

        // Create order object
        const order = {
            id: timestamp,
            orderNumber: orderNumber,
            date: new Date().toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }),
            time: new Date().toLocaleTimeString('en-IN', { hour: '2-digit', minute: '2-digit' }),
            items: checkoutData.items,
            subtotal: checkoutData.subtotal,
            delivery: checkoutData.delivery,
            discount: checkoutData.walletDiscount,
            total: checkoutData.total,
            paymentMethod: selectedPayment === 'cod' ? 'Cash on Delivery' : 'Wallet Balance',
            status: 'Pending',
            address: dummyData.shopOwner.address,
            phone: dummyData.shopOwner.phone,
            deliveryPerson: 'Rajesh Kumar',
            deliveryTime: '10:30 AM - 12:00 PM'
        };

        // Save order to localStorage
        const orders = JSON.parse(localStorage.getItem('shop_orders') || '[]');
        orders.unshift(order); // Add to beginning for newest first
        localStorage.setItem('shop_orders', JSON.stringify(orders));

        // Update wallet balance if wallet payment was used
        if (selectedPayment === 'wallet') {
            dummyData.shopOwner.walletBalance -= checkoutData.walletDiscount;
            // Save updated wallet balance
            localStorage.setItem('shop_owner', JSON.stringify(dummyData.shopOwner));
        }

        // Clear cart and checkout data
        localStorage.removeItem('shop_cart');
        localStorage.removeItem('checkout_details');

        // Show success message
        showToast({
            text: 'Order placed successfully! Order #' + orderNumber,
            type: 'success'
        });

        // Redirect to orders page after delay
        setTimeout(() => {
            window.location.href = '{{ route('shop-owner.orders.index') }}';
        }, 2000);
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
            type === 'info' ? 'bg-indigo-500 text-white' :
            'bg-slate-500 text-white'
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
        // Ctrl+Enter to place order
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            placeOrder();
        }
        // 1 for COD, 2 for Wallet
        if (e.key === '1' && !e.ctrlKey) {
            e.preventDefault();
            selectPayment('cod');
        }
        if (e.key === '2' && !e.ctrlKey) {
            e.preventDefault();
            selectPayment('wallet');
        }
        // Space to toggle terms
        if (e.key === ' ' && !e.ctrlKey) {
            e.preventDefault();
            const termsCheckbox = document.getElementById('acceptTerms');
            termsCheckbox.checked = !termsCheckbox.checked;
        }
    });

    // Add a help tooltip for keyboard shortcuts
    window.addEventListener('load', function() {
        const helpBtn = document.createElement('button');
        helpBtn.className = 'fixed bottom-24 right-4 z-40 h-10 w-10 rounded-full bg-indigo-600 text-white shadow-lg flex items-center justify-center';
        helpBtn.innerHTML = '<iconify-icon icon="lucide:keyboard" width="20"></iconify-icon>';
        helpBtn.title = 'Keyboard Shortcuts\nCtrl+Enter: Place Order\n1: COD Payment\n2: Wallet Payment\nSpace: Toggle Terms';
        helpBtn.onclick = function() {
            alert('Keyboard Shortcuts:\n\n' +
                  '• Ctrl+Enter: Place Order\n' +
                  '• 1: Select COD Payment\n' +
                  '• 2: Select Wallet Payment\n' +
                  '• Space: Toggle Terms Acceptance');
        };
        document.body.appendChild(helpBtn);
    });
</script>
@endsection