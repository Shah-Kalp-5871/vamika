@extends('layouts.salesperson')

@section('content')
<!-- Mobile/Tablet View -->
<div class="block sm:hidden">
    <div class="min-h-screen pb-24">
        <!-- Main Content - Mobile -->
        <main class="p-4 space-y-4">
            <!-- Order Items -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-slate-900">Order Items</h3>
                    <span id="itemCountMobile" class="text-xs text-slate-500">0 items</span>
                </div>

                <div id="orderItemsMobile" class="space-y-3">
                    <!-- Items will be loaded here -->
                    <div class="py-8 text-center">
                        <div class="animate-spin inline-block w-6 h-6 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                            role="status" aria-label="loading"></div>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="emptyStateMobile" class="hidden flex flex-col items-center justify-center py-8 text-slate-400">
                    <iconify-icon icon="lucide:shopping-cart" width="32" class="mb-3 opacity-50"></iconify-icon>
                    <p class="text-sm font-medium">No items in order</p>
                    <p class="text-xs mt-1">Add products to continue</p>
                </div>
            </div>

            <!-- Order Summary - Mobile -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-4">Order Summary</h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Subtotal</span>
                        <span class="text-sm font-medium text-slate-900" id="subtotalMobile">₹0</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-sm text-slate-600">Delivery Charge</span>
                        <span class="text-sm font-medium text-slate-900" id="deliveryMobile">₹50</span>
                    </div>

                    <!-- Discount -->
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-600">Discount</span>
                            <button onclick="showDiscountModal('Mobile')"
                                class="text-xs px-2 py-1 rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors">
                                Apply
                            </button>
                        </div>
                        <span class="text-sm font-medium text-emerald-600" id="discountMobile">-₹0</span>
                    </div>

                    <div class="border-t border-slate-200 pt-3 mt-3">
                        <div class="flex justify-between items-center">
                            <span class="text-base font-semibold text-slate-900">Total Amount</span>
                            <span class="text-lg font-bold text-slate-900" id="totalMobile">₹0</span>
                        </div>
                        <p class="text-xs text-slate-400 mt-1 text-right">Inclusive of all taxes</p>
                    </div>
                </div>
            </div>

            <!-- Order Notes - Mobile -->
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-900 mb-3">Order Notes (Optional)</h3>
                <textarea id="orderNotesMobile" placeholder="Add any special instructions or notes..."
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all resize-none"
                    rows="3"></textarea>
            </div>

            <!-- Action Buttons - Mobile -->
            <div class="space-y-3">
                <button onclick="placeOrder()"
                    class="w-full px-6 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 active:scale-95 transition-all shadow-md flex items-center justify-center gap-2">
                    <iconify-icon icon="lucide:check-circle" width="18"></iconify-icon>
                    Place Order & Generate Invoice
                </button>
                <button onclick="goBack()"
                    class="w-full px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium hover:bg-slate-50 active:scale-95 transition-all">
                    Back to Products
                </button>
            </div>
        </main>
    </div>
</div>

