<?php

namespace App\Http\api;

use App\Http\Controllers\Controller;
use App\Services\inventoryMovementService;
use Illuminate\Http\Request;

class InventoryMovements extends Controller
{
    protected inventoryMovementService $inventoryMovementService;

    public function __construct(inventoryMovementService $inventoryMovementService)
    {
        $this->inventoryMovementService = $inventoryMovementService;
    }

    /**
     * create a new transaction.
     * 
     *  all of those parameters are required
     *
     * @group inventory
     * @header Authorization Bearer required 
     *
     * @method POST
     * 
     * @bodyParam product int required the product ID. ex:2
     * @bodyParam warehouse int required the warehouse ID. ex:5
     * @bodyParam supplier int required the supplier ID. ex:8
     * @bodyParam quantity int required the number of the product to take in/out. ex:214
     * @bodyParam transaction_type string required please select in if you are receiving or out if you are sending. ex:out
     * @bodyParam created_by int required the admin who submited this transaction ID. ex:45
     */
    public function newTransaction(Request $request)
    {
        $request->validate([
            'product' => 'required|integer|min:1',
            'warehouse' => 'required|integer|min:1',
            'supplier' => 'nullable|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'transaction_type' => 'required|string|in:in,out',
            'created_by' => 'required|integer|min:1',
        ]);

    
        try {
            $this->inventoryMovementService->newTransaction([
            'product' => $request->input('product'),
            'warehouse' => $request->input('warehouse'),
            'supplier' => $request->input('supplier'),
            'quantity' => $request->input('quantity'),
            'transaction_type' => $request->input('transaction_type'),
            'created_by' => $request->input('created_by')
        ]);
            return response()->json(['message' => 'Transaction completed successfully'])->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(400);
        }
    }

    /**
     * transfer product among warehouses.
     *
     *  all of those parameters are required
     *  
     * @group inventory
     * @header Authorization Bearer required 
     *
     * @method POST
     * 
     * @bodyParam product int required the product ID. ex:2
     * @bodyParam from_warehouse int required the warehouse we send from. ex:5
     * @bodyParam to_warehouse int required the warehouse we send to. ex:1
     * @bodyParam quantity int required the number of the product to take in/out. ex:214
     * @bodyParam created_by int required the admin who submited this transaction ID. ex:45
     */
    public function transferInventory(Request $request)
    {
        $request->validate([
            'product' => 'required|integer|min:1',
            'from_warehouse' => 'required|integer|min:1',
            'to_warehouse' => 'required|integer|min:1',
            'quantity' => 'required|integer|min:1',
            'created_by' => 'required|integer|min:1'
        ]);

        try {
            $this->inventoryMovementService->transferInventory([
                'product' => $request->input('product'),
                'from_warehouse' => $request->input('from_warehouse'),
                'to_warehouse' => $request->input('to_warehouse'),
                'quantity' => $request->input('quantity'),
                'created_by' => $request->input('created_by')
            ]);
            return response()->json(['success' => 'Inventory transfer completed successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()])->setStatusCode(400);
        }
    }
}
