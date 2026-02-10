@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-24 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.cart.index') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Checkout</h2>
    </header>

    <main class="p-6 space-y-8">
        <!-- Order Summary -->
        <section class="space-y-4">
            <h3 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Order Summary</h3>
            <div id="checkoutItems" class="space-y-3">
                <!-- Items will be injected here -->
            </div>
        </section>

        <!-- Payment Mode -->
        <section class="space-y-4">
            <h3 class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Payment Mode</h3>
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center gap-4">
                <div class="h-10 w-10 rounded-xl bg-white flex items-center justify-center text-emerald-600 border border-emerald-100">
                    <iconify-icon icon="lucide:banknote" width="20"></iconify-icon>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-slate-900">Cash on Delivery</h4>
                    <p class="text-[10px] text-slate-500 font-medium">Pay once items are delivered</p>
                </div>
                <div class="ml-auto">
                    <iconify-icon icon="lucide:check-circle-2" width="20" class="text-emerald-600"></iconify-icon>
                </div>
            </div>
        </section>

        <!-- Total -->
        <section class="p-6 rounded-3xl bg-slate-900 text-white space-y-4 shadow-2xl shadow-indigo-100">
            <div class="flex justify-between items-center opacity-60">
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Subtotal</span>
                <span class="font-bold">₹<span id="subtotal">0.00</span></span>
            </div>
            <div class="h-px bg-white/10"></div>
            <div class="flex justify-between items-center text-xl font-black">
                <span class="uppercase tracking-[0.1em]">Grand Total</span>
                <span class="text-emerald-400">₹<span id="total">0.00</span></span>
            </div>
        </section>

        <button onclick="placeOrder()" id="placeOrderBtn" class="h-16 w-full rounded-2xl bg-indigo-600 text-white text-[10px] font-black uppercase tracking-[0.2em] flex items-center justify-center gap-3 hover:bg-indigo-700 transition-all active:scale-95 shadow-xl shadow-indigo-100">
            Place Order Now
            <iconify-icon icon="lucide:shield-check" width="20"></iconify-icon>
        </button>
    </main>
</div>

<script>
    function renderCheckout() {
        const cart = JSON.parse(localStorage.getItem('shop_cart') || '[]');
        const container = document.getElementById('checkoutItems');
        
        if (cart.length === 0) {
            window.location.href = "{{ route('shop-owner.products.index') }}";
            return;
        }

        let total = 0;
        cart.forEach(item => {
            total += item.price * item.quantity;
            const el = document.createElement('div');
            el.className = 'flex justify-between items-center py-2';
            el.innerHTML = `
                <div>
                    <p class="text-sm font-bold text-slate-900">${item.name}</p>
                    <p class="text-[10px] text-slate-400 font-medium">${item.quantity} x ₹${item.price.toLocaleString()}</p>
                </div>
                <span class="text-sm font-bold text-slate-900">₹${(item.price * item.quantity).toLocaleString()}</span>
            `;
            container.appendChild(el);
        });

        document.getElementById('subtotal').textContent = total.toLocaleString();
        document.getElementById('total').textContent = total.toLocaleString();
    }

    async function placeOrder() {
        const btn = document.getElementById('placeOrderBtn');
        const cart = JSON.parse(localStorage.getItem('shop_cart') || '[]');
        
        btn.disabled = true;
        btn.innerHTML = '<iconify-icon icon="lucide:loader-2" width="20" class="animate-spin"></iconify-icon> Processing...';

        try {
            const response = await fetch("{{ route('shop-owner.checkout.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ items: cart })
            });

            const data = await response.json();

            if (response.ok) {
                localStorage.removeItem('shop_cart');
                window.location.href = data.redirect_url;
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Order Failed',
                    text: data.message || 'Something went wrong',
                    confirmButtonText: 'Try Again'
                });
            }
        } catch (error) {
            console.error(error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Failed to place order. Please check your connection.',
                confirmButtonText: 'Close'
            });
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Place Order Now <iconify-icon icon="lucide:shield-check" width="20"></iconify-icon>';
        }
    }

    document.addEventListener('DOMContentLoaded', renderCheckout);
</script>
@endsection