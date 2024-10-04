<?php

namespace App\Helpers;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\_IH_Product_C;

class ReportHelper
{

    /**
     * @param Request $request
     * @return array
     */
    public function getDayly(Request $request):array
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

    /**
     * Ventas del dia actual
     * @return array
     */
    public function getDay()
    {
        //Busca las ventas del dia
        $sale = Sale::where('status', true)
            ->where('close_table', true)
            ->wheredate('created_at', Carbon::today())
            ->get();

        //Sumar todas las cantidades
        return  [
            'tax' => $sale->sum('tax'),
            'sub_total' => $sale->sum('sub_total'),
            'amount' => $sale->sum('amount'),
            'discount' => $sale->sum('discount_amount'),
        ];


    }


    /**
     * @return Product[]|_IH_Product_C
     */
    public function stockLow()
    {
        $products = Product::where('status', true)
            ->where('stock','<', 10)
            ->get();


        return [
            'products' => $products,
            'amount' => $products->sum('price'),
        ];

    }
}
