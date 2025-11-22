<?php

namespace App\Repositories;
use App\Models\countries;
use App\Repositories\Interfaces\countriesInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class CountryRepository implements countriesInterface
{
    public function getAllCountries($chunk)
    {
        if ($chunk == null) {
            return countries::get();
        }
        return countries::limit($chunk)->get();
    }

    public function getCountryById($id)
    {
        try {
            return countries::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
    }

    public function createCountry(array $data)
    {
        if(countries::where('code', $data['code'])->exists()){
            throw new \Exception("Country with code {$data["code"]} already exists.");
        }
        
        if(countries::where('name', $data['name'])->exists()){
            throw new \Exception("Country with code {$data['name']} already exists.");
        }

        countries::create($data)->save();
    }

    public function getStockLevelByCountry($id)
    {
        try {
            $country = countries::findOrFail($id)->first()->get();
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
        
        $warehousesQuery = DB::table("warehouses")->where('country', '=', $id);

        $data = DB::table('inventories')
                ->joinSub($warehousesQuery, 'w', function($join){
                    $join->on('w.warehouse_id', '=', 'inventories.warehouse');
                })
                ->join('products', 'products.product_id', '=', 'inventories.product')
                ->select([
                    'w.warehouse_id',
                    'w.name as wName',
                    'w.location as location',
                    'products.product_id as pid',
                    'products.name as pName',
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
                'inventories' => $w->map(function ($in){
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

    public function deleteCountry($id)
    {
        try {
            countries::destroy($id);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateCountryName($id, $name)
    {
        try {
            $country = countries::findOrFail($id);
        } catch (\Throwable $th) {
            throw new Exception('country does not exist');
        }
        $country->name = $name;
    }
}