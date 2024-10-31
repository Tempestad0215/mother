<?php

namespace App\Http\Controllers;



use App\Helpers\ReportSaleHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportSaleController extends Controller
{
    public function index(Request $request)
    {

        //Sacar los datos
        $from = Carbon::parse($request->get('from'));
        $to = Carbon::parse($request->get('to'));
        $type = $request->get('type');
        $typePayment = $request->get('typePayment');



        //Llmar el metodo para
       $data =  ReportSaleHelper::repotSaleRange($from, $to, $type, $typePayment);


        return Inertia::render('Reports/Sale/Index',[
            'data' => $data,
        ]);
    }

}
