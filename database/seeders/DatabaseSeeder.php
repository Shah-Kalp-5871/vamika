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

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Admin
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@vamika.com',
            'password' => Hash::make('demo123'),
        ]);

        // Demo Salesperson
        User::factory()->salesperson()->create([
            'name' => 'Sales Person',
            'email' => 'sales@vamika.com',
            'password' => Hash::make('demo123'),
        ]);

        // Demo Shop Owner
        User::factory()->shopOwner()->create([
            'name' => 'Shop Owner',
            'email' => 'shop@vamika.com',
            'password' => Hash::make('demo123'),
        ]);

        // 2. Create Areas
        $areas = Area::factory()->count(5)->create();

        // 3. Create Salespersons
        $salespersons = User::factory()->salesperson()->count(5)->create();

        // 4. Create Products with Images
        $products = Product::factory()->count(50)->create()->each(function ($product) {
            ProductImage::factory()->count(3)->create(['product_id' => $product->id]);
            // Set first one as primary
            $product->images()->first()->update(['is_primary' => true]);
        });

        // 5. Create Offers
        Offer::factory()->count(5)->create();

        // 6. Create Shops, Wallets, and Orders
        foreach ($areas as $area) {
            // Create Shops for this area
            $shops = Shop::factory()->count(4)->create([
                'area_id' => $area->id,
            ]);

            foreach ($shops as $shop) {
                // Create Wallet for shop
                Wallet::factory()->create(['shop_id' => $shop->id]);

                // Assign random visits by salespersons
                Visit::factory()->count(3)->create([
                    'shop_id' => $shop->id,
                    'salesperson_id' => $salespersons->random()->id,
                ]);

                // Create Orders
                Order::factory()->count(2)->create([
                    'shop_id' => $shop->id,
                    'salesperson_id' => $salespersons->random()->id,
                ])->each(function ($order) use ($products) {
                    // Create Order Items
                    $orderItems = OrderItem::factory()->count(3)->create([
                        'order_id' => $order->id,
                        'product_id' => $products->random()->id,
                    ]);
                    
                    // Update Order Total
                    $order->update(['total_amount' => $orderItems->sum('subtotal')]);
                });
            }
        }

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@vamika.com / demo123');
        $this->command->info('Salesperson: sales@vamika.com / demo123');
        $this->command->info('Shop Owner: shop@vamika.com / demo123');
    }
}