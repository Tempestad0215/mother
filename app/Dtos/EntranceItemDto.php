<?php

namespace App\Dtos;

use App\Dtos\BaseDto;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class EntranceItemDto extends BaseDto
{
    /**
     * @param string $code
     * @param string $product_name
     * @param string $warehouse_uuid
     * @param float $cost
     * @param float $quantity
     * @param float $discount
     * @param string $tax_uuid
     * @param float $tax
     * @param float $amount
     */
    public function __construct(
        public string $code,
        public string $product_name,
        public string $warehouse_uuid,
        public float $cost,
        public float $quantity,
        public float $discount,
        public string $tax_uuid,
        public float $tax,
        public float $amount
    ) {}

    /**
     * @param array $data
     * @return self
     */
    public static function fromArray(array $data): self
    {
        return new self(
            code: (string) $data['code'],
            product_name: (string) $data['product_name'],
            warehouse_uuid: (string) $data['warehouse_uuid'],
            cost: (float) $data['cost'],
            quantity: (float) $data['quantity'],
            discount: (float) ($data['discount'] ?? 0),
            tax_uuid: $data['tax_uuid'],
            tax: (float) $data['tax'],
            amount: (float) $data['amount'],
        );
    }

    /**
     * @param array $data
     * @return EntranceItemDto[]
     */
    public static function fromArrayList(array $data): array
    {
        return array_map(fn($item) => self::fromArray($item), $data);
    }
}
