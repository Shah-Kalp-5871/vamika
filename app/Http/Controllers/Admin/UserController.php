<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bit;
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
        $query = User::with(['shop', 'bit'])->whereIn('role', ['salesperson', 'shop-owner']);

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

        if ($request->has('source') && in_array($request->source, ['self', 'admin', 'salesperson'])) {
            if ($request->source === 'self') {
                $query->whereNull('created_by');
            } else {
                $query->whereHas('creator', function ($q) use ($request) {
                    $q->where('role', $request->source);
                });
            }
        }

        $users = $query->latest()->paginate(10);
        $totalShopOwners = User::where('role', 'shop-owner')->count();
        $totalSalespersons = User::where('role', 'salesperson')->count();

        return view('admin.users.index', compact('users', 'totalShopOwners', 'totalSalespersons'));
    }
    
    public function create()
    {
        $bits = Bit::where('status', 'active')->get();
        return view('admin.users.create', compact('bits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => $request->user_type === 'shop-owner' ? 'nullable|string|min:8|confirmed' : 'required|string|min:8|confirmed',
            'user_type' => 'required|in:shop-owner,salesperson',
            'bit_id' => 'required_if:user_type,shop-owner|exists:bits,id',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
        ]);
        
        if ($request->user_type === 'shop-owner') {
            $request->validate([
                'shop_name' => 'required|string|max:255',
                'address' => 'required|string',
            ]);
        }
        
        $bit = $request->bit_id ? Bit::find($request->bit_id) : null;

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password ?: ($request->user_type === 'shop-owner' ? 'demo1234' : $request->password)),
                'role' => $request->user_type === 'shop-owner' ? 'shop-owner' : 'salesperson',
                'status' => 'active',
                'bit_id' => ($request->user_type === 'salesperson' && $bit) ? $bit->id : null,
                'employee_id' => $request->user_type === 'salesperson' ? $request->employee_id : null,
                'work_start_time' => $request->work_start_time,
                'work_end_time' => $request->work_end_time,
                'created_by' => auth()->id(),
            ]);

            if ($request->user_type === 'shop-owner') {
                // Create Shop
                Shop::create([
                    'user_id' => $user->id,
                    'bit_id' => $bit->id ?? null,
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
        $bits = Bit::where('status', 'active')->get();
        return view('admin.users.edit', compact('user', 'bits'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'bit_id' => 'required_if:role,shop-owner|exists:bits,id',
            'status' => 'nullable|in:active,inactive',
            'work_start_time' => 'nullable|string', // Support H:i or H:i:s
            'work_end_time' => 'nullable|string',
        ]);

        $status = $request->has('status') ? 'active' : 'inactive';

        try {
            DB::beginTransaction();

            $updateData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $status,
                'work_start_time' => $request->work_start_time,
                'work_end_time' => $request->work_end_time,
            ];

            if ($user->role === 'salesperson') {
                $updateData['employee_id'] = $request->employee_id;
            }

            $user->update($updateData);

            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:8|confirmed']);
                $user->update(['password' => Hash::make($request->password)]);
            }

            if ($user->role === 'shop-owner' && $request->bit_id) {
                $shop = $user->shop;
                if ($shop) {
                    $shop->update([
                        'name' => $request->shop_name,
                        'bit_id' => $request->bit_id,
                        'address' => $request->address,
                        'phone' => $request->phone,
                    ]);
                } else {
                     Shop::create([
                        'user_id' => $user->id,
                        'bit_id' => $request->bit_id,
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
        
        if ($user->role === 'admin') {
             return back()->with('error', 'Cannot delete admin users.');
        }

        try {
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
        return redirect()->route('admin.users.index', ['role' => 'salesperson']);
    }
    
    public function salespersonDetails($id)
    {
        $salesperson = User::with(['bit', 'managedShops', 'visits', 'salespersonOrders'])->findOrFail($id);
        
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();

        // MTD Data
        $mtdOrders = Order::where('salesperson_id', $id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->get();
        
        $mtdOrderCount = $mtdOrders->count();
        $mtdRevenue = $mtdOrders->sum('total_amount');
        $avgOrderValue = $mtdOrderCount > 0 ? $mtdRevenue / $mtdOrderCount : 0;

        // Today's Stats
        // In simple flow, ManagedShops might not be assigned by admin, 
        // but it still tracks which shops this salesperson actually visited/ordered from?
        // Actually, the relationship is still there in the DB.
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

        // Performance Metrics
        $visitRate = $assignedShopsCount > 0 ? ($visitedCount / $assignedShopsCount) * 100 : 0;
        $conversionRate = $visitedCount > 0 ? ($visitedWithOrderCount / $visitedCount) * 100 : 0;

        // Visits List for Today
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

        $firstVisit = $visitsToday->sortBy('created_at')->first();
        $lastVisit = $visitsToday->sortByDesc('created_at')->first();
        $totalValueToday = $ordersToday->sum('total_amount');

        $salespersonData = [
            'name' => $salesperson->name,
            'phone' => $salesperson->phone ?? 'N/A',
            'email' => $salesperson->email,
            'bit' => $salesperson->bit->name ?? 'Not Set',
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
    public function topSalespersons()
    {
        return view('admin.salespersons.top');
    }
}