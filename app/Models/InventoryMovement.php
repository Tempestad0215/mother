<?php

namespace App\Models;

use App\Enums\InventoryMovementTypeEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int id
 * @property InventoryMovementTypeEnum type
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

    //  Almacenar los datos
    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'cost',
        'description',
        'date',
        'status',
        'was_updated'
    ];


    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'status' => 'boolean',
            'was_updated' => 'boolean',
            'deleted_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
            'created_at' => 'datetime:Y-m-d H:i:s',
        ];
    }


    //Relaciones
    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }



}
