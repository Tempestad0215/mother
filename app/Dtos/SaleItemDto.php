<?php

namespace App\Dtos;


class SaleItemDto extends BaseDto
{
    /**
     * 
     * 
     * @param string $product_uuid
     * @param string $product_name
     * @param string $sale_uuid
     * @param float $stock
     * @param float $price
     * @param string $tax_uuid
     * @param float $tax_rate
     * @param float $discount
     * @param float $discount_amount
     * @param float $reserved
     * @param float $amount
     * @param bool $is_service
     */
    public function __construct(
        public string $product_uuid,
        public string $product_name,
        public string $sale_uuid,
        public float $stock,
        public float $price,
        public float $min_price,
        public float $promotional_price,
        public ?float $temp_price,
        public string $tax_uuid,
        public float $tax_rate,
        public float $discount,
        public float $discount_amount,
        public string $warehouse_uuid,
        public float $reserved,
        public float $amount,
        public bool $is_service,
    ) {}


    /**
     * Convertir los datos desde un array
     * @param array $data
     * @return SaleItemDto
     */
    public static function fromArray(array $data): SaleItemDto
    {
        return new SaleItemDto(
            product_uuid: $data['product_uuid'],
            product_name: $data['product_name'],
            sale_uuid: $data['sale_uuid'],
            stock: $data['stock'],
            price: $data['price'],
            min_price: $data['min_price'],
            promotional_price: $data['promotional_price'],
            temp_price: $data['temp_price'],
            tax_uuid: $data['tax_uuid'],
            tax_rate: $data['tax_rate'],
            discount: $data['discount'],
            warehouse_uuid: $data['warehouse_uuid'],
            discount_amount: $data['discount_amount'],
            reserved: $data['reserved'],
            amount: $data['amount'],
            is_service: $data['is_service'],
        );
    }

    /**
     * De un array de datos, convertirlo a una lista de SaleItemDto
     * @param array $data
     * @return SaleItemDto[]
     */
    public static function fromLArrayList(array $data):array
    {
        return array_map(fn($info) => self::fromArray($info), $data);
    }
    

}
