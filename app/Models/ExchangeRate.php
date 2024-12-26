<?php

namespace App\Models;

use Date;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string uuid
 * @property string currency_id
 * @property Date date
 * @property float rate
 * @property boolean is_confirmed
 * @property boolean status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 */
class ExchangeRate extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;


    /**
     * Llave primaria
     * @var string
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * Datos para almacenar
     * @var array
     */
    protected $fillable = [
        'currency_id',
        'exchange_rate',
        'date',
        'rate',
        'is_confirmed',
        'status'
    ];


    /**
     * Formatear
     * @var string[]
     */
    protected $casts = [
        'is_confirmed' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

}
