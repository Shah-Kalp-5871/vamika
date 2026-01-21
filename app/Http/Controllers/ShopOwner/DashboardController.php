<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('shop-owner.dashboard');
    }
}