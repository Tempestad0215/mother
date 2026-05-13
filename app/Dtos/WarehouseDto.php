<?php

namespace App\Dtos;

use Illuminate\Support\Collection;

class WarehouseDto extends BaseDto
{
    /**
     * @param string $warehouse_uuid
     * @param float $stock_quantity
     * @param float $min_stock
     * @param float $max_stock
     * @param float $reorder_level
     * @param bool $is_active
     */
    public function __construct(
        public string $warehouse_uuid,
        public float $stock_quantity,
        public float $min_stock = 0,
        public float $max_stock = 0,
        public float $reorder_level = 0,
        public bool $is_active = true,
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
            warehouse_uuid: $data['warehouse_uuid'],
            stock_quantity: $data['stock_quantity'],
            min_stock: $data['min_stock'] ?? 0,
            max_stock: $data['max_stock'] ?? 0,
            reorder_level: $data['reorder_level'] ?? 0,
            is_active: $data['is_active'] ?? true
        );
    }


    /**
     * @param array $data
     * @return Collection<int, self>
     */
    public static function formArrayCollection(array $data): Collection
    {
        return collect(array_map(fn($item) => WarehouseDto::fromArray($item), $data));
    }
}
