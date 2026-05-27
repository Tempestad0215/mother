<?php

namespace App\Helpers;

use App\Dtos\SaleItemDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Models\InventoryMovement;
use App\Models\Sale;
use App\Models\WarehouseProduct;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

class SaleItemHelper
{


    /**
     * @param Sale $sale
     * @param SaleItemDto[] $data
     * @return void
     * @throws Throwable
     */
    public static function multipleInsertWithSale(Sale $sale, array $data):void
    {

        // Si no hay datos, no hacemos nada
        if (empty($data))
        {
            return;
        }

        // Formatear los datos para la inserción masiva
        $fomattedData = collect($data)->map(fn(SaleItemDto $item) => $item->toArray())->all();
    
        // Crear los objetos SaleItemFactory a partir de los datos y convertirlos a array para la inserción masiva
        $sale->items()->createMany($fomattedData);

        // Obtner los datos de product_uuid y el warehoyse_uuid para cada item
        $infoSale = collect($data)->map(fn(SaleItemDto $item) => [
            'product_uuid' => $item->product_uuid,
            'warehouse_uuid' => $item->warehouse_uuid,
            'stock' => $item->stock
        ])->all();


        // Obtener el stock actual de los productos en los almacenes correspondientes
        $warehouseCurrent = WarehouseProduct::where(function(Builder $quey) use ($infoSale) {
            foreach ($infoSale as $item) {
                $quey->orWhere(function(Builder $q) use ($item) {
                    $q->where('product_uuid', $item['product_uuid'])
                      ->where('warehouse_uuid', $item['warehouse_uuid']);
                });
            }
        })->get()->keyBy(fn(WarehouseProduct $stock) => "{$stock->product_uuid}-{$stock->warehouse_uuid}");

        // Para crear los datos 
        $inventoryMovements = [];

        // Actualizar el stock de los productos en los almacenes correspondientes
        foreach ($data as $item) {
            // Crear la clave para acceder al stock actual del producto en el almacén
            $key = "{$item->product_uuid}-{$item->warehouse_uuid}";

            // Scar el stock actual del producto en el almacén y restarle el stock vendido
            /**
             * @var WarehouseProduct|null $warehouse 
             */
            $warehouse = $warehouseCurrent->get($key) ?? null;


            // Si el stock actual existe, se le resta el stock vendido
            if (isset($warehouse)) {

                // Tomar el stock anterior para verificar si el stock se actualiza correctamente
                $previousStock = $warehouse->stock_quantity;

                // Restar el stock vendido al stock actual
                WarehouseProduct::where('product_uuid', $item->product_uuid)
                    ->where('warehouse_uuid', $item->warehouse_uuid)
                    ->decrement('stock_quantity', $item->stock);

                // Verificar si el stock se actualiza correctamente
                $newStock = $warehouse->stock_quantity;

                // Crear el movimiento de inventario
                if($sale->close_table){
                    // convertir los datos
                    InventoryMovement::create([
                        'product_uuid'   => $item->product_uuid,
                        'warehouse_uuid' => $item->warehouse_uuid,
                        'type'           => InventoryMovementTypeEnum::OUT->value,
                        'concept'        => "Venta de Producto {$warehouse->products->name} en el almacen {$warehouse->warehouse->name}, id de la venta: {$sale->uuid}",
                        'quantity'       => $item->stock,
                        'cost'           => $item->price,
                        'stock_before'   => $previousStock,
                        'stock_after'    => $newStock,
                    ]);
                }

                

            }

        }

    }


}
