<?php

namespace App\Dtos;

class PurchaseRequestItemDto extends BaseDto
{
    public function __construct(
        public readonly string $product_uuid,
        public readonly float $quantity,
        public readonly float $cost,
        public readonly string $warehouse_uuid,
        public readonly string $tax_uuid,
        public readonly float $tax,
        public readonly ?float $discount_rate,
        public readonly ?float $discount,
        public readonly ?float $amount
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            product_uuid: $data['uuid'],
            quantity: (float) $data['quantity'],
            cost: (float) $data['cost'],
            warehouse_uuid: $data['warehouse_uuid'],
            tax_uuid: $data['tax_uuid'],
            tax: (float) $data['tax'],
            discount_rate: isset($data['discount_rate']) ? (float) $data['discount_rate'] : null,
            discount: isset($data['discount_amount']) ? (float) $data['discount_amount'] : null,
            amount: isset($data['amount']) ? (float) $data['amount'] : null
        );
    }
}
