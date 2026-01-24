<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('shops')->get();
        return view('admin.areas.index', compact('areas'));
    }
    
    public function create()
    {
        return view('admin.areas.create');
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
            'code' => 'required|string|max:50|unique:areas,code',
            'pincodes' => 'nullable|array',
            'pincodes.*' => 'string|digits:6',
            'status' => 'required|in:active,inactive',
        ]);

        Area::create($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area created successfully.');
    }
    
    public function edit($id)
    {
        $area = Area::findOrFail($id);
        return view('admin.areas.create', compact('area')); // Reusing create view for edit
    }
    
    public function update(Request $request, $id)
    {
        $area = Area::findOrFail($id);
        
        // Convert comma-separated string to array
        if ($request->has('pincodes_string')) {
             $pincodes = array_map('trim', explode(',', $request->pincodes_string));
             $request->merge(['pincodes' => $pincodes]);
        }
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:areas,code,' . $id,
            'pincodes' => 'nullable|array',
            'pincodes.*' => 'string|digits:6',
            'status' => 'required|in:active,inactive',
        ]);

        $area->update($validated);

        return redirect()->route('admin.areas.index')
            ->with('success', 'Area updated successfully.');
    }

    public function destroy($id)
    {
        $area = Area::withCount('shops')->findOrFail($id);
        
        if ($area->shops_count > 0) {
            return back()->with('error', 'Cannot delete area with assigned shops. Please reassign shops first.');
        }

        $area->delete();
        return back()->with('success', 'Area deleted successfully.');
    }
    
    public function performance($id)
    {
        $area = Area::with(['shops.orders'])->findOrFail($id);
        
        // Calculate stats
        $totalShops = $area->shops->count();
        $totalOrders = $area->shops->sum(fn($shop) => $shop->orders->count());
        $totalRevenue = $area->shops->sum(fn($shop) => $shop->orders->sum('total_amount'));
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        
        // Get assigned salespersons (unique)
        $salespersonsCount = \App\Models\User::whereHas('managedShops', function($query) use ($id) {
            $query->where('area_id', $id);
        })->count();

        // Get recent shops performance
        $shopsPerformance = $area->shops->map(function($shop) {
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

        return view('admin.areas.performance', compact('area', 'stats'));
    }
    
    public function assignForm()
    {
        return view('admin.areas.assign');
    }
    
    public function viewAssignments()
    {
        // This seems to be a placeholder for a different feature, keeping as is for now or implementing later
        return view('admin.salespersons.view-assignments');
    }
}