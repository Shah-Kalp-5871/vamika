<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Visit;
use App\Models\Order;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->bit_id) {
            return redirect()->route('salesperson.bits.select');
        }

        $salespersonId = $user->id;
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        // Stats
        $todayVisits = Visit::where('salesperson_id', $salespersonId)
            ->whereDate('visit_date', $today)
            ->count();

        $todayOrders = Order::where('salesperson_id', $salespersonId)
            ->whereDate('created_at', $today)
            ->count();
            
        $todayOrderValue = Order::where('salesperson_id', $salespersonId)
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $monthlySales = Order::where('salesperson_id', $salespersonId)
            ->whereDate('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        // Recent Activity
        $recentVisits = Visit::with('shop')
            ->where('salesperson_id', $salespersonId)
            ->latest('visit_date')
            ->limit(5)
            ->get();

        $recentOrders = Order::with('shop')
            ->where('salesperson_id', $salespersonId)
            ->latest()
            ->limit(5)
            ->get();

        return view('salesperson.dashboard', compact(
            'todayVisits', 
            'todayOrders', 
            'todayOrderValue', 
            'monthlySales',
            'recentVisits',
            'recentOrders'
        ));
    }
    
    public function sales()
    {
        return view('salesperson.sales.index');
    }
}