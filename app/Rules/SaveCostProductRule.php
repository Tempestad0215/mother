<?php

namespace App\Rules;

use App\Services\configService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SaveCostProductRule implements ValidationRule
{

    public function __construct(
        protected float $cost,
    )
    {

    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //Tomar el valor de proteger costo
        $saveCots = configService::get('save_cost');

        //Verifica si es true el valor
        if ($saveCots)
        {
            if ($value < $this->cost)
            {
                $fail("El {$attribute} No Puede Ser Menor a Al Costo : {$this->cost}");
            }

        }

    }
}
