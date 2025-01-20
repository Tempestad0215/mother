<?php

namespace App\Models;

use App\Enums\INTYEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int id
 * @property INTYEnum type
 * @property float quantity
 * @property float cost
 * @property string description
 * @property Carbon date
 * @property boolean status
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
        'status'
    ];


    /**
     * @return string[]
     */
    public function casts(): array
    {
        return [
            'status' => 'boolean',
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
