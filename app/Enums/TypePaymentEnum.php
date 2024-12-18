<?php

namespace App\Enums;

enum TypePaymentEnum:string
{
    case CONTADO = 'contado';
    case CREDITO = 'credito';
    case CHEQUE = 'cheque';
    case TARJETA = 'tarjeta';
    case TRANSFERENCIA = 'transferencia';
    case ANTICIPO = 'anticipo';
    case OTROS = 'otros';
}
