<?php

namespace App\Enums;

enum TRCOTYEnum:string
{
    case SALE = 'ventas';
    case PAYMENT = 'pagos';
    case DISCOUNT = 'descuentos';
    case refund = 'reembolso';
}
