<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class InitialUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'admin',
            'phone' => '1234567890',
            'status' => 'active',
        ]);

        // 2. Create Salesperson
        User::create([
            'name' => 'Sales Person',
            'email' => 'sales@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'salesperson',
            'phone' => '9876543210',
            'status' => 'active',
        ]);

        // 3. Create Shop Owner
        User::create([
            'name' => 'Shop Owner',
            'email' => 'shop@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'shop-owner',
            'phone' => '1122334455',
            'status' => 'active',
        ]);
    }
}
