<?php

namespace App\Enums;

enum ProductTransactionTypeEnum: string
{
    case SALE = 'sale';
    case RETURN = 'return';
    case RESERVATION = 'reservation';
    case CANCELLED = 'cancelled';
    case ADJUSTMENT = 'adjustment';
}
