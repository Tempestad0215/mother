<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int purchase_receipts_id
 * @property int purchase_item_id
 * @property int product_id
 * @property float cost
 * @property float quantity_expected
 * @property float quantity_received
 * @property int tax_id
 * @property int warehouse_id
 * @property float tax_rate
 * @property float tax_amount
 * @property float discount
 * @property float amount
 *
 *
 *
 * @property-read Warehouse warehouse
 * @property-read Tax tax
 * @property-read PurchaseReceipts purchaseReceipts
 * @property-read Product product
 */
class PurchaseReceiptsItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'purchase_receipts_id',
        'purchase_item_id',
        'product_id',
        'cost',
        'quantity_expected',
        'quantity_received',
        'tax_id',
        'warehouse_id',
        'tax_rate',
        'tax_amount',
        'discount',
        'amount'
    ];

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function purchaseReceipts(): BelongsTo
    {
        return $this->belongsTo(PurchaseReceipts::class);
    }
}
