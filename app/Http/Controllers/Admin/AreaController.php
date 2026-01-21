<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AreaController extends Controller
{
    public function index()
    {
        return view('admin.areas.index');
    }
    
    public function create()
    {
        return view('admin.areas.create');
    }
    
    public function edit($id)
    {
        return view('admin.areas.edit');
    }
    
    public function performance($id)
    {
        return view('admin.areas.performance');
    }
    
    public function assignForm()
    {
        return view('admin.areas.assign');
    }
    
    public function viewAssignments()
    {
        return view('admin.salespersons.view-assignments');
    }
}