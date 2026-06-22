<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class DProductStockLowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'code' => $this->code,
            'name' => $this->name,

            // 🚀 Evaluamos de forma segura si la relación 'warehouses' fue cargada
            'warehouses' => $this->whenLoaded('warehouses', function () {
                // Recorremos cada almacén donde existe el producto
                return $this->warehouses->map(function ($warehouse) {
                    return [
                        'warehouse_uuid' => $warehouse->uuid,
                        'warehouse_code' => $warehouse->prefix,
                        'warehouse_name' => $warehouse->name,
                        // 🎯 Aquí es donde vive el pivote de manera individual
                        'stock_quantity' => (float) $warehouse->pivot->stock_quantity,
                        'min_stock'      => (float) $warehouse->pivot->min_stock,
                    ];
                });
            }),

            // 🔥 Opcional: Si en el dashboard solo quieres mostrar la sumatoria total
            // de todos los almacenes de manera directa:
            'total_stock' => $this->whenLoaded('warehouses', function () {
                return (float) $this->warehouses->sum('pivot.stock_quantity');
            }),
        ];
    }
}
