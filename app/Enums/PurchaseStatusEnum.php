<?php

namespace App\Enums;

enum PurchaseStatusEnum: string
{
    case Borrador = 'Borrador';
    case Pendiente = 'Pendiente';
    case Parcial = 'Parcial';
    case Completada = 'Completada';
    case Cancelada = 'Cancelada';


    public static function options():array
    {

        return array_map(fn(self $status) => [
            'label' => $status->name,
            'value' => $status->value
        ], self::cases());
    }
}
