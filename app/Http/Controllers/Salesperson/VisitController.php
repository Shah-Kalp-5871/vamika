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
        $visits = Visit::with('shop')
            ->where('salesperson_id', Auth::id())
            ->latest('visit_date')
            ->get();
            
        return view('salesperson.visits.index', compact('visits'));
    }
    
    public function create()
    {
        $user = Auth::user();
        $shops = Shop::where('area_id', $user->area_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();
            
        return view('salesperson.visits.create', compact('shops'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'notes' => 'nullable|string',
            'location_lat' => 'nullable|numeric',
            'location_lng' => 'nullable|numeric',
        ]);
        
        Visit::create([
            'salesperson_id' => Auth::id(),
            'shop_id' => $request->shop_id,
            'visit_date' => now(),
            'notes' => $request->notes,
            'location_lat' => $request->location_lat,
            'location_lng' => $request->location_lng,
        ]);
        
        return redirect()->route('salesperson.visits.index')
            ->with('success', 'Visit logged successfully');
    }
}