<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceListProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_uuid',
        'price_list_uuid',
        'price',
        'min_price',
        'promotional_price'
    ];

    public function productUuid(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid');
    }

    public function priceListUuid(): BelongsTo
    {
        return $this->belongsTo(PriceList::class, 'price_list_uuid');
    }

    public function warehouseUuid(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid');
    }
}
