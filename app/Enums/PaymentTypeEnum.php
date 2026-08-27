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
    case TODO = 'TODO';

    public static function options():array
    {
        return array_map(fn($item) => [
            'label' => $item->value,
            'value' => $item->name
        ], self::cases());
    }
}
