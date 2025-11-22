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
     * global product stock level view by country or warehouse or product.
     *
     * please provide one of the parameters
     * 
     * 
     * @group inventory
     * @header Authorization Bearer required 
     *
     * @method GET
     * 
     * @queryParam country int Optional the country name. ex:2
     * @queryParam warehouse int Optional the warehouse name. ex:2
     * @queryParam product int Optional the warehouse name. ex:2
     */
    public function globalView(Request $request)
    {
        if(
            !$request->filled('country') &&
            !$request->filled('warehouse') &&
            !$request->filled('chunk')
            ){
                return response()->json(['message' => 'please provice fetch Size, country ID, or warehouse ID']);
            }
        
        $validated = Validator::make($request->all(), [
            'chunk' => 'regex:/^[0-9]+$/',
            'warehouse' => 'regex:/^[0-9]+$/',
            'country' => 'regex:/^[0-9]+$/',
        ]);

        $validated->validate();


        try {
            $result = $this->inventoryViewService->getStockLevelByCountryOrWarehouse([
                'country' => $request->query('country') ?? null,
                'warehouse' => $request->query('warehouse') ?? null,
                'chunk' => $request->query('chunk') ?? null
            ]);
            return response()->json(['data' => $result]);
        } catch(Exception $e){
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * see all products around the world which are in low stock.
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