<!-- Desktop View -->
<div class="hidden sm:block">
    <div class="max-w-6xl mx-auto min-h-screen py-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <!-- Content - Desktop -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column: Order Items -->
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-slate-200 rounded-xl p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-base font-semibold text-slate-900">Order Items</h3>
                                <div class="flex items-center gap-4">
                                    <span id="itemCountDesktop" class="text-sm text-slate-500">0 items</span>
                                    <button onclick="goBack()"
                                        class="text-sm px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition-colors">
                                        Edit Products
                                    </button>
                                </div>
                            </div>

                            <div id="orderItemsDesktop" class="space-y-4">
                                <!-- Items will be loaded here -->
                                <div class="py-12 text-center">
                                    <div class="animate-spin inline-block w-8 h-8 border-[3px] border-current border-t-transparent text-slate-400 rounded-full"
                                        role="status" aria-label="loading"></div>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div id="emptyStateDesktop"
                                class="hidden flex flex-col items-center justify-center py-12 text-slate-400">
                                <iconify-icon icon="lucide:shopping-cart" width="48"
                                    class="mb-4 opacity-50"></iconify-icon>
                                <p class="text-sm font-medium">No items in order</p>
                                <p class="text-xs mt-1">Add products to continue</p>
                            </div>
                        </div>

                        <!-- Order Notes - Desktop -->
                        <div class="bg-white border border-slate-200 rounded-xl p-6 mt-6">
                            <h3 class="text-base font-semibold text-slate-900 mb-4">Order Notes (Optional)</h3>
                            <textarea id="orderNotesDesktop"
                                placeholder="Add any special instructions, delivery notes, or comments..."
                                class="w-full px-4 py-3 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all resize-none"
                                rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Right Column: Order Summary -->
                    <div>
                        <div class="bg-white border border-slate-200 rounded-xl p-6 sticky top-6">
                            <h3 class="text-base font-semibold text-slate-900 mb-6">Order Summary</h3>

                            <div class="space-y-4">
                                <!-- Subtotal -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600">Subtotal</span>
                                    <span class="text-sm font-medium text-slate-900" id="subtotalDesktop">₹0</span>
                                </div>

                                <!-- Delivery -->
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-slate-600">Delivery Charge</span>
                                    <span class="text-sm font-medium text-slate-900" id="deliveryDesktop">₹50</span>
                                </div>

                                <!-- Discount -->
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm text-slate-600">Discount</span>
                                        <button onclick="showDiscountModal('Desktop')"
                                            class="text-xs px-3 py-1 rounded-lg border border-slate-200 bg-white text-slate-500 hover:bg-slate-50 transition-colors">
                                            Apply Discount
                                        </button>
                                    </div>
                                    <span class="text-sm font-medium text-emerald-600" id="discountDesktop">-₹0</span>
                                </div>

                                <!-- Applied Discount (if any) -->
                                <div id="discountDetailsDesktop"
                                    class="hidden p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <iconify-icon icon="lucide:tag" width="14"
                                                class="text-emerald-600"></iconify-icon>
                                            <span class="text-sm text-emerald-800" id="discountTypeDesktop">10%
                                                Discount</span>
                                        </div>
                                        <button onclick="removeDiscount()"
                                            class="text-xs text-emerald-600 hover:text-emerald-800">
                                            Remove
                                        </button>
                                    </div>
                                </div>

                                <!-- Total -->
                                <div class="border-t border-slate-200 pt-4 mt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-slate-900">Total Amount</span>
                                        <span class="text-xl font-bold text-slate-900" id="totalDesktop">₹0</span>
                                    </div>
                                    <p class="text-xs text-slate-400 mt-2 text-right">Inclusive of all taxes</p>
                                </div>

                                <!-- Place Order Button -->
                                <button onclick="placeOrder()"
                                    class="w-full mt-6 px-6 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors flex items-center justify-center gap-2">
                                    <iconify-icon icon="lucide:check-circle" width="18"></iconify-icon>
                                    Place Order & Generate Invoice
                                </button>

                                <!-- Back Button -->
                                <button onclick="goBack()"
                                    class="w-full mt-3 px-6 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                                    Back to Products
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discount Modal -->
<div id="discountModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
    <div class="bg-white rounded-2xl w-full max-w-md max-h-[90vh] overflow-hidden shadow-xl">
        <div class="p-6 border-b border-slate-100">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">Apply Discount</h2>
                <button onclick="hideDiscountModal()" class="p-1 rounded-lg hover:bg-slate-100 transition-colors">
                    <iconify-icon icon="lucide:x" width="20" class="text-slate-500"></iconify-icon>
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Discount Type</label>
                    <div class="flex gap-2">
                        <button onclick="selectDiscountType('percentage')"
                            class="flex-1 px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium hover:bg-slate-50 transition-colors"
                            id="percentageBtn">
                            Percentage (%)
                        </button>
                        <button onclick="selectDiscountType('fixed')"
                            class="flex-1 px-4 py-2 rounded-lg border border-slate-200 bg-white text-sm font-medium hover:bg-slate-50 transition-colors"
                            id="fixedBtn">
                            Fixed Amount (₹)
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Discount Value</label>
                    <input type="number" id="discountValue" min="0" max="100" placeholder="Enter discount value"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all discount-input">
                    <p id="discountHelp" class="text-xs text-slate-500 mt-2">Enter percentage value (0-100%)</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Reason (Optional)</label>
                    <input type="text" id="discountReason" placeholder="Enter reason for discount"
                        class="w-full px-4 py-2.5 rounded-lg border border-slate-200 bg-white focus:outline-none focus:border-indigo-300 text-sm placeholder-slate-400 transition-all">
                </div>
            </div>
        </div>

        <div class="p-6 border-t border-slate-100 bg-slate-50">
            <div class="flex gap-3">
                <button type="button" onclick="hideDiscountModal()"
                    class="flex-1 px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-700 font-medium hover:bg-slate-50 transition-colors">
                    Cancel
                </button>
                <button type="button" onclick="applyDiscount()"
                    class="flex-1 px-4 py-3 rounded-xl bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors">
                    Apply Discount
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Review Order',
        'showBack' => true,
        'backUrl' => route('salesperson.orders.create'),
        'showBottomNav' => false
    ];
@endphp
@endpush

