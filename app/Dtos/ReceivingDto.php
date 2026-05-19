<?php

namespace App\Dtos;

class ReceivingDto extends BaseDto
{

    /**
     * @param string $purchase_uuid
     * @param string $supplier_uuid
     * @param string $user_uuid
     * @param string|null $supplier_name
     * @param array<int, ReceivingItemDto> $items
     * @param string $doc_date
     * @param float $amount
     * @param float $tax
     * @param float $discount
     * @param float $sub_total
     * @param string $comment
     * @param string $status
     */
    public function __construct(
        public string          $purchase_uuid,
        public string          $supplier_uuid,
        public string          $user_uuid,
        public ?string          $supplier_name = null,
        public array           $items,
        public string          $doc_date,
        public float           $amount,
        public  float  $tax,
        public  float  $discount,
        public  float  $sub_total,
        public  string $comment,
        public  string $status,
    )
    {
    }

    /**
     * @param array $item
     * @return self
     */
    public static function fromArray(array $item):self
    {

        return new self(
            purchase_uuid: $item['uuid'],
            supplier_uuid: $item['supplier_uuid'],
            user_uuid: $item['user_uuid'],
            supplier_name: $item['supplier_name'],
            items: ReceivingItemDto::fromListArray($item['items'], $item['uuid']),
            doc_date: $item['doc_date'],
            amount: $item['amount'],
            tax: $item['tax'],
            discount: $item['discount'],
            sub_total: $item['sub_total'],
            comment: $item['comment'],
            status: $item['status']
        );
    }


}
