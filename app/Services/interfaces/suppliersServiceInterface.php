<?php
namespace App\Services\interfaces;

interface suppliersServiceInterface
{
    public function createSupplier(array $data);
    public function getAllSuppliers($chunk);
    public function getSupplierById(int $id);
    public function updateSupplierName(int $id, string $name);
    public function updateSupplierContact(int $id, string $contact);
    public function updateSupplierAddress(int $id, string $address);
    public function deleteSupplier(int $id);
}