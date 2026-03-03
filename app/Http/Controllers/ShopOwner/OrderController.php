<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $orders = $shop ? $shop->orders()->latest()->get() : collect();
        return view('shop-owner.orders.index', compact('orders'));
    }
    
    public function show($id)
    {
        $order = Order::with(['items.product', 'shop'])->findOrFail($id);
        
        // Security check
        if ($order->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        return view('shop-owner.orders.show', compact('order'));
    }
    
    public function details($id)
    {
        $order = Order::with(['items.product', 'shop'])->findOrFail($id);
        
        if ($order->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        return view('shop-owner.orders.details', compact('order'));
    }
    
    public function invoices()
    {
        $shop = Auth::user()->shop;
        $orders = $shop ? $shop->orders()->whereIn('status', ['delivered', 'completed'])->latest()->get() : collect();
        return view('shop-owner.invoice.index', compact('orders'));
    }
    
    public function invoice($id)
    {
        $order = Order::with(['items.product', 'shop'])->findOrFail($id);
        
        if ($order->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        return view('shop-owner.invoice.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::with('items')->findOrFail($id);
        
        if ($order->shop_id !== Auth::user()->shop->id) {
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Order cannot be cancelled in its current status.');
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            // Restore stock
            foreach ($order->items as $item) {
                $product = \App\Models\Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            \Illuminate\Support\Facades\DB::commit();

            return redirect()->route('shop-owner.orders.index')->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }
}