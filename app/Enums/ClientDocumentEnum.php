<?php

namespace App\Enums;

enum ClientDocumentEnum:string
{
    case Cedula = 'cedula';
    case Pasaporte = 'pasaporte';
    case  RNC = 'rnc';
    case Otro = 'otro';

    public function label():string
    {
        return match ($this) {
            self::Cedula => 'Cedula',
            self::Pasaporte => 'Pasaporte',
            self::RNC => 'RNC',
            self::Otro => 'Otro',
        };
    }

    /**
     * Retorna un arreglo asociativo clave => label (Mapa básico).
     * Ejemplo: ['CONTADO' => 'Contado', 'CREDITO' => 'Crédito', ...]
     */
    public static function map(): array
    {
        $map = [];
        foreach (self::cases() as $case) {
            $map[$case->value] = $case->label();
        }

        return $map;
    }

    /**
     * Retorna un arreglo de objetos con formato label/value ideal para PrimeVue / Frontend.
     * Ejemplo: [
     *   ['label' => 'Contado', 'value' => 'CONTADO'],
     *   ['label' => 'Crédito', 'value' => 'CREDITO'],
     *   ...
     * ]
     */
    public static function options(): array
    {
        return array_map(fn (self $case) => [
            'label' => $case->label(),
            'value' => $case->value,
        ], self::cases());
    }
}
