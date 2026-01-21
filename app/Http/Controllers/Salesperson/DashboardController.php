<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('salesperson.dashboard');
    }
    
    public function sales()
    {
        return view('salesperson.sales.index');
    }
}