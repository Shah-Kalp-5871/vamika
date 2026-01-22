<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->shopOwner(),
            'area_id' => \App\Models\Area::factory(),
            'name' => fake()->company(),
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'status' => 'active',
            'credit_limit' => fake()->numberBetween(10000, 100000),
            'current_balance' => fake()->numberBetween(0, 5000),
        ];
    }
}
