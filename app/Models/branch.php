<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 *
 * @property Date $deleted_at
 */

class branch extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo
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
        $code = config('appconfig.catCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}