<?php

namespace App\Http\Resources;

use App\Models\Brand;
use App\Models\PriceList;
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
 * @property bool $handle_warehouse
 * @property string $supplier_uuid
 * @property string $category_uuid
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon|null $deleted_at
 *
 *
 * @property-read Brand $brand
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

        // 👇 TOMAMOS LA TASA DE IMPUESTO CORRECTAMENTE
        // Verificamos que la relación tax esté cargada, si no, usamos 0
        $tax_rate_value = $this->whenLoaded('tax', $this->tax->rate ?? 0, 0);
        // Convertimos a cadena para que bcmath funcione bien
        $tax_rate = (string)$tax_rate_value;
        // Calculamos porcentaje y multiplicador
        $tax_percent = bcdiv($tax_rate, '100', 2);
        $tax_multiplier = bcadd($tax_percent, '1', 2);


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
            'price_lists' => $this->whenLoaded('price_list', function () use ($tax_percent, $tax_multiplier, $tax_rate) {
                return $this->price_list->map(function (PriceList $item) use ($tax_percent, $tax_multiplier, $tax_rate) {
                    // Tomamos los precios de la tabla intermedia
                    $price = (string)($item->pivot->price ?? '0.00');
                    $min_price = (string)($item->pivot->min_price ?? '0.00');
                    $promotional_price = (string)($item->pivot->promotional_price ?? '0.00');

                    return [
                        'uuid' => $item->uuid,
                        'name' => $item->name,
                        'currency' => $item->currency,

                        // Precios sin impuesto
                        'price' => (float)$price,
                        'min_price' => (float)$min_price,
                        'promotional_price' => (float)$promotional_price,

                        // Datos de impuesto
                        'tax_rate' => (int)$tax_rate,
                        'tax_percent' => (float)$tax_percent,

                        // Impuestos calculados (formato con coma)
                        'tax_amount_price' => number_format((float)bcmul($price, $tax_percent, 2), 2),
                        'tax_amount_min_price' => number_format((float)bcmul($min_price, $tax_percent, 2), 2),
                        'tax_amount_promotional' => number_format((float)bcmul($promotional_price, $tax_percent, 2), 2),

                        // Totales con impuesto
                        'total_with_tax_price' => number_format((float)bcmul($price, $tax_multiplier, 2), 2),
                        'total_with_tax_min_price' => number_format((float)bcmul($min_price, $tax_multiplier, 2), 2),
                        'total_with_tax_promotional' => number_format((float)bcmul($promotional_price, $tax_multiplier, 2), 2),
                    ];
                });
            }),
            'warehouses' => $this->whenLoaded('warehouses', function (){

                return $this->warehouses->map(function (Warehouse $warehouse){
                    $quantity = (string)$warehouse->pivot->stock_quantity ?? 0;
                    $committed = (string)$warehouse->pivot->committed_stock ?? 0;

                    return [
                        'warehouse_uuid' => $warehouse->uuid,
                        'stock_quantity' => (float)$quantity,
                        'committed_stock' => (float)$committed,
                        'available_stock' => (float)bcsub($quantity, $committed,2),
                        'min_stock' => (float)$warehouse->pivot->min_stock ?? 0,
                        'max_stock' => (float)$warehouse->pivot->max_stock ?? 0,
                        'reorder_leve' => (float)$warehouse->pivot->reorder_leve ?? 0,
                        'is_active' => (bool)$warehouse->pivot->is_active,
                    ];
                });
            }),
            'cost'           => (float) number_format($this->cost, 2),
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
