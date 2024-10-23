<?php

namespace App\Services;

use App\Repository\OrderItemsRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class OrderItemsService
{
    protected OrderItemsRepository $orderItemsRepository;

    /**
     * @param OrderItemsRepository $orderItemsRepository
     */
    public function __construct(OrderItemsRepository $orderItemsRepository)
    {
        $this->orderItemsRepository = $orderItemsRepository;
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
        try {
            $this->orderItemsRepository->store($products, $orderId);

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a order items-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }
}
