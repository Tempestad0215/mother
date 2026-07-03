<?php

namespace App\Dtos;

use App\Enums\PaymentTypeEnum;
use Carbon\Carbon;

class ReportSaleDto extends BaseDto
{

    public function __construct(
        public Carbon $from,
        public Carbon $to,
        public ?PaymentTypeEnum $type_payment,
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            from: Carbon::parse($data['from']),
            to: Carbon::parse($data['to']),
            type_payment: $data['type_payment'] != null ? PaymentTypeEnum::from($data['type_payment']) : null,
        );
    }

}
