<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Shop;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Total Revenue (Sales) - This Month
        $monthlyRevenue = Order::where('status', 'delivered')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');

        // 2. Today's Revenue
        $todayRevenue = Order::where('status', 'delivered')
            ->whereDate('created_at', Carbon::today())
            ->sum('total_amount');

        // 3. Total Shops
        $totalShops = Shop::where('status', 'active')->count();

        // 4. Pending Orders
        $pendingOrders = Order::where('status', 'pending')->count();

        // 5. Active Salespersons
        $activeSalespersons = User::where('role', 'salesperson')
            ->where('status', 'active')
            ->count();

        // 6. Recent Activities (Mocked for now or fetch from multiple tables)
        // For simplicity, let's fetch recent 5 orders
        $recentOrders = Order::with('shop')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        $recentActivities = $recentOrders->map(function ($order) {
            return [
                'type' => 'order',
                'title' => 'Order #' . $order->id,
                'description' => $order->shop->name . ' placed an order.',
                'time' => $order->created_at->diffForHumans(),
                'icon' => 'shopping-bag'
            ];
        });

        // Pass data to view
        $data = [
            'monthlyRevenue' => $monthlyRevenue,
            'todayRevenue' => $todayRevenue,
            'totalShops' => $totalShops,
            'pendingOrders' => $pendingOrders,
            'activeSalespersons' => $activeSalespersons,
            'recentActivities' => $recentActivities
        ];

        return view('admin.dashboard', compact('data'));
    }
}