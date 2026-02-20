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
            product_id: $data['product_id'],
            product_name: $data['product_name'],
            stock: $data['stock'],
            price: $data['price'],
            min_price: $data['min_price'],
            special_price: $data['special_price'],
            warehouse_id: $data['warehouse_id'],
            tax_rate: $data['tax_rate'],
            tax_id: $data['tax_id'],
            price_temp: $data['price_temp'],
            discount: $data['discount'],
            discount_amount: $data['discount_amount'],
            reserved: $data['reserved'],
            amount: $data['amount'],
            is_service: $data['is_service'],
        );
    }
}
