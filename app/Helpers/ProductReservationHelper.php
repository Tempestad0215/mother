<?php

namespace App\Helpers;


use App\Dtos\ProductReservationDto;
use App\Models\ProductReservation;
use App\Models\Sale;
use Laravel\Octane\Exceptions\DdException;

class ProductReservationHelper
{


    /**
     * @param ProductReservationDto $data
     * @return void
     */
    public static function createReservation(ProductReservationDto $data): void
    {
        ProductReservation::create($data);
    }

    /**
     * @param ProductReservationDto[] $data
     * @param Sale $sale
     * @return void
     */
    public static function createMultipleReservation(array $data, Sale $sale): void
    {
        $dataForInsert = [];

        foreach ($data as $item) {
            $itemArray = $item->toArray();
            $itemArray['sale_id'] = $sale->id;
            $dataForInsert[] = $itemArray;
        }

        ProductReservation::insert($dataForInsert);
    }

}
