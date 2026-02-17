<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['images' => function($q) {
            $q->where('is_primary', true);
        }])->where('status', 'active')
           ->where('stock', '>', 0)
           ->orderBy('name')->get();

        return view('salesperson.products.index', compact('products'));
    }
}