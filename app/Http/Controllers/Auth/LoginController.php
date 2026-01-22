<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Add this import

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Basic validation
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // DEBUG: Log what we're trying
        Log::info('Login attempt', [
            'email' => $credentials['email'],
            'ip' => $request->ip()
        ]);

        // Check user exists
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            Log::warning('User not found: ' . $credentials['email']);
            return back()->withErrors([
                'email' => 'User not found.',
            ])->onlyInput('email');
        }

        Log::info('User found', [
            'email' => $user->email,
            'role' => $user->role,
            'id' => $user->id
        ]);

        // Attempt to authenticate
        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            Log::info('Login successful', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'role' => Auth::user()->role
            ]);

            // Redirect based on user role
            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard');
                case 'salesperson':
                    return redirect()->route('salesperson.dashboard');
                case 'shop-owner':
                    return redirect()->route('shop-owner.dashboard');
                default:
                    return redirect('/');
            }
        }

        Log::warning('Password mismatch for: ' . $credentials['email']);

        // If authentication fails
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}