<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;


/**
 * @property string $uuid
 * @property string $purchase_receipt_uuid
 * @property string $purchase_item_uuid
 * @property string $product_uuid
 * @property numeric $cost
 * @property numeric $quantity_expected
 * @property numeric $quantity_received
 * @property string $tax_uuid
 * @property string $warehouse_uuid
 * @property numeric $tax_rate
 * @property numeric $tax_amount
 * @property numeric $discount
 * @property numeric $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\PurchaseReceipts|null $purchaseReceipts
 * @property-read \App\Models\Tax|null $tax
 * @property-read \App\Models\Warehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem wherePurchaseItemUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem wherePurchaseReceiptUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereQuantityExpected($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereQuantityReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereTaxUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem whereWarehouseUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PurchaseReceiptsItem withoutTrashed()
 * @mixin \Eloquent
 */
class PurchaseReceiptsItem extends Model
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
        'purchase_receipts_uuid',
        'purchase_item_uuid',
        'product_uuid',
        'cost',
        'quantity_expected',
        'quantity_received',
        'tax_uuid',
        'warehouse_uuid',
        'tax_rate',
        'tax_amount',
        'discount',
        'amount',
        'purchase_receipt_uuid',
        'quantity_d'
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
    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class, 'tax_uuid','uuid');
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
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid','uuid');
    }

    /**
     * @return BelongsTo
     */
    public function purchaseReceipts(): BelongsTo
    {
        return $this->belongsTo(PurchaseReceipts::class, 'purchase_receipt_uuid','uuid');
    }
}
