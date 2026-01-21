<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function index()
    {
        return view('admin.products.index');
    }
    
    public function create()
    {
        return view('admin.products.create');
    }
    
    public function edit($id)
    {
        return view('admin.products.edit');
    }
    
    public function stock()
    {
        return view('admin.products.stock');
    }
    
    public function top()
    {
        return view('admin.products.top');
    }
}