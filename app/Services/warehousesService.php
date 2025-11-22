<?php
namespace App\Services;
use App\Repositories\warehouseRepository;
use App\Services\interfaces\WarehousesServiceInterface;

class WarehousesService implements WarehousesServiceInterface
{
    protected $warehouseRepository;

    public function __construct(warehouseRepository $warehouseRepository)
    {
        $this->warehouseRepository = $warehouseRepository;
    }

    public function getAllWarehouses($chunk)
    {
        return $this->warehouseRepository->getAllWarehouses($chunk);
    }

    public function getAllWarehouseInventoryLevels($chunk){
        return $this->warehouseRepository->getAllWarehouseInventoryLevels($chunk);
    }

    public function getWarehouseById($id)
    {
        return $this->warehouseRepository->getWarehouseById($id);
    }

    public function getWarehousesByCountry($country)
    {
        return $this->warehouseRepository->getWarehousesByCountry($country);
    }

    public function createWarehouse(array $data)
    {
        return $this->warehouseRepository->createWarehouse($data);
    }

    public function updateWarehouseAddress($id, string $address)
    {
        return $this->warehouseRepository->updateWarehouseAddress($id, $address);
    }

    public function deleteWarehouse($id)
    {
        return $this->warehouseRepository->deleteWarehouse($id);
    }

    public function getStockLevelInventories($id){
        return $this->warehouseRepository->getStockLevelInventories($id);
    }
}