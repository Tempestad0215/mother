<?php

namespace App\Models;

use App\Enums\InventoryMovementTypeEnum;
use Exception;
use Laravel\Octane\Exceptions\DdException;

class CreditNoteItemObserver
{

    /**
     * @param CreditNoteItem $creditNoteItem
     * @return void
     * @throws DdException
     * @throws Exception
     */
    public function creating(CreditNoteItem $creditNoteItem):void
    {
        // cargar las relaciones
        $creditNoteItem->load(['warehouse','product','creditNote.sale']);

        // Tomar la nota de creditos
        $creditNote = $creditNoteItem->creditNote;
        // Tomar la venta de esa nota de creditos
        $sale = $creditNote?->sale;

        // Si no tiene ventas, pues no hace nada
        if(!$sale) return;

        // Buscar el producto en la venta
        $saleItems = SaleItem::where('sale_uuid', $sale->uuid)
            ->where('product_uuid', $creditNoteItem->product_uuid)
            ->first();

        // validar si el producto no es enviado correctamente
        if(!$saleItems){
            throw new Exception("El producto {$creditNoteItem->product->name} no pertenece a la venta original.");
        }

        // Tomar la cantidad de stock en la venta
        $stockInSaleItem = $saleItems->stock;

        // tomar la cantidad de las notas de creditos, si existen varias validar todas
        $totalCreditNoteItems =  CreditNoteItem::whereHas('creditNote', function ($query) use ($sale) {
            $query->where('sale_uuid', $sale->uuid);
        })
            ->where('product_uuid', $creditNoteItem->product_uuid)
            ->sum('quantity');

        // 4. Calcular el límite disponible para devolver
        $itemAvailableForReturn = bcsub((string)$stockInSaleItem, (string)$totalCreditNoteItems);

        // 5. Validar si la cantidad que se intenta devolver ahora excede el disponible
        if ($creditNoteItem->quantity > $itemAvailableForReturn) {
            throw new Exception(
                "Acción inválida. Estás intentando devolver {$creditNoteItem->quantity} unidades, " .
                "pero el máximo disponible para devolución en esta factura es de {$itemAvailableForReturn} unidades " .
                "(Ya se habían devuelto {$totalCreditNoteItems} de {$stockInSaleItem} vendidas)."
            );
        }


    }

    /**
     * @param CreditNoteItem $creditNoteItem
     * @return void
     */
    public function created(CreditNoteItem $creditNoteItem): void
    {
        // Si no existe el almacen, pues no hace nada
        if(!$creditNoteItem->warehouse_uuid) return;

        // Buscar el producto en el almacen
        $warehouseProduct = WarehouseProduct::where('warehouse_uuid', $creditNoteItem->warehouse_uuid)
            ->where('product_uuid', $creditNoteItem->product_uuid)
            ->first();

        // El stock antiguo
        $stockBefore = $warehouseProduct ? $warehouseProduct->stock_quantity: 0;

        // sumar la cantidad de la nota de credito
        $stockAfter = $stockBefore + $creditNoteItem->quantity;


        // Crear el movimeintos de stock
        // 1. Creamos el movimiento con la "fotografía" del stock
        InventoryMovement::create([
            'product_uuid'   => $creditNoteItem->product_uuid,
            'warehouse_uuid' => $creditNoteItem->warehouse_uuid,
            'type'           => InventoryMovementTypeEnum::IN,
            'concept'        => "Devolución - Nota de Crédito: {$creditNoteItem->credit_note_uuid}",
            'quantity'       => $creditNoteItem->quantity,
            'cost'           => $creditNoteItem->price,
            'stock_before'   => $stockBefore, // 💡 Guardamos el estado anterior
            'stock_after'    => $stockAfter,  // 💡 Guardamos el estado nuevo
            'inventoryable_uuid'     => $creditNoteItem->credit_note_uuid,
            'inventoryable_type'   => CreditNote::class,
        ]);

    }

    /**
     * @param CreditNoteItem $creditNoteItem
     * @return void
     */
    public function updated(CreditNoteItem $creditNoteItem): void
    {
    }
}
