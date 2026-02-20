<?php

namespace App\Dtos;

use BackedEnum;
use PhpParser\Builder\EnumCase;

class GeneralDto
{
    /**
     * @param class-string<BackedEnum> $data
     * @return array
     */
    public static function getEnumToArray(string $data)
    {
        return collect($data::cases())
            ->mapWithKeys(
                fn (BackedEnum $case) => [$case->name => $case->value]
            )
            ->toArray();
    }
}
