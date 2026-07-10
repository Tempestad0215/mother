<?php

namespace App\Dtos;

class PriceListProductDto extends BaseDto
{
    public function __construct(
        public string $product_uuid,
        public string $price_list_uuid,
        public float $price,
        public float $min_price,
        public float $promotional_price
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            product_uuid: $data['product_uuid'],
            price_list_uuid: $data['price_list_uuid'],
            price: $data['price'],
            min_price: $data['min_price'],
            promotional_price: $data['promotional_price']
        );
    }
}
