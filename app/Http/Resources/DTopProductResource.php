<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property float $total_qty
 * @mixin Product
 */
class DTopProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,
            'cost' => (float) $this->cost,
            // 🚀 Capturamos la columna virtual generada por el conSum y la casteamos a número
            'total_qty' => $this->total_qty ? (float) $this->total_qty : 0,
        ];
    }
}
