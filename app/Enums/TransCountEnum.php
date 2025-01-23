<?php

namespace App\Enums;

enum TransCountEnum:string
{
    case SALE = 'ventas';
    case PAYMENT = 'pagos';
    case DISCOUNT = 'descuentos';
    case refund = 'reembolso';
}
