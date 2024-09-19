<?php

namespace App\Http\Resources;

use App\Enums\ProductTransType;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int $id
 * @property string $code
 * @property Sale $sale
 * @property Product $product
 * @property float $stock
 * @property float $tax
 * @property float $amount
 * @property float $price
 * @property float $discount
 * @property float $discount_amount
 * @property ProductTransType $type
 * @property boolean $status
 * @property string $created_at
 * @property string $updated_at
 */

class ProductTransResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'sale_id' => $this->sale ? $this->sale->id : 0,
            'product_id' => $this->product->id,
            'product_code' => $this->product->code,
            'product_name' => $this->product->name,
            'cost' => $this->product->cost,
            'tax_rate' => $this->product->tax_rate,
            'stock' => $this->stock,
            'price' => $this->price,
            'discount' => $this->discount,
            'tax' => $this->tax,
            'amount' => $this->amount,
            'discount_amount' => $this->discount_amount,
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
