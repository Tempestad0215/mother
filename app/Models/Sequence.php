<?php

namespace App\Models;

use App\Enums\SequenceTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $id
 * @property string $code
 * @property SequenceTypeEnum $type
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

    //Tabla a utilizar
    protected $table = 'sequences';


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
        'deleted_at'
    ];

    protected $casts = [
        'type' => SequenceTypeEnum::class,
    ];




    /*
     * Relaciones
     */

    //Comentario
    public function comment():MorphOne
    {
        //Retornar la relacion polimorfica
        return $this->morphOne(Comment::class, 'commentable');
    }



    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo en
        static::creating(function ($category) {
            $category->code = self::generateCode();
        });
    }



    /**
     * @return string
     */

    // funcion para generar el codigo
    private static function generateCode():string
    {
        // Obtener el ultimo registros
        $last = self::orderBy('id','desc')->first();

        // Generar el proximo ID
        $nextID = $last ? $last->id +1 : 1;

        // Devolver los datos
        $code = config('appconfig.seqCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}
