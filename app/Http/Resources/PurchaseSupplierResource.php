<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseSupplierResource extends JsonResource
{

    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        
        return [
            ...parent::toArray($request),
            'supplier' => $this->whenLoaded('supplier'),
            'items' => PurchaseItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
