<?php
namespace App\Services;

use App\Services\interfaces\InventoryViewInterface;
class InventoryViewService implements InventoryViewInterface {
    protected CountriesServices $countriesServices;
    protected WarehousesService $warehousesService;
    protected inventoryServices $inventoryServices;

    public function __construct(WarehousesService $warehousesService, CountriesServices $countriesServices, inventoryServices $inventoryServices)
    {
        $this->countriesServices = $countriesServices;
        $this->warehousesService = $warehousesService;
        $this->inventoryServices = $inventoryServices;
    }

    public function getGlobalLowStock(){
        return $this->inventoryServices->getGlobalLowStock();
    }

    public function getStockLevelByCountryOrWarehouse(array $data){
         if ($data['chunk'] != null) {
            return $this->warehousesService->getAllWarehouseInventoryLevels($data['chunk']);
        }
        if ($data['country'] != null && $data['warehouse'] == null) {
            return $this->countriesServices->getStockLevelByCountry($data['country']);
        }
        
        return $this->warehousesService->getStockLevelInventories($data['warehouse']);
    }
}