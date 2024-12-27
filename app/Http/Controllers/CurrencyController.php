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
     * @param Request $request
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
