<?php
namespace App\Repositories;

use App\Models\countries as ModelsCountries;
use App\Models\inventories;
use App\Models\products;
use App\Models\warehouses;
use App\Repositories\Interfaces\InventoryViewRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\isNull;

class InventoryViewRepository implements InventoryViewRepositoryInterface {
        
    public function getGlobalView(array $data){
        if ( !isNull($data['country']) &&  !isNull($data['product']) && !isNull($data['warehouse'])) {
            return $this->getGlobalStockPerProductFilterByCountryAndWarehouse($data['product'], $data['country'], $data['warehouse']);
        
        } else if (!isNull($data['country']) &&  !isNull($data['product'])) {
            return $this->getGlobalStockPerProductFilterByCountry($data['product'], $data['country']);
        
        } else if (!isNull($data['country']) && !isNull($data['warehouse'])) {
            return $this->getGlobalStockPerProductFilterByWarehouse($data['product'], $data['warehouse']);
        }
        return $this->getGlobalStockPerProduct($data['product']);
    }
    public function getGlobalLowStock()
        {
            $inv = DB::table('inventories')->whereColumn('quantity', '<=', 'minimium_quantity');

            $data = DB::table('countries')
                    ->join('warehouses', 'warehouses.country', '=', 'countries.country_id')
                    ->joinSub($inv, 'inv', function ($join){
                        $join->on('inv.warehouse', '=', 'warehouses.warehouse_id');
                    })
                    ->join('products', 'products.product_id', '=', 'inv.product')
                    ->select([
                        'countries.name as cName',
                        'warehouses.name as wName',
                        'warehouses.location as location',
                        'products.product_id as pid',
                        'products.name as pName',
                        'inv.quantity as q',
                        'inv.inventory_id as invid',
                        'countries.name as cName'
                    ])
                    ->get();

                        
            if ($data->isEmpty()) {
                return [];
            }

            return $data->groupBy('cName')->map(function ($c){
                return [
                    'cName' => $c->first()->cName,
                    'warehouses' => $c->groupBy('wName')->map(function ($w){
                        return [
                            'wName' => $w->first()->wName,
                            'warehouseLocation' => $w->first()->location,
                            'inventories' => $w->map(function ($in){
                                return [
                                    'inventoryID' => $in->invid,
                                    'productID' => $in->pid,
                                    'productName' => $in->pName,
                                    'quantity' => $in->q,
                                ];
                            })
                    ];
                })->values()
            ];
        })->values();
        }

    public function getGlobalStockPerProductFilterByWarehouse($product_id, $warehouse_id){
        try {
            warehouses::findOrFail($warehouse_id);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist');
        }
        
        try {
            $product = products::findOrFail($product_id, ['product_id', 'name']);
        } catch (\Throwable $th) {
            throw new Exception('product does not exist');
        }
        
        $data = DB::table('inventories')
                ->where('inventories.warehouse', '=', $warehouse_id)
                ->select([
                    'inventories.quantity',
                    'inventories.inventory_id'
                ])
                ->get();


        if ($data->isEmpty()) {
            return [];
        }

        return [
            'inventory' => $data->first()->inventory_id,
            'product_id' => $product->product_id,
            'product_name' => $product->name,
            'quantity' => $data->first()->quantity
        ];
    }

    public function getGlobalStockPerProduct($id){
        try {
            products::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('product does not exist');
        }
        
        $inventoriesQuery = DB::table("inventories")->where('product', '=', $id);
        $productsQuery = DB::table("products")->where('product_id', '=', $id);

        $data = DB::table('warehouses')
                ->joinSub($inventoriesQuery, 'i', function($join){
                    $join->on('i.warehouse', '=', 'warehouses.warehouse_id');
                })
                ->joinSub($productsQuery, 'p', function($join){
                    $join->on('p.product_id', '=', 'i.product');
                })
                ->join('countries', 'countries.country_id', '=', 'warehouses.country')
                ->select([
                    'countries.name as cName',
                    'warehouses.name as wName',
                    'warehouses.location as location',
                    'p.product_id as pid',
                    'p.name as pName',
                    'i.quantity',
                    'i.inventory_id',
                    'countries.name as cName'
                ])
                ->get();

                    
        if ($data->isEmpty()) {
            return [];
        }

        return $data->groupBy('cName')->map(function ($c){
            return [
                'cName' => $c->first()->cName,
                 'warehouses' => $c->groupBy('wName')->map(function ($w){
                    return [
                        'wName' => $w->first()->wName,
                        'warehouseLocation' => $w->first()->location,
                        'inventories' => $w->map(function ($in){
                            return [
                                'inventoryID' => $in->inventory_id,
                                'productID' => $in->pid,
                                'productName' => $in->pName,
                                'quantity' => $in->quantity,
                            ];
                        })
                    ];
                })->values()
            ];
        })->values();
    }
    public function getGlobalStockPerProductFilterByCountryAndWarehouse($product_id, $country_id, $warehouse_id){
        try {
            ModelsCountries::findOrFail($product_id);
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
        
        try {
            $product = products::findOrFail($product_id, ['product_id', 'name']);
        } catch (\Throwable $th) {
            throw new Exception('product does not exist');
        }
        
        try {
            warehouses::findOrFail($warehouse_id);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist');
        }

        $inv = inventories::where('product', '=', $product_id)
                ->where('warehouse', '=', $warehouse_id)->get(['*']);

        if ($inv->isEmpty()) {
            return [];
        }

        $data = [
            'inventoryID' => $inv->inventory_id,
            'productID' => $product->product_id,
            'productName' => $product->name,
            'quantity' => $inv->quantity,
        ];
        return $data;
    }
    public function getGlobalStockPerProductFilterByCountry($product_id, $country_id){
        try {
            ModelsCountries::findOrFail($country_id);
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
        
        try {
            products::findOrFail($product_id);
        } catch (\Throwable $th) {
            throw new Exception('product does not exist');
        }
        
        $warehousesQuery = DB::table("warehouses")->where('country', '=', $country_id);
        $productsQuery = DB::table("products")->where('product_id', '=', $product_id);

        $data = DB::table('inventories')
                ->joinSub($productsQuery, 'p', function($join){
                    $join->on('p.product_id', '=', 'inventories.product');
                })
                ->joinSub($warehousesQuery, 'w', function($join){
                    $join->on('w.warehouse_id', '=', 'inventories.warehouse');
                })
                ->select([
                    'w.warehouse_id',
                    'w.name as wName',
                    'w.location as location',
                    'p.product_id as pid',
                    'p.name as pName',
                    'inventories.quantity',
                    'inventories.inventory_id'
                ])
                ->get();

                    
        if ($data->isEmpty()) {
            return [];
        }

        return $data->groupBy('warehouse_id')->map(function ($w){
            return [
                'warehouseID' => $w->first()->warehouse_id,
                'warehouseName' => $w->first()->wName,
                'warehouseLocation' => $w->first()->location,
                'inventories' => $w->groupBy('inventory_id')->map(function ($in){
                    return [
                        'inventoryID' => $in->inventory_id,
                        'productID' => $in->pid,
                        'productName' => $in->pName,
                        'quantity' => $in->quantity,
                    ];
                })
            ];
        })->values();
    }
}