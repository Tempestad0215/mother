<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Tax;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Date;

/** @see \App\Models\PurchaseItem */

/**
 * @property int $id
 * @property int $product_id
 * @property int $purchase_id
 * @property int $tax_id
 * @property int $warehouse_id
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
 * @property-read Tax $taxR
 * @property-read Warehouse $warehouse
 */
class PurchaseItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {


        dd($this->taxR);
        $tax = bcadd(1, $this->tax->rate,2);
        $tax_amount = bcmul($tax, $this->cost);
        return [
            ...parent::toArray($request),
            'product_name' => $this->product?->name,
            'tax_rate' => $this->tax?->rate ?? $this->product->tax_rate ?? 0,
            'warehouse_name' => $this->warehouse?->name,
            'tax_amount' => (float)$tax_amount
        ];
    }
}
