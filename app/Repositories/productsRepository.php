<?php

namespace App\Repositories;
use App\Models\products;
use App\Repositories\Interfaces\productsInterface as InterfacesProductsInterface;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;

class productsRepository implements InterfacesProductsInterface
{
    public function getAllProducts($chunk)
    {
        if ($chunk == null) {
            return products::all();
        }
        return products::limit($chunk)->get();
    }

    public function getProductById($id)
    {
        try {
            return products::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('product does not exisyt');
        }
    }

    public function createProduct(array $data)
    {
        if (!in_array($data['status'], ['active', 'inactive'])) {
            throw new \InvalidArgumentException("Invalid status value");
        }

        if($data['price'] <= 0){
            throw new \InvalidArgumentException("Price cannot be negative or zero");
        }

        if(products::where('sku', $data['price'])->exists()){
            throw new \InvalidArgumentException("SKU must be unique");
        }

        products::create($data)->save();
    }

    public function updateProductDescription($id, string $description)
    {
        try {
            $product = products::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('product does not exisyt');
        }
        $product->description = $description;
        $product->save();
    }
    public function updateProductName($id, string $name)
    {
        try {
            $product = products::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('product does not exisyt');
        }
        $product->name = $name;
        $product->save();
    }
    public function updateProductStatus($id, string $status)
    {
        if (!in_array($status, ['active', 'inactive'])) {
            throw new \InvalidArgumentException("Invalid status value");
        }
       try {
            $product = products::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('product does not exisyt');
        }
        $product->status = $status;
        $product->save();
    }
    
    public function updateProductPrice($id, float $price)
    {
        if($price <= 0){
            throw new \InvalidArgumentException("Price cannot be negative or zero");
        }
        
        try {
            $product = products::findOrFail($id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            throw new Exception('product does not exisyt');
        }
        $product->price = $price;
        $product->save();
    }

    public function getProductLevelOnWarehouses($id){
        try {
            $product = products::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('product does not exist');
        }
        
        $filteredInventories = FacadesDB::table('inventories')
        ->where('product', $id);
        
        $filteredProducts = FacadesDB::table('products')
        ->where('product_id', $id);

        $rows = FacadesDB::table('warehouses')
                    ->joinSub($filteredInventories, 'inv', function ($callback){
                        $callback->on('warehouses.warehouse_id', '=', 'inv.warehouse');
                    })
                    ->joinSub($filteredProducts, 'prod', function ($callback){
                        $callback->on('prod.product_id', '=', 'inv.product');
                    })
                    ->select([
                        'warehouses.warehouse_id',
                        'warehouses.name as wName',
                        'inv.inventory_id',
                        'prod.product_id',
                        'prod.name as prodName',
                        'inv.quantity'
                    ])
                    ->get();


        if ($rows->isEmpty()) {
            return [];
        }


        return $rows->groupBy('warehouses.warehouse_id')->map(function($call){
            return [
                'warehouseID' => $call->first()->warehouse_id,
                'warehouseName' => $call->first()->wName,
                'product' => $call->map(function ($call2){
                    return [
                        'productID' => $call2->product_id,
                        'productName' => $call2->prodName,
                        'quantity' => $call2->quantity
                    ];
                })->values()
            ];
        })->values();
    }


    public function deleteProduct($id)
    {
        products::destroy($id);
    }
}