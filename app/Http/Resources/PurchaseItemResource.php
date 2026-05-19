<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReceipts;
use App\Models\Tax;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

/** @see \App\Models\PurchaseItem */

/**
 * @property string $uuid
 * @property string $product_uuid
 * @property string $purchase_uuid
 * @property string $tax_uuid
 * @property string $warehouse_uuid
 * @property float $quantity
 * @property float $cost
 * @property float $discount
 * @property float $amount
 * @property float $tax_amount
 * @property Date $created_at
 * @property Date $updated_at
 * @property Date $deleted_at
 *
 * @property-read Product $product
 * @property-read Purchase $purchase
 * @property-read PurchaseReceipts $purchaseReceipts
 * @property-read Tax $taxR
 * @property-read Warehouse $warehouse
 */
class PurchaseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {

        $receipts = PurchaseReceipts::with('items')->get();

        dd($receipts->firstWhere('uuid', $this->purchaseReceipts));

        $tax = bcadd(1, $this->taxR->rate,2);
        $tax_amount = bcmul($tax, $this->cost);
        return [
            ...parent::toArray($request),
            'product_name' => $this->product?->name,
            'tax_rate' => $this->taxR?->rate ?? $this->product->tax_rate ?? 0,
            'warehouse_name' => $this->warehouse?->name,
            'tax_amount' => (float)$tax_amount
        ];
    }
}
