<?php
namespace App\Http\Controllers\Salesperson;

use App\Http\Controllers\Controller;

class VisitController extends Controller
{
    public function index()
    {
        return view('salesperson.visits.index');
    }
}