<?php

namespace App\Factories;

use Illuminate\Support\Arr;



abstract class BaseFactory
{
    /**
     * @param array $data
     * @return object
     */
    abstract public static function fromArray(array $data): object;


    /**
     * @param array $data
     * @return array
     */
    public static function fromListArray(array $data): array
    {

        return Arr::map(
            $data,
            fn(array $row) => static::fromArray($row)
        );
    }
}
