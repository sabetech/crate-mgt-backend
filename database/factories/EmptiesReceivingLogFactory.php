<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EmptiesReceivingLog>
 */
class EmptiesReceivingLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => $this->faker->date(),
            'quantity_received' => $this->faker->numberBetween(1, 100),
            'vehicle_number' => $this->faker->word(),
            'purchase_order_number' => $this->faker->word(),
            'received_by' => $this->faker->word(),
            'delivered_by' => $this->faker->word(),
            'image_reference' => $this->faker->word(),
        ];
    }
}
