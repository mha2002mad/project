<?php

namespace App\Repositories;

use App\Models\inventories;
use App\Models\products;
use App\Models\warehouses;
use App\Repositories\Interfaces\InventoryInterface as InterfacesInventoryInterface;
use Exception;

class inventoryRepository implements InterfacesInventoryInterface
{
    public function getAllInventories($chunk)
    {
        return inventories::limit($chunk)->get();
    }

    public function getInventoryById($id)
    {
        try {
            return inventories::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('inventory does nor exist'); 
        }
    }

    public function inventoryExistsByProductAndWarehouse($product, $warehouse)
    {
        return inventories::where('product', $product)->where('warehouse', $warehouse)->exists();
    }

    public function getInventoryByProductAndWarehouse($product, $warehouse)
    {
        return inventories::where('product', $product)->where('warehouse', $warehouse)->first();
    }

    public function createInventory(array $data)
    {
        try{
            products::find($data['product']);
        } catch(Exception $e){
            throw new Exception('product is not found');
        }
        
        try{
            warehouses::find($data['warehouse']);
        } catch(Exception $e){
            throw new Exception('warehouse is not found');
        }


        $inv = inventories::where('product', $data['product'])->where('warehouse', $data['warehouse']);

        if($inv->exists()){
            $inv->first()->quantity += $data['quantity'];
            return $inv->first()->save();
        }

        return inventories::create([
            'product' => $data['product'],
            'warehouse' => $data['warehouse'],
            'quantity' => $data['quantity'],
            'minimium_quantity' => 1
        ])->save();
    }

    public function reduceInventoryQuanitity(array $data)
    {
        $inventory = inventories::where('product', $data['product'])->where('warehouse', $data['warehouse'])->first();

        if($inventory === null){
            throw new Exception("Inventory not found");
        }

        if($inventory->minimium_quantity == $inventory->quantity){
            throw new Exception("Insufficient stock");
        }
        
        if($inventory->quantity < $data['quantity']){
            throw new Exception("Insufficient inventory quantity");
        }

        $inventory->quantity -= $data['quantity'];
        return $inventory->save();
    }

    public function increaseInventoryQuantity(array $data)
    {
        $inventory = inventories::where('product', $data['product'])->where('warehouse', $data['warehouse'])->first();

        if($inventory === null){
            throw new Exception("Inventory not found");
        }

        $inventory->quantity += $data['quantity'];
        return $inventory->save();
    }

    public function getGlobalLowStock()
    {
        $results = inventories::whereColumn('quantity', "<=", 'minimium_quantity')
        ->with(['warehouse.country', 'product'])
        ->get();

        return $results;
    }

    public function updateInventoryQuantity($id, $quntity)
    {
        try {
            $inventory = inventories::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('inventory does not exist'); 
        }
        $inventory->quantity = $quntity;
        $inventory->save();
    }

    public function updateInventoryMinimiumQuantity($id, $quntity)
    {
        try {
            $inventory = inventories::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('inventory does not exist'); 
        }
        $inventory->minimium_quantity = $quntity;
        $inventory->save();
    }

    public function updateInventoryProduct($id, $product)
    {
        try {
            $product = products::findOrFail($product);
        } catch (\Throwable $th) {
            throw new Exception('product does not exist'); 
        }
        
        try {
            $inventory = inventories::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('inventory does not exist'); 
        }

        $inventory->product = $product;
        $inventory->save();
    }

    public function updateInventoryWarehouse($id, $warehouse)
    {
        try {
            $warehouse = warehouses::findOrFail($warehouse);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist'); 
        }
        try {
            $inventory = inventories::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('inventory does nor exist'); 
        }
        $inventory->warehouse = $warehouse;
        $inventory->save();
    }

    public function deleteInventory($id)
    {
        try {
            $inventory = inventories::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('inventory does nor exist'); 
        }
        $inventory->delete();
    }
}