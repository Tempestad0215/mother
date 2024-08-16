<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


/**
 * @property string code
 * @property int product_id
 * @property int sale_id
 * @property int stock
 * @property int price
 * @property int discount
 * @property int tax
 * @property int amount
 */

class ProTrans extends Model
{
    /**
     *
     */
    use HasFactory;


    /**
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'sale_id',
        'stock',
        'price',
        'discount',
        'tax',
        'amount',
        'type'
    ];


    /**
     * @return void
     */
    protected static function boot():void
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
        $code = config('setting.transCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}
