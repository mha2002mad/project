<?php

namespace Database\Factories;

use App\Models\countries;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\warehouses>
 */
class warehousesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location' => $this->faker->address(),
            'name' => $this->faker->unique()->company(),
            'country' => random_int(1, countries::count()),
        ];
    }
}
