<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $id
 * @property string $content
 * @property int $commentable_id
 * @property string $commentable_type
 * @property string $created_at
 * @property string $updated_at
 * @property Date $deleted_at
 */
class Comment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


    protected $fillable = [
        'content'
    ];

    //Formatear los datos

    /**
     * convertir la iniciar a mayuscula
     * @return Attribute
     */
    protected function content():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords($value),
        );
    }

    protected function createdAt():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s')
        );
    }




    /**
     * Relacion polimorfica
     * @return MorphTo
     */
    public function commentable():morphTo
    {
        return $this->morphTo();
    }


    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo
        static::creating(function ($client) {
            $client->code = self::generateCode();
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
        $nextID = $last ? $last->id + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.coCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }

}
