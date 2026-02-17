<?php

namespace App\Dtos;

use App\Enums\InventoryMovementTypeEnum;

class InventoryMovementDto
{
    public function __construct(
        public InventoryMovementTypeEnum $type,
        public int $product_id,
        public float $quantity,
        public ?int $warehouse_id = null,
        public ?float $price = null,
        public ?float $cost = null,
        public ?string $description = null,

    )
    {
    }


    public function toArray():array
    {
        return get_object_vars($this);
    }

}
