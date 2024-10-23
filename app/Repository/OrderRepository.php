<?php

namespace App\Repository;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderRepository
{
    protected Order $order;

    /**
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
            $order = new $this->order();
            $order->user_id = $products['user_id'];
            $order->total_price = $products['total_price'];
            $order->status = $this->order::PENDING;
            $order->save();

            return $order;

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a order-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

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
            $order = $this->order::findOrFail($orderId);
            $order->status = $request->status;
            $order->save();

            return $order;

        } catch (Exception $exception) {
            Log::error('An error occurred while updating a order status-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }
}
