<?php

namespace App\Http\api;

use App\Http\Controllers\Controller;
use App\Services\WarehousesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator as FacadesValidator;


class warehouses extends Controller
{
    protected WarehousesService $warehousesService;

    public function __construct(WarehousesService $warehousesService)
    {
        $this->warehousesService = $warehousesService;
    }

    /**
     * Get a list of warehouses.
     * 
     * if you do not specify chunk, all warehouses will be received
     *
     * @group warehouses
     *
     * @method GET
     * 
     * @header Authorization Bearer required 
     * @queryParam chunk integer Optional. Number of warehouses to return. Example: 15
     */
    public function getWarehouses(Request $request)
    {
        $validation = FacadesValidator::make($request->all(), [
            "chunk" => 'regex:/^[0-9]+$/'
        ]);

        $validation->validate();

        try {
            $warehouses = $this->warehousesService->getAllWarehouses($request->query('chunk'));
            return response()->json($warehouses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * create a warehouses.
     *
     * @group warehouses
     *
     * @header Authorization Bearer required 
     * 
     * @method POST
     * 
     * @queryParam name string required warehouse name. Example: frankfort warehouse
     * @queryParam country int required the country ID the warehouse is from. Example: 5 
     * @queryParam location string required the warehouse location. Example: iraq,erbil 
     *
     */
    public function createWarehouse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|min:5',
            'country' => 'required|regex:/^[0-9]*$/',
            'location' => 'required|string|max:255|min:10',
        ]);
        
        $data = $request->only(['name', 'country', 'location']);
        try {
            $this->warehousesService->createWarehouse($data);
            return response()->json(['message' => 'Warehouse created successfully.'])->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
