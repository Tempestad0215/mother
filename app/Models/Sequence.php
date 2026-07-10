<?php

namespace App\Models;

use App\Enums\SequenceSaleTypeEnum;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property string $uuid
 * @property string $code
 * @property SequenceSaleTypeEnum $type
 * @property int $from
 * @property int $next
 * @property int $to
 * @property int $advise
 * @property string $num_request
 * @property string $num_authorization
 * @property Date $date_request
 * @property Date $date_expire
 * @property boolean $status
 * @property Date $deleted_at
 */

class Sequence extends Model implements Auditable
{

    use softDeletes;
    use \OwenIt\Auditing\Auditable;
    use HasUuids;

    //Tabla a utilizar
    protected $table = 'sequences';
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    //Datos a llenar masivamente
    protected $fillable = [
        'code',
        'type',
        'from',
        'next',
        'to',
        'advise',
        'num_request',
        'num_authorization',
        'date_request',
        'date_expire',
        'status',
        'deleted_at',
        'uuid'
    ];

    /**
     * @var \class-string[]
     */
    protected $casts = [
        'type' => SequenceSaleTypeEnum::class,
    ];


    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo los codigos
        static::creating(function ($model) {
            $model->code = self::generateCode();
        });
    }


    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode():string
    {
        // Obtener el ultimo registros
        $total = self::withTrashed()->where('status', true)->count();


        // Generar el proximo ID
        $nextID = $total ? $total + 1 : 1;

        // Devolver los datos
        $code = Str::upper(config('appconfig.seqCode'));


        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
