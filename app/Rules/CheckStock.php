<?php

namespace App\Rules;

use App\Dtos\SaleItemApiDto;
use App\Dtos\SaleItemDto;
use App\Factories\SaleItemApiFactory;
use App\Helpers\GeneralHelper;
use App\Helpers\InventoryHelper;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\WarehouseProduct;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Builder;
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

    
        // Si el valor no es un array, no se puede validar, por lo que se retorna sin hacer nada
        if(!is_array($value))
        {
            return;
        }

        // Convertir el array a una colección de SaleItemDto
        $saleItemsDto = SaleItemDto::fromLArrayList($value);

        // Variable para verificar si existe un error de stock
        $existsError = false;
        // Variable para almacenar el mensaje de error
        $errorMessage = '';

        // Tomar los product_uuid y warehouse_id para obtener el stock actual de esos productos en esos almacenes
        $productWarehousePairs = collect($saleItemsDto)->map(fn(SaleItemDto $item) => [
            'product_uuid' => $item->product_uuid,
            'warehouse_uuid' => $item->warehouse_uuid
        ])->unique()->toArray();

        // Obtener el stock actual de los productos en los almacenes correspondientes
        $currentStock = WarehouseProduct::where(function(Builder $query) use ($productWarehousePairs) {
            foreach ($productWarehousePairs as $pair) {
                $query->orWhere(function(Builder $q) use ($pair) {
                    $q->where('product_uuid', $pair['product_uuid'])
                      ->where('warehouse_uuid', $pair['warehouse_uuid']);
                });
            }
        })->get();

        // Crear un mapeo de stock actual para acceso rápido
        $stockMapping = $currentStock->keyBy(fn(WarehouseProduct $stock) => "{$stock->product_uuid}_{$stock->warehouse_uuid}");

        // Recorrer cada SaleItemDto para verificar el stock
        foreach ($saleItemsDto as $item) {

            // Crear la clave de búsqueda para el stock
            $searchStock = "{$item->product_uuid}_{$item->warehouse_uuid}";


            // Buscar el stock actual del producto en el almacén correspondiente
            /**
             * @var WarehouseProduct| null $currentStockItem 
             */
            $currentStockItem = $stockMapping->get($searchStock);

            // Actual stock
            $actualStock = $currentStockItem ? $currentStockItem->getAvailableStockAttribute() : 0;

            // Si no se encuentra el stock, se asume que es 0
            if ($actualStock <= 0 ||  $item->stock > $actualStock) {
                // Si el stock requerido es mayor que el stock disponible, se marca el error y se construye el mensaje de error
                $existsError = true;
                // Construir el mensaje de error con el nombre del producto y el nombre del almacén
                $errorMessage = 'El Producto "' . Product::find($item->product_uuid)->name . '" no tiene suficiente stock en el almacén "' . $currentStockItem->warehouse->name . '".';
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
