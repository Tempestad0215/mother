<?php

namespace App\Dtos;

use App\Interfaces\ArrayableDto;

abstract class BaseDto implements ArrayableDto
{

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
