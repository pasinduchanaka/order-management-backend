<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    protected ProductService $productService;

    /**
     * @param ProductService $productService
     */
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Get all products
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->only(['name', 'status']);
            $products = $this->productService->index($search);

            $response = [
                'status' => 'success',
                'result' => ['products' => $products]
            ];

            return response()->json($response, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

        } catch (Exception $exception) {
            Log::error('An error occurred while fetching products-(controller): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            $response = [
                'status' => 'failed',
                'message' => $exception->getMessage()
            ];

            return response()->json($response, 500, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Store product
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        try {
            Log::info('Store request received for creating a new product', [
                'request_data' => $request->all()
            ]);

            $product = $this->productService->store($request);

            $response = [
                'status' => 'success',
                'result' => ['product' => $product]
            ];

            return response()->json($response, 201, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a product-(controller): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            $response = [
                'status' => 'failed',
                'message' => $exception->getMessage()
            ];

            return response()->json($response, 500, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Update product
     *
     * @param UpdateProductRequest $request
     * @param $productId
     * @return JsonResponse
     */
    public function update(UpdateProductRequest $request, $productId): JsonResponse
    {
        try {
            Log::info('Update request received for product ID: ' . $productId, [
                'request_data' => $request->all()
            ]);

            $product = $this->productService->update($request, $productId);

            $response = [
                'status' => 'success',
                'result' => ['product' => $product]
            ];

            return response()->json($response, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

        } catch (Exception $exception) {
            Log::error('An error occurred while updating a product-(controller): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            $response = [
                'status' => 'failed',
                'message' => $exception->getMessage()
            ];

            return response()->json($response, 500, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Delete product
     *
     * @param $productId
     * @return JsonResponse
     */
    public function delete($productId): JsonResponse
    {
        try {
            Log::info('Delete request received for product ID: ' . $productId);

            $product = $this->productService->delete($productId);

            $response = [
                'status' => 'success',
                'result' => ['product' => $product]
            ];

            return response()->json($response, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

        } catch (Exception $exception) {
            Log::error('An error occurred while deleting a product-(controller): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            $response = [
                'status' => 'failed',
                'message' => $exception->getMessage()
            ];

            return response()->json($response, 500, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }
}
