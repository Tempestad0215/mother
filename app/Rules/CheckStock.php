<?php

namespace App\Rules;

use App\Factories\SaleItemFactory;
use App\Models\Inventory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckStock implements ValidationRule
{


    /**
     * Run the validation rule.
     * @param string $attribute
     * @param mixed $value
     * @param Closure(string): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $existsError = false;
        $errorMessage = '';

        $saleItem = SaleItemFactory::fromArrayList($value);

        $productIds = [];
        $warehouseIds = [];

        foreach ($saleItem as $product) {
            $productIds[] = $product->product_id;
            $warehouseIds[] = $product->warehouse_id;
        }

        $inventories = Inventory::whereIn('id', $productIds)
            ->whereIn('warehouse_id', $warehouseIds)
            ->with('product')
            ->get()
            ->keyBy('id');


        foreach ($saleItem as $item)
        {
            /**@var Inventory $inventory */
            $inventory = $inventories[$item->product_id];

            if(!$item->is_service)
            {
                if($inventory->qty_on_hand <= 0 || $item->stock > $inventory->qty_on_hand)
                {
                    $existsError = true;
                    $errorMessage = 'El Producto "' . $inventory->product->name. '" no tiene suficiente stock.';
                    break;
                }
            }

        }


        //Verificar si existe un mensaje de error
        if($existsError)
        {
            $fail($errorMessage);
        }

    }
}
