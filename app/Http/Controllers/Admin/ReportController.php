<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Visit;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Date ranges for analytics
        $dateFrom = $request->get('date_from', now()->startOfMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        $start = \Carbon\Carbon::parse($dateFrom)->startOfDay();
        $end = \Carbon\Carbon::parse($dateTo)->endOfDay();
        
        // Sales Revenue in selected period
        $periodRevenue = Order::whereBetween('created_at', [$start, $end])
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount');
            
        // For comparison/other cards
        $dailyRevenue = Order::whereDate('created_at', now())
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount');
            
        $weeklyRevenue = Order::where('created_at', '>=', now()->startOfWeek())
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount');
            
        $monthlyRevenue = Order::where('created_at', '>=', now()->startOfMonth())
            ->whereIn('status', ['delivered', 'completed'])
            ->sum('total_amount');
        
        // Order Statistics in period
        $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        
        // User Statistics
        $activeSalespersons = User::where('role', 'salesperson')->count();
        $activeShops = Shop::count();
        
        // Leaderboards (Top 5 in period)
        $topSalespersons = User::where('role', 'salesperson')
            ->withSum(['salespersonOrders as total_revenue' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->whereIn('status', ['delivered', 'completed']);
            }], 'total_amount')
            ->withCount(['salespersonOrders as total_orders' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }])
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $topShops = Shop::withSum(['orders as total_revenue' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end])
                  ->whereIn('status', ['delivered', 'completed']);
            }], 'total_amount')
            ->withCount(['orders as total_orders' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }])
            ->with('bit')
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        $topProducts = Product::withSum(['orderItems as total_revenue' => function($q) use ($start, $end) {
                $q->whereHas('order', function($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                          ->whereIn('status', ['delivered', 'completed']);
                });
            }], 'subtotal')
            ->withCount(['orderItems as total_sales' => function($q) use ($start, $end) {
                $q->whereHas('order', function($query) use ($start, $end) {
                    $query->whereBetween('created_at', [$start, $end])
                          ->whereIn('status', ['delivered', 'completed']);
                });
            }])
            ->orderByDesc('total_revenue')
            ->limit(5)
            ->get();

        // Calculate Average Order Value in period
        $avgOrderValue = $totalOrders > 0 ? (Order::whereBetween('created_at', [$start, $end])->whereIn('status', ['delivered', 'completed'])->sum('total_amount') / $totalOrders) : 0;
        
        // New Customers (in period)
        $newShopsCount = Shop::whereBetween('created_at', [$start, $end])->count();

        // Low Stock Alert

        // Recent Activities
        $recentOrders = Order::with(['shop'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($order) {
                return [
                    'type' => 'order',
                    'title' => 'Order #' . str_pad($order->id, 5, '0', STR_PAD_LEFT),
                    'description' => 'Shop: ' . $order->shop->name . ' - Amount: ₹' . number_format($order->total_amount, 2),
                    'time' => $order->created_at,
                    'status' => $order->status
                ];
            });
            
        $recentVisits = Visit::with(['salesperson', 'shop'])
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($visit) {
                return [
                    'type' => 'visit',
                    'title' => 'Shop Visit',
                    'description' => $visit->salesperson->name . ' visited ' . $visit->shop->name,
                    'time' => $visit->created_at,
                    'status' => 'completed'
                ];
            });
        
        $recentActivities = $recentOrders->concat($recentVisits)
            ->sortByDesc('time')
            ->take(10)
            ->values();

        // Format for frontend
        $topSalespersonsData = $topSalespersons->map(fn($sp) => [
            'name' => $sp->name,
            'orders' => $sp->total_orders,
            'revenue' => (float)($sp->total_revenue ?? 0),
            'target' => 100000
        ]);

        $topShopsData = $topShops->map(fn($shop) => [
            'name' => $shop->name,
            'orders' => $shop->total_orders,
            'revenue' => (float)($shop->total_revenue ?? 0),
            'bit' => $shop->bit->name ?? 'N/A'
        ]);

        $topProductsData = $topProducts->map(fn($p) => [
            'name' => $p->name,
            'sales' => $p->total_sales,
            'revenue' => (float)($p->total_revenue ?? 0)
        ]);
        
        return view('admin.reports.index', compact(
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'periodRevenue',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders',
            'completedOrders',
            'activeSalespersons',
            'activeShops',
            'recentActivities',
            'topSalespersons',
            'topShops',
            'topProducts',
            'topSalespersonsData',
            'topShopsData',
            'topProductsData',
            'avgOrderValue',
            'newShopsCount',
            'dateFrom',
            'dateTo'
        ));
    }
    
    public function visitReports(Request $request)
    {
        $today = now()->startOfDay();
        $tomorrow = now()->endOfDay();

        // 1. Overall Statistics (All Today)
        $totalShopsCount = Shop::count();
        $visitsToday = Visit::whereDate('created_at', today())->get();
        
        $visitedTodayCount = $visitsToday->unique('shop_id')->count();
        $notVisitedTodayCount = max(0, $totalShopsCount - $visitedTodayCount);
        
        $visitedWithOrderCount = $visitsToday->where('status', 'ordered')->unique('shop_id')->count();
        $visitedWithoutOrderCount = $visitsToday->where('status', 'no_order')->unique('shop_id')->count();

        $overallStats = [
            'totalShops' => $totalShopsCount,
            'visitedToday' => $visitedTodayCount,
            'notVisited' => $notVisitedTodayCount,
            'withOrder' => $visitedWithOrderCount,
            'noOrder' => $visitedWithoutOrderCount,
            'visitedPercent' => $totalShopsCount > 0 ? round(($visitedTodayCount / $totalShopsCount) * 100, 1) : 0,
            'withOrderPercent' => $visitedTodayCount > 0 ? round(($visitedWithOrderCount / $visitedTodayCount) * 100, 1) : 0,
            'noOrderPercent' => $visitedTodayCount > 0 ? round(($visitedWithoutOrderCount / $visitedTodayCount) * 100, 1) : 0,
        ];

        // 2. Salesperson-wise Statistics
        $salespersons = User::where('role', 'salesperson')
            ->with(['managedShops', 'bit'])
            ->get();

        $salespersonsData = $salespersons->map(function($sp) use ($today, $tomorrow) {
            $assignedShops = $sp->managedShops;
            $assignedShopsCount = $assignedShops->count();
            $assignedShopIds = $assignedShops->pluck('id')->toArray();

            // Visits by this SP today
            $visitsToday = Visit::where('salesperson_id', $sp->id)
                ->whereDate('created_at', today())
                ->get();
                
            $visitedIds = $visitsToday->pluck('shop_id')->unique()->toArray();
            $visitedCount = count($visitedIds);
            $notVisitedCount = max(0, $assignedShopsCount - $visitedCount);

            $withOrderCount = $visitsToday->where('status', 'ordered')->unique('shop_id')->count();
            $noOrderCount = $visitsToday->where('status', 'no_order')->unique('shop_id')->count();

            // MTD Orders
            $mtdOrderCount = Order::where('salesperson_id', $sp->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            // Last visit
            $lastVisit = Visit::where('salesperson_id', $sp->id)
                ->latest()
                ->first();

            // Detailed visits today for JS
            $visitsTodayList = $visitsToday->map(function($v) use ($sp) {
                $order = Order::where('salesperson_id', $sp->id)
                    ->where('shop_id', $v->shop_id)
                    ->whereDate('created_at', $v->created_at->toDateString())
                    ->first();
                    
                return [
                    'outlet' => $v->shop->name ?? 'Unknown',
                    'time' => $v->created_at->format('h:i A'),
                    'orderAmount' => $order ? (float)$order->total_amount : 0
                ];
            });

            return [
                'id' => $sp->id,
                'name' => $sp->name,
                'phone' => $sp->phone ?? 'N/A',
                'email' => $sp->email,
                'bit' => $sp->bit->name ?? 'Unassigned',
                'status' => $sp->status ?? 'active',
                'assignedOutlets' => $assignedShopsCount,
                'stats' => [
                    'visited' => $visitedCount,
                    'notVisited' => $notVisitedCount,
                    'withOrder' => $withOrderCount,
                    'noOrder' => $noOrderCount
                ],
                'totalOrders' => $mtdOrderCount,
                'lastVisit' => $lastVisit ? ($lastVisit->created_at->isToday() ? 'Today, ' . $lastVisit->created_at->format('h:i A') : $lastVisit->created_at->diffForHumans()) : 'Never',
                'visitsToday' => $visitsTodayList
            ];
        });

        // 3. No Order Insights (Latest 50 No-Order Visits)
        $noOrderVisits = Visit::where('status', 'no_order')
            ->with(['salesperson', 'shop'])
            ->latest()
            ->limit(50)
            ->get();

        // Areas for filter (though the view doesn't use it yet in the redesign)
        $bits = \App\Models\Bit::all();

        return view('admin.reports.visit', compact(
            'overallStats',
            'salespersonsData',
            'noOrderVisits',
            'bits'
        ));
    }
    
    public function shopAnalysis(Request $request)
    {
        $shop_id = $request->get('shop_id');
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        if ($shop_id) {
            $shop = Shop::with(['bit', 'user', 'salesperson'])->findOrFail($shop_id);
            
            // Stats for this specific shop
            $stats = [
                'total_orders' => $shop->orders()->whereBetween('created_at', [$dateFrom, $dateTo])->count(),
                'total_revenue' => $shop->orders()->whereBetween('created_at', [$dateFrom, $dateTo])
                    ->whereIn('status', ['delivered', 'completed'])
                    ->sum('total_amount'),
                'avg_order_value' => 0,
            ];
            
            if ($stats['total_orders'] > 0) {
                $stats['avg_order_value'] = $stats['total_revenue'] / $stats['total_orders'];
            }

            // Monthly trends for this shop
            $trends = [];
            for ($i = 5; $i >= 0; $i--) {
                $monthStart = now()->subMonths($i)->startOfMonth();
                $monthEnd = now()->subMonths($i)->endOfMonth();
                $period = $monthStart->format('M Y');
                
                $orders = $shop->orders()->whereBetween('created_at', [$monthStart, $monthEnd])->count();
                $revenue = $shop->orders()->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->whereIn('status', ['delivered', 'completed'])
                    ->sum('total_amount');
                
                $trends[] = [
                    'period' => $period,
                    'orders' => $orders,
                    'revenue' => $revenue,
                    'growth' => '0%' // Growth calculation can be added if needed
                ];
            }

            // Top products for this shop
            $top_products = \App\Models\OrderItem::whereHas('order', function($q) use ($shop_id, $dateFrom, $dateTo) {
                    $q->where('shop_id', $shop_id)->whereBetween('created_at', [$dateFrom, $dateTo]);
                })
                ->select('product_id', \DB::raw('count(*) as total_orders'), \DB::raw('sum(subtotal) as total_revenue'))
                ->with('product')
                ->groupBy('product_id')
                ->orderByDesc('total_revenue')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'name' => $item->product->name ?? 'Unknown',
                        'orders' => $item->total_orders,
                        'revenue' => $item->total_revenue,
                        'growth' => '+0%'
                    ];
                });

            // Recent orders
            $recent_orders = $shop->orders()->latest()->limit(5)->get();

            return view('admin.shops.analysis', compact(
                'shop',
                'stats',
                'trends',
                'top_products',
                'recent_orders',
                'dateFrom',
                'dateTo'
            ));
        }

        // --- Overall Analysis (Existing logic kept or slightly improved) ---
        $sortBy = $request->get('sort_by', 'revenue'); 
        $sortOrder = $request->get('sort_order', 'desc');
        
        $shops = Shop::with(['bit'])
            ->withCount(['orders' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withSum(['orders' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo])
                  ->whereIn('status', ['delivered', 'completed']);
            }], 'total_amount')
            ->get()
            ->map(function($shop) use ($dateFrom, $dateTo) {
                $totalRevenue = $shop->orders_sum_total_amount ?? 0;
                $orderCount = $shop->orders_count ?? 0;
                $avgOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;
                
                return [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'bit' => $shop->bit->name ?? 'N/A',
                    'total_orders' => $orderCount,
                    'total_revenue' => $totalRevenue,
                    'avg_order_value' => $avgOrderValue,
                ];
            });
        
        // Sort shops based on selected criteria
        if ($sortBy === 'revenue') {
            $shops = $sortOrder === 'desc' 
                ? $shops->sortByDesc('total_revenue') 
                : $shops->sortBy('total_revenue');
        } elseif ($sortBy === 'orders') {
            $shops = $sortOrder === 'desc' 
                ? $shops->sortByDesc('total_orders') 
                : $shops->sortBy('total_orders');
        } elseif ($sortBy === 'avg_order') {
            $shops = $sortOrder === 'desc' 
                ? $shops->sortByDesc('avg_order_value') 
                : $shops->sortBy('avg_order_value');
        }
        
        // Overall statistics
        $totalShops = Shop::count();
        $totalRevenue = $shops->sum('total_revenue');
        $totalOrders = $shops->sum('total_orders');
        $avgRevenuePerShop = $totalShops > 0 ? $totalRevenue / $totalShops : 0;
        
        return view('admin.shops.analysis', compact(
            'shops',
            'totalShops',
            'totalRevenue',
            'totalOrders',
            'avgRevenuePerShop',
            'sortBy',
            'sortOrder',
            'dateFrom',
            'dateTo'
        ));
    }
    
    public function topShops(Request $request)
    {
        // Get ranking criteria
        $rankBy = $request->get('rank_by', 'revenue'); // revenue, orders, avg_order
        
        // Date range filter
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));
        
        // Bit filter
        $bitId = $request->get('bit_id');
        
        // Build base query
        $query = Shop::with(['bit']);
        
        if ($bitId) {
            $query->where('bit_id', $bitId);
        }
        
        // Get shops with statistics
        $shops = $query->withCount(['orders' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo]);
            }])
            ->withSum(['orders' => function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('created_at', [$dateFrom, $dateTo])
                  ->whereIn('status', ['delivered', 'completed']);
            }], 'total_amount')
            ->get()
            ->map(function($shop) {
                $totalRevenue = $shop->orders_sum_total_amount ?? 0;
                $orderCount = $shop->orders_count ?? 0;
                $avgOrderValue = $orderCount > 0 ? $totalRevenue / $orderCount : 0;
                
                return [
                    'id' => $shop->id,
                    'name' => $shop->name,
                    'bit' => $shop->bit->name ?? 'N/A',
                    'total_orders' => $orderCount,
                    'total_revenue' => $totalRevenue,
                    'avg_order_value' => $avgOrderValue,
                    'contact_person' => $shop->contact_person,
                    'phone' => $shop->phone,
                ];
            });
        
        // Rank shops based on criteria
        if ($rankBy === 'revenue') {
            $shops = $shops->sortByDesc('total_revenue')->values();
        } elseif ($rankBy === 'orders') {
            $shops = $shops->sortByDesc('total_orders')->values();
        } elseif ($rankBy === 'avg_order') {
            $shops = $shops->sortByDesc('avg_order_value')->values();
        }
        
        // Take top 20
        $topShops = $shops->take(20);
        
        // Get areas for filter
        $bits = \App\Models\Bit::all();
        
        return view('admin.shops.top', compact(
            'topShops',
            'rankBy',
            'dateFrom',
            'dateTo',
            'bitId',
            'bits'
        ));
    }
}