<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property boolean $status
 * @property string $name
 * @property null|string $description
 * @property string $unit
 * @property float $stock
 * @property float $cost
 * @property float $price
 * @property string $sku
 * @property string $bar_code
 * @property float $weight
 * @property string $dimensions
 * @property string $brand
 * @property float $discount
 * @property float $tax_rate
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



    protected $fillable = [
        'name',
        'description',
        'unit',
        'stock',
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
        'status'
    ];


    protected $hidden = [
        'status',
        'created_at',
        'update_at'
    ];


    protected $casts = [
        'status' => 'boolean'
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

}
