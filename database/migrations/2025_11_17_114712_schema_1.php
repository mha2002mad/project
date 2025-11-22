<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id('country_id');
            $table->string('name', 50)->unique();
            $table->string('code', 3)->unique();
        });

        Schema::create('warehouses', function (Blueprint $table) {
            $table->id('warehouse_id');
            $table->string('location', 150);
            $table->string('name', 100)->unique();
            $table->unsignedBigInteger('country');
            $table->foreign('country')->references('country_id')->on('countries')->cascadeOnDelete();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name', 150);
            $table->string('sku', 50)->unique();
            $table->text('description')->nullable();
            $table->string('status', 10)->default('active');
            $table->float('price');
        });

        Schema::create('inventories', function (Blueprint $table) {
            $table->id('inventory_id');
            $table->unsignedBigInteger('product');
            $table->unsignedBigInteger('warehouse');

            $table->foreign('product')->references('product_id')->on('products')->cascadeOnDelete();
            $table->foreign('warehouse')->references('warehouse_id')->on('warehouses')->cascadeOnDelete();

            $table->integer('quantity');
            $table->integer('minimium_quantity');
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id('supplier_id');
            $table->string('name', 150);
            $table->string('contact_info', 50);
            $table->string('address', 200);
        });

        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('inventory_transaction_id'); // fixed typo
            $table->unsignedBigInteger('product');
            $table->unsignedBigInteger('warehouse');
            $table->unsignedBigInteger('supplier')->nullable();

            $table->foreign('product')->references('product_id')->on('products')->cascadeOnDelete();
            $table->foreign('warehouse')->references('warehouse_id')->on('warehouses')->cascadeOnDelete();
            $table->foreign('supplier')->references('supplier_id')->on('suppliers')->cascadeOnDelete();

            $table->integer('quantity');
            $table->string('transaction_type', 10);
            $table->timestamp('transaction_date')->useCurrent();
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventories');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('warehouses');
        Schema::dropIfExists('products');
        Schema::dropIfExists('countries');
    }
};
