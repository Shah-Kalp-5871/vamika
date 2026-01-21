<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('shop-owner.checkout.index');
    }
}