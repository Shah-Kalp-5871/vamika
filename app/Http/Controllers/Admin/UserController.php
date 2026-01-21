<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        return view('admin.users.index');
    }
    
    public function create()
    {
        return view('admin.users.create');
    }
    
    public function edit($id)
    {
        return view('admin.users.edit');
    }
    
    public function salespersons()
    {
        return view('admin.salespersons.index');
    }
    
    public function salespersonDetails($id)
    {
        return view('admin.salespersons.details');
    }
    
    public function assignSalespersonForm()
    {
        return view('admin.salespersons.assign');
    }
    
    public function topSalespersons()
    {
        return view('admin.salespersons.top');
    }
}