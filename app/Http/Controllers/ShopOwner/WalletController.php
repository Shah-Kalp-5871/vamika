<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class WalletController extends Controller
{
    public function index()
    {
        return view('shop-owner.wallet.index');
    }
}