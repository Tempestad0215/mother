<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseReceiptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Tomar el item
        $items = $this->whenLoaded('items');
        // Convertir a colección de recursos para tener los valores recalculados
        $itemResources = $items ? PurchaseItemResource::collection($items) : null;

        // Extraer los arrays con los valores ya recalculados
        $itemsArray = $itemResources ? $itemResources->resolve() : [];


        // ✅ BCMath seguro con reduce
        $subTotal = collect($itemsArray)->reduce(
            fn ($carry, $item) => bcadd($carry, (string) ($item['amount'] ?? 0), 4),
            '0'
        );

        $tax = collect($itemsArray)->reduce(
            fn ($carry, $item) => bcadd($carry, (string) ($item['tax'] ?? 0), 4),
            '0'
        );

        // Ajustar precisión final
        $subTotal = bcadd($subTotal, '0', 2);
        $tax = bcadd($tax, '0', 2);

        // Si amount en item ya incluye tax, entonces amount total = subTotal
        // Si no, descomenta: $amount = bcadd($subTotal, $tax, 2);
        $amount = $subTotal;

        $subTotalReal = bcsub($subTotal, $tax, 2);



        return [
            ...parent::toArray($request),
            'sub_total' => (float) $subTotalReal,
            'tax' => (float) $tax,
            'amount' => (float) $amount,
            'supplier' => $this->whenLoaded('supplier'),
            'total_items' => count($itemsArray),
            'items' => $itemsArray,
        ];
    }
}
