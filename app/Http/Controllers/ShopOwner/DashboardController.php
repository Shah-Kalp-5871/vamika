<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Shop;
use App\Models\WalletTransaction;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shop = $user->shop; // Assuming User hasOne Shop relationship
        
        if (!$shop) {
            return redirect()->route('logout')->with('error', 'No shop associated with this user.');
        }

        $stats = [
            'totalOrders' => $shop->orders()->count(),
            'pendingOrders' => $shop->orders()->whereIn('status', ['pending', 'processing'])->count(),
            'deliveredOrders' => $shop->orders()->where('status', 'delivered')->count(),
            'walletBalance' => $shop->wallet->balance ?? 0,
        ];

        $recentOrders = $shop->orders()->latest()->limit(3)->get()->map(function($order) {
            return [
                'type' => 'order',
                'title' => 'Order #' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                'description' => 'Amount: ₹' . number_format($order->total_amount, 2),
                'time' => $order->created_at->diffForHumans(),
                'icon' => 'shopping-bag'
            ];
        });

        $recentTransactions = [];
        if ($shop->wallet) {
            $recentTransactions = $shop->wallet->transactions()->latest()->limit(2)->get()->map(function($tx) {
                return [
                    'type' => 'payment',
                    'title' => ucfirst($tx->type),
                    'description' => $tx->description . ': ₹' . number_format($tx->amount, 2),
                    'time' => $tx->created_at->diffForHumans(),
                    'icon' => 'dollar-sign'
                ];
            });
        }

        $recentActivities = $recentOrders->concat($recentTransactions)->sortByDesc('time')->values()->all();

        return view('shop-owner.dashboard', compact('user', 'shop', 'stats', 'recentActivities'));
    }
}