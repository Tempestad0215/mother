<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property Product $product
 * @mixin SaleItem
 */
class SaleItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'price_type' => $this->price_type,
            'temp_price' => $this->price,
            'product_name' => $this->product?->name,
            'warehouse_uuid' => $this->product?->default_warehouse,
            'tax_uuid' => $this->product?->tax_uuid,
            'price_list' => $this->product?->default_price_list
        ];
    }
}
