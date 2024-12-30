<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\ExchangeRate;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CurrencyController extends Controller
{


    /**
     * @param string $month
     * @param int $year
     * @return JsonResponse
     */
    public function getExchange(string $month, int $year):JsonResponse
    {
        // tomar los datos busqueda
        $exchange = ExchangeRate::where('month', $month)
            ->where('year', $year)
            ->first();

        // Enviar mensaje de que no fue encontrado
        if(!$exchange)
        {
            return response()->json([
                'message' => 'Datos No Encontrado',

            ],200);
        }

        // Repuesta
        return response()->json([
            'message' => 'Datos Obtenido Correctamente',
            'data' => $exchange
        ]);
    }



    /**
     * Guardar los exchange
     * @param Request $request
     * @return JsonResponse
     */
    public function exchangeStore(Request $request):JsonResponse
    {

        //Validar los datos
        $validator = Validator::make($request->all(), [
            'month' => ['required','numeric','between:1,12'],
            'year' => ['required','numeric'],
            'rate_info' => ['required','array'],
            'rate_info.*.day' => ['required','numeric','min:1','max:31'],
            'rate_info.*.usd' => ['required','numeric'],
            'rate_info.*.eur' => ['required','numeric'],
            'rate_info.*.dop' => ['required','numeric'],
        ]);

        //Si la validacion falla
        if ($validator->fails())
        {
            //Devovler el mensjae al validar los datos
            return response()->json([
                'message' => 'Error Al Validar Los Datos',
                'errors' => $validator->errors()
            ],422);
        }

        //Verificar si existe alguna con esos datos
        $exchange = ExchangeRate::where('month',$request->month)
            ->where('year',$request->year)
            ->first();

        //verificar si existe solo para actualizar
        if ($exchange)
        {

            //Actualizar los datos del exchange
            $exchange->rate_info = $request->rate_info;
            $exchange->save();

        }else{

            //Crear los datos ya validados
            $exchange = ExchangeRate::create($validator->validated());
        }

        // Repuesta con el
        return response()->json([
            'message' => 'Registro Creado Correctamente',
            'data' => $exchange
        ]);
    }
}
