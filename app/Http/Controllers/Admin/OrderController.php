<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
    }
    
    public function show($id)
    {
        return view('admin.orders.show');
    }
    
    public function details($id)
    {
        return view('admin.orders.details');
    }
    
    public function consolidation()
    {
        return view('admin.orders.consolidation');
    }
    
    public function updateStatusForm($id)
    {
        return view('admin.orders.update-status');
    }
}