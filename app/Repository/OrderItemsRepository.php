<?php

namespace App\Repository;

use App\Models\OrderItem;
use App\Services\ProductService;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderItemsRepository
{
    protected OrderItem $orderItem;
    protected ProductService $productService;

    /**
     * @param OrderItem $orderItem
     * @param ProductService $productService
     */
    public function __construct(OrderItem $orderItem, ProductService $productService)
    {
        $this->orderItem = $orderItem;
        $this->productService = $productService;
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Store order items
     *
     * @param array $products
     * @param $orderId
     * @return void
     * @throws Exception
     */
    public function store(array $products, $orderId): void
    {
        foreach ($products['products'] as $product) {
            try {
                $orderItem = new $this->orderItem();
                $orderItem->order_id = $orderId;
                $orderItem->product_id = $product['product_id'];
                $orderItem->quantity = $product['quantity'];
                $orderItem->price = $product['price'];
                $orderItem->save();

                $this->productService->updateProductQuantity($product['quantity'], $product['product_id']);

            } catch (Exception $exception) {
                Log::error('An error occurred while creating a order items-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

                throw $exception;
            }
        }
    }
}
