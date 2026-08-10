<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 *
 * @property string $uuid
 * @property string $warehouse_uuid
 * @property string $product_uuid
 * @property string $type
 * @property string $concept
 * @property int $quantity
 * @property float $cost
 * @property float $stock_before
 * @property float $stock_after
 * @property string $inventoryable_type
 * @property string $inventoryable_id
 *
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 */
#[ObservedBy([InventoryMovementObserver::class])]
class InventoryMovement extends Model
{
    //

    use softDeletes;
    use HasUuids;


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
