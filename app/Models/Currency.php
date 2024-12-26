<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string uuid
 * @property string code
 * @property string name
 * @property string symbol
 * @property boolean is_base
 * @property boolean status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 */
class Currency extends Model implements Auditable
{
    use SoftDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;


    //Tipo de llave
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * Para guardar los datos
     * @var array
     */
    protected $fillable = [
        'code',
        'name',
        'symbol',
        'is_base',
        'status'
    ];


    /**
     * Formatear los datos
     * @var string[]
     */
    protected $casts = [
        'is_base' => 'boolean',
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Datos ocultos
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
