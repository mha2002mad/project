<?php
namespace App\Services;
use App\Repositories\inventoryRepository;
use App\Services\interfaces\inventoryServicesInterface;

class inventoryServices implements inventoryServicesInterface
{
    protected $inventoryRepository;

    public function __construct(inventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    public function getAllInventories($chunk)
    {
        return $this->inventoryRepository->getAllInventories($chunk);
    }

    public function getInventoryById($id)
    {
        return $this->inventoryRepository->getInventoryById($id);
    }

    public function inventoryExistsByProductAndWarehouse($product, $warehouse)
    {
        return $this->inventoryRepository->inventoryExistsByProductAndWarehouse($product, $warehouse);
    }

    public function getInventoryByProductAndWarehouse($product, $warehouse)
    {
        return $this->inventoryRepository->getInventoryByProductAndWarehouse($product, $warehouse);
    }

    public function createInventory(array $data)
    {
        return $this->inventoryRepository->createInventory($data);
    }

    public function updateInventoryQuantity(array $data)
    {
        return $this->inventoryRepository->updateInventoryQuantity($data['id'], $data['quantity']);
    }

    public function reduceInventoryQuanitity(array $data)
    {
        return $this->inventoryRepository->reduceInventoryQuanitity($data);
    }

    public function increaseInventoryQuantity(array $data)
    {
        return $this->inventoryRepository->increaseInventoryQuantity($data);
    }

    public function updateInventoryMinimiumQuantity(array $data)
    {
        return $this->inventoryRepository->updateInventoryMinimiumQuantity($data['id'], $data['minimium_quantity']);
    }

    public function updateInventoryProduct(array $data)
    {
        return $this->inventoryRepository->updateInventoryProduct($data['id'], $data['product']);
    }

    public function updateInventoryWarehouse(array $data)
    {
        return $this->inventoryRepository->updateInventoryWarehouse($data['id'], $data['warehouse']);
    }

    public function deleteInventory($id)
    {
        return $this->inventoryRepository->deleteInventory($id);
    }
}