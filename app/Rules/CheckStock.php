<?php

namespace App\Rules;

use App\Dtos\SaleItemApiDto;
use App\Dtos\SaleItemDto;
use App\Factories\SaleItemApiFactory;
use App\Helpers\GeneralHelper;
use App\Helpers\InventoryHelper;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\WarehouseProduct;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
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

        // Convetir los datos a dto
        $saleItemDto = SaleItemDto::fromLArrayList($value);

        // Variable para verificar si existe un error de stock
        $existsError = false;
        // Variable para almacenar el mensaje de error
        $errorMessage = '';



        // Recorrer los datos
        foreach ($saleItemDto as $item)
        {
            // Tomar la ventas
            $saleItem = SaleItem::where("sale_uuid", $item->sale_uuid)
                ->where("product_uuid", $item->product_uuid)
                ->first();

            // si el item no existe, continuar
            if(!$saleItem) continue;

            // sumar la cantidad de la venta
            $stockTotal = floatval(bcsub((string)$item->stock, (string)$saleItem->stock, 4));

            
            // Buscar el producto en cuestion
            $warehouseProduct = WarehouseProduct::where('product_uuid', $item->product_uuid)
                ->where('warehouse_uuid', $item->warehouse_uuid)
                ->first();


            // Verificar si existe
            if($stockTotal >  $warehouseProduct->stock_quantity)
            {
                // Enviar los datos
                $existsError = true;
                $errorMessage = "El Item Code: {$saleItem->product->code}, Nombre: {$saleItem->product->name} no tiene stock suficiente";
                break;
            }
        }


        //Verificar si existe un mensaje de error
        if($existsError)
        {
            $fail($errorMessage);
        }

    }
}
