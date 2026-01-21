<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopOwnerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'shop_owner') {
            return redirect('/login')->with('error', 'Unauthorized access.');
        }

        return $next($request);
    }
}