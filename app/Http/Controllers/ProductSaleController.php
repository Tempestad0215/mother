<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Helpers\ClientHelper;
use App\Helpers\FacturaVentaA;
use App\Helpers\FacturaVentaB;
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

    /**
     * constructor de la para llamar el helpers
     */
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
        return Inertia::render('ProductsSale/Create',[
            'products' => $products,
            'clients' => $clients
        ]);
    }



    public function store(StoreProductSaleRequest $request)
    {

        //Obtener los datos
        $products = $this->get($request);
        $clients = $this->clientHelper->get($request);

        //crear la instancia del PDF
        $pdf = new FacturaVentaB();
        //Crear la pagina del PDF
        $pdf->AddPage();
        // Poner el tipo de fuente
        $pdf->SetFont('Courier', '', 8);

        //Colocar los datos de ventas
        $pdf->setSaleInfo($request->tax, $request->sub_total, $request->total, 0, $request->info);


        //Colocar el comentario
        $pdf->SetX(2);
        $pdf->Cell(30,5, 'Comentario',0,0,'L');
        $pdf->SetX(22);
        $pdf->Cell(30,5, ':',0,1,'L');
        $pdf->SetX(5);
        $pdf->MultiCell(70,3, $request->comment, 0, 'L');


        $pdf->SetAutoPageBreak(false);


        $pdfString = base64_encode($pdf->Output('S','', true));

        return Inertia::render('ProductsSale/Create',[
            'pdf' => $pdfString,
            'products' => $products,
            'clients' => $clients

        ]);


        // Evitar que se realicen 2 operaciones al mismo tiempo
//        Cache::lock('sale', 3)->get(function () use ($request) {
//            //Para asegurar que se cumplan los registro
//            DB::transaction(function () use ($request) {
//
//                // Registrar la ventas
//                $sale = Sale::create($request->validated());
//
//                //Recorrer la ventas para descontar los productos
//                foreach ($request->info as $key => $value)
//                {
//                    // Actualizar cada producto
//                    $product = Product::where('id', $value['id'])
//                        ->decrement('stock', $value['quantity']);
//
//
//                    //Crea la transaction
//                    ProTrans::create([
//                        'product_id' => $value['id'],
//                        'sale_id' => $sale->id,
//                        'stock' => $value['quantity'],
//                        'price' => $value['price'],
////                        'discount' => $value['discount'],
//                        'tax' => $value['tax'],
//                        'amount' => $value['amount'],
//                        'type' => ProductTypeEnum::SALIDA
//                    ]);
//
//                }
//
//
//
//            });
//        });
//
//        //Devolver hacia atras
//        return back();

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
