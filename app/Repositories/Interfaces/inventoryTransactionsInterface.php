<?php
namespace App\Repositories\Interfaces;

use App\Models\products;
use App\Models\suppliers;
use App\Models\warehouses;

interface inventoryTransactionsInterface
{
    public function getAllInventoryTransactions($chunk);
    public function getInventoryTransactionById($id);
    public function createInventoryTransaction(array $data);
    public function updateInventoryTransactionsProduct($id, $product);
    public function updateInventoryTransactionsSupplier($id, $supplier);
    public function updateInventoryTransactionsWarehouse($id, $warehouse);
    public function updateInventoryTransactionsTransactionType($id, string $transactionType);
    public function updateInventoryTransactionsQuantity($id, int $quantity);
    public function deleteInventoryTransaction($id);
}