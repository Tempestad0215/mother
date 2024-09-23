<?php

namespace App\Helpers;

use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportHelper
{

    public function getDayly(Request $request)
    {

        //Tomar los datos formateado
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');


        //Relizar la busqueda
        $sale = Sale::where('status', true)
            ->where('close_table', true)
            ->whereBetween('created_at', [$from, $to])
            ->get();


        //Sumar todas las cantidades
        return  [
            'tax' => $sale->sum('tax'),
            'sub_total' => $sale->sum('sub_total'),
            'amount' => $sale->sum('amount'),
            'discount' => $sale->sum('discount_amount'),
        ];
    }
}
