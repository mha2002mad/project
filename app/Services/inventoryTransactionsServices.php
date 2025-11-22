<?php
namespace App\Services;

use App\Repositories\inventoryTransactionsRepository;
use App\Services\interfaces\inventoryTransactionsServiceInterface;

class InventoryTransactionsServices implements inventoryTransactionsServiceInterface{
    protected inventoryTransactionsRepository $inventoryTransactionsRepository;

    public function __construct(inventoryTransactionsRepository $inventoryTransactionModel){
        $this->inventoryTransactionsRepository = $inventoryTransactionModel;
    }

    public function getAllInventoryTransactions($chunk){
        return $this->inventoryTransactionsRepository->getAllInventoryTransactions($chunk);
    }

    public function getInventoryTransactionById($id){
        return $this->inventoryTransactionsRepository->getInventoryTransactionById($id);
    }

    public function createInventoryTransaction(array $data){
        return $this->inventoryTransactionsRepository->createInventoryTransaction($data);
    }

    public function updateInventoryTransactionsProduct($id, $product){
        return $this->inventoryTransactionsRepository->updateInventoryTransactionsProduct($id, $product);
    }

    public function updateInventoryTransactionsSupplier($id, $supplier){
        return $this->inventoryTransactionsRepository->updateInventoryTransactionsSupplier($id, $supplier);
    }

    public function updateInventoryTransactionsWarehouse($id, $warehouse){
        return $this->inventoryTransactionsRepository->updateInventoryTransactionsWarehouse($id, $warehouse);
    }

    public function updateInventoryTransactionsQuantity($id, int $quantity){
        return $this->inventoryTransactionsRepository->updateInventoryTransactionsQuantity($id, $quantity);
    }

    public function updateInventoryTransactionsType($id, string $transactionType){
        return $this->inventoryTransactionsRepository->updateInventoryTransactionsTransactionType($id, $transactionType);
    }

    public function deleteInventoryTransaction($id){
        return $this->inventoryTransactionsRepository->deleteInventoryTransaction($id);
    }
}