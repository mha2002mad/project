<?php
namespace App\Services;

use App\Repositories\suppliersRepository;
use App\Services\interfaces\suppliersServiceInterface;

class suppliersService implements suppliersServiceInterface
{
    protected suppliersRepository $suppliersRepository;

    public function __construct(suppliersRepository $suppliersRepository)
    {
        $this->suppliersRepository = $suppliersRepository;
    }

    public function createSupplier(array $data)
    {
        return $this->suppliersRepository->createSupplier($data);
    }

    public function getAllSuppliers($chunk)
    {
        return $this->suppliersRepository->getAllSuppliers($chunk);
    }

    public function getSupplierById(int $id)
    {
        return $this->suppliersRepository->getSupplierById($id);
    }

    public function updateSupplierName(int $id, string $name)
    {
        return $this->suppliersRepository->updateSupplierName($id, $name);
    }

    public function updateSupplierContact(int $id, string $contact)
    {
        return $this->suppliersRepository->updateSupplierContact($id, $contact);
    }

    public function updateSupplierAddress(int $id, string $address)
    {
        return $this->suppliersRepository->updateSupplierAddress($id, $address);
    }

    public function deleteSupplier(int $id)
    {
        return $this->suppliersRepository->deleteSupplier($id);
    }
}