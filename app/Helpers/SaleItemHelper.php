<?php

namespace App\Helpers;

use App\Factories\SaleItemFactory;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use Throwable;

class SaleItemHelper
{


    /**
     * @param Sale $sale
     * @param array $data
     * @return void
     * @throws Throwable
     */
    public static function multipleInsertWithSale(Sale $sale, array $data):void
    {

        DB::transaction(function() use ($data, $sale)  {
            if (empty($data))
            {
                return;
            }

            $dataForInsert = [];

            foreach ($data as $value) {

                $value['sale_id'] = $sale->id;

                $dataForInsert[] = SaleItemFactory::fromArray($value)->toArray();
            }

            // Insertar de forma masiva
            SaleItem::insert($dataForInsert);
        });



    }
}
