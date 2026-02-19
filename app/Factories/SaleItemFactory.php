<?php

namespace App\Factories;

use App\Dtos\SaleItemDto;

class SaleItemFactory extends BaseFactory
{
    /**
     * Crea un SaleItemDto desde un array (por ejemplo, un item de info_sale del request).
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): SaleItemDto
    {
        return new SaleItemDto(
            product_id: (int) $data['product_id'],
            product_name: (string) $data['product_name'],
            stock: (float) $data['stock'],
            price: (float) $data['price'],
            min_price: (float) $data['min_price'],
            special_price: (float) $data['special_price'],
            tax_id: (int) $data['tax_id'],
            warehouse_id: (int) $data['warehouse_id'],
            tax_rate: (float) $data['tax_rate'],
            discount: (float) $data['discount'],
            discount_amount: (float) $data['discount_amount'],
            reserved: (float) $data['reserved'],
            amount: (float) $data['amount'],
            is_service: (bool) $data['is_service'],
            price_temp: (float) $data['price_temp']
        );
    }
}
