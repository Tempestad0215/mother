<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\PriceList;
use App\Models\PurchaseReceiptsItem;
use App\Models\Tax;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;


/**
 * @property string $uuid
 * @property string $type
 * @property string $code
 * @property string $name
 * @property string $description
 * @property string $unit_uuid
 * @property float $cost
 * @property string $sku
 * @property string $bar_code
 * @property float $weight
 * @property string $dimensions
 * @property string $brand_uuid
 * @property float $discount
 * @property float $discount_amount
 * @property float $benefits
 * @property float $benefits_rate
 * @property string $comment
 * @property string $default_price_list
 * @property bool $inventoried
 * @property bool $status
 * @property bool $has_fraction
 * @property bool $has_special
 * @property bool $has_promotion
 * @property bool $has_tax
 * @property bool $handle_warehouse
 * @property string $supplier_uuid
 * @property string $category_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 * @property-read Collection<int, PriceList $price_list>
 * @property-read Brand $brand
 * @property-read Tax $tax
 * @property-read PurchaseReceiptsItem $receiptsItem
 * @method static create(mixed $validated)
 */
class ProductPriceListResource extends JsonResource
{


    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // 👇 TOMAMOS LA TASA DE IMPUESTO CORRECTAMENTE
        // Verificamos que la relación tax esté cargada, si no, usamos 0
        $tax_rate_value = $this->whenLoaded('tax', $this->tax->rate ?? 0, 0);
        // Convertimos a cadena para que bcmath funcione bien
        $tax_rate = (string)$tax_rate_value;
        // Calculamos porcentaje y multiplicador
        $tax_percent = bcdiv($tax_rate, '100', 2);
        $tax_multiplier = bcadd($tax_percent, '1', 2);


        $info = $this->price_list->map(function (PriceList $item) use($tax_percent, $tax_multiplier, $tax_rate) {
            $price = (string)($item->pivot->price ?? '0.00');
            $min_price = (string)($item->pivot->min_price ?? '0.00');
            $promotional_price = (string)($item->pivot->promotional_price ?? '0.00');

            return [
                'uuid' => $item->uuid,
                'name' => $item->name,
                'currency' => $item->currency,
                'price' => (float)$price,
                'min_price' => (float)$min_price,
                'promotional_price' => (float)$promotional_price,
                // Datos de impuesto
                'tax_rate' => (int)$tax_rate,
                'tax_percent' => (float)$tax_percent,

                // Impuestos calculados (formato con coma)
                'tax_amount_price' => (float)number_format((float)bcmul($price, $tax_percent, 2), 2),
                'tax_amount_min_price' =>(float) number_format((float)bcmul($min_price, $tax_percent, 2), 2),
                'tax_amount_promotional' => (float)number_format((float)bcmul($promotional_price, $tax_percent, 2), 2),

                // Totales con impuesto
                'total_with_tax_price' => (float)number_format((float)bcmul($price, $tax_multiplier, 2), 2),
                'total_with_tax_min_price' =>(float) number_format((float)bcmul($min_price, $tax_multiplier, 2), 2),
                'total_with_tax_promotional' =>(float) number_format((float)bcmul($promotional_price, $tax_multiplier, 2), 2),
            ];
        });

        return $info->toArray();

    }
}
