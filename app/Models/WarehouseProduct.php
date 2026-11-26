<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * Summary of WarehouseProduct
 *
 * @property string $product_uuid
 * @property string $warehouse_uuid
 * @property numeric $stock_quantity
 * @property numeric|null $committed
 * @property numeric|null $min_stock
 * @property numeric|null $max_stock
 * @property numeric|null $reorder_level
 * @property string|null $last_counted_at
 * @property numeric|null $purchase_pending
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read float $available_stock
 * @property-read \App\Models\Product|null $products
 * @property-read \App\Models\Warehouse|null $warehouses
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereCommitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereLastCountedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereMaxStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereMinStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct wherePurchasePending($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereReorderLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereStockQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct whereWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WarehouseProduct withoutTrashed()
 * @mixin \Eloquent
 */
class WarehouseProduct extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;


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



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

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
     * Summary of Products
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
