<?php

namespace App\Enums;

enum SaleTypeEnum:string
{
    case Ventas = 'Ventas';
    case Cotizacion = 'Cotizacion';

    case Devolucion = 'Devolucion';


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
