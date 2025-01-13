<?php

namespace App\Models;

use App\Enums\INTYEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
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



}
