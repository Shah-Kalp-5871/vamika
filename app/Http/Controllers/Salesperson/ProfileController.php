<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Bit;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->relationLoaded('bit')) {
            $user->load('bit');
        }

        $stats = [
            'total_orders' => Order::where('salesperson_id', $user->id)->count(),
            'total_sales' => Order::where('salesperson_id', $user->id)->sum('total_amount'),
        ];

        return view('salesperson.profile.index', compact('user', 'stats'));
    }

    public function selectBit()
    {
        $bits = Bit::where('status', 'active')->withCount('shops')->get();
        return view('salesperson.bits.select', compact('bits'));
    }

    public function updateBit(Request $request)
    {
        $request->validate([
            'bit_id' => 'required|exists:bits,id'
        ]);

        $user = Auth::user();
        $user->bit_id = $request->bit_id;
        $user->save();

        return redirect()->route('salesperson.visits.index')->with('success', 'Active area updated successfully.');
    }
}