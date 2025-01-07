<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Enums\ProductTypeEnum;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use LaravelIdea\Helper\App\Models\_IH_Product_C;

class ReportHelper
{

    /**
     * @param Request $request
     * @return array
     */
    public function getDaily(Request $request):array
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
    public function getDay(): array
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
     * @return Product[]
     */
    public function stockLow(): array
    {
        //Tomar los productos menos a 10
        $products = Product::where('status', true)
            ->where('stock','<', 10)
            ->get();

        //Vista con los datos
        return [
            'products' => $products,
            'amount' => $products->sum('price'),
        ];

    }


    /**
     * Productos mas vendido
     * @return array
     */
    public static function productMostSold():array
    {
        //Tomar los datos con mas stock vendido
        $data = Product::whereHas('trans', function (Builder $q){
            $q->where('type','=', ProductTransType::VENTAS )
            ->whereBetween('created_at', [Carbon::today()->subDays(30), Carbon::today()]);
        })->withSum('trans', 'stock')
        ->orderBy('created_at')
        ->limit(10)
        ->get();


        //Separar los datos
        $dataClean = [];

        //Tomar los datos
        $data->map(function (Product $item) use (&$dataClean) {
            $dataClean[] = [
                'id' => $item->id,
                'code' => $item->code,
                'name' => $item->name,
                'totalSaled' => $item->trans->sum('stock'),
            ];

        });

        //DEvolver los datos
        return $dataClean;

    }


    /**
     * Obtner los productos bajo de stock
     * @return Product[]|_IH_Product_C
     */
    public static function productStockLow(): _IH_Product_C|array
    {
        //Tomar los productos con stock bajo
        return Product::where('stock','<',11)
            ->where('type','=',ProductTypeEnum::PRODUCTO)
            ->orderBy('stock')
            ->limit(10)
            ->get(['id','code','name','stock']);
    }
}
