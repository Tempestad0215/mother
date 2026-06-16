<?php

namespace App\Helpers;

use App\Dtos\SaleItemDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Models\InventoryMovement;
use App\Models\Sale;
use App\Models\WarehouseProduct;
use Illuminate\Support\Facades\DB;
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
    public static function multipleInsertWithSale(Sale $sale, array $data, bool $update = false): void
    {

        // Si no hay datos, no hacemos nada
        if (empty($data)) {
            return;
        }

        // Recorremos cada item para actualizar o crear el registro correspondiente y ajustar el stock si es necesario
        collect($data)->each(function (SaleItemDto $item) use ($sale, $update) {

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
                saleUuid: $sale->uuid, // Solo crear movimiento si la venta se cierr
                shouldCreateMovement: $sale->close_table,
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
     * @param bool $shouldCreateMovement
     * @return void
     */
    private static function updateStockAndMovement(
        string $productUuid,
        string $warehouseUuid,
        float  $quantity,
        float  $price,
        string $saleUuid,
        bool   $shouldCreateMovement = false,
    ): void
    {

        // Tomar el warehose que exste
        $warehouseProduct = WarehouseProduct::where('product_uuid', $productUuid)
            ->where('warehouse_uuid', $warehouseUuid)
            ->first();

        // Verificar si no existe
        if (!$warehouseProduct) {
            return;
        }

        // Tomar los datos de cantidad
        $oldStock = $warehouseProduct->stock_quantity;
        $newStock = $oldStock - $quantity;

        // Para ver si es necesario crear movimiento
        if ($shouldCreateMovement) {
            InventoryMovement::create([
                'product_uuid' => $productUuid,
                'warehouse_uuid' => $warehouseUuid,
                'type' => InventoryMovementTypeEnum::OUT,
                'concept' => "Esta es una venta con el UUID: $saleUuid, y el producto: $productUuid",
                'quantity' => $quantity,
                'cost' => $price,
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
            ]);

        } else {
            DB::table('warehouse_products')->where('product_uuid', $productUuid)
                ->where('warehouse_uuid', $warehouseUuid)
                ->increment('stock_quantity', $quantity);
        }

    }

}
