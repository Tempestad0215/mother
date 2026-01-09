<?php

namespace App\Enums;

enum PaymentTypeEnum:string
{
    case Contado = 'Contado';
    case Tarjeta = 'Tarjeta';
    case Credito = 'Credito';
    case Transferencia = 'Trasnferencia';
    case Anticipo = 'Anticipo';
    case Cheque = 'Cheque';
}
