<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $uuid
 * @property string $product_uuid
 * @property string $tax_uuid
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

class SaleItem extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;

    // Corregido: Era primaryKey con 'a'
    protected $primaryKey = 'uuid'; 
    
    protected $keyType = 'string';
    
    public $incrementing = false;



    /**
     * 
     * 
     * @var array
     */
    protected $fillable = [
        'sale_uuid',
        'product_uuid',
        'stock',
        'price',
        'tax_rate',
        'discount',
        'discount_amount',
        'reserved',
        'amount',
        'is_service',
    ];

    /**
     * Summary of Sale
     * @return BelongsTo<Sale, SaleItem>
     */
    public function Sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_uuid', 'uuid');
    }

    /**
     * Summary of product
     * @return BelongsTo<Product, SaleItem>
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }

    


    /**
     * 
     * @return array{is_service: string}
     */
    protected function casts(): array
    {
        return [
            'is_service' => 'boolean',
        ];
    }
}
