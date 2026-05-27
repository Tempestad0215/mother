<?php

namespace App\Dtos;

class SaleItemApiDto extends BaseDto
{
    public function __construct(
        public string $product_uuid,
        public string $product_name,
        public float $stock,
        public float $price,
        public float $min_price,
        public float $promotional_price,
        public string $warehouse_uuid,
        public float $tax_rate,
        public string $tax_uuid,
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
