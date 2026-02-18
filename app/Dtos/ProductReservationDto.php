<?php

namespace App\Dtos;

use App\Enums\ProductReservationEnum;

class ProductReservationDto
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


    public function toArray():array
    {
        return get_object_vars($this);
    }

}
