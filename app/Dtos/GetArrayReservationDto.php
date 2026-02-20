<?php

namespace App\Dtos;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductReservation;

class GetArrayReservationDto extends BaseDto
{
    /**
     * @param ProductReservation $productReservation
     * @param Product $product
     * @param Inventory $Inventory
     */
    public function __construct(
        public ProductReservation $productReservation,
        public Product $product,
        public Inventory $Inventory,
    )
    {
    }
}
