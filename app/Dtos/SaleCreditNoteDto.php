<?php

namespace App\Dtos;

use App\Interfaces\ArrayableDto;

class SaleCreditNoteDto extends BaseDto
{
    public function __construct(
        public string $uuid,
        public string $code,
        public string $client_name,
        public float $tax,
        public float $sub_total,
        public float $amount,
        public bool $close_table,
        public string $ncf,
        public float $n_available
    )
    {


    }


}
