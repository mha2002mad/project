<?php
namespace App\Repositories\Interfaces;

use App\Models\products;
use App\Models\warehouses;

interface InventoryInterface
{
    public function getAllInventories($chunk);
    public function getInventoryById($id);
    public function createInventory(array $data);
    public function reduceInventoryQuanitity(array $data);
    public function increaseInventoryQuantity(array $data);
    public function updateInventoryQuantity($id, $quntity);
    public function inventoryExistsByProductAndWarehouse($product, $warehouse);
    public function getInventoryByProductAndWarehouse($product, $warehouse);
    public function updateInventoryMinimiumQuantity($id, $quntity);
    public function getGlobalLowStock();
    public function updateInventoryProduct($id, $product);
    public function updateInventoryWarehouse($id, $warehouse);
    public function deleteInventory($id);
}