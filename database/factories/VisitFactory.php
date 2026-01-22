<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visit>
 */
class VisitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'salesperson_id' => \App\Models\User::factory()->salesperson(),
            'shop_id' => \App\Models\Shop::factory(),
            'visit_date' => fake()->dateTimeThisMonth(),
            'notes' => fake()->sentence(),
            'location_lat' => fake()->latitude(),
            'location_lng' => fake()->longitude(),
        ];
    }
}
