<?php
namespace App\Repositories\Interfaces;
interface warehousesInterface
{
    public function getAllWarehouses($chunk);
    public function getAllWarehouseInventoryLevels($chunk);
    public function getWarehouseById($id);
    public function getWarehousesByCountry($country);
    public function createWarehouse(array $data);
    public function updateWarehouseAddress($id, string $address);
    public function deleteWarehouse($id);
    public function getStockLevelInventories($id);
}