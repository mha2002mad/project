<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\inventoryTransactions>
 */
class inventoryTransactionsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = \App\Models\inventoryTransactions::class;
    public function definition(): array
    {
        return [
            'product' => random_int(1, \App\Models\products::count()),
            'warehouse' => random_int(1, \App\Models\warehouses::count()),
            'supplier' => random_int(1, \App\Models\suppliers::count()),
            'quantity' => $this->faker->numberBetween(1, 100),
            'transaction_type' => $this->faker->randomElement(['in', 'out']),
            'transaction_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'created_by' => random_int(1, 5)
        ];
    }
}
