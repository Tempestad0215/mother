<?php

namespace App\Models;

use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Date;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $type
 * @property boolean $inventoried
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
 * @property float $tax_rate
 * @property float  $tax
 * @property float $discount
 * @property float $discount_amount
 * @property float $product_no_tax
 * @property float $benefits
 * @property string $comment
 * @property bool $close_table
 * @property  int $supplier_id
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 * @property Date $deleted_at
 *
 * @method static create(mixed $validated)
 */
class Product extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use softDeletes;


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
        'discount_amount',
        'product_tax',
        'benefits',
        'tax',
        'tax_rate',
        'status',
        'comment',
        'close_table',
        'type',
        'inventoried'
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
        'type' => ProductTypeEnum::class
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

    //Transacciones
    public function trans():HasMany
    {
        return $this->hasMany(ProTrans::class);
    }


    /**
     * @return void
     */
    protected static function boot():void
    {
        // Llamar el metodo principal
        parent::boot();

        //Generar el codigo
        static::creating(function ($product) {
            $product->code = self::generateCode();
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
        $code = config('appconfig.proCode');

        // craer el codigp
        return $code.str_pad($nextID, 6,'0', STR_PAD_LEFT);
    }

}
