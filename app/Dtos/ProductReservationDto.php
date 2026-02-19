<?php

namespace App\Dtos;

use App\Enums\ProductReservationEnum;

class ProductReservationDto extends BaseDto
{

    public function __construct(
        public int $product_id,
        public int $sale_id,
        public int $warehouse_id,
        public float $quantity,
        public ProductReservationEnum $status
    )
    {
    }


}
