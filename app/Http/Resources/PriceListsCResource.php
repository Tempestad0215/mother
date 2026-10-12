<?php

namespace App\Http\Resources;

use App\Models\PriceList;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PriceListsCResource extends ResourceCollection
{
    protected float $taxRate = 0;

    public function withTaxRate(float $taxRate): static
    {
        $this->taxRate = $taxRate;
        return $this;
    }

    public function toArray(Request $request): array
    {
        $taxRateStr    = (string) $this->taxRate;
        $taxPercent    = bcdiv($taxRateStr, '100', 6);
        $taxMultiplier = bcadd($taxPercent, '1', 6);

        return $this->collection->map(function (PriceList $item) use ($taxPercent, $taxMultiplier, $taxRateStr) {
            $price            = (string) ($item->pivot?->price ?? '0.00');
            $minPrice         = (string) ($item->pivot?->min_price ?? '0.00');
            $promotionalPrice = (string) ($item->pivot?->promotional_price ?? '0.00');

            return [
                'uuid'              => $item->uuid,
                'name'              => $item->name,
                'currency'          => $item->currency,
                'price'             => (float) $price,
                'min_price'         => (float) $minPrice,
                'promotional_price' => (float) $promotionalPrice,
                'tax_rate'          => (float) $taxRateStr,
                'tax_percent'       => (float) $taxPercent,
                'tax_amount_price'       => (float) bcmul($price, $taxPercent, 2),
                'tax_amount_min_price'   => (float) bcmul($minPrice, $taxPercent, 2),
                'tax_amount_promotional' => (float) bcmul($promotionalPrice, $taxPercent, 2),
                'total_with_tax_price'       => (float) bcmul($price, $taxMultiplier, 2),
                'total_with_tax_min_price'   => (float) bcmul($minPrice, $taxMultiplier, 2),
                'total_with_tax_promotional' => (float) bcmul($promotionalPrice, $taxMultiplier, 2),
            ];
        })->all();
    }
}
