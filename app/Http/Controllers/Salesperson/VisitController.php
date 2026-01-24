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
        
        // Get all shops assigned to this salesperson
        $shops = Shop::where('salesperson_id', $salesperson->id)
            ->with(['area', 'visits' => function($q) use ($salesperson) {
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

        // Statistics for today
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
        $shop = Shop::where('salesperson_id', Auth::id())->findOrFail($id);
        
        Visit::updateOrCreate(
            [
                'salesperson_id' => Auth::id(),
                'shop_id' => $id,
                'visit_date' => now()->toDateString(),
            ],
            [
                'status' => 'no_order',
                'notes' => $request->notes ?? 'No order placed during visit',
                'visit_date' => now(), // Full timestamp for created_at logic if needed
            ]
        );
        
        return redirect()->back()->with('success', 'Visit marked as No Order');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'status' => 'required|in:ordered,no_order',
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
                'notes' => $request->notes,
                'visit_date' => now(),
            ]
        );
        
        return redirect()->route('salesperson.visits.index')
            ->with('success', 'Visit logged successfully');
    }
}