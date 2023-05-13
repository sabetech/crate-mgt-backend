<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmptiesLogProduct>
 */
class EmptiesLogProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->numberBetween(1,10),
            'empties_log_id' => $this->faker->numberBetween(1,10),
            'quantity' => $this->faker->numberBetween(6,40)
        ];
    }
}
