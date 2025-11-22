<?php

namespace App\Repositories;

use App\Models\countries;
use App\Models\warehouses;
use App\Repositories\Interfaces\warehousesInterface;
use Exception;
use Illuminate\Support\Facades\DB as FacadesDB;

class warehouseRepository implements warehousesInterface
{
    public function getAllWarehouses($chunk)
    {
        if ($chunk == null) {
            return warehouses::all();
        }
        return warehouses::limit($chunk)->get();
    }

    public function getAllWarehouseInventoryLevels($chunk){
        $data = FacadesDB::table('warehouses')
                    ->limit($chunk)
                    ->join('inventories', 'inventories.warehouse', '=', 'warehouses.warehouse_id')
                    ->join('products', 'products.product_id', '=', 'inventories.product')
                    ->select([
                        'warehouses.warehouse_id AS WID',
                        'warehouses.name as wName',
                        'products.product_id',
                        'products.name as pName',
                        'inventories.inventory_id',
                        'inventories.quantity'
                    ])
                    ->get();

        if ($data->isEmpty()) {
            return [];
        }

        return $data->groupBy('WID')->map(function ($subgroup){
            return [
                'warehouse_id' => $subgroup->first()->WID,
                'warehouse_name' => $subgroup->first()->wName,
                'inventories' => $subgroup->map(function ($inv){
                    return [
                        'product_id' => $inv->product_id,
                        'product_name' => $inv->pName,
                        'inventory_id' => $inv->inventory_id,
                        'quantity' => $inv->quantity
                    ];
                })->values()
            ];
        })->values();
    }

    public function getWarehouseById($id)
    {
        try {
            return warehouses::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist');
        }
    }

    public function getWarehousesByCountry($country)
    {
        try {
            countries::findOrFail($country);
        } catch (\Throwable $th) {
            throw new Exception('country not found');
        }

        $data = FacadesDB::table('warehouses')
                    ->selectRaw('warehouses.country = ?' [$country])
                    ->select([
                        'warehouses.warehouse_id AS WID',
                        'warehouses.name as wName',
                        'warehouses.loction',
                    ])
                    ->get();
        if ($data->isEmpty()) {
            return [];
        }

        return [
            'country_id' => $country->country_id,
            'country_name' => $country->name,
            'warehouses' => $data->groupBy('WID')->map(function ($w){
                return [
                    'warehouse_id' => $w->first()->WID,
                    'warehouse_name' => $w->first()->wName,
                    'location' => $w->first()->loction,
                ];
            })->values()
        ];
    }

    public function createWarehouse(array $data)
    {
        if (!countries::find($data['country'])) {
            throw new Exception("Country with ID {$data['country']} does not exist.");
        }
        if (warehouses::where('name', $data['name'])->exists()) {
            throw new Exception("warehouse with name {$data['name']} already exists.");
        }

        warehouses::create($data)->save();
    }

    public function updateWarehouseAddress($id, string $address)
    {
        try {
            $warehouse = warehouses::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist');
        }
        $warehouse->update(['loction' => $address]);
    }

    public function deleteWarehouse($id)
    {
        try {
            $warehouse = warehouses::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist');
        }
        $warehouse->delete();
    }

    public function getStockLevelInventories($id)
    {
        try {
            $warehouse = warehouses::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('warehouse does not exist');
        }
        return $warehouse->inventories()->get();
    }
}