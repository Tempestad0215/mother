<?php

namespace App\Helpers;

use App\Dtos\PriceListProductDto;
use App\Models\Product;

class PriceListProductHelper
{
    public static function upSert(PriceListProductDto $data, Product $product):void
    {
        $product->price_list()->syncWithoutDetaching([
           $data->price_list_uuid => [
               'price' => $data->price,
               'min_price' => $data->min_price,
               'promotional_price' => $data->promotional_price,
           ]
        ]);
    }
}
