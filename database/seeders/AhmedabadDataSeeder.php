<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Bit;
use App\Models\Shop;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Wallet;
use Carbon\Carbon;

class AhmedabadDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create 5 Bits for Ahmedabad Areas
        $areas = [
            ['name' => 'Navrangpura', 'code' => 'AMD-NAV', 'pincodes' => ['380009']],
            ['name' => 'Satellite', 'code' => 'AMD-SAT', 'pincodes' => ['380015']],
            ['name' => 'Vastrapur', 'code' => 'AMD-VAS', 'pincodes' => ['380054']],
            ['name' => 'Maninagar', 'code' => 'AMD-MAN', 'pincodes' => ['380008']],
            ['name' => 'Chandkheda', 'code' => 'AMD-CHA', 'pincodes' => ['382424']],
        ];

        $bits = [];
        foreach ($areas as $area) {
            $bit = Bit::updateOrCreate(
                ['code' => $area['code']],
                [
                    'name' => $area['name'],
                    'pincodes' => $area['pincodes'],
                    'status' => 'active',
                ]
            );
            $bits[] = $bit;
        }

        // 2. Create 10 Salespeople
        $salespersonNames = [
            'Rajesh Patel', 'Sandeep Shah', 'Amit Mehta', 'Jignesh Vora', 'Hiren Thakkar',
            'Pankaj Joshi', 'Suresh Gajjar', 'Manish Dave', 'Bhavin Rana', 'Viral Panchal'
        ];

        $salespersons = [];
        foreach ($salespersonNames as $index => $name) {
            $email = strtolower(explode(' ', $name)[0]) . ($index + 1) . '@vamika.com';
            $salesperson = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => Hash::make('demo123'),
                    'role' => 'salesperson',
                    'phone' => '9898' . str_pad($index, 6, '0', STR_PAD_LEFT),
                    'status' => 'active',
                    'employee_id' => 'EMP' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                    'bit_id' => $bits[$index % 5]->id,
                    'work_start_time' => '09:00',
                    'work_end_time' => '18:00',
                ]
            );
            $salespersons[] = $salesperson;
        }

        // 3. Create 10 Products (FMCG/Local Gujarati focus)
        $productData = [
            ['name' => 'Amul Gold Milk 500ml', 'category' => 'dairy', 'brand' => 'amul', 'price' => 32, 'mrp' => 32],
            ['name' => 'Ashirwad Atta 5kg', 'category' => 'staples', 'brand' => 'ashirwad', 'price' => 245, 'mrp' => 260],
            ['name' => 'Tata Salt 1kg', 'category' => 'staples', 'brand' => 'tata', 'price' => 25, 'mrp' => 28],
            ['name' => 'Parle-G Biscuit 250g', 'category' => 'snacks', 'brand' => 'parle', 'price' => 25, 'mrp' => 30],
            ['name' => 'Balaji Masala Masti 100g', 'category' => 'snacks', 'brand' => 'others', 'price' => 20, 'mrp' => 20],
            ['name' => 'Wagh Bakri Tea 500g', 'category' => 'beverages', 'brand' => 'others', 'price' => 280, 'mrp' => 300],
            ['name' => 'Fortune Soyabean Oil 1L', 'category' => 'staples', 'brand' => 'fortune', 'price' => 145, 'mrp' => 160],
            ['name' => 'Surf Excel Quick Wash 1kg', 'category' => 'home-care', 'brand' => 'surf-excel', 'price' => 190, 'mrp' => 210],
            ['name' => 'Colgate MaxFresh 150g', 'category' => 'personal-care', 'brand' => 'colgate', 'price' => 95, 'mrp' => 110],
            ['name' => 'Amul Butter 100g', 'category' => 'dairy', 'brand' => 'amul', 'price' => 56, 'mrp' => 58],
        ];

        foreach ($productData as $index => $item) {
            $sku = 'PROD' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $product = Product::updateOrCreate(
                ['sku' => $sku],
                [
                    'name' => $item['name'],
                    'description' => 'Quality ' . $item['name'] . ' for your daily needs.',
                    'price' => $item['price'],
                    'mrp' => $item['mrp'],
                    'category' => $item['category'],
                    'brand' => $item['brand'],
                    'status' => 'active',
                    'unit' => 'piece',
                ]
            );

            ProductImage::updateOrCreate(
                ['product_id' => $product->id, 'is_primary' => true],
                [
                    'image_path' => 'https://placehold.co/600x400?text=' . urlencode($item['name']),
                    'sort_order' => 1,
                ]
            );
        }

        // 4. Create 5 Shops per Bit (25 shops total)
        $shopNames = [
            'Jay Ambe Provision Store', 'Shreeji General Store', 'Krishna Bakery', 'Nilkanth Dairy', 'Riddhi Siddhi Mart',
            'Sagar Cold Drinks', 'Maruti Kirana', 'Ganesh Sweets', 'Ambica Medical', 'Patel Super Market',
            'Khodiyar Pan Parlour', 'Umiya Provision', 'Pooja Traders', 'Vishwa Mart', 'Shakti Sales',
            'Gayatri Kirana', 'Chamunda Daily Needs', 'Haridham Bakery', 'Surshaan Dairy', 'Om Sai Ram Store',
            'Manek Chowk Special', 'Karnavati Dairy', 'Ashapura Mart', 'Bapa Sitaram Store', 'Gujarat Provision'
        ];

        foreach ($bits as $bitIndex => $bit) {
            for ($i = 0; $i < 5; $i++) {
                $shopIndex = ($bitIndex * 5) + $i;
                $ownerName = $salespersonNames[$shopIndex % 10] . ' Owner'; // Just reuse names for simplicity
                $email = 'shop' . ($shopIndex + 1) . '@vamika.com';
                
                $owner = User::create([
                    'name' => $shopNames[$shopIndex],
                    'email' => $email,
                    'password' => Hash::make('demo123'),
                    'role' => 'shop-owner',
                    'phone' => '9723' . str_pad($shopIndex, 6, '0', STR_PAD_LEFT),
                    'status' => 'active',
                ]);

                $shop = Shop::create([
                    'user_id' => $owner->id,
                    'bit_id' => $bit->id,
                    'salesperson_id' => $salespersons[$shopIndex % 10]->id, // Distribute among salespeople
                    'name' => $shopNames[$shopIndex],
                    'address' => 'Plot no ' . ($i + 10) . ', Near ' . $bit->name . ' Cross Roads, Ahmedabad',
                    'phone' => '079-' . (2500000 + $shopIndex),
                    'status' => 'active',
                    'credit_limit' => 20000.00,
                    'current_balance' => 0.00,
                ]);

                Wallet::create([
                    'shop_id' => $shop->id,
                    'balance' => 0.00,
                ]);
            }
        }
    }
}
