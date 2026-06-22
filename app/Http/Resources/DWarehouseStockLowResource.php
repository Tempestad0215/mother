<?php

namespace App\Http\Resources;

use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read WarehouseProduct $pivot
 * @mixin Warehouse
 */
class DWarehouseStockLowResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'warehouse_uuid' => $this->uuid,
            'warehouse_name' => $this->name,

            // Mapeamos los productos críticos de ESTE almacén
            'critical_products' => $this->products->map(function ($product) {
                return [
                    'product_uuid'   => $product->uuid,
                    'product_code'   => $product->code,
                    'product_name'   => $product->name,
                    // 🚀 Aquí el pivote es seguro porque viene filtrado por almacén
                    'stock_quantity' => (float) $product->pivot->stock_quantity,
                    'min_stock'      => (float) $product->pivot->min_stock,
                ];
            }),
        ];
    }
}
