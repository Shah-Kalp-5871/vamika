<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bit;
use Illuminate\Http\Request;

class BitController extends Controller
{
    public function index()
    {
        $bits = Bit::withCount('shops')->get();
        return view('admin.bits.index', compact('bits'));
    }
    
    public function create()
    {
        return view('admin.bits.create');
    }
    
    public function store(Request $request)
    {
        // Convert comma-separated string to array for validation and storage
        if ($request->has('pincodes_string')) {
             $pincodes = array_map('trim', explode(',', $request->pincodes_string));
             $request->merge(['pincodes' => $pincodes]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bits,code',
            'pincodes' => 'nullable|array',
            'pincodes.*' => 'string|digits:6',
            'status' => 'required|in:active,inactive',
        ]);

        Bit::create($validated);

        return redirect()->route('admin.bits.index')
            ->with('success', 'Bit created successfully.');
    }
    
    public function edit($id)
    {
        $bit = Bit::findOrFail($id);
        return view('admin.bits.create', compact('bit')); // Reusing create view for edit
    }
    
    public function update(Request $request, $id)
    {
        $bit = Bit::findOrFail($id);
        
        // Convert comma-separated string to array
        if ($request->has('pincodes_string')) {
             $pincodes = array_map('trim', explode(',', $request->pincodes_string));
             $request->merge(['pincodes' => $pincodes]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:bits,code,' . $id,
            'pincodes' => 'nullable|array',
            'pincodes.*' => 'string|digits:6',
            'status' => 'required|in:active,inactive',
        ]);

        $bit->update($validated);

        return redirect()->route('admin.bits.index')
            ->with('success', 'Bit updated successfully.');
    }

    public function destroy($id)
    {
        $bit = Bit::withCount('shops')->findOrFail($id);
        
        if ($bit->shops_count > 0) {
            return back()->with('error', 'Cannot delete bit with existing shops. Please reposition shops first.');
        }

        $bit->delete();
        return back()->with('success', 'Bit deleted successfully.');
    }
    
    public function performance($id)
    {
        $bit = Bit::with(['shops.orders'])->findOrFail($id);
        
        // Calculate stats
        $totalShops = $bit->shops->count();
        $totalOrders = $bit->shops->sum(fn($shop) => $shop->orders->count());
        $totalRevenue = $bit->shops->sum(fn($shop) => $shop->orders->sum('total_amount'));
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Get assigned salespersons (unique)
        $salespersonsCount = \App\Models\User::whereHas('managedShops', function($query) use ($id) {
            $query->where('bit_id', $id);
        })->count();

        // Get recent shops performance
        $shopsPerformance = $bit->shops->map(function($shop) {
            return [
                'name' => $shop->name,
                'owner' => $shop->user->name ?? 'N/A',
                'orders' => $shop->orders->count(),
                'revenue' => $shop->orders->sum('total_amount'),
                'last_order' => $shop->orders->max('created_at'),
            ];
        })->sortByDesc('revenue')->take(5);

        $stats = [
            'total_shops' => $totalShops,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'avg_order_value' => $avgOrderValue,
            'salespersons_count' => $salespersonsCount,
            'shops_performance' => $shopsPerformance,
        ];

        return view('admin.bits.performance', compact('bit', 'stats'));
    }
    public function shops($id)
    {
        $bit = Bit::findOrFail($id);
        $shops = \App\Models\Shop::where('bit_id', $id)->with(['salesperson', 'user'])->get();
        return response()->json([
            'bit' => $bit->name,
            'shops' => $shops
        ]);
    }
}