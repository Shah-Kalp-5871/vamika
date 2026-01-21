<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('shop-owner.orders.index');
    }
    
    public function show($id)
    {
        return view('shop-owner.orders.show');
    }
    
    public function details($id)
    {
        return view('shop-owner.orders.details');
    }
    
    public function invoices()
    {
        return view('shop-owner.invoice.index');
    }
    
    public function invoice($id)
    {
        return view('shop-owner.invoice.show');
    }
}