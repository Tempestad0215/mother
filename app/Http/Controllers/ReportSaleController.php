<?php

namespace App\Http\Controllers;



use App\Dtos\ReportSaleDto;
use App\Enums\PaymentTypeEnum;
use App\Helpers\ReportSaleHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportSaleController extends Controller
{
    public function index(Request $request)
    {

        $request->mergeIfMissing([
            'from'        => Carbon::today()->toDateString(), // Genera '2026-07-03'
            'to'          => Carbon::today()->toDateString(), // Genera '2026-07-03'
            'type_payment' => null,                             // '*' representará "Todos" en la vista
        ]);

        $validate = $request->validate([
            'from' => ['required','date'],
            'to' => ['required','date'],
            'type_payment' => ['nullable','string',PaymentTypeEnum::class],
        ]);

        $reportSaleDto = ReportSaleDto::fromArray($validate);


        //Llamar el metodo para
        $reportSaleHelper = new ReportSaleHelper();
        //Llamar el metodo con los datos
        $data =  $reportSaleHelper->repotSaleRange($reportSaleDto);

       //Tranformar los datos
        return Inertia::render('Reports/Sale/SaleIndex',[
            'data' => $data['saleInfo'],
            'total' => $data['total'],
            'totalSold' => $data['totalSold'],
            'from' => $request->input('from'),
            'to' => $request->input('to'),
            'typePayment' => $request->input('typePayment'),
        ]);
    }


}
