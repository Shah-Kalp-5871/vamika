<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $shop = $user->shop;
        return view('shop-owner.profile.index', compact('user', 'shop'));
    }
    
    public function edit()
    {
        $user = Auth::user();
        $shop = $user->shop;
        return view('shop-owner.profile.edit', compact('user', 'shop'));
    }
    
    public function referral()
    {
        $user = Auth::user();
        return view('shop-owner.referral.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $shop = $user->shop;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'shop_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($shop) {
            $shop->update([
                'name' => $request->shop_name,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);
        }

        return redirect()->route('shop-owner.profile.index')->with('success', 'Profile updated successfully');
    }
}