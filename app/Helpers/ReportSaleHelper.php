<?php

namespace App\Helpers;

use App\Enums\SalePaymentEnum;
use App\Enums\SaleTypeEnum;
use App\Http\Resources\SaleDateRangeResource;
use App\Models\ProTrans;
use App\Models\Sale;
use function PHPUnit\Framework\isNull;

class ReportSaleHelper
{


    /**
     * @param string $from
     * @param string $to
     * @param string|null $typePayment
     * @return array
     */
    public static function repotSaleRange(string $from, string $to, string $typePayment = null)
    {


        $query = Sale::whereBetween('created_at', [$from, $to])
            ->where('status', '=', true);


//        dd()


        //Si existe tipo de apgo
        if (!isNull($typePayment))
        {
            $query->where('type_payment','=', $typePayment);
        }

        //conseguir los datos
        $data = $query->get();



//        dd($data->pluck('id')->toArray());

        //suma los productos vendidos
        $productsSold = ProTrans::where('status', true)
            ->whereIn('sale_id', $data->pluck('id'))
            ->sum('stock');



        //Para almacenar los datos
        $dataTotal = [
            'contado' => $data->where('type_payment', SalePaymentEnum::CONTADO)->sum('amount'),

            'credito' => $data->where('type_payment', SalePaymentEnum::CREDITO)->sum('amount') ?? 0,

            'cheque' => $data->where('type_payment', SalePaymentEnum::CHEQUE)->sum('amount'),

            'tarjeta' => $data->where('type_payment', SalePaymentEnum::TARJETA)->sum('amount'),

            'anticipo' => $data->where('type_payment', SalePaymentEnum::ANTICIPO)->sum('amount'),

            'transferencia' => $data->where('type_payment', SalePaymentEnum::TRANSFERENCIA)->sum('amount'),

            'tax' => $data->sum('tax'),

            'discount' => $data->sum('discount_amount'),

            'amount' => $data->sum('amount'),
        ];


        return [
            'saleInfo' => $data,
            'total' => $dataTotal,
            'totalSold' => floatval($productsSold),
        ];

    }
}
