<?php

namespace App\Dtos;

use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceSaleTypeEnum;

class SaleDto extends BaseDto
{

    /**
     * Summary of __construct
     * @param float $discount_amount
     * @param SequenceSaleTypeEnum $sale_type
     * @param float $tax
     * @param float $sub_total
     * @param float $amount
     * @param SaleTypeEnum $type
     * @param PaymentTypeEnum $type_payment
     * @param SaleItemDto $info_sale
     * @param float $received
     * @param float $returned
     * @param bool $close_table
     * @param mixed $credit_note_amount
     * @param mixed $ncf
     * @param mixed $invoice_type
     * @param mixed $client_name
     * @param mixed $client_rnc
     * @param mixed $client_uuid
     * @param mixed $credit_notes
     * @param mixed $comment
     * @param mixed $status
     */
 
    public function __construct(
        public float $discount_amount,
        public float $tax,
        public SequenceSaleTypeEnum $sale_type,
        public float $sub_total,
        public float $amount,
        public SaleTypeEnum $type,
        public PaymentTypeEnum $type_payment,
        public SaleItemDto $info_sale,
        public float $received,
        public float $returned,
        public bool $close_table,
        public ?float $credit_note_amount = null,
        public ?string $ncf = null,
        public ?string $invoice_type = null,
        public ?string $client_name = null,
        public ?string $client_rnc = null,
        public ?string $client_uuid = null,
        public ?array $credit_notes = null,
        public ?string $comment = null,
        public ?bool $status = null,

    )
    {
    }


    /**
     * 
     * Convertir los datos desde un array
     * @param array $data
     * @return SaleDto
     */
    public static function fromArray(array $data): SaleDto
    {
        return new SaleDto(
            discount_amount: $data['discount_amount'],
            sale_type: SequenceSaleTypeEnum::from($data['sale_type']),
            tax: $data['tax'],
            sub_total: $data['sub_total'],
            amount: $data['amount'],
            type: SaleTypeEnum::from($data['type']),
            type_payment: PaymentTypeEnum::from($data['type_payment']),
            info_sale: SaleItemDto::fromArray($data['info_sale']),
            received: $data['received'],
            returned: $data['returned'],
            close_table: $data['close_table'],
            credit_note_amount: $data['credit_note_amount'] ?? 0,
            ncf: $data['ncf'],
            invoice_type: $data['invoice_type'],
            client_name: $data['client_name'],
            client_rnc: $data['client_rnc'],
            client_uuid: $data['client_uuid'],
            credit_notes: $data['credit_notes'],
            comment: $data['comment'],
        );
    }

}
