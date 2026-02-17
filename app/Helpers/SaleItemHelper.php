<?php

namespace App\Helpers;

use App\Dtos\SaleItemDto;
use App\Models\Sale;
use App\Models\SaleItem;

class SaleItemHelper
{
    public static function createItem(Sale $sale, SaleItemDto $data):SaleItem
    {
        $payload = $data->toArray();
        $payload['sale_id'] = $sale->id;


        return SaleItem::create($payload);
    }
}
