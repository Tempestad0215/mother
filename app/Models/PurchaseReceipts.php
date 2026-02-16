<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property-read InventoryMovement[] $itemMovements
 * @property-read Supplier $supplier
 * @property-read PurchaseReceiptsItem[] $items
 */



class PurchaseReceipts extends Model
{
    use SoftDeletes;


    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'user_id',
        'doc_date',
        'tax',
        'discount',
        'sub_total',
        'amount',
        'status',
        'comment'
    ];


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseReceiptsItem::class);
    }

    public function itemMovements(): MorphMany
    {
        return $this->morphMany(InventoryMovement::class, 'movementable');
    }
}
