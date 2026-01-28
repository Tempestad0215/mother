<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
