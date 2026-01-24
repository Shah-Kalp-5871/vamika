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
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'status' => 'active',
            'credit_limit' => $this->faker->numberBetween(10000, 100000),
            'current_balance' => $this->faker->numberBetween(0, 5000),
        ];
    }
}
