<?php


namespace App\Enums;

Enum ClientTypeEnum:string {
    case Contado = 'contado';
    case Credito = 'credito';
    case Anticipo = 'anticipo';
}
