<?php

namespace App\Factories;

use App\Dtos\ProductReservationDto;
use App\Enums\ProductReservationEnum;

class ProductReservationFactory extends BaseFactory
{
    /**
     * Crea un SaleItemDto desde un array (por ejemplo, un item de info_sale del request).
     *
     * @param array<string,mixed> $data
     */
    public static function fromArray(array $data): ProductReservationDto
    {
        return new ProductReservationDto(
            product_id: (int)$data['product_id'],
            sale_id: (int)$data['sale_id'],
            warehouse_id: (int)$data['warehouse_id'],
            quantity: (float)$data['quantity'],
            status: ProductReservationEnum::from($data['status'])
        );
    }
    

}
