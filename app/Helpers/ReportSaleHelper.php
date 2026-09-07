<?php

namespace App\Helpers;

use App\Dtos\ReportSaleDto;
use App\Enums\PaymentTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Models\Sale;

class ReportSaleHelper
{


    /**
     * @param ReportSaleDto $reportDto
     * @return array
     */
    public function repotSaleRange(ReportSaleDto $reportDto): array
    {

        //buscar los datos de la venta
        $data = Sale::whereBetween('created_at', [$reportDto->from, $reportDto->to])
            ->where('type',SaleTypeEnum::VENTAS)
            ->where('status', true)
            ->when($reportDto->type_payment != null, function ($query) use ($reportDto) {
                $query->where('type_payment', $reportDto->type_payment);
            })->get();

        //Inicializar la variable
        $dataTotal = [];
        $productsSold = 0.0;

        //si exist, pues se toman los datos vendidos
        if ($data)
        {
            //suma los productos vendidos
            foreach ($data as $item)
            {
                $productsSold = (float)bcadd((string)$productsSold, (string)$item->items->sum('stock'),4);
            }

            //Para almacenar los datos
            $dataTotal = [
                'cash' => $data->where('type_payment', PaymentTypeEnum::CONTADO)->sum('amount'),
                'credit' => $data->where('type_payment', PaymentTypeEnum::CREDITO)->sum('amount') ?? 0,
                'check' => $data->where('type_payment', PaymentTypeEnum::Cheque)->sum('amount'),
                'card' => $data->where('type_payment', PaymentTypeEnum::TARJETA)->sum('amount'),
                'advance' => $data->where('type_payment', PaymentTypeEnum::ANTICIPO)->sum('amount'),
                'transfer' => $data->where('type_payment', PaymentTypeEnum::TRANSFERENCIA)->sum('amount'),
                'tax' => $data->sum('tax'),
                'discount' => $data->sum('discount_amount'),
                'amount' => $data->sum('amount'),
            ];
        }

        // Devolver los datgos
        return [
            'saleInfo' => $data,
            'total' => $dataTotal,
            'totalSold' => $productsSold,
        ];

    }
}
