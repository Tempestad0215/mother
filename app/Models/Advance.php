<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $client_id
 * @property float $amount
 * @property string $date
 * @property string $expire
 * @property float $balance
 * @property float $consumed
 * @property boolean $status
 * @property Date $deleted_at
 */

class Advance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


    /**
     * @var string[]
     */
    protected $fillable = [
        'client_id',
        'amount',
        'date',
        'expire',
        'balance',
        'consumed',
        'status'
    ];


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
        $code = config('appconfig.advCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}
