<?php

namespace App\Http\Controllers;

use App\Dtos\GeneralDto;
use App\Enums\SaleTypeEnum;
use App\Helpers\ClientHelper;
use App\Helpers\ProductHelper;
use App\Helpers\SaleHelper;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Setting;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class SaleController extends Controller
{

    /**
     * Summary of index
     * @param Request $request
     * @return RedirectResponse|\Inertia\Response
     */
    public function index(Request $request):RedirectResponse|Response
    {

        //Verificar si existe la configuracion
        $setting = Setting::first();

        //Si no existe redirecciona a setting
        if (!$setting)
        {
            return redirect()->route('setting.index');
        }


        //Instancia de los datos
        $dataSale = $this->dataSale($request);
        $lastRecord = Sale::orderBy('created_at', 'desc')->first();

        //
        $warehouses = Warehouse::pluck('uuid','prefix')->toArray();

        //DEvolver la vista y los datos
        return Inertia::render('Sale/SaleCreate', [
            'products' => $dataSale['products'],
            'clients' => $dataSale['clients'],
            'saleOpen' => $dataSale['saleOpen'],
            'invoiceType' => config('appconfig.invoiceType'),
            'lastRecord' => $lastRecord?->id,
            'saleTypeEnum' => GeneralDto::getEnumToArray(SaleTypeEnum::class),
            'warehouses' => $warehouses,
        ]);
    }


    /**
     * Summary of store
     * @param StoreProductSaleRequest $request
     * @return RedirectResponse
     */

    public function store(StoreProductSaleRequest $request)
    {

        // Variable para colocar los datos
        $data = null;

        // Evitar que se realicen 2 operaciones al mismo tiempo
        Cache::lock('sale_warehouse'.auth()->id(), 5)
            ->block(3, function () use (&$request, &$data) {

            //Instancia de los datos
            $saleHelper = new SaleHelper();
            //Llamar el servicio
            $data = $saleHelper->store($request);

        });

        return back();

    }

    /**
     * 
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreProductSaleRequest $request, Sale $sale)
    {

        // Evitar que se realicen 2 operaciones al mismo tiempo
        Cache::lock('sale_warehouse'.auth()->id(), 5)
           ->block(3, function () use ($request, $sale) {
        
                //Actualizar los datos
               $sale =  DB::transaction(function () use (&$request, &$sale) {
                //Instancia
                $saleHelper = new SaleHelper();
                //Llamar la funcion
                return $saleHelper->updateSale($request, $sale);
            });
           });

       

       //DEvolver el id de la venta
       return response()->json(['pdfUuid' => $sale->id]);


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

        return Inertia::render('Sale/SaleShow',[
            'sales' => $sales
        ]);
    }


    /**
     * Eliminar el producto seleccionado
     * @param Request $request
     * @param Product $product
     * @param Sale $sale
     * @return RedirectResponse
     * @throws Throwable
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
     * @throws Throwable
     */
    public function destroySale(Request $request, Sale $sale, bool $inventoried)
    {
        //Validar el comentario que llega
        Validator::make($request->all(),[
            'comment' => ['required','string','min:5','max:255'],
        ])->validate();

        //Crear la instancia
        $saleHelper = new SaleHelper();

        //llamar el método
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

        //Obtener los datos
        $products = ProductHelper::get($request, true);
        $clients = $clientHelper->get($request);
    
        // Obtener las ventas abiertas para mostrar en la ventana de ventas abiertas
        $saleOpen = $saleHelper->getSaleOpen($request);


        // Devolver los datos
        return  [
            'products' => $products,
            'clients' => $clients,
            'saleOpen' => $saleOpen
        ];

    }


    /**
     * Mostar la ventana para crear el cierre
     * @param Request $request
     * @return Response
     */
    public function close(Request $request)
    {

        return Inertia::render('Reports/Sale/Close',[
            'users' => UserResource::collection(User::all())
        ]);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClose(Request $request)
    {
        //Obtner el codigo del usuarios
        $user = $request->input('user',1);

        //Obtner la ventas de ese usuarios por el dia
        $sale = Sale::whereHas('audits', function ($query) use ($user) {
            $query->where('user_id', $user);
        })->whereDate('sales.created_at', Carbon::today()->format("Y-m-d"))
            ->join('pro_trans as tr','sales.id','=','tr.sale_id')
            ->join('products as p','tr.product_id','=','p.id')
            ->select([
                'tr.tax',
                'tr.discount_amount',
                'tr.amount',
                'p.name',
                'p.cost',
                'tr.price',
                DB::raw('(tr.amount - tr.tax) as sub_total'),
                DB::raw('(tr.price - p.cost) as benefits')])
            ->get();

        //Obtner los datos sumado para el resultado de datos
        $data_final = [
            'tax' => $sale->sum('tax'),
            'sub_total' => $sale->sum('sub_total'),
            'discount_amount' => $sale->sum('discount_amount'),
            'amount' => $sale->sum('amount'),
            'benefits' => $sale->sum('benefits'),
        ];


        //Devolver los datos
        return response()->json([
            $data_final
        ]);

    }

    /**
     * @return Response
     */
    public function counter()
    {
        return Inertia::render('Sale/MoneyCounter');
    }
}
