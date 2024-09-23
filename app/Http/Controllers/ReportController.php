<?php

namespace App\Http\Controllers;


use App\Helpers\ReportHelper;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReportController extends Controller
{
    //

    /**
     * Devolver la vista de todos los reporte
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Reports/Index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function getDailyByDate(Request $request)
    {


        //Instancia
        $reportHelper = new ReportHelper();

        //Devolver la vista con los datos
        $data = $reportHelper->getDayly($request);


        return Inertia::render('Reports/Daily/Index', [
            'report' => $data,
            'to' => Carbon::parse($request->get('to'))->format('Y-m-d H:i:s'),
            'from' => Carbon::parse($request->get('from'))->format('Y-m-d H:i:s'),
        ]);



    }

//    public function getAllDaySale(Request $request)
//    {
//        $sold = ProTrans::where('');
//    }



}
