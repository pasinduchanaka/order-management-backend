<?php

namespace App\Repository;

use App\Models\Product;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProductRepository
{
    protected Product $product;

    /**
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Get all products
     *
     * @param $search
     * @param string $paginate
     * @return LengthAwarePaginator|Product|Collection|null
     * @throws Exception
     */
    public function index($search = null, string $paginate = "true"): LengthAwarePaginator|Product|Collection|null
    {
        try {
            if ($search && $paginate) {
                $query = $this->product::query();
                foreach ($search as $filterKey => $filterValue) {
                    switch ($filterKey) {
                        case 'name':
                            if ($filterValue != null) {
                                $query->where('name', 'like', '%' . $filterValue . '%');
                            }
                            break;
                        case 'status':
                            if ($filterValue != null) {
                                $query->where('status', $filterValue);
                            }
                            break;
                    }
                }

                $products = $query
                    ->orderBy('id', 'DESC')
                    ->paginate(5);

            } else if ($paginate == "false") {
                $products = $this->product::orderBy('id', 'DeSC')
                    ->get();

            } else {
                $products = $this->product::orderBy('id', 'DeSC')
                    ->paginate(5);
            }

            return $products;

        } catch (Exception $exception) {
            Log::error('An error occurred while fetching products-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

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
            $product = new $this->product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock_quantity = $request->stock_quantity;
            $product->status = $this->product::ACTIVE;
            $product->save();

            return $product;

        } catch (Exception $exception) {
            Log::error('An error occurred while creating a product-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

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
            $product = $this->product::findOrFail($productId);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->stock_quantity = $request->stock_quantity;
            $product->status = $request->status;
            $product->save();

            return $product;

        } catch (Exception $exception) {
            Log::error('An error occurred while updating a product-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

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
            $product = $this->product::findOrFail($productId);
            $product->delete();

            return $product;

        } catch (Exception $exception) {
            Log::error('An error occurred while deleting a product-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.23
     * Summary: Get product details by product ids
     *
     * @param array $productIds
     * @return Collection
     * @throws Exception
     */
    public function getProductDetails(array $productIds): Collection
    {
        try {
            return $this->product::whereIn('id', $productIds)->get();

        } catch (Exception $exception) {
            Log::error('An error occurred while retrieving a product-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

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
            return $this->product::where('id', '=', $productId)
                ->decrement('stock_quantity', $quantity);

        } catch (Exception $exception) {
            Log::error('An error occurred while updating product quantity-(repository): ' . $exception->getMessage() . ' (Line: ' . $exception->getLine() . ')');

            throw $exception;
        }
    }
}
