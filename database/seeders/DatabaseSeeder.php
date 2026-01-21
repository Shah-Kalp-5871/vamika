<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Remove or comment out the factory call
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        
        // Clear any existing users first
        User::truncate();
        
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'admin',
        ]);
        
        // Create Salesperson User
        User::create([
            'name' => 'Sales Person',
            'email' => 'sales@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'salesperson',
        ]);
        
        // Create Shop Owner User
        User::create([
            'name' => 'Shop Owner',
            'email' => 'shop@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'shop_owner',
        ]);
        
        $this->command->info('Demo users created successfully!');
        $this->command->info('Admin: admin@vamika.com / demo123');
        $this->command->info('Salesperson: sales@vamika.com / demo123');
        $this->command->info('Shop Owner: shop@vamika.com / demo123');
    }
}