<?php

namespace App\Http\Controllers;


use App\Helpers\ReportHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class REController extends Controller
{
    //

    /**
     * Devolver la vista de todos los reporte
     * @return Response
     */
    public function index(): Response
    {


        //DEvolver la vista con los datos
        return Inertia::render('Reports/Index',[
            'mostSold' => ReportHelper::productMostSold()
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function getDailyByDate(Request $request)
    {

        //Instancia
        $reportHelper = new ReportHelper();
        //Devolver la vista con los datos
        $data = $reportHelper->getDaily($request);

        //Devolver la vista con los datos
        return Inertia::render('Reports/Daily/Index', [
            'report' => $data,
            'to' => Carbon::parse($request->get('to'))->format('Y-m-d H:i:s'),
            'from' => Carbon::parse($request->get('from'))->format('Y-m-d H:i:s'),
            'title' => 'Reporte Por Fecha'
        ]);

    }

    /**
     * Ventas del dia
     * @return Response
     */
    public function getDay()
    {
        //Intancia
        $reportHelper = new ReportHelper();

        //Obtener los datos
        $data = $reportHelper->getDay();

        return Inertia::render('Reports/Daily/Index', [
            'report' => $data,
            'title' => 'Reporte Del Día'
        ]);
    }


    public function stockLow()
    {
        //Instancia
        $reportHelper = new ReportHelper();

        //Obtener los datos de producto bajo en estock
        $data = $reportHelper->stockLow();

        //Devolver la vista con los datos
        return Inertia::render('Reports/Product/Low',[
            'products' => $data['products'],
            'amount' => $data['amount'],

        ]);
    }



//    public function getAllDaySale(Request $request)
//    {
//        $sold = ProTrans::where('');
//    }



}
