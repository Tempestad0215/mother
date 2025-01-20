<?php

namespace App\Http\Resources;

use App\Enums\INTYEnum;
use App\Models\Product;
use Carbon\Traits\Date;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int id
 * @property  int product_id
 * @property INTYEnum type
 * @property float quantity
 * @property float cost
 * @property string description
 * @property Product product
 * @property boolean status
 * @property Date created_at
 * @property Date updated_at
 * @property Date deleted_at
 */
class InventoryProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'cost' => $this->cost,
            'product' => $this->product
        ];
    }
}
