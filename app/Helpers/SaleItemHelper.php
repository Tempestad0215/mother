<?php

namespace App\Helpers;

use App\Dtos\ProductInventoryDto;
use App\Dtos\SaleItemDto;
use App\Factories\ProductInventoryFactory;
use App\Factories\SaleItemFactory;
use App\Models\Sale;
use App\Models\SaleItem;
use Database\Factories\ProductFactory;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaleItemHelper
{


    /**
     * @throws Throwable
     */
    public static function multipleUpsert(Sale $sale, array $data):void
    {

        DB::transaction(function() use ($data, $sale)  {
            if (empty($data))
            {
                return;
            }

            $inventoryDtos = [];
            $dataForInsert = [];

            foreach ($data as $value) {

                $saleItemDto = SaleItemFactory::fromArray($value);

                $inventoryDtos[] = ProductInventoryFactory::fromSaleItemDto($saleItemDto);

                $dataForInsert[] = [
                    ...$value,
                    'sale_id' => $sale->id,
                ];
            }

            ProductInventoryHelper::decrementStockMultiple($inventoryDtos, $sale);

            SaleItem::insert($dataForInsert);
        });



    }
}
