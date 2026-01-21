@extends('layouts.admin')

@section('title', 'Create Offers')
@section('subtitle', 'Configure offer details and rules')

@section('css')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
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

    /* Grid layout */
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 640px) {
        .grid-2 {
            grid-template-columns: 1fr 1fr;
        }
    }

    /* Gift specific styles */
    .gift-fields {
        border: 1px solid #E5E7EB;
        border-radius: 0.5rem;
        /* padding: 1.5rem; */
        margin-top: 1rem;
        background: #F9FAFB;
    }

    .gift-field-row {
        margin-bottom: 1rem;
        /* display: flex; */
        align-items: center;
        gap: 1rem;
    }

    .gift-field-input {
        flex: 1;
    }

    .remove-gift-btn {
        background: #EF4444;
        color: white;
        border: none;
        border-radius: 0.375rem;
        padding: 0.5rem 1rem;
        cursor: pointer;
        font-size: 0.875rem;
        transition: background-color 0.2s;
    }

    .remove-gift-btn:hover {
        background: #DC2626;
    }

    .add-gift-btn {
        background: #10B981;
        color: white;
        border: none;
        border-radius: 0.5rem;
        padding: 0.75rem 1.5rem;
        cursor: pointer;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: background-color 0.2s;
        margin-top: 1rem;
    }

    .add-gift-btn:hover {
        background: #059669;
    }

    .gift-section-title {
        font-size: 1rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection

@section('content')
@php
    $action = request()->get('action') ?? 'add';
    $id = request()->get('id');
@endphp

<div class="main-content">
    <main class="p-6">
        <form id="offerForm" class="space-y-6" onsubmit="handleSubmit(event)">
            <input type="hidden" id="offerId" value="{{ $id ?? '' }}">

            <div class="form-group">
                <label class="form-label" for="offerName">Offer Name *</label>
                <input type="text" id="offerName" class="form-input" required
                    placeholder="Enter offer name (e.g., Welcome Bonus)">
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="offerCode">Offer Code *</label>
                    <input type="text" id="offerCode" class="form-input" required placeholder="e.g., WELCOME500">
                    <p class="text-xs text-slate-500 mt-1">Unique code customers will use</p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="offerType">Offer Type</label>
                    <select id="offerType" class="form-select" onchange="updateOfferType()">
                        <option value="percentage">Percentage Discount</option>
                        <option value="fixed">Fixed Amount</option>
                        <option value="bogo">Buy One Get One</option>
                        <option value="physical_gift">Physical Gift</option>
                    </select>
                </div>
            </div>

            <!-- Discount Value Field (Dynamically Updated) -->
            <div class="form-group" id="discountValueGroup">
                <label class="form-label" id="discountValueLabel">Discount Value (%) *</label>
                <input type="number" id="discountValue" class="form-input" required step="0.01"
                    placeholder="Enter discount value">
            </div>

            <!-- Physical Gift Fields (Initially Hidden) -->
            <div class="form-group" id="giftFieldsGroup" style="display: none;">
                <div class="gift-section-title">
                    <span>Gift Items</span>
                    <button type="button" class="add-gift-btn" onclick="addGiftField()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 00-1 1v5H4a1 1 0 100 2h5v5a1 1 0 102 0v-5h5a1 1 0 100-2h-5V4a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        Add Gift
                    </button>
                </div>

                <div id="giftItemsContainer" class="gift-fields">
                    <!-- Gift items will be added here dynamically -->
                    <div class="gift-field-row">
                        <div class="gift-field-input">
                            <label class="form-label">Gift Name *</label>
                            <input type="text" class="form-input gift-name" placeholder="e.g., Bluetooth Speaker" required>
                        </div>
                        <div class="gift-field-input">
                            <label class="form-label">Quantity Available</label>
                            <input type="number" class="form-input gift-quantity" placeholder="Unlimited" min="1">
                        </div>
                        <div class="gift-field-input">
                            <label class="form-label">Gift Value (₹)</label>
                            <input type="number" class="form-input gift-value" placeholder="Estimated value" step="0.01" min="0">
                        </div>
                        <button type="button" class="remove-gift-btn" onclick="removeGiftField(this)" style="display: none;">
                            Remove
                        </button>
                    </div>
                </div>

                <div class="grid-2 mt-4">
                    <div class="form-group">
                        <label class="form-label" for="minOrderForGift">Minimum Order for Gift (₹)</label>
                        <input type="number" id="minOrderForGift" class="form-input" step="0.01" placeholder="0.00" value="0">
                        <p class="text-xs text-slate-500 mt-1">Minimum order amount to receive gift</p>
                    </div>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="minOrderAmount">Minimum Order Amount (₹)</label>
                    <input type="number" id="minOrderAmount" class="form-input" step="0.01" placeholder="0.00">
                    <p class="text-xs text-slate-500 mt-1">Leave 0 for no minimum</p>
                </div>
                <div class="form-group">
                    <label class="form-label" for="maxUses">Maximum Usage</label>
                    <input type="number" id="maxUses" class="form-input" placeholder="Unlimited">
                    <p class="text-xs text-slate-500 mt-1">Leave empty for unlimited usage</p>
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label class="form-label" for="startDate">Start Date *</label>
                    <input type="date" id="startDate" class="form-input" required>
                </div>
                <div class="form-group">
                    <label class="form-label" for="endDate">End Date *</label>
                    <input type="date" id="endDate" class="form-input" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="offerStatus">Status</label>
                <select id="offerStatus" class="form-select">
                    <option value="active">Active</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="draft">Draft</option>
                    <option value="expired">Expired</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="offerDescription">Description</label>
                <textarea id="offerDescription" class="form-input" rows="4"
                    placeholder="Describe the offer details for customers"></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.offers.index') }}" class="btn-secondary no-underline">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    {{ $action === 'edit' ? 'Update Offer' : 'Save Offer' }}
                </button>
            </div>
        </form>
    </main>
</div>
@endsection

@section('scripts')
<script>
    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function () {
        const urlParams = new URLSearchParams(window.location.search);
        const action = urlParams.get('action');
        const id = urlParams.get('id');

        // Set default dates
        const today = new Date().toISOString().split('T')[0];
        const nextMonth = new Date();
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        const nextMonthStr = nextMonth.toISOString().split('T')[0];

        document.getElementById('startDate').value = today;
        document.getElementById('endDate').value = nextMonthStr;

        if (action === 'edit' && id) {
            loadOfferData(parseInt(id));
        }

        // Initialize offer type display
        updateOfferType();
    });

    // Sample offers data (in real app, this would come from API or localStorage)
    const sampleOffers = [
        {
            id: 1,
            name: 'Welcome Bonus',
            code: 'WELCOME500',
            type: 'fixed',
            value: 500,
            description: '₹500 welcome bonus for new shop owners',
            minOrder: 0,
            maxUses: 1000,
            used: 245,
            startDate: '2024-01-01',
            endDate: '2024-12-31',
            status: 'active'
        },
        {
            id: 2,
            name: 'Festival Discount',
            code: 'FEST20',
            type: 'percentage',
            value: 20,
            description: '20% off on orders above ₹1000',
            minOrder: 1000,
            maxUses: 500,
            used: 189,
            startDate: '2024-01-20',
            endDate: '2024-01-31',
            status: 'active'
        },
        {
            id: 3,
            name: 'Buy 1 Get 1',
            code: 'BOGOATTA',
            type: 'bogo',
            value: 100,
            description: 'Buy 1 Get 1 free on Aashirvaad Atta',
            minOrder: 0,
            maxUses: 200,
            used: 156,
            startDate: '2024-01-15',
            endDate: '2024-01-25',
            status: 'active'
        },
        {
            id: 4,
            name: 'Diwali Gift Pack',
            code: 'DIWALIGIFT',
            type: 'physical_gift',
            value: 1000,
            description: 'Get premium Diwali gift pack with orders above ₹5000',
            minOrder: 5000,
            maxUses: 100,
            used: 45,
            startDate: '2024-10-01',
            endDate: '2024-11-15',
            status: 'active',
            gifts: [
                { name: 'Premium Dry Fruits Box', quantity: 100, value: 750 },
                { name: 'Silver Coated Diya Set', quantity: 100, value: 250 }
            ],
            minOrderForGift: 5000
        }
    ];

    function loadOfferData(id) {
        const offer = sampleOffers.find(o => o.id === id);
        if (offer) {
            document.getElementById('offerId').value = offer.id;
            document.getElementById('offerName').value = offer.name;
            document.getElementById('offerCode').value = offer.code;
            document.getElementById('offerType').value = offer.type;
            document.getElementById('discountValue').value = offer.value;
            document.getElementById('minOrderAmount').value = offer.minOrder || '';
            document.getElementById('maxUses').value = offer.maxUses || '';
            document.getElementById('startDate').value = offer.startDate;
            document.getElementById('endDate').value = offer.endDate;
            document.getElementById('offerStatus').value = offer.status;
            document.getElementById('offerDescription').value = offer.description || '';

            // Load gift data if exists
            if (offer.type === 'physical_gift' && offer.gifts) {
                // Clear existing gift fields
                const container = document.getElementById('giftItemsContainer');
                container.innerHTML = '';
                
                // Add gift fields
                offer.gifts.forEach((gift, index) => {
                    addGiftField(gift.name, gift.quantity, gift.value, index === 0);
                });
                
                document.getElementById('minOrderForGift').value = offer.minOrderForGift || 0;
            }

            updateOfferType();
        }
    }

    function updateOfferType() {
        const type = document.getElementById('offerType').value;
        const discountGroup = document.getElementById('discountValueGroup');
        const giftGroup = document.getElementById('giftFieldsGroup');

        if (type === 'physical_gift') {
            discountGroup.style.display = 'none';
            giftGroup.style.display = 'block';
            // Ensure at least one gift field exists
            const giftFields = document.querySelectorAll('.gift-field-row');
            if (giftFields.length === 0) {
                addGiftField();
            }
        } else {
            discountGroup.style.display = 'block';
            giftGroup.style.display = 'none';
            
            // Update label based on type
            const label = document.getElementById('discountValueLabel');
            const input = document.getElementById('discountValue');
            
            if (type === 'percentage') {
                label.textContent = 'Discount Value (%) *';
                input.placeholder = 'Enter percentage (e.g., 20 for 20%)';
            } else if (type === 'fixed') {
                label.textContent = 'Discount Amount (₹) *';
                input.placeholder = 'Enter amount (e.g., 500 for ₹500)';
            } else if (type === 'bogo') {
                label.textContent = 'Product Value (₹) *';
                input.placeholder = 'Enter product value for BOGO offer';
            }
        }
    }

    function addGiftField(name = '', quantity = '', value = '', isFirst = false) {
        const container = document.getElementById('giftItemsContainer');
        
        const giftField = document.createElement('div');
        giftField.className = 'gift-field-row';
        giftField.innerHTML = `
            <div class="gift-field-input">
                <label class="form-label">Gift Name *</label>
                <input type="text" class="form-input gift-name" 
                       placeholder="e.g., Bluetooth Speaker" 
                       value="${name}" required>
            </div>
            <div class="gift-field-input">
                <label class="form-label">Quantity Available</label>
                <input type="number" class="form-input gift-quantity" 
                       placeholder="Unlimited" min="1" value="${quantity}">
            </div>
            <div class="gift-field-input">
                <label class="form-label">Gift Value (₹)</label>
                    <input type="number" class="form-input gift-value" 
                       placeholder="Estimated value" step="0.01" 
                       min="0" value="${value}">
            </div>
            <button type="button" class="remove-gift-btn" onclick="removeGiftField(this)">
                Remove
            </button>
        `;
        
        container.appendChild(giftField);
        
        // Show remove button for all except first gift field
        const removeButtons = container.querySelectorAll('.remove-gift-btn');
        if (removeButtons.length === 1 && isFirst) {
            removeButtons[0].style.display = 'none';
        } else {
            removeButtons.forEach(btn => btn.style.display = 'block');
        }
    }

    function removeGiftField(button) {
        const giftField = button.closest('.gift-field-row');
        giftField.remove();
        
        // Update remove button visibility
        const container = document.getElementById('giftItemsContainer');
        const removeButtons = container.querySelectorAll('.remove-gift-btn');
        if (removeButtons.length === 1) {
            removeButtons[0].style.display = 'none';
        }
    }

    function handleSubmit(event) {
        event.preventDefault();

        const id = document.getElementById('offerId').value;
        const name = document.getElementById('offerName').value;
        const code = document.getElementById('offerCode').value;
        const type = document.getElementById('offerType').value;
        const value = parseFloat(document.getElementById('discountValue').value);
        const minOrder = parseFloat(document.getElementById('minOrderAmount').value) || 0;
        const maxUses = document.getElementById('maxUses').value ? parseInt(document.getElementById('maxUses').value) : null;
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const status = document.getElementById('offerStatus').value;
        const description = document.getElementById('offerDescription').value;

        // Validation for non-gift offers
        if (type !== 'physical_gift' && (!name || !code || isNaN(value))) {
            showToast({
                text: 'Please fill in all required fields',
                type: 'error'
            });
            return;
        }

        // Validation for gift offers
        if (type === 'physical_gift') {
            const giftNames = document.querySelectorAll('.gift-name');
            let validGifts = true;
            
            giftNames.forEach(gift => {
                if (!gift.value.trim()) {
                    validGifts = false;
                    gift.style.borderColor = '#EF4444';
                } else {
                    gift.style.borderColor = '';
                }
            });
            
            if (!validGifts) {
                showToast({
                    text: 'Please fill in all gift names',
                    type: 'error'
                });
                return;
            }
        }

        if (startDate >= endDate) {
            showToast({
                text: 'End date must be after start date',
                type: 'error'
            });
            return;
        }

        // Prepare offer data
        const offerData = {
            id: id || Math.max(...sampleOffers.map(o => o.id), 0) + 1,
            name,
            code,
            type,
            value: type === 'physical_gift' ? 0 : value,
            minOrder,
            maxUses,
            startDate,
            endDate,
            status,
            description,
            used: 0
        };

        // Add gift data if physical gift type
        if (type === 'physical_gift') {
            offerData.gifts = [];
            const giftNames = document.querySelectorAll('.gift-name');
            const giftQuantities = document.querySelectorAll('.gift-quantity');
            const giftValues = document.querySelectorAll('.gift-value');
            
            for (let i = 0; i < giftNames.length; i++) {
                offerData.gifts.push({
                    name: giftNames[i].value,
                    quantity: giftQuantities[i].value ? parseInt(giftQuantities[i].value) : null,
                    value: giftValues[i].value ? parseFloat(giftValues[i].value) : 0
                });
            }
            
            offerData.minOrderForGift = parseFloat(document.getElementById('minOrderForGift').value) || 0;
            
            // Calculate total gift value for reporting
            const totalGiftValue = offerData.gifts.reduce((total, gift) => total + (gift.value || 0), 0);
            offerData.value = totalGiftValue;
        }

        console.log('Saving offer:', offerData);

        // Show success message
        showToast({
            text: id ? 'Offer updated successfully!' : 'Offer created successfully!',
            type: 'success'
        });

        // Redirect back to manage offers page
        setTimeout(() => {
            window.location.href = '{{ route('admin.offers.index') }}';
        }, 1500);
    }

    function showToast({ text, type }) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg border ${
            type === 'error' ? 'bg-red-50 border-red-200 text-red-800' :
            type === 'success' ? 'bg-green-50 border-green-200 text-green-800' :
            'bg-blue-50 border-blue-200 text-blue-800'
        }`;
        toast.innerHTML = `
            <div class="flex items-center gap-2">
                <iconify-icon icon="lucide:${
                    type === 'error' ? 'x-circle' :
                    type === 'success' ? 'check-circle' : 'info'
                }" width="18"></iconify-icon>
                <span class="text-sm font-medium">${text}</span>
            </div>
        `;

        document.body.appendChild(toast);

        // Remove toast after 3 seconds
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }
</script>
@endsection