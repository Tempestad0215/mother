<?php

namespace App\Dtos;

use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;

class SaleDto extends BaseDto
{
    public function __construct(
        public float $discount_amount,
        public float $tax,
        public float $sub_total,
        public float $amount,
        public SaleTypeEnum $type,
        public PaymentTypeEnum $type_payment,
        public float $received,
        public float $returned,
        public bool $close_table,
        public ?float $credit_note_amount = null,
        public ?string $ncf = null,
        public ?string $invoice_type = null,
        public ?string $client_name = null,
        public ?string $client_rnc = null,
        public ?int $client_id = null,
        /** @var SaleCreditNoteDto[]|null */
        public ?array $credit_notes = null,
        public ?string $comment = null,
        public ?bool $status = null,

    )
    {
    }


    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
