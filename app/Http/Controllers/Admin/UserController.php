<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Shop;
use App\Models\User;
use App\Models\Order;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with(['shop', 'area'])->whereIn('role', ['salesperson', 'shop-owner']);

        if ($request->has('role') && in_array($request->role, ['salesperson', 'shop-owner'])) {
            $query->where('role', $request->role);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('shop', function ($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $users = $query->latest()->paginate(10);
        $totalShopOwners = User::where('role', 'shop-owner')->count();
        $totalSalespersons = User::where('role', 'salesperson')->count();

        return view('admin.users.index', compact('users', 'totalShopOwners', 'totalSalespersons'));
    }
    
    public function create()
    {
        $areas = Area::where('status', 'active')->get();
        return view('admin.users.create', compact('areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'user_type' => 'required|in:shop-owner,salesperson',
            'area_id' => 'required_if:user_type,shop-owner|exists:areas,id',
        ]);
        
        if ($request->user_type === 'shop-owner') {
            $request->validate([
                'shop_name' => 'required|string|max:255',
                'address' => 'required|string',
            ]);
        }
        
        
        $area = $request->area_id ? Area::find($request->area_id) : null;


        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => $request->user_type === 'shop-owner' ? 'shop-owner' : 'salesperson',
                'status' => 'active',
                'area_id' => ($request->user_type === 'salesperson' && $area) ? $area->id : null, // Salesperson might not have area initially

                'employee_id' => $request->user_type === 'salesperson' ? $request->employee_id : null,
            ]);

            if ($request->user_type === 'shop-owner') {
                $request->validate([
                    'shop_name' => 'required|string|max:255',
                ]);

                // Create Shop
                Shop::create([
                    'user_id' => $user->id,
                    'area_id' => $area->id ?? null,

                    'name' => $request->shop_name,
                    'address' => $request->address,
                    'phone' => $request->phone,
                    'status' => 'active',
                ]);
            }

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error creating user: ' . $e->getMessage())->withInput();
        }
    }
    
    public function edit($id)
    {
        $user = User::with('shop')->findOrFail($id);
        $areas = Area::where('status', 'active')->get();
        return view('admin.users.edit', compact('user', 'areas'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'area_id' => 'required|exists:areas,id',
            'status' => 'nullable|in:active,inactive', // Form sends checkbox value if checked, or nothing
        ]);

        $area = Area::findOrFail($request->area_id);
        $status = $request->has('status') ? 'active' : 'inactive'; // Handle checkbox

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $status,
                'area_id' => $user->role === 'salesperson' ? $area->id : $user->area_id, // Keep null if shop-owner, or update if salesperson? 
                // Wait, Shop Owner has area via Shop. Salesperson has area via User. 
                // Let's be consistent.
                // If salesperson, update area_id.
            ]);
            
            if ($user->role === 'salesperson') {
                 $user->update([
                    'area_id' => $area->id,
                    'employee_id' => $request->employee_id,
                 ]);
            }

            if ($request->filled('password')) {
                $request->validate([
                    'password' => 'string|min:8|confirmed',
                ]);
                $user->update(['password' => Hash::make($request->password)]);
            }

            if ($user->role === 'shop-owner') {
                $request->validate([
                    'shop_name' => 'required|string|max:255',
                ]);

                // Update or Create Shop logic (in case it's missing for some reason)
                $shop = $user->shop;
                if ($shop) {
                    $shop->update([
                        'name' => $request->shop_name,
                        'area_id' => $area->id,
                        'address' => $request->address,
                        'phone' => $request->phone, // Update shop phone too
                    ]);
                } else {
                     Shop::create([
                        'user_id' => $user->id,
                        'area_id' => $area->id,
                        'name' => $request->shop_name,
                        'address' => $request->address,
                        'phone' => $request->phone,
                        'status' => 'active',
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }
    
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting self or other admins if needed (though query filters unrelated roles)
        if ($user->role === 'admin') {
             return back()->with('error', 'Cannot delete admin users.');
        }

        try {
            // DB constraints should handle cascade, but explicitly deleting shop is safer if no cascade
            if ($user->shop) {
                $user->shop->delete();
            }
            $user->delete();
            
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function salespersons()
    {
        return view('admin.salespersons.index');
    }
    
    public function salespersonDetails($id)
    {
        $salesperson = User::with(['area', 'managedShops', 'visits', 'salespersonOrders'])->findOrFail($id);
        
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // 1. MTD Data
        $mtdOrders = Order::where('salesperson_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
        
        $mtdOrderCount = $mtdOrders->count();
        $mtdRevenue = $mtdOrders->sum('total_amount');
        $avgOrderValue = $mtdOrderCount > 0 ? $mtdRevenue / $mtdOrderCount : 0;

        // 2. Today's Stats
        $assignedShopsCount = $salesperson->managedShops->count();
        
        $visitsToday = Visit::with('shop')
            ->where('salesperson_id', $id)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->get();
            
        $visitedIds = $visitsToday->pluck('shop_id')->unique();
        $visitedCount = $visitedIds->count();
        $notVisitedCount = max(0, $assignedShopsCount - $visitedCount);

        $ordersToday = Order::where('salesperson_id', $id)
            ->whereBetween('created_at', [$today, $tomorrow])
            ->get();
            
        $shopsWithOrderIds = $ordersToday->pluck('shop_id')->unique();
        $visitedWithOrderCount = $visitedIds->intersect($shopsWithOrderIds)->count();
        $noOrderCount = max(0, $visitedCount - $visitedWithOrderCount);

        // 3. Performance Metrics
        $visitRate = $assignedShopsCount > 0 ? ($visitedCount / $assignedShopsCount) * 100 : 0;
        $conversionRate = $visitedCount > 0 ? ($visitedWithOrderCount / $visitedCount) * 100 : 0;

        // 4. Visits List for Today
        $visitsList = $visitsToday->map(function($visit) use ($id, $ordersToday) {
            $order = $ordersToday->where('shop_id', $visit->shop_id)->first();
            return [
                'id' => $visit->id,
                'outlet' => $visit->shop->name ?? 'Unknown',
                'time' => $visit->created_at->format('h:i A'),
                'orderAmount' => $order ? (float)$order->total_amount : 0,
                'status' => $order ? 'with-order' : 'no-order',
                'orderId' => $order ? $order->id : null,
                'outletId' => $visit->shop_id
            ];
        });

        // 5. Activity
        $firstVisit = $visitsToday->sortBy('created_at')->first();
        $lastVisit = $visitsToday->sortByDesc('created_at')->first();
        $totalValueToday = $ordersToday->sum('total_amount');

        $salespersonData = [
            'name' => $salesperson->name,
            'phone' => $salesperson->phone ?? 'N/A',
            'email' => $salesperson->email,
            'area' => $salesperson->area->name ?? 'Unassigned',
            'status' => $salesperson->status ?? 'active',
            'assignedOutlets' => $assignedShopsCount,
            'stats' => [
                'visited' => $visitedCount,
                'notVisited' => $notVisitedCount,
                'withOrder' => $visitedWithOrderCount,
                'noOrder' => $noOrderCount
            ],
            'totalOrders' => $mtdOrderCount,
            'performance' => [
                'visitRate' => round($visitRate),
                'conversionRate' => round($conversionRate),
                'avgOrderValue' => round($avgOrderValue)
            ],
            'activity' => [
                'startTime' => $firstVisit ? 'Today at ' . $firstVisit->created_at->format('h:i A') : 'Not started',
                'lastUpdate' => $lastVisit ? 'Today at ' . $lastVisit->created_at->format('h:i A') : 'No updates',
                'totalValue' => (float)$totalValueToday
            ],
            'visitsToday' => $visitsList
        ];

        return view('admin.salespersons.details', compact('salespersonData', 'id'));
    }
    
    public function assignSalespersonForm(Request $request)
    {
        $user_id = $request->get('user_id');
        $salesperson = $user_id ? User::with(['area', 'managedShops'])->find($user_id) : null;
        
        // Fetch all salespersons for the selection dropdown if needed
        $salespersons = User::where('role', 'salesperson')->where('status', 'active')->get();
        $areas = Area::where('status', 'active')->get();
        
        return view('admin.salespersons.assign', compact('salesperson', 'areas', 'salespersons'));
    }

    public function getShopsByArea($area_id)
    {
        $shops = Shop::with('salesperson:id,name')->where('area_id', $area_id)->where('status', 'active')->get(['id', 'name', 'salesperson_id']);
        return response()->json($shops);
    }

    public function storeAssignment(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'area_id' => 'required|exists:areas,id',
            'shop_ids' => 'required|array',
            'shop_ids.*' => 'exists:shops,id',
        ]);

        try {
            DB::beginTransaction();

            $salesperson = User::findOrFail($request->user_id);
            $salesperson->update(['area_id' => $request->area_id]);

            // Clear previous shop assignments for this salesperson if any (optional, based on requirement)
            // or just add to them. The request says "select the shop inside it".
            // I'll update all selected shops to this salesperson.
            
            // First, remove this salesperson from shops they might have had before
            Shop::where('salesperson_id', $salesperson->id)->update(['salesperson_id' => null]);
            
            // Then assign to new ones
            Shop::whereIn('id', $request->shop_ids)->update(['salesperson_id' => $salesperson->id]);

            DB::commit();

            return redirect()->route('admin.users.index')->with('success', 'Assignment saved successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error saving assignment: ' . $e->getMessage());
        }
    }
    
    public function topSalespersons()
    {
        return view('admin.salespersons.top');
    }
}