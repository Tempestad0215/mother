<?php

namespace App\Dtos;

class ProductWarehouseDto extends BaseDto
{

//    Poder
    public function __construct(
        public string $product_uuid,
        public string $warehouse_uuid,
        public float $stock_quantity,
        public float $min_stock,
        public float $max_stock,
        public float $reorder_level,
        public float $committed,
    )
    {

    }

//    Convertir desde un array
    public static function fromArray(array $data):self
    {
        return new self(
            product_uuid: $data['product_uuid'] ?? '',
            warehouse_uuid: $data['warehouse_uuid'] ?? '',
            stock_quantity: $data['stock_quantity'] ?? 0,
            min_stock: $data['min_stock'] ?? 0,
            max_stock: $data['max_stock'] ?? 0,
            reorder_level: $data['reorder_level'] ?? 0,
            committed: $data['committed'] ?? 0,
        );
    }

}
