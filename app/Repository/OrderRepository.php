<?php

namespace App\Repository;

use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
     * Summary: Get all orders
     *
     * @param $search
     * @return LengthAwarePaginator|Order|null
     * @throws Exception
     */
    public function index($search = null): LengthAwarePaginator|Order|null
    {
        try {
            if ($search) {
                $query = $this->order::query()
                    ->join('users', 'users.id', '=', 'orders.user_id')
                    ->select('orders.*', 'users.id as user_id');
                foreach ($search as $filterKey => $filterValue) {
                    switch ($filterKey) {
                        case 'name':
                            if ($filterValue != null) {
                                $query->where('users.name', 'like', '%' . $filterValue . '%');
                            }
                            break;
                        case 'status':
                            if ($filterValue != null) {
                                $query->where('orders.status', $filterValue);
                            }
                            break;
                    }
                }

                $orders = $query
                    ->orderBy('orders.id', 'DESC')
                    ->with(['orderItems.product', 'createdBy:id,name'])
                    ->paginate(5);

            } else {
                $orders = $this->order::orderBy('orders.id', 'DESC')
                    ->with(['orderItems.product', 'createdBy:id,name'])
                    ->paginate(5);
            }

            return $orders;

        } catch (Exception $exception) {
            Log::error('An error occurred while fetching orders-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
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
            $order->user_id = auth()->user()->id;
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
