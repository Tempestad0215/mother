<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Summary of WarehouseProduct
 * @property string $uuid
 * @property string $product_uuid
 * @property string $warehouse_uuid
 * @property float $stock_quantity
 * @property float $commited
 * @property float $min_stock
 * @property float $max_stock
 * @property bool $is_active
 * @property float $reorder_level
 * @property float $purchase_pending
 *
 *
 * @property-read Product $product
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class WarehouseProduct extends Model
{
    use SoftDeletes;
    use HasUuids;


    protected $fillable = [
        'committed',
        'warehouse_id',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'is_active',
        'reorder_level',
        'purchase_pending',
        'product_uuid',
        'warehouse_uuid',
    ];

    /**
     * Summary of casts
     * @return array{is_active: string, product_uuid: string, warehouse_uuid: string}
     */
    protected function casts(): array
    {
        return [
            'product_uuid' => 'string',
            'warehouse_uuid' => 'string',
            'is_active' => 'boolean',
        ];
    }


    /**
     * Summary of warehouse
     * @return BelongsTo<Warehouse, WarehouseProduct>
     */
    public function warehouses():BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid', 'uuid');
    }


    /**
     * Summary of products
     * @return BelongsTo<Product, WarehouseProduct>
     */
    public function products():BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }

    /**
     * Summary of getAvailableStockAttribute
     * @return float
     */
    public function getAvailableStockAttribute(): float
    {
        return $this->stock_quantity - ($this->commited ?? 0);
    }

    /**
     * Summary of needsReorder
     * @return bool
     */
    public function needsReorder(): bool
    {
        return $this->getAvailableStockAttribute() <= $this->reorder_level;
    }
}
