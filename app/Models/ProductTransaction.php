<?php

namespace App\Models;

use App\Enums\ProductTransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

/**
 * @property string $uuid
 * @property string|null $code
 * @property string|null $product_uuid
 * @property int|null $sale_id
 * @property string|null $credit_note_uuid
 * @property numeric $quantity
 * @property numeric $price
 * @property numeric $cost
 * @property numeric $discount
 * @property numeric $discount_amount
 * @property numeric $tax_rate
 * @property numeric $tax
 * @property numeric $tax_amount
 * @property numeric $min_price
 * @property numeric $promotional_price
 * @property numeric $subtotal
 * @property numeric $amount
 * @property bool $status
 * @property ProductTransactionTypeEnum $type
 * @property numeric $reserved_quantity
 * @property string|null $product_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activitiesAsSubject
 * @property-read int|null $activities_as_subject_count
 * @property-read \App\Models\CreditNote|null $creditNote
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Sale|null $sale
 * @method static \Database\Factories\ProductTransactionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereCreditNoteUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereMinPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereProductName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereProductUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction wherePromotionalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereReservedQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereSubtotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereTaxAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereTaxRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductTransaction withoutTrashed()
 * @mixin \Eloquent
 */
class ProductTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'product_transactions';

    protected $primaryKey = 'uuid';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'code',
        'product_uuid',
        'sale_id',
        'credit_note_uuid',
        'quantity',
        'price',
        'cost',
        'discount',
        'discount_amount',
        'tax_rate',
        'tax',
        'tax_amount',
        'min_price',
        'promotional_price',
        'subtotal',
        'amount',
        'status',
        'type',
        'reserved_quantity',
        'product_name',
        'deleted_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:6',
        'price' => 'decimal:6',
        'cost' => 'decimal:6',
        'discount' => 'decimal:6',
        'discount_amount' => 'decimal:6',
        'tax_rate' => 'decimal:6',
        'tax' => 'decimal:6',
        'tax_amount' => 'decimal:6',
        'min_price' => 'decimal:6',
        'promotional_price' => 'decimal:6',
        'subtotal' => 'decimal:6',
        'amount' => 'decimal:6',
        'reserved_quantity' => 'decimal:6',
        'status' => 'boolean',
        'type' => ProductTransactionTypeEnum::class,
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty();
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_uuid', 'uuid');
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'sale_uuid', 'uuid');
    }

    public function creditNote(): BelongsTo
    {
        return $this->belongsTo(CreditNote::class, 'credit_note_uuid', 'uuid');
    }
}
