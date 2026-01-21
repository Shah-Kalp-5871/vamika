<?php
namespace App\Http\Controllers\ShopOwner;

use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function index()
    {
        return view('shop-owner.profile.index');
    }
    
    public function edit()
    {
        return view('shop-owner.profile.edit');
    }
    
    public function referral()
    {
        return view('shop-owner.referral.index');
    }
}