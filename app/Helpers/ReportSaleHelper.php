<?php

namespace App\Helpers;

use App\Enums\TypePaymentEnum;
use App\Models\ProTrans;
use App\Models\Sale;

class ReportSaleHelper
{


    /**
     * @param string $from
     * @param string $to
     * @param string|null $typePayment
     * @return array
     */
    public static function repotSaleRange(string $from, string $to, string $typePayment = null): array
    {

        //buscar los datos de la ventas
        $query = Sale::whereBetween('created_at', [$from, $to])
            ->where('status', '=', true);

        //Si existe tipo de apgo
        if ($typePayment)
        {

            $query->where('type_payment','=', $typePayment);
        }

        //conseguir los datos
        $data = $query->get();

        //Inicializar la variable
        $dataTotal = [];
        $productsSold = 0;

        //si exist pues se toman los datos vendido
        if ($data)
        {
            //suma los productos vendidos
            $productsSold = ProTrans::where('status', true)
                ->whereIn('sale_id', $data->pluck('id'))
                ->sum('stock');

            //Para almacenar los datos
            $dataTotal = [
                'contado' => $data->where('type_payment', TypePaymentEnum::CONTADO)->sum('amount'),

                'credito' => $data->where('type_payment', TypePaymentEnum::CREDITO)->sum('amount') ?? 0,

                'cheque' => $data->where('type_payment', TypePaymentEnum::CHEQUE)->sum('amount'),

                'tarjeta' => $data->where('type_payment', TypePaymentEnum::TARJETA)->sum('amount'),

                'anticipo' => $data->where('type_payment', TypePaymentEnum::ANTICIPO)->sum('amount'),

                'transferencia' => $data->where('type_payment', TypePaymentEnum::TRANSFERENCIA)->sum('amount'),

                'tax' => $data->sum('tax'),

                'discount' => $data->sum('discount_amount'),

                'amount' => $data->sum('amount'),
            ];
        }








        return [
            'saleInfo' => $data,
            'total' => $dataTotal,
            'totalSold' => floatval($productsSold),
        ];

    }
}
