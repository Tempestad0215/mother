<?php

namespace App\Models;

use App\Enums\ProductReservationEnum;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $product_uuid
 * @property string $sale_uuid
 * @property string $warehouse_uuid
 * @property numeric $quantity
 * @property ProductReservationEnum $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Sale|null $sale
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static Builder<static>|ProductReservation newModelQuery()
 * @method static Builder<static>|ProductReservation newQuery()
 * @method static Builder<static>|ProductReservation onlyTrashed()
 * @method static Builder<static>|ProductReservation query()
 * @method static Builder<static>|ProductReservation whereCreatedAt($value)
 * @method static Builder<static>|ProductReservation whereDeletedAt($value)
 * @method static Builder<static>|ProductReservation whereProductUuid($value)
 * @method static Builder<static>|ProductReservation whereQuantity($value)
 * @method static Builder<static>|ProductReservation whereSaleUuid($value)
 * @method static Builder<static>|ProductReservation whereStatus($value)
 * @method static Builder<static>|ProductReservation whereUpdatedAt($value)
 * @method static Builder<static>|ProductReservation whereUuid($value)
 * @method static Builder<static>|ProductReservation whereWarehouseUuid($value)
 * @method static Builder<static>|ProductReservation withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|ProductReservation withoutTrashed()
 * @mixin \Eloquent
 */
class ProductReservation extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'product_id',
        'sale_id',
        'warehouse_id',
        'quantity',
        'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    protected function casts(): array
    {
        return [
            'status' => ProductReservationEnum::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
