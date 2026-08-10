<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class Inventory extends Model
{
    use SoftDeletes;
    use LogsActivity;

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


    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
