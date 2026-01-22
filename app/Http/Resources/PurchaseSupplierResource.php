<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseSupplierResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        $data = parent::toArray($request);
        $data['supplier'] = $this->supplier;
        $data['products'] = $this->items;

        return $data;
    }
}
