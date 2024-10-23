<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Models\Product;
use App\Services\OrderItemsService;
use App\Services\OrderService;
use App\Services\ProductService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected OrderService $orderService;
    protected ProductService $productService;
    protected OrderItemsService $orderItemsService;

    /**
     * @param OrderService $orderService
     * @param ProductService $productService
     * @param OrderItemsService $orderItemsService
     */
    public function __construct(OrderService $orderService, ProductService $productService, OrderItemsService $orderItemsService)
    {
        $this->orderService = $orderService;
        $this->productService = $productService;
        $this->orderItemsService = $orderItemsService;
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Store order
     *
     * @param StoreOrderRequest $request
     * @return JsonResponse
     */
    public function store(StoreOrderRequest $request): JsonResponse
    {
        try {
            Log::info('Store request received for creating a new order', [
                'request_data' => $request->all()
            ]);

            $products = $this->productService->getProductDetails($request->items);

            $productsArr = array();
            $totalPrice = 0;

            foreach ($request->items as $item) {
                $product = $products->firstWhere('id', $item['product_id']);

                if (!$product) {
                    throw new Exception('Product not found: ' . $item['product_id']);
                }

                if ($product->status == Product::DEACTIVE) {
                    throw new Exception('The product is not available for order: ' . $product->name);
                }

                if ($product->stock_quantity < $item['quantity']) {
                    throw new Exception('Not enough stock for product: ' . $product->name);
                }

                $totalPrice += $product->price * $item['quantity'];

                $newProduct = [
                    'product_id' => $product['id'],
                    'quantity' => $item['quantity'],
                    'price' => $product['price'],
                ];

                $productsArr['products'][] = $newProduct;
            }

            $productsArr['total_price'] = $totalPrice;
            $productsArr['user_id'] = auth()->user()->id;

            DB::beginTransaction();

            $order = $this->orderService->store($productsArr);
            $this->orderItemsService->store($productsArr, $order->id);

            DB::commit();

            $response = [
                'status' => 'success',
                'result' => ['order' => $order]
            ];

            return response()->json($response, 201, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

        } catch (Exception $exception) {
            DB::rollBack();

            Log::error('An error occurred while creating a order-(controller): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

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
     * Summary: Update order status
     *
     * @param UpdateOrderStatusRequest $request
     * @param $orderId
     * @return JsonResponse
     */
    public function updateOrderStatus(UpdateOrderStatusRequest $request, $orderId): JsonResponse
    {
        try {
            Log::info('Update order status request received for order ID: ' . $orderId, [
                'request_data' => $request->all()
            ]);

            $order = $this->orderService->updateOrderStatus($request, $orderId);

            $response = [
                'status' => 'success',
                'result' => ['order' => $order]
            ];

            return response()->json($response, 200, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);

        } catch (Exception $exception) {
            Log::error('An error occurred while updating a order status-(controller): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            $response = [
                'status' => 'failed',
                'message' => $exception->getMessage()
            ];

            return response()->json($response, 500, ['Access-Control-Allow-Origin' => '*', 'Content-Type' => 'application/json']);
        }
    }
}
