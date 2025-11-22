<?php
namespace App\Repositories\Interfaces;

interface suppliersInterface
{
    public function getAllSuppliers($chunk);
    public function getSupplierById(int $id);
    public function createSupplier(array $data): void;
    public function updateSupplierName(int $id, string $name): void;
    public function updateSupplierContact(int $id, string $name): void;
    public function updateSupplierAddress(int $id, string $address): void;
    public function deleteSupplier(int $id): void;
}