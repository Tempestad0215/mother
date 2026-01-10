<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCounterRequest;
use App\Models\MoneyCounter;
use Inertia\Inertia;
use Inertia\Response;

class ExchangeController extends Controller
{
    //

    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('Sale/MoneyCounter');
    }


    /**
     *
     * Guardar los datos del conteo de papeleta
     */
    public function store(StoreCounterRequest $request)
    {

        //Para guardar los datos
        $counter = MoneyCounter::create($request->validated());


        //Devolver un json con los datos para imprimir
        return response()->json($counter);

    }



}
