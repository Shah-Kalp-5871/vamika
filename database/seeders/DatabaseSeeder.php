<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Area;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Offer;
use App\Models\Visit;
use App\Models\Wallet;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'admin',
            'phone' => '1234567890',
            'status' => 'active',
        ]);

        $salesperson1 = User::create([
            'name' => 'Sales Person',
            'email' => 'sales@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'salesperson',
            'phone' => '9876543210',
            'status' => 'active',
        ]);

        $shopOwner1 = User::create([
            'name' => 'Shop Owner',
            'email' => 'shop@vamika.com',
            'password' => Hash::make('demo123'),
            'role' => 'shop-owner',
            'phone' => '1122334455',
            'status' => 'active',
        ]);

        // 2. Create Areas
        $area1 = Area::create([
            'name' => 'Mumbai South',
            'code' => 'MUM-S',
            'pincodes' => ['400001', '400002'],
            'status' => 'active',
        ]);

        $area2 = Area::create([
            'name' => 'Delhi Central',
            'code' => 'DEL-C',
            'pincodes' => ['110001', '110002'],
            'status' => 'active',
        ]);

        // 3. Create Products
        $product1 = Product::create([
            'name' => 'Paracetamol 500mg',
            'sku' => 'MED001',
            'description' => 'Effective pain reliever and fever reducer.',
            'price' => 20.00,
            'stock_quantity' => 100,
            'category' => 'Medicine',
            'status' => 'active',
        ]);

        $product2 = Product::create([
            'name' => 'Vitamin C Supplements',
            'sku' => 'SUP001',
            'description' => 'Immunity booster supplements.',
            'price' => 150.00,
            'stock_quantity' => 50,
            'category' => 'Supplements',
            'status' => 'active',
        ]);

        // 3.1 Product Images
        ProductImage::create([
            'product_id' => $product1->id,
            'image_path' => 'https://placehold.co/600x400?text=Paracetamol',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

         ProductImage::create([
            'product_id' => $product2->id,
            'image_path' => 'https://placehold.co/600x400?text=Vitamin+C',
            'is_primary' => true,
            'sort_order' => 1,
        ]);


        // 4. Create Shops
        $shop1 = Shop::create([
            'user_id' => $shopOwner1->id,
            'area_id' => $area1->id,
            'name' => 'Healthy Life Pharmacy',
            'address' => '123 Marine Drive, Mumbai',
            'phone' => '022-12345678',
            'status' => 'active',
            'credit_limit' => 50000.00,
            'current_balance' => 0.00,
        ]);

        // 5. Create Wallet for Shop
        Wallet::create([
            'shop_id' => $shop1->id,
            'balance' => 0.00,
        ]);

        // 6. Create Offer
        Offer::create([
            'title' => 'Monsoon Sale',
            'description' => 'Flat 10% off on all medicines.',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(30),
            'status' => 'active',
        ]);

        $this->command->info('Database seeded successfully with static data!');
        $this->command->info('Admin: admin@vamika.com / demo123');
        $this->command->info('Salesperson: sales@vamika.com / demo123');
        $this->command->info('Shop Owner: shop@vamika.com / demo123');
    }
}
