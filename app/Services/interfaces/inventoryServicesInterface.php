<?php
namespace App\Services\interfaces;

interface inventoryServicesInterface
{
    public function getAllInventories($chunk);
    public function getInventoryById($id);
    public function createInventory(array $data);
    public function updateInventoryQuantity(array $data);
    public function updateInventoryMinimiumQuantity(array $data);
    public function reduceInventoryQuanitity(array $data);
    public function increaseInventoryQuantity(array $data);
    public function inventoryExistsByProductAndWarehouse(string $product, string $warehouse);
    public function getInventoryByProductAndWarehouse(string $product, string $warehouse);
    public function updateInventoryProduct(array $data);
    public function getGlobalLowStock();
    public function updateInventoryWarehouse(array $data);
    public function deleteInventory($id);
}