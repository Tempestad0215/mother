<?php

namespace App\Helpers;

use App\Dtos\SaleItemDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Enums\OperationTypeEnum;
use App\Models\InventoryMovement;
use App\Models\Sale;
use App\Models\WarehouseProduct;
use Throwable;

class SaleItemHelper
{


    /**
     * @param Sale $sale
     * @param SaleItemDto[] $data
     * @param bool $update 
     * @return void
     * @throws Throwable
     */
    public static function multipleInsertWithSale(Sale $sale, array $data, bool $update = false):void
    {

        // Si no hay datos, no hacemos nada
        if (empty($data))
        {
            return;
        }

        // Recorremos cada item para actualizar o crear el registro correspondiente y ajustar el stock si es necesario
        collect($data)->each(function(SaleItemDto $item) use ($sale, $update) {

            // Verificar si ya existe un item para este producto y almacén en la venta
            $oldItem = $sale->items()
            ->where('product_uuid', $item->product_uuid)
            ->where('sale_uuid', $sale->uuid)
            ->first();

            // Si estamos en modo actualización, necesitamos conocer la cantidad que ya se había retenido en la cuenta para este producto antes de esta actualización para calcular correctamente el ajuste en el stock físico
            $oldQuantity = $oldItem->stock ?? 0;
    
            // Actualizar el stock físico y registrar el movimiento correspondiente basándonos en la diferencia
            self::updateStockAndMovement(
                productUuid: $item->product_uuid,
                warehouseUuid: $item->warehouse_uuid,
                quantity: $item->stock,
                price: $item->price,
                saleUuid: $sale->uuid,
                operation: OperationTypeEnum::SUSTRACT,
                shouldCreateMovement: $sale->close_table, // Solo crear movimiento si la venta se cierr
                update: $update,
                oldQuantity: $oldQuantity
            );

            // Actualizar o crear el item de la venta
            $sale->items()->updateOrCreate([
                'product_uuid' => $item->product_uuid,
                'sale_uuid' => $sale->uuid,
            ], $item->toArray());
            
        });

    }





        /**
     * Gestiona el stock de un producto en un almacén y registra el movimiento si corresponde.
     * En actualizaciones de cuentas abiertas, calcula la diferencia para ajustar el inventario.
     * @param string $productUuid
     * @param string $warehouseUuid
     * @param float $quantity Cantidad actual/nueva que se quiere dejar en la cuenta
     * @param float $price
     * @param string $saleUuid
     * @param OperationTypeEnum $operation
     * @param bool $shouldCreateMovement
     * @param bool $update Si es true, calcula la diferencia contra lo que ya se había retenido
     * @param float $oldQuantity Solo se usa si $update es true, representa la cantidad que ya se había retenido en la cuenta antes de esta actualización
     * @return void
     */
    private static function updateStockAndMovement(
        string $productUuid,
        string $warehouseUuid,
        float $quantity,
        float $price,
        string $saleUuid,
        OperationTypeEnum $operation = OperationTypeEnum::SUSTRACT,
        bool $shouldCreateMovement = false,
        bool $update = false,
        float $oldQuantity = 0.0
    ): void {
        // 1. Obtener el almacén físico actual
        $warehouse = WarehouseProduct::with(['products', 'warehouses'])
            ->where('product_uuid', $productUuid)
            ->where('warehouse_uuid', $warehouseUuid)
            ->first();

        if (!$warehouse) {
            return; 
        }

        // Almacenamos el stock antes de la actualización para registrar correctamente el movimiento
        $previousStock = (float) $warehouse->stock_quantity;
        
        // Cantidad final que se va a usar para alterar el stock físico y el historial
        $finalQuantity = $quantity; 
        $finalOperation = $operation;

        // 2. LOGICA CRÍTICA DE ACTUALIZACIÓN (Cuentas Abiertas)
        if ($update) {
            // Calculamos la diferencia neta
            $difference = $quantity - $oldQuantity;

            if ($difference == 0) {
                return; // No hubo cambios en la cantidad de este ítem, salimos de una vez
            }

            if ($operation === OperationTypeEnum::SUSTRACT) {
                // Si es una VENTA abierta:
                if ($difference > 0) {
                    // Agregaron más piezas a la cuenta -> Hay que RESTAR del stock físico
                    $finalOperation = OperationTypeEnum::SUSTRACT;
                    $finalQuantity = $difference;
                } else {
                    // Devolvieron piezas de la cuenta -> Hay que SUMAR (devolver) al stock físico
                    $finalOperation = OperationTypeEnum::ADD;
                    $finalQuantity = abs($difference); // Pasamos el valor a positivo para el incremento
                }
            } else {
                // Si fuera una COMPRA/NOTA DE CRÉDITO abierta (Inverso):
                if ($difference > 0) {
                    $finalOperation = OperationTypeEnum::ADD;
                    $finalQuantity = $difference;
                } else {
                    $finalOperation = OperationTypeEnum::SUSTRACT;
                    $finalQuantity = abs($difference);
                }
            }
        }

        // 3. Ejecutar la alteración real en la base de datos basándonos en la operación calculada
        $query = WarehouseProduct::where('product_uuid', $productUuid)
            ->where('warehouse_uuid', $warehouseUuid);


        // Dependiendo de la operación, actualizamos el stock físico
        if ($finalOperation === OperationTypeEnum::ADD) {
            $query->increment('stock_quantity', $finalQuantity);
            $newStock = $previousStock + $finalQuantity;
            $movementType = InventoryMovementTypeEnum::IN->value;
            $conceptAction = $update ? "Ajuste por devolución en cuenta abierta" : "Devolución/Ingreso";
        } else {
            $query->decrement('stock_quantity', $finalQuantity);
            $newStock = $previousStock - $finalQuantity;
            $movementType = InventoryMovementTypeEnum::OUT->value;
            $conceptAction = $update ? "Ajuste por incremento en cuenta abierta" : "Venta";
        }

        // 4. Registrar en el historial de movimientos de inventario sólo si se requiere
        if ($shouldCreateMovement) {
            $productName = $warehouse->product->name ?? 'Desconocido';
            $warehouseName = $warehouse->warehouse->name ?? 'Desconocido';

            InventoryMovement::create([
                'product_uuid'   => $productUuid,
                'warehouse_uuid' => $warehouseUuid,
                'type'           => $movementType,
                'concept'        => "{$conceptAction} de Producto {$productName} en el almacén {$warehouseName}, ID de referencia: {$saleUuid}",
                'quantity'       => $finalQuantity,
                'cost'           => $price,
                'stock_before'   => $previousStock,
                'stock_after'    => $newStock,
            ]);
        }
    }


}
