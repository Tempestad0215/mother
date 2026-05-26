<?php

namespace App\Dtos;


class InventoryMovementDto extends BaseDto
{

    /**
     * Summary of __construct
     * @param string $type
     * @param string $concept
     * @param string $product_uuid
     * @param float $quantity
     * @param mixed $warehouse_uuid
     * @param mixed $cost
     */
    public function __construct(
        public string $product_uuid,
        public float $quantity,
        public string $warehouse_uuid,
        public float $cost,
        public string $concept,
        public string $type,

    )
    {
    }

    /**
     * 
     * @param array $data
     * @return InventoryMovementDto
     */
    public function fromArray(array $data):self
    {
        return new self(
            product_uuid: $data['product_uuid'],
            quantity: $data['quantity'],
            warehouse_uuid: $data['warehouse_uuid'],
            cost: $data['cost'],
            concept: $data['concept'],
            type: $data['type'],
        );
    }


    // Crear desde un listado de array
    /**
     * 
     * @param array $data
     * @return self[]
     */
    public function fromArrayList(array $data):array
    {
        return array_map(fn($info) => self::fromArray($info), $data);
    }



}
