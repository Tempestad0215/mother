<?php

namespace App\Models;

use App\Enums\ProductReservationEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReservation extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_id',
        'sale_id',
        'warehouse_id',
        'quantity',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => ProductReservationEnum::class,
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }
}
