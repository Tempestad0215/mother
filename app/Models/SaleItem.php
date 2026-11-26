<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $product_uuid
 * @property string $sale_uuid
 * @property numeric $stock
 * @property numeric $price
 * @property numeric $tax_rate
 * @property string $price_type
 * @property string $tax_uuid
 * @property numeric|null $discount
 * @property numeric|null $discount_amount
 * @property numeric|null $reserved
 * @property numeric $amount
 * @property bool $is_service
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $warehouse_uuid
 * @property-read \App\Models\Sale|null $Sale
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static Builder<static>|SaleItem newModelQuery()
 * @method static Builder<static>|SaleItem newQuery()
 * @method static Builder<static>|SaleItem onlyTrashed()
 * @method static Builder<static>|SaleItem query()
 * @method static Builder<static>|SaleItem whereAmount($value)
 * @method static Builder<static>|SaleItem whereCreatedAt($value)
 * @method static Builder<static>|SaleItem whereDeletedAt($value)
 * @method static Builder<static>|SaleItem whereDiscount($value)
 * @method static Builder<static>|SaleItem whereDiscountAmount($value)
 * @method static Builder<static>|SaleItem whereIsService($value)
 * @method static Builder<static>|SaleItem wherePrice($value)
 * @method static Builder<static>|SaleItem wherePriceType($value)
 * @method static Builder<static>|SaleItem whereProductUuid($value)
 * @method static Builder<static>|SaleItem whereReserved($value)
 * @method static Builder<static>|SaleItem whereSaleUuid($value)
 * @method static Builder<static>|SaleItem whereStock($value)
 * @method static Builder<static>|SaleItem whereTaxRate($value)
 * @method static Builder<static>|SaleItem whereTaxUuid($value)
 * @method static Builder<static>|SaleItem whereUpdatedAt($value)
 * @method static Builder<static>|SaleItem whereUuid($value)
 * @method static Builder<static>|SaleItem whereWarehouseUuid($value)
 * @method static Builder<static>|SaleItem withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|SaleItem withoutTrashed()
 * @mixin \Eloquent
 */
class SaleItem extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;

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
        'price_type',
        'is_service',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

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

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid', 'uuid');
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
