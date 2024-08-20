<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Carbon\Carbon;
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


    public function getDailyByDate(Request $request)
    {
        //Validar los datos
       $request->validate([
           'from' => ['required', 'date'],
           'to' => ['required', 'date'],
       ]);

       $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
       $to = Carbon::parse($request->to)->format('Y-m-d H:i:s');




       //Relizar la busqueda
        $sale = Sale::where('status', false)
            ->where('close_table', true)
            ->whereBetween('created_at', [$from, $to])
            ->get();

        //Sumar todas las cantidades
        $data = [
            'tax' => $sale->sum('tax'),
            'sub_total' => $sale->sum('sub_total'),
            'amount' => $sale->sum('amount')
        ];

        //Devolvere los datos en json
        return response()->json($data);

    }
}
