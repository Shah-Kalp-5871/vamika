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
            'visit_date' => $this->faker->dateTimeThisMonth(),
            'notes' => $this->faker->sentence(),
            'location_lat' => $this->faker->latitude(),
            'location_lng' => $this->faker->longitude(),
        ];
    }
}
