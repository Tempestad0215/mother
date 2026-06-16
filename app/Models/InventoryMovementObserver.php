<?php

namespace App\Models;

use App\Enums\InventoryMovementTypeEnum;

class InventoryMovementObserver
{
    public function created(InventoryMovement $inventoryMovement): void
    {

        // Buscar el registro en cuestion
        $warehouseProduct = WarehouseProduct::where('warehouse_uuid', $inventoryMovement->warehouse_uuid)
            ->where('product_uuid', $inventoryMovement->product_uuid)
            ->first();

        // Verificar si existe
        if($warehouseProduct)
        {
            //
            $oldStock = $warehouseProduct->stock_quantity;

            // Verificar si es una entrada o una salida
            if($inventoryMovement->type == InventoryMovementTypeEnum::IN){
                $newStock = bcadd((string)$oldStock, (string)$inventoryMovement->quantity);

            }else{
                $newStock = bcsub((string)$oldStock,(string)$inventoryMovement->quantity);

            }



            // Actualizar el stock en el almacen
            WarehouseProduct::where('warehouse_uuid', $inventoryMovement->warehouse_uuid)
                ->where('product_uuid', $inventoryMovement->product_uuid)
                ->update(['stock_quantity' => $newStock]);
        }
    }

    public function updated(InventoryMovement $inventoryMovement): void
    {
    }
}
