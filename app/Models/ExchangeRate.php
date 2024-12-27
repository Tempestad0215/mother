<?php

namespace App\Models;

use Date;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string uuid
 * @property array rate_info
 * @property string month
 * @property int year
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
        'rate_info',
        'month',
        'year',
        'status'
    ];


    /**
     * Formatear
     * @var string[]
     */
    protected $casts = [
        'rate_info' => 'array',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

}
