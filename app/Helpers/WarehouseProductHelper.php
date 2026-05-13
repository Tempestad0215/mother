<?php

namespace App\Helpers;

use App\Dtos\WarehouseDto;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Validation\ValidationException;

class WarehouseProductHelper
{
    /**
     * @param array $data
     * @param Product $product
     * @param bool $is_service
     * @return void
     */
    public static function upSert(array $data, Product $product, bool $is_service = false):void
    {

//        Convertir a una Collection
        $info = WarehouseDto::formArrayCollection($data);
        // No hacer nada si es servicios
        if($is_service){
            return;
        }

        $pivotData = [];

        // Recorrder los datos
        foreach ($info as $item){

            // Si no existe pasa al otros for
            if (empty($item->warehouse_uuid))
            {
                continue;
            }

            // Verificar si el almacen existe en la base de datos
            if (!Warehouse::find($item->warehouse_uuid)->exists()){
                throw ValidationException::withMessages([
                    'warehouse_uuid' => "El almacén {$item->warehouse_uuid} no existe"
                ]);
            }

            // Guardar los datos en la base de datos
            $pivotData[$item->warehouse_uuid] = [
                'stock_quantity' => $item->stock_quantity ?? 0,
                'min_stock' => $item->min_stock ?? 0,
                'max_stock' => $item->max_stock ?? 0,
                'reorder_level' => $item->reorder_level ?? 0,
                'is_active' => $item->is_active ?? true,
            ];
        }
        // 🟢 AQUÍ EL CAMBIO: Pasamos todos los datos de una vez
        // Si ya existe, actualiza; si no, crea. NO intenta insertar duplicados
        if (!empty($pivotData)) {
            $product->warehouses()->syncWithoutDetaching($pivotData);
        }

    }

    /**
     * @param Product $product
     * @return bool
     */
    public static function checkStockForProduct(Product $product):bool
    {
        return $product->warehouses->contains(function(Warehouse $item){
            return !!$item->pivot->stock_quantity > 1;
        });
    }

}
