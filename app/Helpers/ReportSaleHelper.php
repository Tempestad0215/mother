<?php

namespace App\Helpers;

use App\Enums\SalePaymentEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Sale;
use function PHPUnit\Framework\isNull;

class ReportSaleHelper
{

    public static function repotSaleRange($from, $to, ?SaleTypeEnum $type, ?SalePaymentEnum $typePayment)
    {
        $query = Sale::whereBetween('created_at', [$from, $to])
            ->where('status', '=', true);


        //Si existe algun tipo
        if (!isNull($type))
        {
            $query->where('type','=', $type);
        }

        //Si existe tipo de apgo
        if (!isNull($typePayment))
        {
            $query->where('type_payment','=', $typePayment);
        }

        //conseguir los datos
        return $query->get();

    }
}
