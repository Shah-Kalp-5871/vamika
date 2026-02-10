<?php

namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Bit;

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
        
        // Show all shops
        // $query->where('bit_id', $user->bit_id);

        // Search functionality
        if (request('search')) {
            $search = request('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%");
        }

        $shops = $query->with('bit')->withCount(['orders', 'visits'])->latest()->get();
        
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
        $user = Auth::user();
        $bitId = $user->bit_id;
        
        // If no bit selected, redirect to bit selection
        if (!$bitId) {
            return redirect()->route('salesperson.bits.select');
        }

        $bit = Bit::withCount('shops')->findOrFail($bitId);
        
        // Shops in this bit HAVEN'T been visited today
        $shops = Shop::where('status', 'active')
            ->where('bit_id', $bitId)
            ->whereDoesntHave('visits', function($q) {
                $q->whereDate('visit_date', now()->toDateString());
            })
            ->with(['user', 'bit'])
            ->withCount('orders')
            ->get()
            ->map(function($shop) {
                $shop->owner = $shop->user ? $shop->user->name : 'Unknown';
                return $shop;
            });
        
        return view('salesperson.shops.select', compact('bit', 'shops'));
    }
    
    public function create()
    {
        $bits = Bit::where('status', 'active')->get();
        return view('salesperson.shops.create', compact('bits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'bit_id' => 'required|exists:bits,id',
            'address' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            // 1. Create User for Shop Owner
            $user = User::create([
                'name' => $request->owner_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'shop-owner',
                'status' => 'active',
            ]);

            // 2. Create Shop
            $shop = Shop::create([
                'user_id' => $user->id,
                'bit_id' => $request->bit_id,
                'salesperson_id' => Auth::id(),
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'status' => 'active',
                'credit_limit' => 0,
                'current_balance' => 0,
            ]);

            // 3. Create Wallet for Shop
            $shop->wallet()->create([
                'balance' => 0,
                'status' => 'active'
            ]);

            DB::commit();

            return redirect()->route('salesperson.shops.index')
                             ->with('success', 'Shop and owner account created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create shop: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $shop = Shop::with(['user', 'bit', 'orders' => function($q) {
            $q->latest()->limit(10);
        }, 'visits' => function($q) {
            $q->latest()->limit(5);
        }])->findOrFail($id);

        // Calculate Stats
        $stats = [
            'total_orders' => $shop->orders()->count(),
            'total_revenue' => $shop->orders()->where('status', '!=', 'cancelled')->sum('total_amount'),
            'last_order' => $shop->orders()->latest()->first(),
        ];
        
        $stats['avg_order_value'] = $stats['total_orders'] > 0 
            ? $stats['total_revenue'] / $stats['total_orders'] 
            : 0;

        // Frequently Ordered Products (Example logic)
        $popularProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.shop_id', $shop->id)
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_quantity'), DB::raw('COUNT(*) as order_frequency'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();

        return view('salesperson.shops.details', compact('shop', 'stats', 'popularProducts'));
    }
}