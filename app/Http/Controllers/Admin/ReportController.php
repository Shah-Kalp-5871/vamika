<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }
    
    public function visitReports()
    {
        return view('admin.reports.visit');
    }
    
    public function shopAnalysis()
    {
        return view('admin.shops.analysis');
    }
    
    public function topShops()
    {
        return view('admin.shops.top');
    }
}