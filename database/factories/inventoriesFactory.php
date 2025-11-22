<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\inventories>
 */
class inventoriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product' => random_int(1, \App\Models\products::count()),
            'warehouse' => random_int(1, \App\Models\warehouses::count()),
            'quantity' => $this->faker->numberBetween(0, 1000),
            'minimium_quantity' => $this->faker->numberBetween(0, 20),
        ];
    }
}
