<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $client_name
 * @property int $client_id
 * @property array $info
 * @property float $discount
 * @property float $tax
 * @property float $sub_total
 * @property float $total
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
        'total',
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
    private static function generateCode()
    {

        //codigo de producto
        $code = config('Setting.saleCode');

        // Sacar el ultimo producto
        $lastProduct = DB::table('sales')->latest('id')->first();

        //Verificar si existe
        if($lastProduct){
            //Extraer el numero secuencial
            $lastNumber = (int)substr($lastProduct->code, 3);
            $newNumber = str_pad(++$lastNumber, 8, "0", STR_PAD_LEFT);
        }else{
            $newNumber = '00000001';
        }

        // Generar el nuevo codigo
        return $code . $newNumber;
    }

}
