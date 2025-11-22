<?php
namespace App\Services\interfaces;

interface InventoryViewInterface {
    public function getStockLevelByCountryOrWarehouse(array $Data);
    public function getGlobalLowStock();
}