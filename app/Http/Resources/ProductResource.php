<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\PriceList;
use App\Models\PriceListProduct;
use App\Models\PurchaseReceiptsItem;
use App\Models\Tax;
use App\Models\Warehouse;
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
 * @property string $default_warehouse
 * @property float $cost
 * @property string $sku
 * @property string $bar_code
 * @property float $weight
 * @property string $dimensions
 * @property string $brand_uuid
 * @property string $tax_uuid
 * @property float $discount
 * @property float $discount_amount
 * @property float $benefits
 * @property float $benefits_rate
 * @property string $comment
 * @property bool $inventoried
 * @property bool $status
 * @property bool $has_fraction
 * @property bool $has_special
 * @property bool $has_promotion
 * @property bool $has_tax
 * @property bool $is_service
 * @property string $default_price_list
 * @property bool $handle_warehouse
 * @property string $supplier_uuid
 * @property string $category_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 *
 * @property-read Brand $brand
 * @property-read PriceList[] $priceList
 * @property-read Tax $tax
 * @property-read Collection<int, PriceList $price_list>
 * @property-read Collection<int, Warehouse $warehouses>
 * @property-read PurchaseReceiptsItem $receiptsItem
 * @method static create(mixed $validated)
 */
class ProductResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        /** @var PriceList[] $priceLists */
        $priceLists = $this->whenLoaded('priceList');
        //Tomar la primera
        /** @var PriceList $priceListCurrent */
        $priceListCurrent = collect($priceLists)->firstWhere('uuid', $this->default_price_list);
        // Pivot
        /** @var PriceListProduct $pivotPrice */
        $pivotPrice = $priceListCurrent->pivot;


        return [
            // 🆔 Identificadores
            'uuid'           =>  $this->uuid,
            'code'           =>  $this->code,
            'bar_code'       => $this->bar_code ?: null,
            'sku'            => $this->sku ?: null,

            // 📝 Datos generales
            'name'           =>  $this->name,
            'description'    => $this->description ?: null,
            'brand'         => $this->whenLoaded('brand'),

            // 🔗 Relaciones UUID
            'unit_uuid'      => $this->unit_uuid ?: null,
            'supplier_uuid'  => $this->supplier_uuid ?: null,
            'category_uuid'  => $this->category_uuid ?: null,
            'tax'       => $this->tax,

            // 💰 Valores numéricos e impuestos por lista de precio
            'price_lists' => $this->whenLoaded('priceList', function () {
                return new ProductPriceListResource($this);
            }),
            'warehouses' => $this->whenLoaded('warehouses', function (){
                return new ProductWarehouseResource($this);
            }),
            'cost'           => (float) number_format($this->cost, 2),
            'price' => (float) $pivotPrice->price,
            'benefits'       => (float) number_format($this->benefits, 2),
            'benefits_rate'  => (float) number_format($this->benefits_rate, 2),

            // ⚖️ Medidas
            'weight'         => $this->weight ? (float) number_format($this->weight, 2) : null,
            'dimensions'     => $this->dimensions ?: null,

            // ✅ Booleanos (siempre true/false)
            'is_service'     => $this->is_service,
            'inventoried'    => $this->inventoried,
            'has_fraction'  => $this->has_fraction,
            'status'         => $this->status,
            'has_tax'        => $this->has_tax,
            'has_special'    => $this->has_special,
            'has_promotion'  => $this->has_promotion,
            'handle_warehouse'=> $this->handle_warehouse ?? false,
            'default_price_list' => $this->default_price_list ?? PriceList::first('uuid')->uuid,
            'default_warehouse' => $this->default_warehouse ?? Warehouse::first('uuid')->uuid



        ];
    }
}
