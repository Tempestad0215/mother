<?php

namespace App\Rules;

use App\Dtos\SaleItemApiDto;
use App\Factories\SaleItemApiFactory;
use App\Helpers\GeneralHelper;
use App\Helpers\InventoryHelper;
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

        $saleItemDto = [];


        foreach ($value as $item) {
            $saleItemDto[] = SaleItemApiFactory::fromArray($item);
        }

        $infoProducts = GeneralHelper::getProductWarehouseArray($value);

        $inventories = InventoryHelper::getInventoryProductWarehouse($infoProducts);

        /** @var SaleItemApiDto $item */
        foreach ($saleItemDto as $item)
        {
            $indexFind = $item->product_id.'-'.$item->warehouse_id;

            /**@var Inventory $inventory */
            $inventory = $inventories[$indexFind];

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
