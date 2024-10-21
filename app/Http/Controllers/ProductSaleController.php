<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Helpers\CustomSaleInvoice;
use App\Helpers\ProductHelper;
use App\Helpers\SaleHelper;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ProductSaleController extends Controller
{

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function create(Request $request):RedirectResponse|Response
    {

        //Verificar si existe la configuracion
        $setting = Setting::first();

        //Si no existe redirecciona a setting
        if (!$setting)
        {
            return redirect()->route('setting.index');
        }
        //Intancia de los datos
        $dataSale = $this->dataSale($request);


        //DEvolver la vista y los datos
        return Inertia::render('ProductsSale/Create', [
            'products' => $dataSale['products'],
            'clients' => $dataSale['clients'],
            'saleOpen' => $dataSale['saleOpen'],
            'invoiceType' => config('appconfig.invoiceType'),
        ]);
    }


    /**
     * Guardar los datos de la ventas
     * @param StoreProductSaleRequest $request
     * @return Response
     */
    public function store(StoreProductSaleRequest $request)
    {

        // Evitar que se realicen 2 operaciones al mismo tiempo
        $pdf = Cache::lock('sale', 5)->get(function () use (&$request) {

            //Intancia de los datos
            $saleHelper = new SaleHelper();
            //Llamar el metodo
            return $saleHelper->store($request);
        });

        //Intancia de los datos
        $dataSale = $this->dataSale($request);


        //DEvolver la vista y los datos
        return Inertia::render('ProductsSale/Create', [
            'products' => $dataSale['products'],
            'clients' => $dataSale['clients'],
            'saleOpen' => $dataSale['saleOpen'],
            'invoiceType' => config('appconfig.invoiceType'),
            'pdf' => $pdf
        ]);

    }


    /**
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return Response
     */
    public function update(StoreProductSaleRequest $request, Sale $sale)
    {

        $pdf = DB::transaction(function () use (&$request, &$sale) {
            //Instanacia
            $saleHelper = new SaleHelper();

            //Llamar el metodo
            return $saleHelper->updateSale($request, $sale);

        });

        //Intancia de los datos
        $dataSale = $this->dataSale($request);

        //DEvolver la vista y los datos
        return Inertia::render('ProductsSale/Create', [
            'products' => $dataSale['products'],
            'clients' => $dataSale['clients'],
            'saleOpen' => $dataSale['saleOpen'],
            'invoiceType' => config('appconfig.invoiceType'),
            'pdf' => $pdf,
        ]);

    }


    /**
     * Devolver la vista con los datos
     * @param Request $request
     * @return Response
     */
    public function show(Request $request)
    {
        //Crear la instancia
        $saleHelper = new SaleHelper();

        //Tomar los datos
        $sales = $saleHelper->getSalePagination($request);

        return Inertia::render('ProductsSale/Show',[
            'sales' => $sales
        ]);
    }


    /**
     * Eliminar el producto seleccionado
     * @param Request $request
     * @param Product $product
     * @param Sale $sale
     * @return RedirectResponse
     */
    public function destroyItem(Request $request, Product $product, Sale $sale)
    {

        //Crear la instancia
        $saleHelper = new SaleHelper();

        //llamar los datos para actualizar
        $saleHelper->deleteItem($request, $product, $sale);

        return back();

    }


    /**
     * Eliminar la venta seleccionada
     * @param Request $request
     * @param Sale $sale
     * @param bool $inventoried
     * @return RedirectResponse
     */
    public function destroySale(Request $request, Sale $sale, bool $inventoried)
    {
        //Validar el comentario que llega
        Validator::make($request->all(),[
            'comment' => ['required','string','min:5','max:255'],
        ])->validate();

        //Crear la instancia
        $saleHelper = new SaleHelper();

        //llamar el metodo
        $saleHelper->deleteSale($request, $sale, $inventoried);

        return back();

    }


    /**
     * @param Request $request
     * @return array
     */
    public function dataSale(Request $request):array
    {
        $saleHelper = new SaleHelper();
        $clientHelper = new ClientHelper();
        $productHelper = new ProductHelper();

        //Obtener los datos
        $products = $productHelper->get($request);
        $clients = $clientHelper->get($request);
        $saleOpen = $saleHelper->getSaleOpen($request);

        return  [
            'products' => $products,
            'clients' => $clients,
            'saleOpen' => $saleOpen
        ];


    }


}
