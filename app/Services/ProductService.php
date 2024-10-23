<?php

namespace App\Services;

use App\Models\Product;
use App\Repository\ProductRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProductService
{
    protected ProductRepository $productRepository;

    /**
     * @param ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Get all products
     *
     * @param $request
     * @return LengthAwarePaginator|Product|null
     * @throws Exception
     */
    public function index($request): LengthAwarePaginator|Product|null
    {
        try {
            return $this->productRepository->index($request);

        } catch (Exception $exception) {
            Log::error('An error occurred while fetching product-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Store product
     *
     * @param Request $request
     * @return Product
     * @throws Exception
     */
    public function store(Request $request): Product
    {
        try {
            return $this->productRepository->store($request);

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a product-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Update product
     *
     * @param Request $request
     * @param $productId
     * @return Product
     * @throws Exception
     */
    public function update(Request $request, $productId): Product
    {
        try {
            return $this->productRepository->update($request, $productId);

        } catch (Exception $exception) {
            Log::error('An error occurred while updating a product-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Delete product
     *
     * @param $productId
     * @return Product
     * @throws Exception
     */
    public function delete($productId): Product
    {
        try {
            return $this->productRepository->delete($productId);

        } catch (Exception $exception) {
            Log::error('An error occurred while deleting a product-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     * @param array $orderItems
     * @return Collection
     * @throws Exception
     */
    public function getProductDetails(array $orderItems): Collection
    {
        try {
            $productIds = array_column($orderItems, 'product_id');

            return $this->productRepository->getProductDetails($productIds);

        } catch (Exception $exception) {
            Log::error('An error occurred while deleting a product-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Update product quantity
     *
     * @param int $quantity
     * @param $productId
     * @return int
     * @throws Exception
     */
    public function updateProductQuantity(int $quantity, $productId): int
    {
        try {
            return $this->productRepository->updateProductQuantity($quantity, $productId);

        } catch (Exception $exception) {
            Log::error('An error occurred while updating product quantity-(service): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }
}
