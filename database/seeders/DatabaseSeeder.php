<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Bit;
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
        // 0. Base Settings
        $this->call(SettingSeeder::class);
        // $this->call(AhmedabadDataSeeder::class);

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

        // 2. Create Bits
        $bit1 = Bit::create([
            'name' => 'Mumbai South',
            'code' => 'MUM-S',
            'pincodes' => ['400001', '400002'],
            'status' => 'active',
        ]);

        $bit2 = Bit::create([
            'name' => 'Delhi Central',
            'code' => 'DEL-C',
            'pincodes' => ['110001', '110002'],
            'status' => 'active',
        ]);

        // 3. Create Products
        $products = [
            ['name' => 'Durby Special', 'sku' => 'DBS-001', 'category' => 'durby', 'price' => 1200, 'unit' => '1kg', 'mrp' => 1500, 'stock' => 50],
            ['name' => 'Forolly Premium', 'sku' => 'FRP-002', 'category' => 'forolly', 'price' => 850, 'unit' => '500g', 'mrp' => 1000, 'stock' => 30],
            ['name' => 'Million Gold', 'sku' => 'MLG-003', 'category' => 'million', 'price' => 2100, 'unit' => '2kg', 'mrp' => 2500, 'stock' => 20],
            ['name' => 'Michi\'s Choice', 'sku' => 'MIC-004', 'category' => 'michi-s', 'price' => 450, 'unit' => '1pc', 'mrp' => 500, 'stock' => 100],
            ['name' => 'Oshon Classic', 'sku' => 'OSC-005', 'category' => 'oshon', 'price' => 125, 'unit' => '100g', 'mrp' => 150, 'stock' => 200],
            ['name' => 'Crazzy\'s Delight', 'sku' => 'CZD-006', 'category' => 'crazzy-s', 'price' => 95, 'unit' => '150g', 'mrp' => 110, 'stock' => 150],
            ['name' => 'Ankit Regular', 'sku' => 'ANR-007', 'category' => 'ankit', 'price' => 650, 'unit' => '500g', 'mrp' => 750, 'stock' => 45],
            ['name' => 'Mayora Treat', 'sku' => 'MYT-008', 'category' => 'mayora', 'price' => 280, 'unit' => '250g', 'mrp' => 320, 'stock' => 75],
            ['name' => 'Confito Mix', 'sku' => 'CFM-009', 'category' => 'confito', 'price' => 550, 'unit' => '400g', 'mrp' => 600, 'stock' => 60],
            ['name' => 'Bakemate Fresh', 'sku' => 'BKF-010', 'category' => 'bakemate', 'price' => 180, 'unit' => '500g', 'mrp' => 200, 'stock' => 80],
        ];

        foreach ($products as $pData) {
            $product = Product::create([
                'name' => $pData['name'],
                'sku' => $pData['sku'],
                'category' => $pData['category'],
                'price' => $pData['price'],
                'unit' => $pData['unit'],
                'mrp' => $pData['mrp'],
                'stock' => $pData['stock'],
                'status' => 'active',
                'description' => 'Quality product from ' . ucfirst($pData['category']),
            ]);
        }

        // 3.1 Product Images
        // 3.1 Product Images
        foreach (Product::all() as $index => $product) {
             ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://placehold.co/600x400?text=' . urlencode($product->name),
                'is_primary' => true,
                'sort_order' => 1,
            ]);
        }


        // 4. Create Shops
        $shop1 = Shop::create([
            'user_id' => $shopOwner1->id,
            'bit_id' => $bit1->id,
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

        // 7. Info messages
        $this->command->info('Database seeded successfully with static data!');
        $this->command->info('Admin: admin@vamika.com / demo123');
        $this->command->info('Salesperson: sales@vamika.com / demo123');
        $this->command->info('Shop Owner: shop@vamika.com / demo123');
    }
}
