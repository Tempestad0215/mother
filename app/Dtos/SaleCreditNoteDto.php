<?php

namespace App\Dtos;

class SaleCreditNoteDto
{
    public function __construct(
        public int $id,
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
