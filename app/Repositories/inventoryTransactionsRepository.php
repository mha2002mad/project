<?php

namespace App\Repositories;

use App\Models\inventoryTransactions;
use App\Models\products;
use App\Models\suppliers;
use App\Models\warehouses;
use App\Repositories\Interfaces\inventoryTransactionsInterface as InterfacesInventoryTransactionsInterface;
use Exception;

class inventoryTransactionsRepository implements InterfacesInventoryTransactionsInterface
{
    public function getAllInventoryTransactions($chunk)
    {
        return inventoryTransactions::limit($chunk)->get();
    }

    public function getInventoryTransactionById($id)
    {
        try {
            return inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
    }

    public function createInventoryTransaction(array $data)
    {
        if($data['supplier'] != null && !suppliers::find($data['supplier'])) {
            throw new Exception("supplier does not exist");
        }

        if(!products::find($data['product'])) {
            throw new Exception("product does not exist");
        }

        if(!warehouses::find($data['warehouse'])) {
            throw new Exception("warehouse does not exist");
        }
        return inventoryTransactions::create($data)->save();
    }

    public function updateInventoryTransactionsProduct($id, $product)
    {
        try {
            $transation = inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
        try {
            $product = products::findOrFail($product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('product does not exisyt');
        }
        $transation->product = $product;
        $transation->save();
    }

    public function updateInventoryTransactionsSupplier($id, $supplier)
    {
        try {
            $transation = inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
        try {
            $supplier = suppliers::findOrFail($supplier);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('supplier does not exisyt');
        }
        $transation->supplier = $supplier;
        $transation->save();
    }
    public function updateInventoryTransactionsWarehouse($id, $warehouse)
    {
        try {
            $transation = inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
        try {
            $warehouse = warehouses::findOrFail($warehouse);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('warehouse does not exisyt');
        }
        $transation->warehouse = $warehouse;
        $transation->save();
    }
    public function updateInventoryTransactionsQuantity($id, int $quantity)
    {
        if ($quantity < 0) {
            throw new \InvalidArgumentException("Quantity cannot be negative");
        }
        try {
            $transation = inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
        $transation->quantity = $quantity;
        $transation->save();
    }
    public function updateInventoryTransactionsTransactionType($id, string $transactionType)
    {
        if (!in_array($transactionType, ['in', 'out'])) {
            throw new \InvalidArgumentException("Invalid transaction type");
        }
        try {
            $transation = inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
        $transation->transaction_type = $transactionType;
        $transation->save();
    }

    public function deleteInventoryTransaction($id)
    {
        try {
            $transation = inventoryTransactions::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('inventoryTransaction does not exisyt');
        }
        $transation->delete();
    }
}