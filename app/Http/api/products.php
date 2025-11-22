<?php

namespace App\Http\api;

use App\Http\Controllers\Controller;
use App\Services\ProductsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class products extends Controller
{
    protected ProductsService $productsService;

    public function __construct(ProductsService $productsService)
    {
        $this->productsService = $productsService;
    }

    /**
     * Get a list of countries.
     *
     * @group products
     *
     * @method GET
     * 
     * @header Authorization Bearer required 
     * @queryParam chunk integer Optional. Number of products to return. Example: 15
     * 
     * if you do not specify chunk, all products will be received
     *
     */
    public function getProducts(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "chunk" => 'nullable|regex:/^[0-9]+$/',
        ]);

        $validation->validate();

        try {
            $products = $this->productsService->getAllProducts($request->query('chunk'));
            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch products', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * create a product.
     *
     * @group products
     * @header Authorization Bearer required 
     *
     * @method POST
     * 
     * @bodyParam name string required the product name. ex:shoes
     * @bodyParam sku string required the unique inventory product identifier code. ex:fscc_23232_r
     * @bodyParam description string required product description. ex:shoes size 45 colour black
     * @bodyParam status string required if the product is ready for sales type active if not inactive. ex:inactive
     * @bodyParam price number required if the product is ready for sales type active if not inactive. ex:inactive
     *
     */
    public function createProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:150|min:5',
            'sku' => 'required|string|max:50|unique:products,sku',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'price' => 'required|numeric|min:0',
        ]);

        try {
            $this->productsService->createProduct([
                'name' => $request->input('name'),
                'sku' => $request->input('sku'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
                'price' => $request->input('price'),
            ]);
            return response()->json(['message' => 'Product created successfully'])->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
        /**
         * get global inventory levels by product
         * if you do not proivde a product, then all products will be assumed
         * 
         * @group inventory
         * @Header Authorization Bearer required
         * 
         * @method GET
         * 
         * @queryParam product int required the product ID. ex: 45
        */
        public function GetProductLevelOnWarehouses(Request $request)
        {
            $validation = Validator::make($request->all(), [
                'product' => 'required|regex:/^[0-9]*$/'
            ]);
            
            $validation->validate();

            try {
                $result = $this->productsService->getProductLevelOnWarehouses($request->query('product'));
                return response()->json(['message' => $result]);
            } catch (\Throwable $th) {
                return response()->json(['message' => $th->getMessage()]);
            }
        }
}
