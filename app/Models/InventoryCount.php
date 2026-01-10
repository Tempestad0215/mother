<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryCount extends Model
{
    //

    protected $fillable = [
        'product_id',
        'current_stock',
        'system_stock',
        'difference',
        'description'
    ];
}
