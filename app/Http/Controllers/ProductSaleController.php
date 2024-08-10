<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Helpers\ClientHelper;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductSaleController extends Controller
{
    private $clientHelper;

    public function __construct()
    {
        $this->clientHelper = new ClientHelper();
    }


    /**
     * Crear la ventas de productoss
     */
    public function create(Request $request)
    {


        //Obtener los datos
        $products = $this->get($request);
        $clients = $this->clientHelper->get($request);


        //DEvolver la vista y los datos
        return Inertia::render('ProductSale/Create',[
            'products' => $products,
            'clients' => $clients
        ]);
    }


    /**
     * @param StoreProductSaleRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductSaleRequest $request)
    {

        // Evitar que se realicen 2 operaciones al mismo tiempo
        Cache::lock('sale', 3)->get(function () use ($request) {
            //Para asegurar que se cumplan los registro
            DB::transaction(function () use ($request) {

                // Registrar la ventas
                $sale = Sale::create($request->validated());

                //Recorrer la ventas para descontar los productos
                foreach ($request->info as $key => $value)
                {
                    // Actualizar cada producto
                    $product = Product::where('id', $value['id'])
                        ->decrement('stock', $value['quantity']);


                    //Crea la transaction
                    ProTrans::create([
                        'product_id' => $value['id'],
                        'sale_id' => $sale->id,
                        'stock' => $value['quantity'],
                        'price' => $value['price'],
//                        'discount' => $value['discount'],
                        'tax' => $value['tax'],
                        'amount' => $value['amount'],
                        'type' => ProductTypeEnum::SALIDA
                    ]);

                }



            });
        });

        //DEvolver hacia atras
        return back();

    }





    /**
     * @param Request $request
     * @return Paginator
     */
    private  function get(Request $request)
    {
        //Obtner los datos de busqueda
        $search = $request->get('search');

        //Pasar los datos a la variable
        $data = Product::where('status', false)
            ->where('name','LIKE','%'.$search.'%')
            ->where('stock','>',0)
            ->latest()
            ->simplePaginate(15);

        //Devolver los datos yla respuesta
        return $data;
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    private  function getJson(Request $request)
    {
        //Obtner los datos de busqueda
        $search = $request->get('search');

        //Pasar los datos a la variable
        $data = Product::where('status', 1)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->limit(10)
            ->get();

        //Devolver los datos yla respuesta
        return response()->json($data);
    }

}
