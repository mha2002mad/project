<?php

namespace Database\Seeders;

use App\Models\countries;
use App\Models\inventories;
use App\Models\inventory_transactions;
use App\Models\inventoryTransactions;
use App\Models\products;
use App\Models\suppliers;
use App\Models\User;
use App\Models\warehouses;
use Database\Factories\inventoryTransactionsFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        countries::factory(10)->create();
        warehouses::factory(100)->create();
        products::factory(300)->create();
        suppliers::factory(100)->create();
        inventories::factory(500)->create();
        User::factory(5)->create();
        inventoryTransactions::factory(1000)->create();
    }
}
