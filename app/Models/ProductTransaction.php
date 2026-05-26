<?php

namespace App\Models;

use App\Enums\ProductTransactionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $uuid
 * @property string $code
 * @property string $product_uuid
 * @property string $sale_uuid
 * @property string $credit_note_uuid
 * @property float $quantity
 * @property float $price
 * @property float $cost
 * @property float $discount
 * @property float $discount_amount
 * @property float $tax_rate
 * @property float $tax
 * @property float $tax_amount
 * @property float $min_price
 * @property float $special_price
 * @property float $subtotal
 * @property float $amount
 * @property bool $status
 * @property ProductTransactionTypeEnum $type
 * @property float $reserved_quantity
 * @property string $product_name
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon|null $deleted_at
 * @property-read Product $product
 * @property-read Sale $sale
 * @property-read CreditNote $creditNote
 */
class ProductTransaction extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

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
        'special_price',
        'subtotal',
        'amount',
        'status',
        'type',
        'reserved_quantity',
        'product_name',
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
        'special_price' => 'decimal:6',
        'subtotal' => 'decimal:6',
        'amount' => 'decimal:6',
        'reserved_quantity' => 'decimal:6',
        'status' => 'boolean',
        'type' => ProductTransactionTypeEnum::class,
    ];

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
