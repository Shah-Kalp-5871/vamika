<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function create()
    {
        return view('salesperson.orders.create');
    }
    
    public function review($id)
    {
        return view('salesperson.orders.review');
    }
    
    public function invoice($id)
    {
        return view('salesperson.orders.invoice');
    }
}