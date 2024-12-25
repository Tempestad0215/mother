<?php

namespace App\Enums;

enum TypeAccountEnum: string
{
    case PAGAR = "payable";
    case COBRAR = "receivable";
}
