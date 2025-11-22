<?php

namespace App\Http\api;

use App\Http\Controllers\Controller;
use App\Services\InventoryViewService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class inventory extends Controller
{
    protected InventoryViewService $inventoryViewService;

    public function __construct(InventoryViewService $inventoryViewService)
    {
        $this->inventoryViewService = $inventoryViewService;
    }

    /**
     * View global inventory levels.
     *
     * please provide one of the parameters
     * 
     * 
     * @group inventory
     * @header Authorization Bearer required 
     *
     * @method GET
     * 
     * @queryParam country int Optional the country ID. ex:2
     * @queryParam warehouse int Optional the warehouse ID. ex:2
     * @queryParam product int required the product ID. ex:2
     */
    public function globalView(Request $request)
    {
        if(
            !$request->filled('product')
            ){
                return response()->json(['message' => 'please provice product ID'])->setStatusCode(422);
            }
        
        $validation = Validator::make($request->query(), [
                'product' => 'required|regex:/^[0-9]+$/',
                'warehouse' => 'nullable|regex:/^[0-9]*$/',
                'country' => 'nullable|regex:/^[0-9]*$/'
            ]);
            
            $validation->validate();

            try {
                $result = $this->inventoryViewService->getGlobalView([
                    'product' => $request->query('product'),
                    'country' => $request->query('country') ?? null,
                    'warehouse' => $request->query('warehouse') ?? null
                ]);
                return response()->json(['message' => $result]);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()])->setStatusCode(400);
            }
    }

    /**
     *Get low stock report.
     *
     * @group inventory
     * @method GET
     * @header Authorization Bearer required 
     */
    public function lowstock(){
        $results = $this->inventoryViewService->getGlobalLowStock();

        return response()->json(['data' => $results]);
    }
}
