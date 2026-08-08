<?php

namespace App\Enums;

enum PrintFormatEnum: string
{
    case Ticket80mm = 'ticket_80mm';
    case Letter     = 'letter';
    case A4         = 'a4';

    /**
     * Obtiene los parámetros requeridos por Gotenberg según el formato.
     */
    /**
     * Retorna las dimensiones necesarias para Gotenberg en pulgadas.
     */
    public function dimensions(): array
    {
        return match ($this) {
            // Cinta / POS 80mm
            self::Ticket80mm => [
                'paperWidth'   => '3.14',
                'paperHeight'  => '0', // 0 para alto dinámico según el contenido
                'marginLeft'   => '0.1',
                'marginRight'  => '0.1',
                'marginTop'    => '0.1',
                'marginBottom' => '0.1',
            ],
            // Formato Carta (Cotizaciones)
            self::Letter => [
                'paperWidth'   => '8.5',
                'paperHeight'  => '11',
                'marginLeft'   => '0.4',
                'marginRight'  => '0.4',
                'marginTop'    => '0.4',
                'marginBottom' => '0.4',
            ],
            // Formato A4 (Órdenes de compra / Estándar)
            self::A4 => [
                'paperWidth'   => '8.27',
                'paperHeight'  => '11.69',
                'marginLeft'   => '0.4',
                'marginRight'  => '0.4',
                'marginTop'    => '0.4',
                'marginBottom' => '0.4',
            ],
        };

    }
}
