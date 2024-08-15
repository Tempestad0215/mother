<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * @property boolean $status
 * @property string $code
 * @property string $name
 * @property null|string $description
 * @property string $unit
 * @property float $stock
 * @property float $reserved
 * @property float $cost
 * @property float $price
 * @property string $sku
 * @property string $bar_code
 * @property float $weight
 * @property string $dimensions
 * @property string $brand
 * @property float $discount
 * @property float $tax_rate
 * @property string $comment
 * @property bool $close_table
 * @property  int $supplier_id
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @method static create(mixed $validated)
 */
class Product extends Model
{
    use HasFactory;


    /**
     * Datos para guardar automatico
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'unit',
        'stock',
        'reserved',
        'cost',
        'price',
        'supplier_id',
        'category_id',
        'sku',
        'bar_code',
        'weight',
        'dimensions',
        'brand',
        'discount',
        'tax_rate',
        'status',
        'comment',
        'close_table'
    ];


    /**
     * Ocultar los datos
     * @var string[]
     */
    protected $hidden = [
        'status',
        'created_at',
        'update_at'
    ];


    /**
     * Formatear los datos
     * @var string[]
     */
    protected $casts = [
        'status' => 'boolean',
        'close_table' => 'boolean',
    ];




//    protected function price():Attribute
//    {
//        return Attribute::make(
//            get: fn(float $value) => number_format($value,2)
//        );
//    }
//
//    protected function  cost():Attribute
//    {
//        return Attribute::make(
//            get: fn(float $value) => number_format($value,2)
//        );
//    }
//
//    protected function  stock():Attribute
//    {
//        return Attribute::make(
//            get: fn(float $value) => number_format($value,2)
//        );
//    }
//
//    protected function  weight():Attribute
//    {
//        return Attribute::make(
//            get: fn(float $value) => number_format($value,2)
//        );
//    }



    // Relaciones


    /**
     * @return BelongsTo
     */
    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }


    /**
     * @return void
     */
    protected static function boot()
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo en todo
        static::creating(function ($product) {
            $product->code = self::generateCode();
        });
    }



    /**
     * @return string
     */
    private static function generateCode()
    {

        //codigo de producto
        $code = config('Setting.proCode');

        // Sacar el ultimo producto
        $lastProduct = DB::table('products')->latest('id')->first();

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
