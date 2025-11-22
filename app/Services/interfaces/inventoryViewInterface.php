<?php
namespace App\Services\interfaces;

interface InventoryViewInterface {
    public function getGlobalLowStock();
    public function getGlobalView(array $data);
}