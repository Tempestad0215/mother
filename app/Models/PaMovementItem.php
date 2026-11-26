<?php

namespace App\Models;

use App\Traits\HasCode;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $code
 * @property string $product_uuid
 * @property string $tax_uuid
 * @property string $warehouse_uuid
 * @property numeric $cost
 * @property numeric $quantity
 * @property numeric $tax
 * @property numeric $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem searchByCode($search)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereTaxUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem whereWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PaMovementItem withoutTrashed()
 * @mixin \Eloquent
 */
class PaMovementItem extends Model
{
    use HasUuids, SoftDeletes;
    use HasCode;
    use LogsActivity;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'product_uuid',
        'warehouse_uuid',
        'cost',
        'quantity',
        'tax',
        'tax_uuid',
        'amount',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }
}
