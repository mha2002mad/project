<?php

namespace App\Repositories;
use App\Models\suppliers;
use App\Repositories\Interfaces\suppliersInterface as InterfacesSuppliersInterface;
use Exception;
class suppliersRepository implements InterfacesSuppliersInterface
{
    public function getAllSuppliers($chunk)
    {
        if ($chunk == null) {
            return suppliers::all();
        }
        return suppliers::limit($chunk)->get();
    }

    public function getSupplierById(int $id)
    {
        try {
            return suppliers::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('supplier does not exist');
        }
    }

    public function createSupplier(array $data): void
    {
        if(suppliers::where('contact_info', $data['contact_info'])->exists()){
            throw new \Exception("Supplier with this contact info already exists.");
        }

        if(suppliers::where('name', $data['name'])->exists()){
            throw new \Exception("Supplier with this name already exists.");
        }
        suppliers::create($data)->save();
    }

    public function updateSupplierName(int $id, string $name): void
    {
        try {
            $supplier = suppliers::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('supplier does not exist');
        }
        $supplier->name = $name;
        $supplier->save();
    }
    
    public function updateSupplierContact(int $id, string $contact): void
    {
        try {
            $supplier = suppliers::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('supplier does not exist');
        }
        $supplier->contact_info = $contact;
        $supplier->save();
    }
    public function updateSupplierAddress(int $id, string $address): void
    {
        try {
            $supplier = suppliers::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('supplier does not exist');
        }
        $supplier->address = $address;
        $supplier->save();
    }

    public function deleteSupplier(int $id): void
    {
        try {
            $supplier = suppliers::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('supplier does not exist');
        }
        $supplier->delete();
    }
}