<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request)
    {
        $shopId = $request->get('shop_id');
        $shop = null;
        
        if ($shopId) {
            $shop = Shop::findOrFail($shopId);
        } else {
            // If no shop_id, redirect back to shop selection
            return redirect()->route('salesperson.shops.index')->with('error', 'Please select a shop first.');
        }

        $products = Product::where('status', 'active')->get();
        $categories = Product::CATEGORIES;
        $brands = Product::BRANDS;
        $subBrands = Product::SUB_BRANDS;
        
        return view('salesperson.orders.create', compact('shop', 'products', 'categories', 'brands', 'subBrands'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'shop_id' => $request->shop_id,
                'salesperson_id' => Auth::id(),
                'total_amount' => 0, // Will update after calculating items
                'status' => 'pending',
                'payment_status' => 'pending',
                'notes' => $request->notes,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $itemData) {
                $product = Product::findOrFail($itemData['product_id']);
                
                // Check for stock availability
                if ($product->stock < $itemData['quantity']) {
                    throw new \Exception("Not enough stock for product: {$product->name}. Available: {$product->stock}, Requested: {$itemData['quantity']}.");
                }

                $subtotal = $product->price * $itemData['quantity'];
                $totalAmount += $subtotal;

                // Decrement Stock
                $product->decrementStock($itemData['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

            }

            $order->update(['total_amount' => $totalAmount]);

            // Always record this as a visit
            \App\Models\Visit::updateOrCreate(
                [
                    'salesperson_id' => Auth::id(),
                    'shop_id' => $request->shop_id,
                    'visit_date' => now()->toDateString(),
                ],
                [
                    'status' => 'ordered',
                    'order_id' => $order->id,
                    'visit_date' => now(), // Full timestamp
                ]
            );

            DB::commit();

            return redirect()->route('salesperson.orders.invoice', $order->id)->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function review($id)
    {
        $order = Order::with(['shop', 'items.product'])->findOrFail($id);
        return view('salesperson.orders.review', compact('order'));
    }
    
    public function invoice($id)
    {
        $order = Order::with(['shop', 'items.product'])->findOrFail($id);
        return view('salesperson.orders.invoice', compact('order'));
    }
}