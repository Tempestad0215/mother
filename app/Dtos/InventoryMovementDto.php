<?php

namespace App\Dtos;


use App\Enums\InventoryMovementTypeEnum;

class InventoryMovementDto extends BaseDto
{

    /**
     * Summary of __construct
     * @param string $product_uuid
     * @param float $quantity
     * @param mixed $warehouse_uuid
     * @param mixed $cost
     * @param string $concept
     * @param float $stock_before
     * @param float $stock_after
     * @param InventoryMovementTypeEnum $type
     */
    public function __construct(
        public string $product_uuid,
        public float $quantity,
        public string $warehouse_uuid,
        public float $cost,
        public string $concept,
        public float $stock_before,
        public float $stock_after,
        public InventoryMovementTypeEnum $type,

    )
    {
    }

    /**
     *
     * @param array $data
     * @return InventoryMovementDto
     */
    public static function fromArray(array $data):self
    {
        return new self(
            product_uuid: $data['product_uuid'],
            quantity: $data['quantity'],
            warehouse_uuid: $data['warehouse_uuid'],
            cost: $data['cost'],
            concept: $data['concept'],
            stock_before: $data['stock_before'] ?? 0,
            stock_after: $data['stock_after'] ?? 0,
            type: $data['type'],
        );
    }


    // Crear desde un listado de array
    /**
     *
     * @param array $data
     * @return self[]
     */
    public static function fromArrayList(array $data):array
    {
        return array_map(fn($info) => self::fromArray($info), $data);
    }

    /**
     * @return self
     */
    public static function empty():self
    {
        return new self('', 0, '', 0, 'Sin Movimiento',  0,0, InventoryMovementTypeEnum::IN,);
    }



}
