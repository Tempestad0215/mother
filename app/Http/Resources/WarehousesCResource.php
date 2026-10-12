<?php

namespace App\Http\Resources;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehousesCResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {

        return $this->collection->map(function (Warehouse $warehouse) {
            $quantity  = (string) ($warehouse->pivot?->stock_quantity ?? 0);
            $committed = (string) ($warehouse->pivot?->committed ?? 0);

            return [
                'warehouse_uuid' => $warehouse->uuid,
                'name'           => $warehouse->name,
                'prefix'         => $warehouse->prefix ?? '',
                'stock_quantity' => (float) $quantity,
                'committed'      => (float) $committed,
                'available'      => (float) bcsub($quantity, $committed, 2),
                'min_stock'      => (float) ($warehouse->pivot?->min_stock ?? 0),
                'max_stock'      => (float) ($warehouse->pivot?->max_stock ?? 0),
                'reorder_level'  => (float) ($warehouse->pivot?->reorder_level ?? 0),
                'is_active'      => (bool) ($warehouse->pivot?->is_active ?? false),
            ];
        })->all();
    }
}
