<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Shop;
use App\Models\Bit;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $bits = Bit::where('status', 'active')->orderBy('name')->get();
        return view('auth.register', compact('bits'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:15|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'shop_name' => 'required|string|max:255',
            'bit_id' => 'required|exists:bits,id',
            'shop_address' => 'required|string',
            'terms' => 'required',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                // 1. Create User
                $user = User::create([
                    'name' => $request->first_name . ' ' . $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'password' => Hash::make($request->password),
                    'role' => 'shop-owner',
                    'status' => 'active', 
                ]);

                // 2. Find a Salesperson for this Bit
                $salesperson = User::where('role', 'salesperson')
                    ->where('bit_id', $request->bit_id)
                    ->where('status', 'active')
                    ->first();

                // Fallback: If no salesperson explicitly assigned to this bit, get any active salesperson
                if (!$salesperson) {
                    $salesperson = User::where('role', 'salesperson')
                        ->where('status', 'active')
                        ->first();
                }

                // 3. Create Shop
                $shop = Shop::create([
                    'user_id' => $user->id,
                    'bit_id' => $request->bit_id,
                    'salesperson_id' => $salesperson ? $salesperson->id : null,
                    'name' => $request->shop_name,
                    'address' => $request->shop_address,
                    'phone' => $request->phone,
                    'status' => 'active',
                    'credit_limit' => 0.00,
                    'current_balance' => 0.00,
                ]);

                // 4. Create Wallet with Signup Bonus
                $wallet = Wallet::create([
                    'shop_id' => $shop->id,
                    'balance' => 500.00,
                ]);

                // 5. Create Transaction Record for Bonus
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'type' => 'credit',
                    'amount' => 500.00,
                    'description' => 'Signup Bonus',
                ]);

                Auth::login($user);

                return redirect()->route('shop-owner.dashboard')
                    ->with('success', 'Welcome to Vamika Enterprise! Your account has been created and ₹500 bonus credited to your wallet.');
            });
        } catch (\Exception $e) {
            Log::error('Registration Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }
}