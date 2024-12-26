<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CurrencyController extends Controller
{

    /**
     * @return Response
     */
    public function index(Request $request): Response
    {
        //Devolver la vista
        return Inertia::render('Setting/Currency',[
            'currencies' =>  Currency::withTrashed()->get()
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request)
    {
        //Validar los datos
        $request->validate([
            'code' => ['string','required','max:10','unique:currencies,code'],
            'name' => ['string','required','max:50'],
            'symbol' => ['string','required','max:3'],
            'is_base' => ['boolean','required'],
            'status' => ['boolean','required'],
        ]);


        //Verificar que es lo que se envia
        if ($request->is_base)
        {
            //Verificar si existe una predeterminada
            $existDefault = Currency::where('is_base', true)
                ->exists();

            // Existe algún ya predeterminado
            if ($existDefault) {
                throw ValidationException::withMessages([
                    'is_base' => 'Ya Existe Una Moneda Predetermianda'
                ]);
            }
        }

        //Guardar los datos
        Currency::create($request->toArray());

        //Devolver hacia atras
        return back();
    }


    /**
     * @param string $currency
     * @return RedirectResponse
     */
    public function restore(string $currency)
    {
        //Volver a restaurar los datos
        Currency::where('uuid', $currency)->restore();

        //Retornar hacia atras
        return back();
    }

    /**
     * @param Currency $currency
     * @return RedirectResponse
     */
    public function destroy(Currency $currency)
    {
        //Eliminar los datos
        $currency->delete();

        //Retornar hacia atras
        return back();
    }

    public function checkExchange()
    {
        // Verificar si existe la mondea primaria
        $exits = ExchangeRate::where('created_at', '!=', now())->exists();

        //Tomar los datos de la moneda
        $currencies = Currency::all();

       return response()->json([
           'exits' => $exits,
           'currencies' => $currencies
       ]);
    }




}
