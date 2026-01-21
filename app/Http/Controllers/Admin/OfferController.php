<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class OfferController extends Controller
{
    public function index()
    {
        return view('admin.offers.index');
    }
    
    public function create()
    {
        return view('admin.offers.create');
    }
    
    public function show($id)
    {
        return view('admin.offers.show');
    }
}