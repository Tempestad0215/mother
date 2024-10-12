<?php

namespace App\Http\Controllers;

use App\Helpers\CreditNoteHelper;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleCreditNoteResource;
use App\Models\Sale;
use Carbon\Carbon;
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



    public function store(StoreProductSaleRequest $request, Sale $sale)
    {
        //Intancia
        $creditNoteHelper = new CreditNoteHelper();

        //Llamar el metodo
        $creditNoteHelper->creditNoteStore($request, $sale);

    }
}
