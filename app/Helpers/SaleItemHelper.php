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

    public static function multipleUpsert(Sale $sale, array $data):void
    {
        if (empty($data))
        {
            return;
        }

//        TODO: Se debe crear el metodo para eliminar del item, pero de igual forma se debe controlar el stock y el movimiento
//        $productIds = array_column($data, 'product_id');


        $dataForInsert = array_map(function ($value) use($sale){
            return [
                ...$value,
                'sale_id' => $sale->id,
            ];
        }, $data);


        SaleItem::upsert(
            $dataForInsert,
            ['sale_id','product_id'],
            ['stock','price','tax_rate','tax_id','discount','amount','discount_amount','reserved','is_service']
        );

    }
}
