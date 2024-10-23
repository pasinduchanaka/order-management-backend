<?php

namespace App\Services;

use App\Models\Order;
use App\Repository\OrderRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderService
{
    protected OrderRepository $orderRepository;

    /**
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Store order
     *
     * @param array $products
     * @return Order
     * @throws Exception
     */
    public function store(array $products): Order
    {
        try {
            return $this->orderRepository->store($products);

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a order-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Update order status
     *
     * @param Request $request
     * @param $orderId
     * @return Order
     * @throws Exception
     */
    public function updateOrderStatus(Request $request, $orderId): Order
    {
        try {
            return $this->orderRepository->updateOrderStatus($request, $orderId);

        } catch (Exception $exception) {
            Log::error('An error occurred while updating a order status-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }
}
