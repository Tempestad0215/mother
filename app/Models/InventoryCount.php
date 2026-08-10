<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class InventoryCount extends Model
{
    //

    use LogsActivity;

    protected $fillable = [
        'product_id',
        'current_stock',
        'system_stock',
        'difference',
        'description'
    ];
}
