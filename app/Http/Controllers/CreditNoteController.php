<?php

namespace App\Http\Controllers;

use App\Helpers\CreditNoteHelper;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleCreditNoteResource;
use App\Models\Sale;
use App\Models\Sequence;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CreditNoteController extends Controller
{

    /**
     * @param Request $request
     * @param Sale $sale
     * @return RedirectResponse|Response
     */
    public function index(Request $request, Sale $sale):RedirectResponse|Response
    {
        //Verificar si existe la secuencio y hay B04
        $setting = Setting::first();

        //Buscar la secuencia de NCF
       $sequence = Sequence::where('type', 'B04')
            ->where('status', true)
           ->first();

       //Verificar si existe la secuencia y hay datos
       if ($setting->sequence && !$sequence)
       {
            //Devolver el mensaje con problema
            throw ValidationException::withMessages([
                'general' => "No Existen NCF Disponible Para NC (B04)"
            ]);
       }

       //Verificar si se puede devolver los articulos
        $maxDay = config('appconfig.saleCreditNote');
        //Formatear la fecha para comparar
        $maxDate = Carbon::parse($sale->created_at);


        //Verificar
        if ($maxDate->addDays($maxDay)->isPast()) {

            //Mensaje de error
            throw ValidationException::withMessages([
                'general' => 'Documento Excede El Limite De Fecha'
            ]);

        }else{
            //Crear la instancia
            $productSaleController = new ProductSaleController();

            //Intancia de los datos
            $dataSale = $productSaleController->dataSale($request);
            $saleInfo = new SaleCreditNoteResource($sale);


            //DEvolver la vista y los datos
            return Inertia::render('ProductsSale/Create', [
                'products' => $dataSale['products'],
                'clients' => $dataSale['clients'],
                'saleOpen' => $dataSale['saleOpen'],
                'invoiceType' => config('appconfig.invoiceType'),
                'saleInfo' => $saleInfo,
                'refund' => true,
            ]);
        }
    }


    /**
     * Guardar los datos
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return void
     */
    public function store(StoreProductSaleRequest $request, Sale $sale):void
    {
        //Intancia
        $creditNoteHelper = new CreditNoteHelper();

        //Llamar el metodo
        $creditNoteHelper->creditNoteStore($request, $sale);

    }


    /**
     * @param string $code
     * @return JsonResponse
     */
    public function creditNoteGet(string $code):JsonResponse
    {
        //Para buscar los datos de nota de credito
        $data = CreditNoteHelper::creditNoteGet($code);

        //Verificar que tiene sto
        if (isset($data))
        {
            //DEvolver el mensaje con los datos
            return response()->json($data);
        }
        else{
            //Deolver el mensaje de error
            return response()->json([
                'error' => 'Datos No Encontrado'
            ]);
        }

        //Devolver los datos en json

    }

}
