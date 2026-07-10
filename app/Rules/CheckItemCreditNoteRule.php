<?php

namespace App\Rules;

use App\Dtos\SaleItemDto;
use App\Models\CreditNoteItem;
use App\Models\Sale;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CheckItemCreditNoteRule implements ValidationRule
{


    public function __construct(
        protected Sale|null $sale
    )
    {
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Convertir los datos a dto
        $saleItemDto = SaleItemDto::fromLArrayList($value);

        // Tomar los productos de la venta, antiguo
        $saleItemOld = $this->sale->items->keyBy('product_uuid');

        $productIds = collect($saleItemDto)->pluck('product_uuid')->toArray();


        $creditNoteItems = CreditNoteItem::whereHas('creditNote', function ($query) {
                $query->where('sale_uuid', $this->sale->uuid);
            })->whereIn('product_uuid', $productIds)->get()->groupBy('product_uuid');


        // Recorrer los items de la venta
        foreach ($saleItemDto as $item){
            // tomar el producto antiguo
            $originalItem = $saleItemOld->get($item->product_uuid);


            // Si el producto no existe en la venta original, lanzar un error
            if (!$originalItem) {
                $fail("El producto {$item->product_name} no pertenece a la venta original.");
                continue;
            }

            $alreadyReturned = 0;


            // calcular todo el total devulto
            if($creditNoteItems->has($item->product_uuid)){
                $alreadyReturned = $creditNoteItems->get($item->product_uuid)->sum('quantity');
            }

            $availableToReturn = bcsub((string)$originalItem->stock,(string)$alreadyReturned, 4);


            // Si el stock es mayor al anterior, lanzar un error
            if($item->stock > (float)$availableToReturn)
            {
                $vendidoFormateado = number_format($availableToReturn, 2);

                $fail("La cantidad a devolver de '{$item->product_name}' ({$item->stock}) es mayor al saldo disponible para devolución. Máximo permitido: {$vendidoFormateado}.");
            }
        }

    }
}
