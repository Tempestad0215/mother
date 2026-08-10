<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string purchase_receipts_uuid
 * @property string purchase_item_uuid
 * @property string product_uuid
 * @property float cost
 * @property float quantity_expected
 * @property float quantity_received
 * @property string tax_uuid
 * @property string warehouse_uuid
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
    use HasUuids;

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
        'purchase_receipt_uuid'
    ];

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
