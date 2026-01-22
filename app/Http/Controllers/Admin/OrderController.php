<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['shop', 'salesperson']);

        // Filter by Date Range
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('created_at', [$request->start_date, $request->end_date . ' 23:59:59']);
        }

        // Filter by Status
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }
    
    public function show($id)
    {
        $order = Order::with(['shop.area', 'salesperson', 'items.product', 'walletTransactions'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        
        DB::transaction(function() use ($order, $request) {
            $oldStatus = $order->status;
            $newStatus = $request->status;

            $order->update([
                'status' => $newStatus,
                'payment_status' => $request->payment_status,
            ]);


        });

        return redirect()->back()->with('success', 'Order status updated successfully');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // Optional: Restore stock if deleting? Or just soft delete?
        // Usually orders aren't hard deleted. Let's assume soft delete or just blocked for now. 
        // But for this CRUD, we will delete.
        $order->delete();
        
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully');
    }
    public function consolidation()
    {
        // Fetch products that have pending/active orders
        $consolidated = \App\Models\OrderItem::whereHas('order', function($q) {
            $q->whereNotIn('status', ['cancelled', 'delivered', 'completed']);
        })
        ->with(['product', 'order.shop', 'order.salesperson'])
        ->get()
        ->groupBy('product_id');

        $products = $consolidated->map(function($items) {
            $product = $items->first()->product;
            if (!$product) return null;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category ?? 'General',
                'unit' => $product->unit ?? 'pcs',
                'price' => $product->price,
                'sku' => $product->sku,
                'description' => $product->description,
                'brand' => $product->brand ?? 'Vamika',
                'currentOrders' => $items->map(function($item) {
                    return [
                        'salesperson' => $item->order->salesperson->name ?? 'N/A',
                        'shop' => $item->order->shop->name ?? 'N/A',
                        'quantity' => $item->quantity,
                        'orderDate' => $item->order->created_at->format('Y-m-d'),
                        'deliveryDate' => $item->order->delivery_date ?? 'TBD',
                        'orderId' => 'ORD-' . str_pad($item->order_id, 5, '0', STR_PAD_LEFT)
                    ];
                })->values()
            ];
        })->filter()->values();

        return view('admin.orders.consolidation', compact('products'));
    }
}