@extends('layouts.salesperson')

@section('content')
<style>
    .filter-chip {
        padding: 6px 12px;
        border-radius: 8px;
        background-color: #F8FAFC;
        border: 1px solid #E2E8F0;
        cursor: pointer;
        transition: all 0.2s ease;
        font-size: 12px;
        font-weight: 500;
        color: #475569;
        white-space: nowrap;
    }

    .filter-chip:hover {
        background-color: #F1F5F9;
        border-color: #CBD5E1;
    }

    .filter-chip.active {
        background-color: #4F46E5;
        color: white;
        border-color: #4F46E5;
    }

    .hide-scrollbar::-webkit-scrollbar {
        display: none;
    }
    .hide-scrollbar {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
<div class="max-w-2xl mx-auto min-h-screen pb-32 bg-slate-50 relative">
    <!-- Header -->
    <header class="sticky top-0 z-30 bg-white border-b border-slate-100 p-4 shadow-sm">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('salesperson.shops.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
                <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
            </a>
            <div>
                <h2 class="text-sm font-semibold text-slate-900">{{ $shop->name }}</h2>
                <p class="text-[10px] text-slate-500 line-clamp-1">{{ $shop->address }}</p>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="space-y-3">
            <div class="relative">
                <iconify-icon icon="lucide:search" width="16" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
                <input type="text" id="productSearch" oninput="filterProducts()" placeholder="Search items, brands..." 
                    class="w-full pl-9 pr-4 py-2 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition-all">
            </div>

            <!-- Category Filter -->
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Category</h4>
                <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
                    <div onclick="filterByCategory('all')" class="filter-chip active category-chip">All</div>
                    @foreach($categories as $key => $label)
                        <div onclick="filterByCategory('{{ $key }}')" class="filter-chip category-chip">{{ $label }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Brand Filter -->
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Brand</h4>
                <div class="flex gap-2 overflow-x-auto pb-1 hide-scrollbar">
                    <div onclick="filterByBrand('all')" class="filter-chip active brand-chip">All Brands</div>
                    @foreach($brands as $key => $label)
                        <div onclick="filterByBrand('{{ $key }}')" class="filter-chip brand-chip">{{ $label }}</div>
                    @endforeach
                </div>
            </div>

            <!-- Sub-Brand Filter -->
            <div>
                <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Product Type</h4>
                <div class="flex gap-2 overflow-x-auto hide-scrollbar">
                    <div onclick="filterBySubBrand('all')" class="filter-chip active sub-brand-chip">All Types</div>
                    @foreach($subBrands as $key => $label)
                        <div onclick="filterBySubBrand('{{ $key }}')" class="filter-chip sub-brand-chip">{{ $label }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </header>

    <main class="p-4">
        <!-- Product Grid/List -->
        <div id="productList" class="grid grid-cols-1 gap-3">
            @foreach($products as $product)
                <div class="product-item bg-white p-3 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3" 
                     data-name="{{ strtolower($product->name) }}" 
                     data-brand="{{ strtolower($product->brand ?? '') }}"
                     data-sub-brand="{{ strtolower($product->sub_brand ?? '') }}"
                     data-category="{{ $product->category }}">
                    
                    <div class="h-16 w-16 bg-slate-100 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden border border-slate-100">
                        @if($product->primary_image)
                            <img src="{{ asset('storage/' . $product->primary_image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <iconify-icon icon="lucide:package" width="24" class="text-slate-300"></iconify-icon>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-semibold text-slate-900 truncate">{{ $product->name }}</h4>
                        <div class="flex items-center gap-2 mt-0.5">
                            <span class="text-xs font-bold text-indigo-600">₹{{ number_format($product->price, 2) }}</span>
                            @if($product->mrp > $product->price)
                                <span class="text-[10px] text-slate-400 line-through">₹{{ number_format($product->mrp, 2) }}</span>
                            @endif
                            <span class="text-[10px] text-slate-400">•</span>
                            <span class="text-[10px] text-slate-500">{{ $product->unit ?? 'No Unit' }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="qty-control hidden" id="qty-{{ $product->id }}">
                            <div class="flex items-center bg-slate-100 rounded-lg p-1">
                                <button onclick="updateQty({{ $product->id }}, -1)" class="h-8 w-8 rounded-md flex items-center justify-center text-slate-600 hover:bg-white hover:shadow-sm transition-all active:scale-95">
                                    <iconify-icon icon="lucide:minus" width="14"></iconify-icon>
                                </button>
                                <span class="w-8 text-center text-sm font-semibold text-slate-900" id="val-{{ $product->id }}">0</span>
                                <button onclick="updateQty({{ $product->id }}, 1)" class="h-8 w-8 rounded-md flex items-center justify-center text-slate-600 hover:bg-white hover:shadow-sm transition-all active:scale-95">
                                    <iconify-icon icon="lucide:plus" width="14"></iconify-icon>
                                </button>
                            </div>
                        </div>
                        <button onclick="showQty({{ $product->id }}, {{ $product->price }}, '{{ $product->name }}')" id="add-{{ $product->id }}" 
                            class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-xs font-semibold hover:bg-indigo-700 active:scale-95 transition-all shadow-sm shadow-indigo-100">
                            Add
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty State -->
        <div id="noProducts" class="hidden py-12 text-center text-slate-400">
            <iconify-icon icon="lucide:search-x" width="48" class="mb-3 opacity-30 mx-auto"></iconify-icon>
            <p class="text-sm font-medium">No products match your search</p>
        </div>
    </main>

    <!-- Bottom Bar (Cart) -->
    <div id="cartBar" class="fixed bottom-0 left-0 right-0 z-40 p-4 transform translate-y-full transition-transform duration-300">
        <div class="max-w-2xl mx-auto">
            <div class="bg-indigo-600 rounded-2xl p-4 shadow-xl shadow-indigo-200 flex items-center justify-between text-white">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <iconify-icon icon="lucide:shopping-bag" width="20"></iconify-icon>
                    </div>
                    <div>
                        <p id="cartCount" class="text-xs font-medium text-indigo-100">0 Items</p>
                        <p id="totalDisplay" class="text-lg font-bold">₹0.00</p>
                    </div>
                </div>
                <button onclick="openCheckout()" class="bg-white text-indigo-600 px-6 py-3 rounded-xl text-sm font-bold shadow-sm active:scale-95 transition-all flex items-center gap-2">
                    Checkout
                    <iconify-icon icon="lucide:chevron-right" width="18"></iconify-icon>
                </button>
            </div>
        </div>
    </div>

    <!-- Checkout Modal -->
    <div id="checkoutModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeCheckout()"></div>
        <div class="absolute bottom-0 left-0 right-0 max-w-2xl mx-auto bg-white rounded-t-3xl shadow-2xl p-6 transition-transform duration-300 translate-y-full" id="modalContent">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full mx-auto mb-6"></div>
            
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">Order Summary</h3>
                <span id="modalShop" class="text-xs font-medium text-slate-500">{{ $shop->name }}</span>
            </div>

            <div id="cartItems" class="space-y-4 max-h-[40vh] overflow-y-auto mb-6 pr-2">
                <!-- Items injected by JS -->
            </div>

            <form id="orderForm" action="{{ route('salesperson.orders.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                @if(request()->has('visit'))
                    <input type="hidden" name="visit" value="true">
                @endif
                <div id="hiddenInputs"></div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 block">Order Notes</label>
                    <textarea name="notes" rows="2" placeholder="Any special instructions?" 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 text-sm focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-300 transition-all resize-none"></textarea>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="flex items-center justify-between mb-6">
                        <span class="text-slate-500 font-medium">Grand Total</span>
                        <span id="finalTotal" class="text-2xl font-bold text-slate-900">₹0.00</span>
                    </div>
                    
                    <button type="submit" class="w-full py-4 rounded-xl bg-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-100 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        Confirm & Place Order
                    </button>
                    <button type="button" onclick="closeCheckout()" class="w-full mt-2 py-3 text-sm font-medium text-slate-400 hover:text-slate-600 transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('pageConfig')
@php
    $pageConfig = [
        'title' => 'Create Order',
        'showBack' => true,
        'showBottomNav' => false
    ];
@endphp
@endpush

@section('scripts')
<script>
    let cart = {};
    const products = @json($products);
    let currentCategory = 'all';
    let currentBrand = 'all';
    let currentSubBrand = 'all';
    let currentSearch = '';

    function filterByCategory(category) {
        if (currentCategory === category && category !== 'all') {
            currentCategory = 'all';
        } else {
            currentCategory = category;
        }
        updateUIActiveState(event.currentTarget, currentCategory, 'category');
        filterProducts();
    }

    function filterByBrand(brand) {
        if (currentBrand === brand && brand !== 'all') {
            currentBrand = 'all';
        } else {
            currentBrand = brand;
        }
        updateUIActiveState(event.currentTarget, currentBrand, 'brand');
        filterProducts();
    }

    function filterBySubBrand(subBrand) {
        if (currentSubBrand === subBrand && subBrand !== 'all') {
            currentSubBrand = 'all';
        } else {
            currentSubBrand = subBrand;
        }
        updateUIActiveState(event.currentTarget, currentSubBrand, 'sub-brand');
        filterProducts();
    }

    function updateUIActiveState(clickedElement, value, type) {
        const container = clickedElement.parentElement;
        container.querySelectorAll('.filter-chip').forEach(chip => {
            chip.classList.remove('active');
        });

        if (value === 'all') {
            container.querySelector('.filter-chip:first-child').classList.add('active');
        } else {
            clickedElement.classList.add('active');
        }
    }

    function filterProducts() {
        currentSearch = document.getElementById('productSearch').value.toLowerCase();
        const items = document.querySelectorAll('.product-item');
        let count = 0;

        items.forEach(item => {
            const matchesSearch = !currentSearch || 
                item.dataset.name.includes(currentSearch) || 
                item.dataset.brand.includes(currentSearch);
            const matchesCategory = currentCategory === 'all' || item.dataset.category === currentCategory;
            const matchesBrand = currentBrand === 'all' || item.dataset.brand === currentBrand;
            const matchesSubBrand = currentSubBrand === 'all' || item.dataset.subBrand === currentSubBrand;

            if (matchesSearch && matchesCategory && matchesBrand && matchesSubBrand) {
                item.style.display = 'flex';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        document.getElementById('noProducts').classList.toggle('hidden', count > 0);
    }

    function showQty(id, price, name) {
        document.getElementById(`add-${id}`).classList.add('hidden');
        document.getElementById(`qty-${id}`).classList.remove('hidden');
        updateQty(id, 1, price, name);
    }

    function updateQty(id, delta, price, name) {
        if (!cart[id]) {
            cart[id] = { quantity: 0, price: price, name: name };
        }

        cart[id].quantity += delta;

        if (cart[id].quantity <= 0) {
            delete cart[id];
            document.getElementById(`qty-${id}`).classList.add('hidden');
            document.getElementById(`add-${id}`).classList.remove('hidden');
        } else {
            document.getElementById(`val-${id}`).textContent = cart[id].quantity;
        }

        updateCartUI();
    }

    function updateCartUI() {
        const cartBar = document.getElementById('cartBar');
        const countEl = document.getElementById('cartCount');
        const totalEl = document.getElementById('totalDisplay');

        const items = Object.values(cart);
        const count = items.reduce((sum, item) => sum + item.quantity, 0);
        const total = items.reduce((sum, item) => sum + (item.quantity * item.price), 0);

        if (count > 0) {
            cartBar.classList.remove('translate-y-full');
            countEl.textContent = `${count} Items`;
            totalEl.textContent = `₹${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        } else {
            cartBar.classList.add('translate-y-full');
        }
    }

    function openCheckout() {
        const modal = document.getElementById('checkoutModal');
        const content = document.getElementById('modalContent');
        const list = document.getElementById('cartItems');
        const inputs = document.getElementById('hiddenInputs');
        const finalTotal = document.getElementById('finalTotal');
        
        list.innerHTML = '';
        inputs.innerHTML = '';
        let total = 0;

        Object.entries(cart).forEach(([id, item], index) => {
            const subtotal = item.quantity * item.price;
            total += subtotal;

            list.innerHTML += `
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-slate-900">${item.name}</p>
                        <p class="text-[11px] text-slate-500">${item.quantity} x ₹${item.price.toFixed(2)}</p>
                    </div>
                    <span class="text-sm font-bold text-slate-700">₹${subtotal.toFixed(2)}</span>
                </div>
            `;

            inputs.innerHTML += `
                <input type="hidden" name="items[${index}][product_id]" value="${id}">
                <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
            `;
        });

        finalTotal.textContent = `₹${total.toLocaleString(undefined, {minimumFractionDigits: 2})}`;
        
        modal.classList.remove('hidden');
        setTimeout(() => content.classList.remove('translate-y-full'), 10);
    }

    function closeCheckout() {
        const modal = document.getElementById('checkoutModal');
        const content = document.getElementById('modalContent');
        
        content.classList.add('translate-y-full');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>
@endsection