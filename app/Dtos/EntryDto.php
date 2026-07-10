<?php

namespace App\Dtos;

class EntryDto extends BaseDto
{

    /**
     * @param string $product_uuid
     * @param string $warehouse_uuid
     * @param float $cost
     * @param float $quantity
     * @param string $reference
     */
    public function __construct(
        public string $product_uuid,
        public string $warehouse_uuid,
        public float $cost,
        public float $quantity,
        public string $reference,

    )
    {
    }

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            product_uuid: $data['product_uuid'],
            warehouse_uuid: $data['warehouse_uuid'],
            cost: $data['cost'],
            quantity: $data['quantity'],
            reference: $data['reference'],
        );
    }
}
