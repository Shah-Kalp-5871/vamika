<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class CartController extends Controller
{
    public function index()
    {
        return view('shop-owner.cart.index');
    }
}