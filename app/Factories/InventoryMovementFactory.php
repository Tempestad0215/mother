<?php

namespace App\Factories;

use App\Dtos\InventoryMovementDto;
use App\Enums\InventoryMovementTypeEnum;
use Illuminate\Support\Arr;

class InventoryMovementFactory
{
    /**
     * @param array $data
     * @return InventoryMovementDto
     */
    public static function fromArray(array $data):InventoryMovementDto
    {
        return new InventoryMovementDto(
            type: InventoryMovementTypeEnum::from($data['type']),
            product_id: (int) $data['product_id'],
            quantity: (float) $data['quantity'],
            warehouse_id: isset($data['warehouse_id']) ? (int) $data['warehouse_id'] : null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            cost: isset($data['cost']) ? (float) $data['cost'] : null,
            description: $data['description'] ?? null,
        );

    }

    /**
     * @param array<int, array<string, mixed>> $data
     * @return InventoryMovementDto[]
     */

    public static function fromListArray(array $data):array
    {
        return Arr::map(
            $data,
            fn(array $row) => self::fromArray($row),
        );
    }

}
