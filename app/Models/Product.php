<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed|true $status
 * @property string $name
 * @property null|string $description
 * @property string $unit
 * @property float $stock
 * @property float $cost
 * @property float $price
 * @property  int $supplier_id
 * @property string $created_at
 * @property string $updated_at
 */
class Product extends Model
{
    use HasFactory;


    /**
     * @var mixed|true
     */

    protected $fillable = [
        'name',
        'description',
        'unit',
        'stock',
        'cost',
        'price',
        'supplier_id',
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

    //Formatear los datos
    protected function price():Attribute
    {
        return Attribute::make(
            get: fn(float $value) => number_format($value,2)
        );
    }

    protected function  cost():Attribute
    {
        return Attribute::make(
            get: fn(float $value) => number_format($value,2)
        );
    }

    protected function  stock():Attribute
    {
        return Attribute::make(
            get: fn(float $value) => number_format($value,2)
        );
    }



    // Relaciones


    public function supplier():BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

}
