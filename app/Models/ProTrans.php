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
    private static function generateCode():string
    {

        //codigo de producto
        $code = config('Setting.transCode');

        // Sacar el ultimo producto
        $lastProduct = DB::table('pro_trans')->latest('id')->first();

        //Verificar si existe
        if($lastProduct){
            //Extraer el numero secuencial
            $lastNumber = (int)substr($lastProduct->code, 3);
            $newNumber = str_pad(++$lastNumber, 8, "0", STR_PAD_LEFT);
        }else{
            $newNumber = '000001';
        }

        // Generar el nuevo codigo
        return $code . $newNumber;
    }
}
