<?php

namespace App\Http\Resources;

use App\Models\PriceList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class ProductPriceListResource extends JsonResource
{


    #[Override]
    public function __construct($resource, PriceList $price_list)
    {
        return parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'pivot' => $this->pivot
        ];
    }
}
