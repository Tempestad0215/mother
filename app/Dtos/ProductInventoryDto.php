<?php

namespace App\Dtos;

use App\Dtos\BaseDto;

class ProductInventoryDto extends BaseDto
{
    public function __construct(
        public int $product_id,
        public int $warehouse_id,
        public float $qty_on_hand,
        public ?float $on_order_qty = null,
        public ?float $committed = null,
        public ?float $avg_cost = null,
        public ?string $description = null,

    )
    {
    }
}
