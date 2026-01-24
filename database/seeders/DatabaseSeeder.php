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
        // -----------------------------------------------
        // 1. Create Admin, Salesperson, Shop Owner
        // -----------------------------------------------
        User::factory()->admin()->create([
            'name' => 'Admin User',
            'email' => 'admin@vamika.com',
            'password' => Hash::make('demo123'),
        ]);

        User::factory()->salesperson()->create([
            'name' => 'Sales Person',
            'email' => 'sales@vamika.com',
            'password' => Hash::make('demo123'),
        ]);

        User::factory()->shopOwner()->create([
            'name' => 'Shop Owner',
            'email' => 'shop@vamika.com',
            'password' => Hash::make('demo123'),
        ]);

        // // -----------------------------------------------
        // // 2. Create Areas
        // // -----------------------------------------------
        // $areas = Area::factory()->count(5)->create();

        // // -----------------------------------------------
        // // 3. Create additional Salespersons
        // // -----------------------------------------------
        // $salespersons = User::factory()->salesperson()->count(5)->create();

        // // -----------------------------------------------
        // // 4. Create Products with Images
        // // -----------------------------------------------
        // $products = Product::factory()->count(50)->create()->each(function ($product) {
        //     $images = ProductImage::factory()->count(3)->create(['product_id' => $product->id]);
        //     // Set first image as primary
        //     $primary = $images->first();
        //     if ($primary) {
        //         $primary->update(['is_primary' => true]);
        //     }
        // });

        // // -----------------------------------------------
        // // 5. Create Offers
        // // -----------------------------------------------
        // Offer::factory()->count(5)->create();

        // // -----------------------------------------------
        // // 6. Create Shops, Wallets, Visits, Orders
        // // -----------------------------------------------
        // foreach ($areas as $area) {
        //     // Shops in this area
        //     $shops = Shop::factory()->count(4)->create([
        //         'area_id' => $area->id,
        //     ]);

        //     foreach ($shops as $shop) {
        //         // Wallet for shop
        //         Wallet::factory()->create(['shop_id' => $shop->id]);

        //         // Visits by random salespersons
        //         Visit::factory()->count(3)->create([
        //             'shop_id' => $shop->id,
        //             'salesperson_id' => $salespersons->random()->id,
        //         ]);

        //         // Orders
        //         Order::factory()->count(2)->create([
        //             'shop_id' => $shop->id,
        //             'salesperson_id' => $salespersons->random()->id,
        //         ])->each(function ($order) use ($products) {
        //             // Order Items
        //             $orderItems = OrderItem::factory()->count(3)->create([
        //                 'order_id' => $order->id,
        //                 'product_id' => $products->random()->id,
        //             ]);

        //             // Update order total
        //             $order->update([
        //                 'total_amount' => $orderItems->sum('subtotal')
        //             ]);
        //         });
        //     }
        // }

        // -----------------------------------------------
        // 7. Output info in console
        // -----------------------------------------------
        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin: admin@vamika.com / demo123');
        $this->command->info('Salesperson: sales@vamika.com / demo123');
        $this->command->info('Shop Owner: shop@vamika.com / demo123');
    }
}
