<?php

namespace App\Helpers;

use App\Dtos\SaleItemDBDto;
use App\Dtos\SaleItemDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Enums\PriceTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\WarehouseProduct;
use Illuminate\Support\Str;
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

        //
        $saleItemSave = [];
        //
        $productUuids = collect($data)->pluck('product_uuid')->toArray();
        //
        $productsDb = Product::with(['priceList', 'warehouses', 'saleItem' => function ($query) use ($sale) {
            $query->where('sale_uuid', $sale->uuid);
        }])
            ->whereIn('uuid', $productUuids)->get()->keyBy('uuid');


        // Recorremos cada item para actualizar o crear el registro correspondiente y ajustar el stock si es necesario
        foreach ($data as $item) {


            // Obtener el producto
            /** @var Product|null $currentProduct */
            $currentProduct = $productsDb->get($item->product_uuid);

            // Verificar si existe el producto
            if (!$currentProduct) continue;

            // tomar el servicios
            $isService = $currentProduct->is_service || $item->is_service;
            // Precio real
            $realPrice = '0';
            if ($isService) {
                $realPrice = (string)$item->price;
            } else {
                // Obtener la lista de precios del producto
                $priceList = $currentProduct->priceList
                    ->where('uuid', $currentProduct->default_price_list)
                    ->first();

                // Obtener el precio del producto en la lista de precios
                $productPrice = $priceList->pivot;

                // Para saber el tipo de precio
                $priceType = PriceTypeEnum::from($item->price_type)->value ?? 'price';

                // Tomar el precio del producto en la lista de precios
                $realPrice = (string)($productPrice->{$priceType} ?? $productPrice->price);


                // 🛡️ CONTROL DE SEGURIDAD (0 Consultas adicionales)
                // Comparamos el precio que envió el frontend con el calculado matemáticamente por el backend
                if (bccomp((string)$item->price, $realPrice, 4) !== 0) {
                    // Opción A: Bloquear la venta por sospecha de alteración de precios
                    throw new \Exception("El precio del producto {$currentProduct->name} fue alterado en el navegador.");
                }
            }

            // Verificar si ya existe un item para este producto y almacén en la venta
            $oldItem = $update ? $currentProduct->saleItem->first() : null;

            // Obtener la cantidad actual del stock
            $oldStock = $oldItem ? (string)$oldItem->stock : '0';

            // Calcular la nueva cantidad de stock
            $result = bcsub((string)$item->stock, $oldStock, 4);



            $quantityForInventory = '0';

            // Verificar si la cantidad es positiva o negativa
            if (bccomp($result, '0', 4) > 0) {
                $quantityForInventory = $result;
            } elseif (bccomp($result, '0', 4) < 0) {
                $quantityForInventory = bcmul($result, '-1', 4);
            }



            // Solo hacer cambio si cambio el resultado
            if (bccomp($result, '0', 4) !== 0 && $sale->type !== SaleTypeEnum::Cotizacion) {
                // Actualizar el stock físico y registrar el movimiento correspondiente basándonos en la diferencia
                self::updateStockAndMovement(
                    productUuid: $item->product_uuid,
                    warehouseUuid: $item->warehouse_uuid,
                    quantity: (float)$quantityForInventory,
                    price: (float)$realPrice,
                    saleUuid: $sale->uuid, // Solo crear movimiento si la venta se cierre
                );
            }

            // Actualizar o crear el item de la venta
            $saleItemSave[] = SaleItemDBDto::fromArray([
                ...$item->toArray(),
                'tax_uuid' => $currentProduct->tax->uuid,
                'sale_uuid' => $sale->uuid,
                'uuid' => $update ? $oldItem->uuid : Str::uuid(),
            ])->toArray();

        }



        // Guardar los datos en la tabla
        SaleItem::upsert($saleItemSave, [
            'product_uuid',
            'sale_uuid'
        ], [
            'stock',
            'tax_uuid',
            'warehouse_uuid',
            'price',
            'tax_rate',
            'discount',
            'discount_amount',
            'reserved',
            'amount',
            'is_service',
        ]);

    }


    /**
     * Gestiona el stock de un producto en un almacén y registra el movimiento si corresponde.
     * En actualizaciones de cuentas abiertas, calcula la diferencia para ajustar el inventario.
     * @param string $productUuid
     * @param string $warehouseUuid
     * @param float $quantity Cantidad actual/nueva que se quiere dejar en la cuenta
     * @param float $price
     * @param string $saleUuid
     * @return void
     */
    private static function updateStockAndMovement(
        string $productUuid,
        string $warehouseUuid,
        float  $quantity,
        float  $price,
        string $saleUuid,
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
        $newStock = bcsub((string)$oldStock, (string)$quantity, 4);


        // Para ver si es necesario crear movimiento
        InventoryMovement::create([
            'product_uuid' => $productUuid,
            'warehouse_uuid' => $warehouseUuid,
            'type' => InventoryMovementTypeEnum::OUT,
            'concept' => "Esta es una venta con el UUID: $saleUuid, y el producto: $productUuid",
            'inventoryable_id' => $saleUuid,
            'inventoryable_type' => Sale::class,
            'quantity' => $quantity,
            'cost' => $price,
            'stock_before' => $oldStock,
            'stock_after' => $newStock,
        ]);


//        DB::table('warehouse_products')->where('product_uuid', $productUuid)
//            ->where('warehouse_uuid', $warehouseUuid)
//            ->increment('stock_quantity', $quantity);


    }

}
