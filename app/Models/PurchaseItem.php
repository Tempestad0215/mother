<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

class PurchaseItem extends Model
{

    use SoftDeletes;
    use HasUuids;
    use LogsActivity;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'product_uuid',
        'purchase_uuid',
        'quantity',
        'cost',
        'discount',
        'amount',
        'tax',
        'tax_uuid',
        'tax_amount',
        'warehouse_uuid'
    ];


    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * @return BelongsTo
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid','uuid');
    }

    /**
     * @return BelongsTo
     */
    public function taxR():BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_uuid', 'uuid');
    }
}
