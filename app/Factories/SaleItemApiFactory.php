<?php

namespace App\Factories;

use App\Dtos\SaleItemApiDto;

class SaleItemApiFactory extends BaseFactory
{

    /**
     * @param array $data
     * @return SaleItemApiDto
     */
    public static function fromArray(array $data): SaleItemApiDto
    {

        return new SaleItemApiDto(
            product_uuid: $data['product_uuid'],
            product_name: $data['product_name'],
            stock: $data['stock'],
            price: $data['price'],
            min_price: $data['min_price'],
            promotional_price: $data['promotional_price'],
            warehouse_uuid: $data['warehouse_uuid'],
            tax_rate: $data['tax_rate'],
            tax_uuid: $data['tax_uuid'],
            price_temp: $data['price_temp'],
            discount: $data['discount'],
            discount_amount: $data['discount_amount'],
            reserved: $data['reserved'],
            amount: $data['amount'],
            is_service: $data['is_service'],
        );
    }
}
