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
            product_id: (int) $data['product_id'],
            sale_id: (int) $data['sale_id'],
            stock: (float) $data['stock'],
            price: (float) $data['price'],
            tax_id: (int) $data['tax_id'],
            tax_rate: (float) $data['tax_rate'],
            discount: (float) $data['discount'],
            discount_amount: (float) $data['discount_amount'],
            reserved: (float) $data['reserved'],
            amount: (float) $data['amount'],
            is_service: (bool) $data['is_service'],
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
        $baseData['sale_id'] = $sale->id;

        return $baseData;
    }
}
