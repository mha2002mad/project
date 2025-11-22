<?php

namespace App\Http\api;

use App\Http\Controllers\Controller;
use App\Services\suppliersService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class suppliers extends Controller
{
    protected suppliersService $suppliersService;

    public function __construct(suppliersService $suppliersService)
    {
        $this->suppliersService = $suppliersService;
    }

    /**
     * Get a list of suppliers.
     *
     *  if you do not specify chunk, all suppliers will be received
     *
     * @group suppliers
     *
     * @method GET
     * 
     * @Header Authorization Bearer required
     * @queryParam chunk integer Optional. Number of suppliers to return. Example: 15
     */
    public function getSuppliers(Request $request)
    {
        $validation = Validator::make($request->query(), [
            "chunk" => 'regex:/^[0-9]+$/'
        ]);

        $validation->validate();

        try {
            $suppliers = $this->suppliersService->getAllSuppliers($request->query('chunk'));
            return response()->json($suppliers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(400);
        }
    }

    /**
     * create a supplier.
     *
     * @group suppliers
     * @header Authorization Bearer required 
     *
     * @method POST
     * 
     * @bodyParam name string required the supplier name.
     * @bodyParam contact_info string required the supplier's phone number. ex:000 0000 000 00 00
     * @bodyParam address string required the supplier address.
     * 
     */
    public function createSupplier(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150|min:5',
            'contact_info' => 'required|string|max:50|min:8|regex:/^[0-9 ]+$/',
            'address' => 'required|string|max:200|min:8',
        ]);

        $validator->validate();

        try {
            $this->suppliersService->createSupplier([
                'name' => $request->input('name'),
                'contact_info' => $request->input('contact_info'),
                'address' => $request->input('address'),
            ]);
            return response()->json(['message' => 'supplier created successfully'])->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(400);
        }
    }
}
