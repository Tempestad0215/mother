<?php

namespace App\Enums;

enum ACTYEnum: string
{
    case PAGAR = "payable";
    case COBRAR = "receivable";
}
