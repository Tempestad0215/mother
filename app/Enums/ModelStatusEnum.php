<?php

namespace App\Enums;

enum ModelStatusEnum: int
{
    case Activo = 1;
    case Inactivo = 0;

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }
}
