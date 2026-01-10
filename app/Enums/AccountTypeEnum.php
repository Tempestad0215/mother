<?php

namespace App\Enums;

enum AccountTypeEnum: string
{
    case PAGAR = "payable";
    case COBRAR = "receivable";
}
