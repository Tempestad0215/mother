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

//        $data = parent::toArray($request);
//
//        $data['products'] = collect($this->items)->map(function ($item){
//            $itemOld = $item;
//            $itemOld['product_name'] => $item->product->name;
//            dd($item->product);
//        });

        return [
            ...parent::toArray($request),
            'supplier' => $this->whenLoaded('supplier'),
            'products' => PurchaseItemResource::collection($this->whenLoaded('products')),
        ];
    }
}
