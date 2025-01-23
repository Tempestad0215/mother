<?php

namespace App\Helpers;

use App\Enums\SalePaymentTypeEnum;
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
    public function repotSaleRange(string $from, string $to, string $typePayment = null): array
    {

        //buscar los datos de la ventas
        $query = Sale::whereBetween('created_at', [$from, $to])
            ->where('status', true);

        //Si existe tipo de apgo
        if ($typePayment)
        {
            $query->where('type_payment', $typePayment);
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
                ->whereIn('sale_id', $data->pluck('uuid'))
                ->sum('stock');

            //Para almacenar los datos
            $dataTotal = [
                'cash' => $data->where('type_payment', SalePaymentTypeEnum::CONTADO)->sum('amount'),

                'credit' => $data->where('type_payment', SalePaymentTypeEnum::CREDITO)->sum('amount') ?? 0,

                'check' => $data->where('type_payment', SalePaymentTypeEnum::CHEQUE)->sum('amount'),

                'card' => $data->where('type_payment', SalePaymentTypeEnum::TARJETA)->sum('amount'),

                'advance' => $data->where('type_payment', SalePaymentTypeEnum::ANTICIPO)->sum('amount'),

                'transfer' => $data->where('type_payment', SalePaymentTypeEnum::TRANSFERENCIA)->sum('amount'),

                'tax' => $data->sum('tax'),

                'discount' => $data->sum('discount_amount'),

                'amount' => $data->sum('amount'),
            ];
        }

        // Devolver los datgos
        return [
            'saleInfo' => $data,
            'total' => $dataTotal,
            'totalSold' => floatval($productsSold),
        ];

    }
}
