<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'stock',
        'qty_on_hand',
        'avg_cost',
        'committed',
        'min_stock',
        'max_stock',
        'rack',
        'bin',
        'zone',
        'expire_date',
        'system_stock',
        'difference',
        'description',
        'on_order_qty'
    ];
}
