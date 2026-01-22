@extends('layouts.shop-owner')

@section('content')
<div class="max-w-2xl mx-auto min-h-screen pb-40 bg-white sm:shadow-xl sm:my-8 sm:rounded-2xl sm:overflow-hidden sm:border sm:border-slate-200 relative">
    <!-- Header -->
    <header class="p-6 bg-white border-b border-slate-100 flex items-center gap-4 sticky top-0 z-30">
        <a href="{{ route('shop-owner.dashboard') }}" class="p-2 -ml-2 rounded-full hover:bg-slate-50 text-slate-500 transition-colors">
            <iconify-icon icon="lucide:arrow-left" width="20"></iconify-icon>
        </a>
        <h2 class="text-lg font-bold text-slate-900">Product Catalog</h2>
    </header>

    <main class="p-6 space-y-6">
        <!-- Search -->
        <div class="relative">
            <iconify-icon icon="lucide:search" width="20" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></iconify-icon>
            <input type="text" id="productSearch" placeholder="Search products..." 
                class="w-full pl-12 pr-4 py-3.5 rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-indigo-100 transition-all text-sm font-medium">
        </div>

        <!-- Product List -->
        <div id="productList" class="grid grid-cols-1 gap-4">
            @forelse($products as $product)
                <div class="product-card p-4 rounded-2xl border border-slate-100 bg-white shadow-sm hover:border-indigo-100 transition-all flex items-center gap-4"
                    data-name="{{ strtolower($product->name) }}"
                    data-id="{{ $product->id }}"
                    data-price="{{ $product->price }}"
                    data-unit="{{ $product->unit }}">
                    
                    <div class="h-16 w-16 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-50 overflow-hidden flex-shrink-0">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <iconify-icon icon="lucide:package" width="24"></iconify-icon>
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div>
                                <h4 class="text-sm font-bold text-slate-900 truncate">{{ $product->name }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $product->unit }}</p>
                            </div>
                            <p class="text-sm font-black text-indigo-600">₹{{ number_format($product->price, 2) }}</p>
                        </div>
                        
                             <div class="flex items-center justify-between mt-3">
                            
                            <div class="flex items-center gap-2">
                                <button onclick="updateQty({{ $product->id }}, -1)" class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 active:scale-95 transition-all">
                                    <iconify-icon icon="lucide:minus" width="14"></iconify-icon>
                                </button>
                                <span id="qty-{{ $product->id }}" class="text-sm font-bold text-slate-900 w-4 text-center">0</span>
                                <button onclick="updateQty({{ $product->id }}, 1)" class="w-8 h-8 rounded-full bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 active:scale-95 transition-all">
                                    <iconify-icon icon="lucide:plus" width="14"></iconify-icon>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center text-slate-400">
                    <iconify-icon icon="lucide:package-search" width="48" class="mb-4 opacity-20"></iconify-icon>
                    <p class="text-sm font-medium">No products available</p>
                </div>
            @endforelse
        </div>
    </main>

    <!-- Floating Cart Bar -->
    <div id="cartBar" class="fixed bottom-24 left-1/2 -translate-x-1/2 w-[calc(100%-3rem)] max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-100 p-4 transition-all translate-y-32 z-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <iconify-icon icon="lucide:shopping-cart" width="20"></iconify-icon>
                </div>
                <div>
                    <h4 class="text-xs font-black text-slate-900 uppercase">Your Cart</h4>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5"><span id="cartCount">0</span> Items</p>
                </div>
            </div>
            <div class="text-right flex items-center gap-4">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase">Total</p>
                    <h4 class="text-lg font-black text-indigo-600">₹<span id="cartTotal">0</span></h4>
                </div>
                <a href="{{ route('shop-owner.cart.index') }}" class="h-10 px-6 rounded-xl bg-indigo-600 text-white text-[10px] font-bold uppercase tracking-widest flex items-center justify-center hover:bg-indigo-700 active:scale-95 transition-all">
                    Checkout
                </a >
            </div>
        </div>
    </div>
</div>

<script>
    const cart = {};
    const products = @json($products);

    function updateQty(id, delta) {
        cart[id] = (cart[id] || 0) + delta;
        if (cart[id] < 0) cart[id] = 0;
        
        document.getElementById(`qty-${id}`).textContent = cart[id];
        updateCart();
    }

    function updateCart() {
        let count = 0;
        let total = 0;
        const cartItems = [];

        Object.entries(cart).forEach(([id, qty]) => {
            if (qty > 0) {
                const product = products.find(p => p.id == id);
                count += qty;
                total += qty * product.price;
                cartItems.push({
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: qty,
                    unit: product.unit,
                    image: product.image_url
                });
            }
        });

        document.getElementById('cartCount').textContent = count;
        document.getElementById('cartTotal').textContent = total.toLocaleString();

        const cartBar = document.getElementById('cartBar');
        if (count > 0) {
            cartBar.classList.remove('translate-y-32');
        } else {
            cartBar.classList.add('translate-y-32');
        }

        localStorage.setItem('shop_cart', JSON.stringify(cartItems));
    }

    // Initialize from storage
    document.addEventListener('DOMContentLoaded', () => {
        const saved = JSON.parse(localStorage.getItem('shop_cart') || '[]');
        saved.forEach(item => {
            cart[item.id] = item.quantity;
            if (document.getElementById(`qty-${item.id}`)) {
                document.getElementById(`qty-${item.id}`).textContent = item.quantity;
            }
        });
        updateCart();
    });

    // Search functionality
    document.getElementById('productSearch').addEventListener('input', (e) => {
        const query = e.target.value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.dataset.name;
            if (name.includes(query)) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>
@endsection