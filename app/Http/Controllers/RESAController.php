<?php

namespace App\Http\Controllers;



use App\Helpers\ReportSaleHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RESAController extends Controller
{
    public function index(Request $request)
    {

        //Sacar los datos
        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'));
        $typePayment = $request->get('typePayment');

        //Llmar el metodo para
        $reportSaleHelper = new ReportSaleHelper();
        //Llamar el metodo con los datos
        $data =  $reportSaleHelper->repotSaleRange($from, $to, $typePayment);

       //Tranformar los datos
        return Inertia::render('Reports/Sale/SaleIndex',[
            'data' => $data['saleInfo'],
            'total' => $data['total'],
            'totalSold' => $data['totalSold'],
            'from' => $request->get('from'),
            'to' => $request->get('to'),
            'typePayment' => $request->get('typePayment'),
        ]);
    }


}
