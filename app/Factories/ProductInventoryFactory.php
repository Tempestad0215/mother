<?php

namespace App\Factories;

use App\Dtos\ProductInventoryDto;
use App\Dtos\SaleItemDto;
use App\Factories\BaseFactory;

class ProductInventoryFactory extends BaseFactory
{

    /**
     * @inheritDoc
     */
    public static function fromArray(array $data): ProductInventoryDto
    {
        return new ProductInventoryDto(
            product_id:  (int)$data['product_id'],
            warehouse_id:  (int)$data['warehouse_id'],
            qty_on_hand:  (float)$data['qty_on_hand'],
            on_order_qty: (float)$data['on_order_qty'],
            committed:  (float)$data['committed'],
            avg_cost:  (float)$data['avg_cost'],
            description:  (string)$data['description'],
        );
    }

    public static function fromSaleItemDto(SaleItemDto $saleItem, int $warehouse_id): ProductInventoryDto
    {

        return new ProductInventoryDto(
            product_id: $saleItem->product_id,
            warehouse_id:  $warehouse_id,
            qty_on_hand: $saleItem->stock,
            committed: $saleItem->reserved,
        );

    }
}
