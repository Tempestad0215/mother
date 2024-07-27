<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProSupRes extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'unit' => $this->unit,
            'stock' => $this->stock,
            'cost' => $this->cost,
            'sku' => $this->sku,
            'bar_code' => $this->barcode,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'brand' => $this->brand,
            'price' => $this->price,
            'tax_rate' => $this->tax_rate,
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name
            ],
            'supplier' => [
                'id' => $this->supplier->id,
                'name' => $this->supplier->company_name,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
