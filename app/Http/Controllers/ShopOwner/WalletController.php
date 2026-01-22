<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function index()
    {
        $shop = Auth::user()->shop;
        $wallet = $shop ? $shop->wallet : null;
        $transactions = $wallet ? $wallet->transactions()->latest()->get() : collect();
        
        return view('shop-owner.wallet.index', compact('shop', 'wallet', 'transactions'));
    }
}