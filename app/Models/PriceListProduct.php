<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceListProduct extends Model
{
    use SoftDeletes;


    /**
     * @var string[]
     */
    protected $fillable = [
        'product_uuid',
        'price_list_uuid',
        'price',
        'min_price',
        'promotional_price'
    ];

    /**
     * @return BelongsTo
     */
    public function productUuid(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid');
    }

    /**
     * @return BelongsTo
     */
    public function priceListUuid(): BelongsTo
    {
        return $this->belongsTo(PriceList::class, 'price_list_uuid');
    }

    /**
     * @return BelongsTo
     */
    public function warehouseUuid(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid');
    }
}
