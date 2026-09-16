<?php

namespace App\Factories;

use App\Dtos\SaleItemDto;
use App\Models\Sale;

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
            product_uuid: (int) $data['product_uuid'],
            product_name: (string) $data['product_name'],
            stock: (float) $data['stock'],
            price: (float) $data['price'],
            price_type: (string) $data['price_type'],
            min_price: (float) $data['min_price'],
            promotional_price: (float) $data['promotional_price'],
            tax_uuid: (int) $data['tax_uuid'],
            tax_rate: (float) $data['tax_rate'],
            discount: (float) $data['discount'],
            discount_amount: (float) $data['discount_amount'],
            warehouse_uuid: (string) $data['warehouse_uuid'],
            reserved: (float) $data['reserved'],
            amount: (float) $data['amount'],
            is_service: (bool) $data['is_service'],
            sale_uuid: (int) $data['sale_uuid'],
        );
    }


    /**
     * @param array $data
     * @param Sale $sale
     * @return array
     */
    public static function arrayWithSale(array $data, Sale $sale): array
    {
        $baseData = self::fromArray($data)->toArray();
        $baseData['sale_uuid'] = $sale->uuid;

        return $baseData;
    }
}
