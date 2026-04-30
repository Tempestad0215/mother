<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'product_id',
        'warehouse_id',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'uuid' => 'string',
            'product_id' => 'string',
            'warehouse_id' => 'string',
            'is_active' => 'boolean',
        ];
    }
}
