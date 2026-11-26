<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $product_uuid
 * @property string $price_list_uuid
 * @property numeric $price
 * @property numeric|null $min_price
 * @property numeric|null $promotional_price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\PriceList|null $priceListUuid
 * @property-read \App\Models\Product|null $productUuid
 * @property-read \App\Models\Warehouse|null $warehouseUuid
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct wherePriceListUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct wherePromotionalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PriceListProduct withoutTrashed()
 * @mixin \Eloquent
 */
class PriceListProduct extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'price_list_products';


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


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

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
