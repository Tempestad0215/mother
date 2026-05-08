<?php

namespace App\Helpers;

use App\Models\Tax;
use http\Exception\InvalidArgumentException;

class TaxHelper
{

    public function getTaxById(string $tax_uuid):?Tax
    {
        return Tax::find($tax_uuid);
    }

    public static function getTaxRate(string $tax_uuid):float
    {
        $tax = self::getTaxById($tax_uuid);

        if(!$tax)
        {
            throw new InvalidArgumentException("Tax not found");
        }

        return (float)bcdiv($tax->rate, 100, 2);
    }

    public static function getTaxProduct(string $uuid, float $price): string
    {
        $tax = self::getTaxById($uuid);

        // tomar el rate
        $rate = bcdiv((string)$tax->rate, "100",2);

        // Call
        return bcmul($price, $rate, 2);
    }
}
