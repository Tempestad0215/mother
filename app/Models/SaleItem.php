<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property int $product_id
 * @property int $tax_id
 * @property float $stock
 * @property float $price
 * @property float $tax_rate
 * @property float $discount
 * @property float $discount_amount
 * @property float $reserved
 * @property float $amount
 * @property boolean $is_service
 *
 *
 *
 * @property-read Product $product
 * @property-read Tax $tax
 *
 * @mixin Builder
 */

class SaleItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sale_id',
        'product_id',
        'stock',
        'price',
        'tax_rate',
        'tax_id',
        'discount',
        'discount_amount',
        'reserved',
        'amount',
        'is_service',
    ];

    public function Sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    protected function casts(): array
    {
        return [
            'is_service' => 'boolean',
        ];
    }
}
