<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('shop-owner.checkout.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $shop = $user->shop;

        if (!$shop) {
            return response()->json(['message' => 'Shop not found'], 404);
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $order = Order::create([
                'shop_id' => $shop->id,
                'status' => 'pending',
                'total_amount' => 0, // Will update after items
                'payment_status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['id']);

                if ($product->status !== 'active') {
                    throw new \Exception("Product: {$product->name} is currently unavailable.");
                }

                // Check for stock availability
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for product: {$product->name}. Available: {$product->stock}, Requested: {$item['quantity']}.");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalAmount += $subtotal;

                // Decrement Stock
                $product->decrementStock($item['quantity']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'redirect_url' => route('shop-owner.orders.show', $order->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}