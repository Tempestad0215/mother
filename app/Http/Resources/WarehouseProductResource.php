<?php

namespace App\Http\Resources;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;


class WarehouseProductResource extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function (Warehouse $warehouse) {
            return [
                'warehouse_uuid' => $warehouse->uuid,
                'name'           => $warehouse->name,
                'prefix'         => $warehouse->prefix,
                'stock_quantity' => (float) $warehouse->pivot->stock_quantity,
                'committed'      => (float) $warehouse->pivot->committed,
                'available'      => (float) ($warehouse->pivot->stock_quantity - $warehouse->pivot->committed),
                'min_stock'      => (float) $warehouse->pivot->min_stock,
                'max_stock'      => (float) $warehouse->pivot->max_stock,
                'reorder_level'  => (float) $warehouse->pivot->reorder_level,
                'is_active'      => (bool) $warehouse->pivot->is_active,
            ];
        })->all();
    }
}
