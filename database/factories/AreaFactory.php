<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Area>
 */
class AreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->city(),
            'code' => strtoupper($this->faker->unique()->lexify('??###')),
            'pincodes' => [$this->faker->postcode(), $this->faker->postcode()],
            'status' => 'active',
        ];
    }
}
