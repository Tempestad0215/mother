<?php

namespace App\Http\Resources;

use App\Enums\SaleTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property float $amount
 * @property string $code
 * @property int $credit_note_id
 * @property string $deleted_at
 * @property float $discount
 * @property float $discount_amount
 * @property int $id
 * @property float $price
 * @property int $product_id
 * @property string $product_name
 * @property int $sale_id
 * @property bool $status
 * @property float $stock
 * @property float $tax
 * @property float $tax_rate
 * @property SaleTypeEnum $type
 */


class InfoSaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id
        ];
    }
}
