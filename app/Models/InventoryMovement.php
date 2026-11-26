<?php

namespace App\Models;


use App\Observers\InventoryMovementObserver;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $product_uuid
 * @property string $warehouse_uuid
 * @property string $type
 * @property string $concept
 * @property numeric $quantity
 * @property numeric $cost
 * @property numeric $stock_before
 * @property numeric $stock_after
 * @property string $inventoryable_type
 * @property string $inventoryable_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read Model|\Eloquent $iventoryable
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereConcept($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereInventoryableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereInventoryableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereStockAfter($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereStockBefore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement whereWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|InventoryMovement withoutTrashed()
 * @mixin \Eloquent
 */
#[ObservedBy([InventoryMovementObserver::class])]
class InventoryMovement extends Model
{
    //

    use softDeletes;
    use HasUuids;
    use LogsActivity;


    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';

    //  Almacenar los datos
    protected $fillable = [
        'type',
        'warehouse_uuid',
        'product_uuid',
        'type',
        'concept',
        'quantity',
        'cost',
        'stock_before',
        'stock_after',
        'description',
        'inventoryable_type',
        'inventoryable_id',
        'price',
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function iventoryable():MorphTo
    {
        return $this->morphTo();
    }


    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
        ];
    }





}
