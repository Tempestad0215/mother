<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $id
 * @property string $code
 * @property Sale $sale_id
 * @property array $info
 * @property float $discount_amount
 * @property float $tax
 * @property float $sub_total
 * @property float $amount
 * @property boolean $status
 * @property bool $close_table
 */
class DeletedSale extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    //Tabla
    protected $table = 'deleted_sales';


    //Datos para Guardar

    protected  $fillable = [
        'code',
        'sale_id',
        'info',
        'discount_amount',
        'tax',
        'sub_total',
        'amount',
        'status',
        'close_table'
    ];


    protected $casts = [
        'info' => 'json'
    ];





    /*
     * Relaciones
     */

    //Comentario
    public function comment():MorphOne
    {
        return $this->morphOne(Comment::class, 'commentable');
    }





    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo
        static::creating(function ($sale) {
            $sale->code = self::generateCode();
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
        $code = config('appconfig.deSale');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}
