<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Visit;

class ShopController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $query = Shop::query();
        
        // salespersons only see their assigned shops
        $query->where('salesperson_id', $user->id);

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
        }

        $shops = $query->with('area')->withCount(['orders', 'visits'])->latest()->get();
        
        // Calculate stats
        $totalShops = $shops->count();
        $activeShops = $shops->where('status', 'active')->count();
        $todayOrders = Order::whereIn('shop_id', $shops->pluck('id'))
                            ->whereDate('created_at', today())
                            ->count();

        return view('salesperson.shops.index', compact('shops', 'totalShops', 'activeShops', 'todayOrders'));
    }
    
    public function select()
    {
        $areas = \App\Models\Area::where('status', 'active')->withCount('shops')->get()->map(function($area) {
            $area->pincode = is_array($area->pincodes) ? implode(', ', $area->pincodes) : ($area->pincodes ?? '');
            return $area;
        });

        $shops = Shop::where('status', 'active')
            ->with(['user', 'area'])
            ->withCount('orders')
            ->get()
            ->map(function($shop) {
                $shop->owner = $shop->user ? $shop->user->name : 'Unknown';
                return $shop;
            });
        
        return view('salesperson.shops.select', compact('areas', 'shops'));
    }
    
    public function show($id)
    {
        $shop = Shop::with(['area', 'orders' => function($q) {
            $q->latest()->limit(5);
        }, 'visits' => function($q) {
            $q->latest()->limit(5);
        }])->findOrFail($id);
        
        return view('salesperson.shops.details', compact('shop'));
    }
}