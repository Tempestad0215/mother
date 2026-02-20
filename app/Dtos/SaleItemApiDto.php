<?php

namespace App\Dtos;

class SaleItemApiDto extends BaseDto
{
    public function __construct(
        public int $product_id,
        public string $product_name,
        public float $stock,
        public float $price,
        public float $min_price,
        public float $special_price,
        public int $warehouse_id,
        public float $tax_rate,
        public int $tax_id,
        public float $price_temp,
        public float $discount,
        public float $discount_amount,
        public float $reserved,
        public float $amount,
        public bool $is_service
    )
    {
    }
}
