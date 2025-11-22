<?php

use App\Http\api\inventory;
use App\Http\api\InventoryMovements;
use App\Http\Api\users;
use App\Http\api\warehouses;
use Illuminate\Support\Facades\Route;

Route::post('/register', [users::class, 'registerUser']);

Route::middleware('auth:api')->group(function() {
    Route::post('/countries', [\App\Http\api\countries::class, 'createCountry']);
    Route::get('/countries', [\App\Http\api\countries::class, 'getCountries']);
    
    Route::get('/warehouses', [warehouses::class, 'getWarehouses']);
    Route::post('/warehouses', [warehouses::class, 'createWarehouse']);

    Route::get('/products', [\App\Http\api\products::class, 'getProducts']);
    Route::post('/products', [\App\Http\api\products::class, 'createProduct']);

    Route::get('/suppliers', [\App\Http\api\suppliers::class, 'getSuppliers']);
    Route::post('/suppliers', [\App\Http\API\suppliers::class, 'createSupplier']);

    Route::post('inventory/newtransaction', [InventoryMovements::class, 'newTransaction']);
    Route::post('inventory-transfer', [InventoryMovements::class, 'transferInventory']);

    Route::get('inventory/globalview', [inventory::class, 'globalView']);
    Route::get('reports/lowstock', [inventory::class, 'lowstock']);
});
