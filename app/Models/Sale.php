<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $code
 * @property string $client_name
 * @property int $client_id
 * @property array $info
 * @property float $discount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property string $comment
 * @property bool $close_table
 */


class Sale extends Model
{
    use HasFactory;


    // La tabla que se ve a utilizar
    protected $table = 'sales';


    // Datos para actualizar masivamente
    protected $fillable = [
        'code',
        'client_name',
        'client_id',
        'info',
        'discount',
        'tax',
        'sub_total',
        'amount',
        'status',
        'comment',
        'close_table'
    ];


    //Formatear los datos
    protected  $casts = [
        'status' => 'boolean',
        'info' => 'array',
        'close_table' => 'boolean'
    ];




    /**
     * @return void
     */
    protected static function boot()
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo en todo
        static::creating(function ($sale) {
            $sale->code = self::generateCode();
        });
    }



    /**
     * @return string
     */
    // funcion para generar el codigo
    private static function generateCode()
    {
        // Obtener el ultimo registros
        $last = self::orderBy('id','desc')->first();

        // Generar el proximo ID
        $nextID = $last ? $last->id + 1 : 1;

        // Devolver los datos
        $code = config('setting.saleCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }

}
