<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }
}