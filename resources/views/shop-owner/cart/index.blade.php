@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.products.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Your Cart</h2>
    </header>

    <main class="p-6 space-y-8">
        <!-- Cart Items -->
        <div id="cartItems" class="space-y-4">
            <!-- Dynamic Content -->
        </div>

        <!-- Empty State -->
        <div id="emptyCart" class="hidden py-20 text-center flex flex-col items-center justify-center">
            <div class="h-20 w-20 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200 mb-4">
                <iconify-icon icon="lucide:shopping-cart" width="40"></iconify-icon>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Cart is empty</h3>
            <p class="text-xs text-slate-400 mt-1 mb-8">Looks like you haven't added anything yet.</p>
            <a href="{{ route('shop-owner.products.index') }}" class="h-12 px-8 rounded-xl bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center hover:bg-indigo-700 transition-all active:scale-95 shadow-lg shadow-indigo-100">
                Go Shopping
            </a>
        </div>

        <!-- Summary -->
        <div id="cartSummary" class="hidden space-y-6 pt-6 border-t border-slate-50">
            <div class="space-y-3">
                <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-slate-400">
                    <span>Subtotal</span>
                    <span class="text-slate-900">₹<span id="subtotal">0.00</span></span>
                </div>
                <div class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-slate-400">
                    <span>Tax (GST 18%)</span>
                    <span class="text-slate-900">Inclusive</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-50">
                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-900">Total Amount</span>
                    <span class="text-2xl font-black text-indigo-600">₹<span id="total">0.00</span></span>
                </div>
            </div>

            <a href="{{ route('shop-owner.checkout.index') }}" class="h-14 w-full rounded-2xl bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-3 hover:bg-indigo-700 transition-all active:scale-95 shadow-xl shadow-indigo-100">
                Proceed to Checkout
                <iconify-icon icon="lucide:arrow-right" width="18"></iconify-icon>
            </a>
        </div>
    </main>
</div>

<script>
    function renderCart() {
        const cart = JSON.parse(localStorage.getItem('shop_cart') || '[]');
        const container = document.getElementById('cartItems');
        const summary = document.getElementById('cartSummary');
        const empty = document.getElementById('emptyCart');

        if (cart.length === 0) {
            container.innerHTML = '';
            summary.classList.add('hidden');
            empty.classList.remove('hidden');
            return;
        }

        empty.classList.add('hidden');
        summary.classList.remove('hidden');
        container.innerHTML = '';

        let total = 0;
        cart.forEach(item => {
            total += item.price * item.quantity;
            const el = document.createElement('div');
            el.className = 'flex items-center gap-4 p-4 rounded-2xl bg-slate-50 border border-slate-100';
            el.innerHTML = `
                <div class="h-14 w-14 rounded-xl bg-white border border-slate-100 overflow-hidden flex-shrink-0">
                    ${item.image ? `<img src="${item.image}" class="h-full w-full object-cover">` : `<div class="h-full w-full flex items-center justify-center text-slate-200"><iconify-icon icon="lucide:package" width="24"></iconify-icon></div>`}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <h4 class="text-sm font-bold text-slate-900 truncate">${item.name}</h4>
                        <button onclick="removeItem(${item.id})" class="text-slate-300 hover:text-rose-500 transition-colors">
                            <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon>
                        </button>
                    </div>
                    <div class="flex items-center justify-between mt-2">
                        <p class="text-[10px] font-black text-indigo-600 uppercase">₹${item.price.toLocaleString()} / ${item.unit}</p>
                        <div class="flex items-center gap-3 bg-white px-3 py-1.5 rounded-xl border border-slate-100 shadow-sm">
                            <button onclick="updateQty(${item.id}, -1)" class="text-slate-400 hover:text-indigo-600"><iconify-icon icon="lucide:minus" width="12"></iconify-icon></button>
                            <span class="text-xs font-bold text-slate-900">${item.quantity}</span>
                            <button onclick="updateQty(${item.id}, 1)" class="text-slate-400 hover:text-indigo-600"><iconify-icon icon="lucide:plus" width="12"></iconify-icon></button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(el);
        });

        document.getElementById('subtotal').textContent = total.toLocaleString();
        document.getElementById('total').textContent = total.toLocaleString();
    }

    function updateQty(id, delta) {
        let cart = JSON.parse(localStorage.getItem('shop_cart') || '[]');
        const item = cart.find(i => i.id == id);
        if (item) {
            item.quantity += delta;
            if (item.quantity <= 0) {
                cart = cart.filter(i => i.id != id);
            }
            localStorage.setItem('shop_cart', JSON.stringify(cart));
            renderCart();
        }
    }

    function removeItem(id) {
        let cart = JSON.parse(localStorage.getItem('shop_cart') || '[]');
        cart = cart.filter(i => i.id != id);
        localStorage.setItem('shop_cart', JSON.stringify(cart));
        renderCart();
    }

    document.addEventListener('DOMContentLoaded', renderCart);
</script>
@endsection