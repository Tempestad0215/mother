<?php

namespace App\Enums;

enum PaymentTypeEnum:string
{
    case CONTADO = 'CONTADO';
    case TARJETA = 'TARJETA';
    case CREDITO = 'CREDITO';
    case TRANSFERENCIA = 'TRANSFERENCIA';
    case ANTICIPO = 'ANTICIPO';
    case Cheque = 'CHEQUE';
}
