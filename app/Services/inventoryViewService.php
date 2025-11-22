<?php
namespace App\Services;

use App\Repositories\InventoryViewRepository;
use App\Services\interfaces\InventoryViewInterface;
class InventoryViewService implements InventoryViewInterface {
    protected InventoryViewRepository $inventoryViewRepository;

    public function __construct(InventoryViewRepository $inventoryViewRepository)
    {
        $this->inventoryViewRepository = $inventoryViewRepository;
    }

    public function getGlobalLowStock(){
        return $this->inventoryViewRepository->getGlobalLowStock();
    }

    public function getGlobalView(array $data){
        return $this->inventoryViewRepository->getGlobalView($data);
    }
}