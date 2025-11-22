<?php
namespace App\Services;

use App\Services\interfaces\inventoryMovementsServiceInterface;
use App\Services\inventoryServices;
use App\Services\InventoryTransactionsServices;
use App\Services\ProductsService;
use App\Services\WarehousesService;

class inventoryMovementService implements inventoryMovementsServiceInterface{
    protected InventoryServices $inventoryServices;
    protected InventoryTransactionsServices $inventoryTransactionsServices;
    protected ProductsService $productsService;
    protected WarehousesService $warehousesService;

    public function __construct(WarehousesService $warehousesService, ProductsService $productsService, inventoryServices $inventoryServices, InventoryTransactionsServices $inventoryTransactionsServices){
        $this->inventoryServices = $inventoryServices;
        $this->inventoryTransactionsServices = $inventoryTransactionsServices;
        $this->productsService = $productsService;
        $this->warehousesService = $warehousesService;
    }

    public function newTransaction(array $data){
        if ($data['transaction_type'] == 'in') {
            $this->inventoryServices->increaseInventoryQuantity($data);
        } else if ($data['transaction_type'] == 'out') {
            $this->inventoryServices->reduceInventoryQuanitity($data);
        } else {
            throw new \Exception("Invalid transaction type");
        }

        return $this->inventoryTransactionsServices->createInventoryTransaction($data);
    }

    public function transferInventory(array $data){
        $this->warehousesService->getWarehouseById($data['from_warehouse']);
        $this->warehousesService->getWarehouseById($data['to_warehouse']);
        $this->productsService->getProductById($data['product']);

        $this->inventoryServices->reduceInventoryQuanitity([
            'product' => $data['product'],
            'warehouse' => $data['from_warehouse'],
            'quantity' => $data['quantity'],
        ]);
        $this->inventoryTransactionsServices->createInventoryTransaction([
            'product' => $data['product'],
            'warehouse' => $data['from_warehouse'],
            'supplier' => null,
            'quantity' => $data['quantity'],
            'transaction_type' => 'out',
            'created_by' => $data['created_by'],
        ]);

        $this->inventoryServices->createInventory([
            'product' => $data['product'],
            'warehouse' => $data['to_warehouse'],
            'quantity' => $data['quantity'],
        ]);

        return $this->inventoryTransactionsServices->createInventoryTransaction([
            'product' => $data['product'],
            'warehouse' => $data['to_warehouse'],
            'supplier' => null,
            'quantity' => $data['quantity'],
            'transaction_type' => 'in',
            'created_by' => $data['created_by'],
        ]);
    }
}