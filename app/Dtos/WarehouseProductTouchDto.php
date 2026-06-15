<?php

namespace App\Dtos;

class WarehouseProductTouchDto extends BaseDto
{
    /**
     * @param string $product_uuid
     * @param string $warehouse_uuid
     * @param float $stock_quantity
     */
    public function __construct(
        public string $product_uuid,
        public string $warehouse_uuid,
        public float $stock_quantity,
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
            stock_quantity: $data['stock_quantity'],
        );
    }

    /**
     * @param array $data
     * @return WarehouseProductTouchDto[] $data
     */
    public static function fromArrayList(array $data): array
    {
        return array_map(fn($item) => self::fromArray($item), $data);
    }
}
