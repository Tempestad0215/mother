<?php

namespace App\Http\Resources;

use App\Models\PriceList;
use App\Models\PurchaseReceiptsItem;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
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
 * @property string $brand
 * @property float $tax_rate
 * @property float $tax
 * @property float $discount
 * @property float $discount_amount
 * @property float $product_no_tax
 * @property float $benefits
 * @property float $benefits_rate
 * @property string $comment
 * @property bool $inventoried
 * @property bool $status
 * @property bool $has_fraction
 * @property bool $has_special
 * @property bool $has_promotion
 * @property bool $has_tax
 * @property string $supplier_uuid
 * @property string $category_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 *
 * @property-read Collection<int, PriceList[] $price_list>
 * @property-read PurchaseReceiptsItem $receiptsItem
 * @method static create(mixed $validated)
 */
class ProductResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            // 🆔 Identificadores
            'uuid'           => (string) $this->uuid,
            'code'           => (string) $this->code,
            'bar_code'       => $this->bar_code ? (string) $this->bar_code : null,
            'sku'            => $this->sku ? (string) $this->sku : null,

            // 📝 Datos generales
            'name'           => (string) $this->name,
            'description'    => $this->description ? (string) $this->description : null,
            'branch'         => $this->branch ? (string) $this->branch : null,

            // 🔗 Relaciones UUID
            'unit_uuid'      => $this->unit_uuid ? (string) $this->unit_uuid : null,
            'supplier_uuid'  => $this->supplier_uuid ? (string) $this->supplier_uuid : null,
            'category_uuid'  => $this->category_uuid ? (string) $this->category_uuid : null,
            'tax_uuid'       => $this->tax_uuid ? (string) $this->tax_uuid : null,

            // 💰 Valores numéricos (siempre float con 2 decimales)
            'price_lists' => $this->whenLoaded('price_list', function (){
                return $this->price_list->map(function (PriceList $item) {
                    return [
                        'uuid' => $item->uuid,
                        'name' => $item->name,
                        'currency' => $item->currency,
                        'price' => (float)$item->pivot->price,
                        'min_price' => (float)$item->pivot->min_price,
                        'special_price' => (float)$item->pivot->promotional_price,
                    ];
                });
            }),
            'cost'           => (float) number_format($this->cost, 2, '.', ''),
            'benefits'       => (float) number_format($this->benefits, 2, '.', ''),
            'benefits_rate'  => (float) number_format($this->benefits_rate, 2, '.', ''),
            'product_no_tax' => (float) number_format($this->product_no_tax, 2, '.', ''),
            'tax_rate'       => (float) number_format($this->tax_rate, 2, '.', ''),
            'tax'            => (float) number_format($this->tax, 2, '.', ''),

            // ⚖️ Medidas
            'weight'         => $this->weight ? (float) number_format($this->weight, 2, '.', '') : null,
            'dimensions'     => $this->dimensions ?  $this->dimensions : null,

            // ✅ Booleanos (siempre true/false)
            'is_service'     => (bool) $this->is_service,
            'inventoried'    => (bool) $this->inventoried,
            'has_fraction'  => (bool) $this->has_fraction,
            'status'         => (bool) $this->status,
            'has_tax'        => (bool) $this->has_tax,
            'has_special'    => (bool) $this->has_special,
            'has_promotion'  => (bool) $this->has_promotion,
            'handle_warehouse'=> (bool) $this->handle_warehouse,


        ];
    }
}
