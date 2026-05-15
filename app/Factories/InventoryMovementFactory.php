<?php

namespace App\Factories;

use App\Dtos\InventoryMovementDto;
use App\Enums\InventoryMovementConceptEnum;
use Illuminate\Support\Arr;

class InventoryMovementFactory extends BaseFactory
{
    /**
     * @param array $data
     * @return InventoryMovementDto
     */
    public static function fromArray(array $data):InventoryMovementDto
    {
        return new InventoryMovementDto(
            type: InventoryMovementConceptEnum::from($data['type']),
            product_id: (int) $data['product_id'],
            quantity: (float) $data['quantity'],
            warehouse_id: isset($data['warehouse_id']) ? (int) $data['warehouse_id'] : null,
            price: isset($data['price']) ? (float) $data['price'] : null,
            cost: isset($data['cost']) ? (float) $data['cost'] : null,
            description: $data['description'] ?? null,
        );

    }

}
