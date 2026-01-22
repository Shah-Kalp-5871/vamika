<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::where('status', 'active')->get();
        return view('shop-owner.products.index', compact('products'));
    }
}