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
class ProductWarehouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = $this->warehouses->map(function (Warehouse $warehouse) {
            $quantity = (string)$warehouse->pivot->stock_quantity ?? 0;
            $committed = (string)$warehouse->pivot->committed_stock ?? 0;

           return [
               'warehouse_uuid' => $warehouse->uuid,
               'stock_quantity' => (float)$quantity,
               'committed_stock' => (float)$committed ?? 0,
               'available_stock' => (float)bcsub($quantity, $committed,2) ?? 0,
               'min_stock' => (float)$warehouse->pivot->min_stock ?? 0,
               'max_stock' => (float)$warehouse->pivot->max_stock ?? 0,
               'reorder_leve' => (float)$warehouse->pivot->reorder_leve ?? 0,
               'is_active' => (bool)$warehouse->pivot->is_active,
               'name' => $warehouse->name,
               'prefix' => $warehouse->prefix ?? ""
           ];
        });

        return $data->toArray();

    }
}
