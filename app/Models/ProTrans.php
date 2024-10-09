<?php

namespace App\Models;

use App\Enums\ProductTransType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;


/**
 * @property int $id
 * @property string $code
 * @property int $product_id
 * @property string $product_name
 * @property int $sale_id
 * @property float $stock
 * @property float $price
 * @property float $discount
 * @property float $discount_amount
 * @property float $tax_rate
 * @property float $tax
 * @property float $tax_amount
 * @property float $amount
 * @property boolean $status
 * @property ProductTransType $type
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */

class ProTrans extends Model implements Auditable
{
    /**
     *
     */
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;

    protected $table = 'pro_trans';


    /**
     * @var string[]
     */
    protected $fillable = [
        'product_id',
        'product_name',
        'sale_id',
        'stock',
        'price',
        'discount',
        'discount_amount',
        'tax_rate',
        'tax',
        'tax_amount',
        'amount',
        'type',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean',
        'type' => ProductTransType::class
    ];


    //Campo Oculto
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    /*
     * Relacionar los datos
     */

    public function sale():BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }




    /*
     * Fomatear los datos
     */
    /**
     * Formatear la fehca de creacion
     * @return Attribute
     */
    protected function createdAt ():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }

    /**
     * Formataer la fecha de actualizacion
     * @return Attribute
     */
    protected function updatedAt ():Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
            set: fn (string $value) => Carbon::parse($value)->format('Y-m-d H:i:s'),
        );
    }



    /**
     * Relaciones
     */
    public function product():belongsTo
    {
        return $this->belongsTo(Product::class);
    }







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
    private static function generateCode():string
    {
        // Obtener el ultimo registros
        $last = self::orderBy('id','desc')->first();

        // Generar el proximo ID
        $nextID = $last ? $last->id + 1 : 1;

        // Devolver los datos
        $code = config('appconfig.transCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }
}
