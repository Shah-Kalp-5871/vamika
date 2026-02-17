<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Visit;
use App\Models\Shop;
use Illuminate\Http\Request;

class VisitController extends Controller
{
    public function index()
    {
        $salesperson = Auth::user();
        $bitId = $salesperson->bit_id;

        // If no bit selected, redirect to bit selection
        if (!$bitId) {
            return redirect()->route('salesperson.bits.select')->with('info', 'Please select a bit to view your daily visits.');
        }

        // Get all shops in the active bit
        $shops = Shop::where('bit_id', $bitId)
            ->with(['bit', 'visits' => function($q) use ($salesperson) {
                $q->where('salesperson_id', $salesperson->id)
                  ->whereDate('visit_date', now()->toDateString());
            }])
            ->get()
            ->map(function($shop) {
                $todayVisit = $shop->visits->first();
                $shop->status_today = $todayVisit ? $todayVisit->status : 'pending';
                $shop->visited_today = $todayVisit ? true : false;
                $shop->visited_time = $todayVisit ? $todayVisit->created_at->format('h:i A') : null;
                return $shop;
            });

        // Statistics for today (based on bit's shops)
        $stats = [
            'total' => $shops->count(),
            'visited' => $shops->where('visited_today', true)->count(),
            'not_visited' => $shops->where('visited_today', false)->count(),
            'orders' => $shops->where('status_today', 'ordered')->count(),
            'no_orders' => $shops->where('status_today', 'no_order')->count(),
        ];

        return view('salesperson.visits.index', compact('shops', 'stats'));
    }
    
    public function markAsNoOrder(Request $request, $id)
    {
        $request->validate([
            'no_order_reason' => 'required|in:shop_closed,owner_not_available,stock_sufficient,payment_issue,other',
            'notes' => 'nullable|string',
        ]);
        
        $shop = Shop::findOrFail($id);
        
        Visit::updateOrCreate(
            [
                'salesperson_id' => Auth::id(),
                'shop_id' => $id,
                'visit_date' => now()->toDateString(),
            ],
            [
                'status' => 'no_order',
                'no_order_reason' => $request->no_order_reason,
                'notes' => $request->notes,
                'visit_date' => now(), // Full timestamp for created_at logic if needed
            ]
        );
        
        return redirect()->route('salesperson.visits.index')->with('success', 'Visit marked as No Order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'status' => 'required|in:ordered,no_order',
            'no_order_reason' => 'required_if:status,no_order|in:shop_closed,owner_not_available,stock_sufficient,payment_issue,other',
            'notes' => 'nullable|string',
        ]);
        
        Visit::updateOrCreate(
            [
                'salesperson_id' => Auth::id(),
                'shop_id' => $request->shop_id,
                'visit_date' => now()->toDateString(),
            ],
            [
                'status' => $request->status,
                'no_order_reason' => $request->status === 'no_order' ? $request->no_order_reason : null,
                'notes' => $request->notes,
                'visit_date' => now(),
            ]
        );
        
        return redirect()->route('salesperson.visits.index')
            ->with('success', 'Visit logged successfully');
    }
}