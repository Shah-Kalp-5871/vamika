<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->relationLoaded('area')) {
            $user->load('area');
        }

        $stats = [
            'total_orders' => Order::where('salesperson_id', $user->id)->count(),
            'total_sales' => Order::where('salesperson_id', $user->id)->sum('total_amount'),
        ];

        return view('salesperson.profile.index', compact('user', 'stats'));
    }
}