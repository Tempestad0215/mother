<?php

namespace App\Dtos;


class SaleItemDto extends BaseDto
{
    /**
     *
     *
     * @param string $product_uuid
     * @param string $product_name
     * @param float $stock
     * @param float $price
     * @param float $min_price
     * @param float $promotional_price
     * @param float|null $temp_price
     * @param string $tax_uuid
     * @param float $tax_rate
     * @param float $discount
     * @param float $discount_amount
     * @param string $warehouse_uuid
     * @param float $reserved
     * @param float $amount
     * @param bool $is_service
     * @param string|null $uuid
     */
    public function __construct(
        public string $product_uuid,
        public string $product_name,
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
        public ?string $uuid
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
            stock: $data['stock'],
            price: $data['price'],
            min_price: $data['min_price'] ?? 0,
            promotional_price: $data['promotional_price'] ?? 0,
            temp_price: $data['temp_price'] ?? 0,
            tax_uuid: $data['tax_uuid'],
            tax_rate: $data['tax_rate'],
            discount: $data['discount'],
            discount_amount: $data['discount_amount'],
            warehouse_uuid: $data['warehouse_uuid'],
            reserved: $data['reserved'] ?? 0,
            amount: $data['amount'],
            is_service: $data['is_service'] ?? false,
            uuid: $data['uuid'] ?? null
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


    /**
     * @return float
     */
    public function getTax():float
    {
        $subTotalBruto = bcmul((string) $this->stock, (string)$this->temp_price, 4);
        $subTotalNetp = bcsub($subTotalBruto, (string)$this->discount_amount, 4);
        $totalTax = bcmul($subTotalNetp, (string)$this->tax_rate, 4);
        return (float)$totalTax;
    }


    /**
     * @return float
     */
    public function getAmount():float
    {

        return (float)bcsub($this->amount, $this->discount_amount, 4);
    }


}
