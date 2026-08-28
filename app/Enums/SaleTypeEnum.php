<?php

namespace App\Enums;

enum SaleTypeEnum:string
{
    case VENTAS = 'VENTAS';
    case COTIZACION = 'COTIZACION';

    case DEVOLUCION = 'DEVOLUCION';
    case TODO = 'TODO';


    /**
     * @return array
     */
    public static function options():array
    {
        return array_map(fn($case) => [
            'label' => $case->value,
            'value' => $case->name
        ], self::cases());
    }

}
