<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\PriceList;
use App\Models\PriceListProduct;
use App\Models\Product;
use App\Models\PurchaseReceiptsItem;
use App\Models\Tax;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 *
 * @mixin Product
 *
 */
class ProductResource extends JsonResource
{

    public function toArray(Request $request): array
    {

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
            'tax_uuid'       => $this->tax_uuid ?: null,
            'tax'       => $this->whenLoaded('tax'),

            // 💰 Valores numéricos e impuestos por lista de precio
            'price_lists' => $this->whenLoaded('priceList', fn()=>
                new PriceListsCResource($this->priceList)->withTaxRate((float) ($this->tax?->rate ?? 0))
            ),
            'warehouses' => $this->whenLoaded('warehouses', fn()=> new WarehousesCResource($this->warehouses)),
            'cost'          => $this->cost,
            'benefits'      => $this->benefits,
            'benefits_rate' => $this->benefits_rate,
            'weight'        => $this->weight ?: null,
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
            'default_price_list' => $this->default_price_list,
            'default_warehouse' => $this->default_warehouse



        ];
    }
}
