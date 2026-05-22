<?php

namespace App\Models;

use App\Enums\InventoryMovementConceptEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int id
 * @property InventoryMovementConceptEnum type
 * @property float quantity
 * @property float cost
 * @property string description
 * @property Carbon date
 * @property boolean status
 * @property boolean was_updated
 * @property Product product
 * @property Carbon deleted_at
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class InventoryMovement extends Model implements  Auditable
{
    //

    use \OwenIt\Auditing\Auditable;
    use softDeletes;
    use HasUuids;

    //  Almacenar los datos
    protected $fillable = [
        'type',
        'warehouse_id',
        'movementable_id',
        'movementable_type',
        'movementable_line_id',
        'movementable_code',
        'quantity',
        'cost',
        'stock_before',
        'stock_after',
        'description',
    ];


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
