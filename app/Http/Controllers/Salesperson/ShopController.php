<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function index()
    {
        return view('salesperson.shops.index');
    }
    
    public function select()
    {
        return view('salesperson.shops.select');
    }
    
    public function show($id)
    {
        return view('salesperson.shops.details');
    }
}