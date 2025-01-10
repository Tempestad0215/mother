<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCounterRequest;
use App\Models\MoneyCounter;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Exception;

class ExchangeController extends Controller
{
    //

    /**
     * @return Response
     */
    public function index(): Response
    {
        return Inertia::render('ProductsSale/MoneyCounter');
    }


    /**
     *
     * Guardar los datos del conteo de papeleta
     */
    public function store(StoreCounterRequest $request)
    {
        try {

            //Para guardar los datos
            MoneyCounter::create($request->validated());

            //Devolver hacia atras
            return back();

        }catch (Exception $exception){
            throw ValidationException::withMessages([
                'general' => 'Error al intentar Guardar los datos'.$exception->getMessage(),
            ]);
        }
    }


    /**
     * Obtener el ultimo DPF
     * @return JsonResponse
     */
    public function get():JsonResponse
    {

        //Tomar el ultimo ID
        $money = MoneyCounter::latest('id')->first();

        //Mostrar el PDF
        $invoice = new InvoiceController();

        //Llamar el pdf
        return $invoice->getB($money);
    }

}
