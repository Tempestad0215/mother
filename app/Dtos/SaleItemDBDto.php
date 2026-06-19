<?php

namespace App\Dtos;

class SaleItemDBDto extends BaseDto
{

    /**
     * @param string|null $uuid
     * @param string $sale_uuid
     * @param string $product_uuid
     * @param string $warehouse_uuid
     * @param float $stock
     * @param float $price
     * @param float $tax_rate
     * @param float $discount
     * @param float $discount_amount
     * @param float $reserved
     * @param float $amount
     * @param bool $is_service
     */
    public function __construct(
        public ?string $uuid = null,
        public string $sale_uuid,
        public string $product_uuid,
        public string $tax_uuid,
        public string $warehouse_uuid,
        public float $stock,
        public float $price,
        public float $tax_rate,
        public float $discount,
        public float $discount_amount,
        public float $reserved,
        public float $amount,
        public bool $is_service,
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
            uuid: $data['uuid'] ?? null,
            sale_uuid: $data['sale_uuid'],
            product_uuid: $data['product_uuid'],
            tax_uuid: $data['tax_uuid'],
            warehouse_uuid: $data['warehouse_uuid'],
            stock: $data['stock'],
            price: $data['price'],
            tax_rate: $data['tax_rate'],
            discount: $data['discount'],
            discount_amount: $data['discount_amount'],
            reserved: $data['reserved'],
            amount: $data['amount'],
            is_service: $data['is_service'],

        );

    }


    /**
     * @param array $data
     * @return self[]
     */
    public static function fromArrayList(array $data): array
    {
        return array_map(fn($item) => self::fromArray($item), $data);
    }
}
