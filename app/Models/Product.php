<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    public const ACTIVE = 1;
    public const DEACTIVE = 0;

    use SoftDeletes;

    /**
     *
     * Created by: Pasindu Chanaka
     * Created date: 2024.10.22
     * Summary: Get the order items associated with the product
     *
     * @return HasMany
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
