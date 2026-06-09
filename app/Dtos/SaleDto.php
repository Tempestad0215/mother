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
     * @param SequenceSaleTypeEnum $invoice_type
     * @param float $tax
     * @param float $sub_total
     * @param float $amount
     * @param SaleTypeEnum $type
     * @param PaymentTypeEnum $type_payment
     * @param SaleItemDto[] $info_sale
     * @param float $received
     * @param float $returned
     * @param bool $close_table
     * @param ?float $credit_note_amount
     * @param ?string $ncf
     * @param ?string $client_name
     * @param ?string $client_rnc
     * @param ?string $client_uuid
     * @param ?array $credit_notes
     * @param ?string $comment
     * @param ?bool $status
     * @params ?string $uuid
     */
 
    public function __construct(
        public float $discount_amount,
        public float $tax,
        public SequenceSaleTypeEnum $invoice_type,
        public float $sub_total,
        public float $amount,
        public SaleTypeEnum $type,
        public PaymentTypeEnum $type_payment,
        public array $info_sale,
        public float $received,
        public float $returned,
        public bool $close_table,
        public ?float $credit_note_amount = null,
        public ?string $ncf = null,
        public ?string $client_name = null,
        public ?string $client_rnc = null,
        public ?string $client_uuid = null,
        public ?array $credit_notes = null,
        public ?string $comment = null,
        public ?bool $status = null,
        public ?bool $update = null,
        public ?string $uuid = null,

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
            invoice_type: SequenceSaleTypeEnum::from($data['invoice_type']),
            tax: $data['tax'],
            sub_total: $data['sub_total'],
            amount: $data['amount'],
            type: SaleTypeEnum::from($data['type']),
            type_payment: PaymentTypeEnum::from($data['type_payment']),
            info_sale: SaleItemDto::fromLArrayList($data['info_sale']),
            received: $data['received'],
            returned: $data['returned'],
            close_table: $data['close_table'],
            credit_note_amount: $data['credit_note_amount'] ?? 0,
            ncf: $data['ncf'] ?? null,
            client_name: $data['client_name'],
            client_rnc: $data['client_rnc'] ?? null,
            client_uuid: $data['client_uuid'] ?? null,
            credit_notes: $data['credit_notes'] ?? null,
            comment: $data['comment'] ?? null,
            status: $data['status'] ?? null,
            update: $data['update'] ?? false,
            uuid: $data['uuid'] ?? null,
        );
    }

}
