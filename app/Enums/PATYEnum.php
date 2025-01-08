<?php

namespace App\Enums;

enum PATYEnum:string
{
    case CONTADO = 'CONTADO';
    case CREDITO = 'CREDITO';
    case CHEQUE = 'CHEQUE';
    case TARJETA = 'TARJETA';
    case TRANSFERENCIA = 'TRANSFERENCAIA';
    case ANTICIPO = 'ANTICIPO';
    case OTROS = 'OTROS';
}
