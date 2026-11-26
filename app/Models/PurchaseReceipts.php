<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string $supplier_uuid
 * @property string $purchase_uuid
 * @property string $account_co_uuid
 * @property string $user_uuid
 * @property string|null $doc_date
 * @property numeric $tax
 * @property numeric $discount
 * @property numeric $sub_total
 * @property numeric $amount
 * @property string $status
 * @property string|null $comment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\InventoryMovement> $itemMovements
 * @property-read int|null $item_movements_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PurchaseReceiptsItem> $items
 * @property-read int|null $items_count
 * @property-read \App\Models\Supplier|null $supplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereAccountCoUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereDocDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts wherePurchaseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereSupplierUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereUserUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceipts withoutTrashed()
 * @mixin \Eloquent
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

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
