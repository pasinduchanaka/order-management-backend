<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.22
     * Summary: Get the order associated with the order item
     *
     * @return BelongsTo
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.22
     * Summary: Get the products associated with the order item
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
