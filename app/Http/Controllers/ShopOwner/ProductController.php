<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('shop-owner.products.index');
    }
}