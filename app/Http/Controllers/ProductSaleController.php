<?php

namespace App\Http\Controllers;

use App\Helpers\ClientHelper;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;

class ProductSaleController extends Controller
{
    private $clientHelper;

    public function __construct()
    {
        $this->clientHelper = new ClientHelper();
    }


    /**
     * Crear la ventas de productos
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


    public function store(Request $request)
    {

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