@section('scripts')
<script>
    // Order data
    let cartItems = [];
    let discount = {
        type: null,
        value: 0,
        reason: ''
    };
    let subtotal = 0;
    const deliveryCharge = 50;

    function initializePage() {
        loadReviewData();
    }

    function loadReviewData() {
        // Load shop info from localStorage
        const selectedShop = JSON.parse(localStorage.getItem('selectedShop')) || {
            name: "Kirana King",
            area: "South Mumbai",
            phone: "9876543210"
        };

        const selectedArea = JSON.parse(localStorage.getItem('selectedBit')) || {
            name: "South Mumbai"
        };

        // Load cart items from localStorage
        cartItems = JSON.parse(localStorage.getItem('cartItems')) || [
            { id: 1, name: "Aata (Wheat Flour)", unit: "5kg", quantity: 2, price: 250, total: 500 },
            { id: 2, name: "Basmati Rice", unit: "5kg", quantity: 1, price: 450, total: 450 },
            { id: 3, name: "Toor Dal", unit: "1kg", quantity: 3, price: 180, total: 540 },
            { id: 4, name: "Cooking Oil", unit: "1L", quantity: 2, price: 190, total: 380 }
        ];

        // Display order items
        displayOrderItems('Mobile');
        displayOrderItems('Desktop');

        // Calculate totals
        calculateTotals();
    }

    function displayOrderItems(version) {
        const itemsContainer = document.getElementById(`orderItems${version}`);
        const emptyState = document.getElementById(`emptyState${version}`);
        const itemCountElement = document.getElementById(`itemCount${version}`);

        if (!itemsContainer) return;

        if (cartItems.length === 0) {
            itemsContainer.classList.add('hidden');
            emptyState.classList.remove('hidden');
            if (itemCountElement) itemCountElement.textContent = '0 items';
            return;
        }

        itemsContainer.classList.remove('hidden');
        emptyState.classList.add('hidden');
        itemsContainer.innerHTML = '';

        if (itemCountElement) {
            itemCountElement.textContent = `${cartItems.length} item${cartItems.length !== 1 ? 's' : ''}`;
        }

        // Calculate subtotal
        subtotal = cartItems.reduce((sum, item) => sum + item.total, 0);

        cartItems.forEach((item, index) => {
            const itemElement = document.createElement('div');
            itemElement.className = 'order-item p-3 rounded-lg border border-slate-100 transition-colors';

            if (version === 'Mobile') {
                itemElement.innerHTML = `
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 flex-shrink-0">
                            <iconify-icon icon="lucide:package" width="16" class="text-slate-600"></iconify-icon>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900 truncate">${item.name}</h4>
                                    <p class="text-xs text-slate-500">${item.unit} × ${item.quantity}</p>
                                </div>
                                <span class="text-sm font-medium text-slate-900">₹${item.total}</span>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-slate-500">Unit price: ₹${item.price}</span>
                                <span class="text-xs text-slate-300">•</span>
                                <span class="text-xs text-slate-500">Total: ₹${item.total}</span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                itemElement.innerHTML = `
                    <div class="flex items-center gap-4">
                        <div class="h-14 w-14 rounded-lg bg-slate-50 flex items-center justify-center border border-slate-200 flex-shrink-0">
                            <iconify-icon icon="lucide:package" width="18" class="text-slate-600"></iconify-icon>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h4 class="text-sm font-semibold text-slate-900">${item.name}</h4>
                                    <p class="text-sm text-slate-500">${item.unit}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-sm font-medium text-slate-900">₹${item.total}</span>
                                    <p class="text-xs text-slate-500 mt-0.5">₹${item.price} × ${item.quantity}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }

            itemsContainer.appendChild(itemElement);
        });
    }

    function calculateTotals() {
        const discountAmount = discount.type === 'percentage'
            ? (subtotal * discount.value) / 100
            : discount.value;

        const total = subtotal + deliveryCharge - discountAmount;

        // Update totals for both versions
        ['Mobile', 'Desktop'].forEach(version => {
            const subtotalElement = document.getElementById(`subtotal${version}`);
            const deliveryElement = document.getElementById(`delivery${version}`);
            const discountElement = document.getElementById(`discount${version}`);
            const totalElement = document.getElementById(`total${version}`);

            if (subtotalElement) subtotalElement.textContent = `₹${subtotal.toLocaleString()}`;
            if (deliveryElement) deliveryElement.textContent = `₹${deliveryCharge}`;
            if (discountElement) discountElement.textContent = `-₹${discountAmount.toFixed(2)}`;
            if (totalElement) totalElement.textContent = `₹${total.toLocaleString()}`;
        });

        // Update discount details for desktop
        const discountDetails = document.getElementById('discountDetailsDesktop');
        const discountTypeElement = document.getElementById('discountTypeDesktop');

        if (discount.value > 0) {
            if (discountDetails) discountDetails.classList.remove('hidden');
            if (discountTypeElement) {
                discountTypeElement.textContent = discount.type === 'percentage'
                    ? `${discount.value}% Discount`
                    : `₹${discount.value} Discount`;
            }
        } else {
            if (discountDetails) discountDetails.classList.add('hidden');
        }
    }

    function showDiscountModal(version) {
        // Reset modal
        const discountValue = document.getElementById('discountValue');
        const discountReason = document.getElementById('discountReason');
        
        if (discountValue) discountValue.value = '';
        if (discountReason) discountReason.value = '';
        
        selectDiscountType('percentage');

        // Show modal
        const discountModal = document.getElementById('discountModal');
        if (discountModal) discountModal.classList.remove('hidden');
    }

    function hideDiscountModal() {
        const discountModal = document.getElementById('discountModal');
        if (discountModal) discountModal.classList.add('hidden');
    }

    function selectDiscountType(type) {
        discount.type = type;

        // Update button styles
        const percentageBtn = document.getElementById('percentageBtn');
        const fixedBtn = document.getElementById('fixedBtn');
        const discountHelp = document.getElementById('discountHelp');
        const discountValue = document.getElementById('discountValue');

        if (percentageBtn && fixedBtn) {
            percentageBtn.classList.remove('bg-indigo-600', 'text-white');
            fixedBtn.classList.remove('bg-indigo-600', 'text-white');
            percentageBtn.classList.add('border-slate-200', 'bg-white', 'text-slate-700');
            fixedBtn.classList.add('border-slate-200', 'bg-white', 'text-slate-700');

            if (type === 'percentage') {
                percentageBtn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
                percentageBtn.classList.add('bg-indigo-600', 'text-white');
                if (discountHelp) discountHelp.textContent = 'Enter percentage value (0-100%)';
                if (discountValue) discountValue.max = 100;
            } else {
                fixedBtn.classList.remove('border-slate-200', 'bg-white', 'text-slate-700');
                fixedBtn.classList.add('bg-indigo-600', 'text-white');
                if (discountHelp) discountHelp.textContent = 'Enter fixed amount in ₹';
                if (discountValue) discountValue.max = subtotal;
            }
        }
    }

    function applyDiscount() {
        const discountValueInput = document.getElementById('discountValue');
        const discountReasonInput = document.getElementById('discountReason');
        
        if (!discountValueInput || !discountReasonInput) return;

        const value = parseFloat(discountValueInput.value);
        const reason = discountReasonInput.value;

        // Basic validation
        if (!value || value <= 0) {
            showToast({
                text: 'Please enter a valid discount value',
                type: 'error'
            });
            return;
        }

        // Percentage limit
        if (discount.type === 'percentage' && value > 100) {
            showToast({
                text: 'Percentage discount cannot exceed 100%',
                type: 'error'
            });
            return;
        }

        // Fixed discount limit
        if (discount.type === 'fixed' && value > subtotal) {
            showToast({
                text: 'Fixed discount cannot exceed subtotal amount',
                type: 'error'
            });
            return;
        }

        // Apply discount
        discount.value = value;
        discount.reason = reason;

        hideDiscountModal();
        calculateTotals();

        // Success feedback
        showToast({
            text: 'Discount applied successfully',
            type: 'success'
        });
    }

    function removeDiscount() {
        discount = { type: null, value: 0, reason: '' };
        calculateTotals();
    }

    function goBack() {
        window.location.href = "{{ route('salesperson.orders.create') }}";
    }

    function placeOrder() {
        if (cartItems.length === 0) {
            showToast({
                text: 'Please add at least one product to the order',
                type: 'error'
            });
            return;
        }

        // Generate order number
        const orderNumber = 'ORD' + Date.now().toString().slice(-6);

        // Calculate discount amount
        const discountAmount = discount.type === 'percentage'
            ? (subtotal * discount.value) / 100
            : discount.value;

        // Create order object
        const orderDetails = {
            orderNumber: orderNumber,
            shop: JSON.parse(localStorage.getItem('selectedShop')) || {},
            area: JSON.parse(localStorage.getItem('selectedArea')) || {},
            items: cartItems,
            subtotal: subtotal,
            delivery: deliveryCharge,
            discount: discountAmount,
            discountType: discount.type,
            discountReason: discount.reason,
            total: subtotal + deliveryCharge - discountAmount,
            notes: document.getElementById('orderNotesMobile')?.value ||
                document.getElementById('orderNotesDesktop')?.value || '',
            date: new Date().toLocaleDateString('en-GB'),
            time: new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })
        };

        // Save to localStorage
        localStorage.setItem('currentOrder', JSON.stringify(orderDetails));

        // Clear cart
        localStorage.removeItem('cartItems');

        // Redirect to invoice page
        window.location.href = "{{ route('salesperson.orders.invoice', ['id' => 1]) }}";
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        initializePage();
        selectDiscountType('percentage');
    });
</script>
@endsection