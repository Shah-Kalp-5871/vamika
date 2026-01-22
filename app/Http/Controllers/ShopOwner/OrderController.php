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
}