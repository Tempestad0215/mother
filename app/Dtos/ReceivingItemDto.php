<?php

namespace App\Dtos;

class ReceivingItemDto extends BaseDto
{
    /**
     * @param string $uuid
     * @param string $product_uuid
     * @param string $purchase_uuid
     * @param string $tax_uuid
     * @param string $warehouse_uuid
     * @param float $quantity
     * @param float $cost
     * @param float $discount
     * @param float $amount
     * @param string $product_name
     * @param float $tax_rate
     * @param string $warehouse_name
     * @param float $tax_amount
     */
    public function __construct(
        public string $uuid,
        public string $product_uuid,
        public string $purchase_uuid,
        public string $tax_uuid,
        public string $warehouse_uuid,
        public float  $quantity,
        public float  $cost,
        public float  $discount,
        public float  $amount,
        public string $product_name,
        public float  $tax_rate,
        public string $warehouse_name,
        public float  $tax_amount,
    )
    {
    }

    /**
     * @param array $data
     * @param string $purchase_uuid
     * @return self
     */
    public static function fromArray(array $data, string $purchase_uuid): self
    {

        return new self(
            uuid: $data['uuid'],
            product_uuid: $data['product_uuid'],
            purchase_uuid: $purchase_uuid,
            tax_uuid: $data['tax_uuid'],
            warehouse_uuid: $data['warehouse_uuid'],
            quantity: $data['quantity'],
            cost: $data['cost'],
            discount: $data['discount'],
            amount: $data['amount'],
            product_name: $data['product_name'],
            tax_rate: $data['tax_rate'],
            warehouse_name: $data['warehouse_name'],
            tax_amount: $data['tax_amount'],
        );
    }

    /**
     * @param array $items
     * @param string $purchase_uuid
     * @return array<int, self>
     */
    public static function fromListArray(array $items, string $purchase_uuid): array
    {
        return array_map(fn($item) => self::fromArray($item, $purchase_uuid), $items);
    }
}
