<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('salesperson.profile.index');
    }
}