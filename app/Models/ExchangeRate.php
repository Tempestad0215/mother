<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int id
 * @property array rate_info
 * @property int month
 * @property int year
 * @property boolean status
 * @property string created_at
 * @property string updated_at
 * @property string deleted_at
 *
 */
class ExchangeRate extends Model
{
    use SoftDeletes;

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
        $total = self::count();

        // Generar el proximo ID
        $nextID = $total ? $total + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.exchange');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }


}
