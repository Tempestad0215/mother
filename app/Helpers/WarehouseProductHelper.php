<?php

namespace App\Helpers;

use App\Dtos\ProductWarehouseDto;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Validation\ValidationException;

class WarehouseProductHelper
{
    // 
    public static function create(array $data,Product $product, bool $is_service = false):void
    {
        // No hacer nada si es servicios
        if($is_service){
            return;
        }

        // si no hay datos pues se deja vacio
        if(empty($data)){
            return;
        }

        // Convertir los datos
        foreach ($data as $item){

            // Si no existe pasa al otros for
            if (empty($item['warehouse_uuid']))
            {
                continue;
            }

            // Verificar si el almacen existe en la base de datos
            if (!Warehouse::find($item['warehouse_uuid'])->exists()){
                throw ValidationException::withMessages([
                    'warehouse_uuid' => "El almacén {$item['warehouse_uuid']} no existe"
                ]);
            }

            // Guardar los datos en la base de datos
            $product->warehouses()->attach($item['warehouse_uuid'],[
                'stock_quantity' => $item['stock_quantity'] ?? 0,
                'min_stock' => $item['min_stock'] ?? 0,
                'max_stock' => $item['max_stock'] ?? 0,
                'reorder_level' => $item['reorder_level'] ?? 0,
            ]);
        }

    }
}
