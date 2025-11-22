<?php

namespace App\Repositories\Interfaces;

interface InventoryViewRepositoryInterface {
    public function getGlobalLowStock();
    public function getGlobalStockPerProduct($id);
    public function getGlobalStockPerProductFilterByCountryAndWarehouse($product_id, $country_id, $warehouse_id);
    public function getGlobalStockPerProductFilterByCountry($product_id, $country_id);
    public function getGlobalStockPerProductFilterByWarehouse($product_id, $warehouse_id);
}