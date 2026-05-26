<?php

namespace App\Helpers;

use App\Dtos\SaleItemDto;
use App\Factories\SaleItemFactory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaleItemHelper
{


    /**
     * @param Sale $sale
     * @param SaleItemDto[] $data
     * @return void
     * @throws Throwable
     */
    public static function multipleInsertWithSale(Sale $sale, array $data):void
    {

        // Si no hay datos, no hacemos nada
        if (empty($data))
        {
            return;
        }

        // Formatear los datos para la inserción masiva
        $fomattedData = collect($data)->map(fn(SaleItemDto $item) => $item->toArray())->all();
    
        // Crear los objetos SaleItemFactory a partir de los datos y convertirlos a array para la inserción masiva
        $sale->items()->createMany($fomattedData);





    }
}
