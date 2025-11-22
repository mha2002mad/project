<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\countries>
 */
class countriesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\countries::class;
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->country(),
            'code' => strtoupper($this->faker->unique()->lexify('??')),
        ];
    }
}
