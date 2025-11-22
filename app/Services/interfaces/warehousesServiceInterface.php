<?php
namespace App\Services\interfaces;

interface WarehousesServiceInterface
{
    public function getAllWarehouses($chunk);
    public function getWarehouseById($id);
    public function getWarehousesByCountry($country);
    public function createWarehouse(array $data);
    public function updateWarehouseAddress($id, string $address);
    public function getAllWarehouseInventoryLevels($chunk);
    public function deleteWarehouse($id);
    public function getStockLevelInventories($id);
}