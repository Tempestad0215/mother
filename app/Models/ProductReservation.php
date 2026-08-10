<?php

namespace App\Models;

use App\Enums\ProductReservationEnum;
use Barryvdh\LaravelIdeHelper\Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;


/**
 * @property int $product_id
 * @property int $sale_id
 * @property int $warehouse_id
 * @property float $quantity
 * @property ProductReservationEnum $status
 *
 *
 * @property-read Product $product
 * @property-read Sale $sale
 * @property-read Warehouse $warehouse
 *
 *
 * @mixin Builder
 */
class ProductReservation extends Model
{
    use SoftDeletes;
    use LogsActivity;

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
