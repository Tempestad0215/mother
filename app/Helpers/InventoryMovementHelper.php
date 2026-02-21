<?php

namespace App\Helpers;

use App\Enums\ProductReservationEnum;
use App\Factories\ProductReservationFactory;
use App\Models\Sale;

class InventoryMovementHelper
{

    /**
     * @param Sale $sale
     * @param array $data
     * @return void
     */
    public static function multipleInsertWithSale(Sale $sale, array $data): void
    {
        if(empty($data))
        {
            return;
        }

        $productWarehouseIds = GeneralHelper::getProductWarehouseArray($data);

        $productReservations = [];

        foreach ($data as $item) {
            $item['sale_id'] = $sale->id;
            $item['status'] = ProductReservationEnum::Active->value;
            $productReservations[] = ProductReservationFactory::fromArray($item);


        }

        ProductReservationHelper::createMultipleReservation($productReservations, $sale);

    }

}
