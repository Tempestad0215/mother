<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use JetBrains\PhpStorm\NoReturn;

class CheckIsServiceRule implements ValidationRule
{
    #[NoReturn]
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $product = Product::find($value);


        dd($product);
    }
}
