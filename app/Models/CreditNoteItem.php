<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;


/**
 * @property string $uuid
 * @property string $product_uuid
 * @property string $warehouse_uuid
 * @property float $quantity
 * @property float $price
 * @property float $sub_total
 * @property float $tax
 * @property float $amount
 *
 * @property-read CreditNote $creditNote
 */

#[ObservedBy([CreditNoteItemObserver::class])]
class CreditNoteItem extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;

    protected $table = 'credit_note_items';

    // Datos para actualizar masivamente
    protected $primaryKey = 'uuid';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'credit_note_uuid',
        'quantity',
        'price',
        'sub_total',
        'tax',
        'amount',
        'product_uuid',
        'warehouse_uuid',
    ];


    /**
     * @return BelongsTo
     */
    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }

    /**
     * @return BelongsTo
     */
    public function warehouse():BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_uuid', 'uuid');
    }

}
