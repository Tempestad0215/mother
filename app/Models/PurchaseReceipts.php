<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;

/**
 * @property-read InventoryMovement[] $itemMovements
 * @property-read Supplier $supplier
 * @property-read PurchaseReceiptsItem[] $items
 */

class PurchaseReceipts extends Model
{
    use SoftDeletes;
    use HasUuids;
    use LogsActivity;

    /**
     * @var string
     */
    protected $primaryKey = 'uuid';
    public $incrementing = false;
    protected $keyType = 'string';


    /**
     * @var string[]
     */
    protected $fillable = [
        'purchase_uuid',
        'supplier_uuid',
        'user_uuid',
        'doc_date',
        'tax',
        'discount',
        'sub_total',
        'amount',
        'status',
        'comment'
    ];


    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_uuid', 'uuid');
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class, 'purchase_receipt_uuid', 'uuid');
    }

    /**
     * @return MorphMany
     */
    public function itemMovements(): MorphMany
    {
        return $this->morphMany(InventoryMovement::class, 'movementable');
    }
}
