<?php

namespace App\Helpers;

use App\Dtos\InventoryMovementDto;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Support\Collection;

class InventoryMovementHelper
{


    /**
     * @param InventoryMovementDto[] $data
     * @return void
     */
    public static function insertMany(
        array $data
    )
    {
        // Obtener los ids de los productos
        $productUids = collect($data)->pluck('product_uuid');

        // Obtener todos los productos
        /** @var Collection<string, Product|null> $productsDB */
        $productsDB = Product::whereIn('uuid', $productUids)->get()->keyBy('uuid');

        // Para almacenar los datos
        $dataForInsert = [];

        // recorder los datos que llegan
        foreach ($data as $item){

            // Obtener el producto
            $currentProduct = $productsDB->get($item->product_uuid);

            // Obtner el stock actual
            $oldStock = $currentProduct?->stock ?? 0;

            // Sumar la cantidad
            $newStock = bcadd((string)$oldStock, (string)$item->quantity, 4);

            // Guardar el movimiento de stock
            $dataForInsert[] = [
                'product_uuid' => $item->product_uuid,
                'warehouse_uuid' => $item->warehouse_uuid,
                'type' => $item->type,
                'concept' => $item->concept,
                'quantity' => $item->quantity,
                'cost' => $item->cost,
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
            ];
        }

        // Insertar los datos en la tabla
        InventoryMovement::insert($dataForInsert);
    }



}
