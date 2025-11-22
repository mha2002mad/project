<?php
namespace App\Services\interfaces;

interface inventoryTransactionsServiceInterface {
    public function getAllInventoryTransactions($chunk);
    public function getInventoryTransactionById($id);
    public function createInventoryTransaction(array $data);
    public function updateInventoryTransactionsProduct($id, $product);
    public function updateInventoryTransactionsSupplier($id, $supplier);
    public function updateInventoryTransactionsWarehouse($id, $warehouse);
    public function updateInventoryTransactionsQuantity($id, int $quantity);
    public function updateInventoryTransactionsType($id, string $transactionType);
    public function deleteInventoryTransaction($id);
}