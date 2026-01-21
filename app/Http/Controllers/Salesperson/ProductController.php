<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('salesperson.products.index');
    }
}