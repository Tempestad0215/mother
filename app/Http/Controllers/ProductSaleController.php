<?php

namespace App\Http\Controllers;

use App\Enums\ProductTransType;
use App\Helpers\ClientHelper;
use App\Helpers\SaleHelper;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Inertia\Response;

class ProductSaleController extends Controller
{
    private ClientHelper $clientHelper;

    /**
     * constructor de la para llamar el helpers
     */
    public function __construct()
    {
        $this->clientHelper = new ClientHelper();
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {


        //Obtener los datos
        $products = $this->get($request);
        $clients = $this->clientHelper->get($request);
        $saleOpen = $this->getSaleOpen($request);


        //DEvolver la vista y los datos
        return Inertia::render('ProductsSale/Create',[
            'products' => $products,
            'clients' => $clients,
            'saleOpen' => $saleOpen
        ]);
    }


    /**
     * @param StoreProductSaleRequest $request
     * @return RedirectResponse
     */
    public function store(StoreProductSaleRequest $request)
    {

        //Obtener los datos
//        $products = $this->get($request);
//        $clients = $this->clientHelper->get($request);
//        $saleOpen = $this->getSaleOpen($request);

        //Calcular la altura
//        $tall = 200;
//
//
//        //Para aumentar el tamaño de la ventana
//        for($i = 0; $i < count($request->info); $i++){
//            if(count($request->info) > 2)
//            {
//                $tall += 20;
//            }
//        }
//
//        //crear la instancia del PDF
//        $pdf = new FacturaVentaB($tall);
//        //Crear la pagina del PDF
//        $pdf->AddPage();
//        // Poner el tipo de fuente
//        $pdf->SetFont('Courier', '', 8);
//
//        //Colocar los datos de ventas
//        $pdf->setSaleInfo($request->tax, $request->sub_total, $request->amount, 0, $request->info);
//
//
//        //Colocar el comentario
//        $pdf->SetX(2);
//        $pdf->Cell(30,5, 'Comentario',0,0,'L');
//        $pdf->SetX(22);
//        $pdf->Cell(30,5, ':',0,1,'L');
//        $pdf->SetX(5);
//        $pdf->MultiCell(70,3, $request->comment, 0, 'L');
//
//        //Poner el salto de pagina en no false
//        $pdf->SetAutoPageBreak(false);
//
//        // Codificar el pdf a base 64
//        $pdfString = base64_encode($pdf->Output('S','', true));

        // Evitar que se realicen 2 operaciones al mismo tiempo
        Cache::lock('sale', 5)->get(function () use ($request) {


            //Para asegurar que se cumplan los registro
            DB::transaction(function () use ($request) {

                // Crear la venta
                $sale = Sale::create($request->validated());

                //Recorrer la ventas para descontar los productos
                foreach ($request->info as $key => $value)
                {
                    //Verificar si la mesa es cerrada
                    $closeTable = $request->get('close_table');
                    //Instancia
                    $saleHelper = new SaleHelper();
                    //Descontar los productos del inventario
                    $saleHelper->processSale($closeTable, $value);

                    //Crear la transaccion individual
                    ProTrans::create([
                        'product_id' => $value['id'],
                        'sale_id' => $sale->id,
                        'stock' => $value['quantity'],
                        'price' => $value['price'],
                        'tax' => $value['tax'],
                        'cost' => $value['cost'],
                        'amount' => $value['amount'],
                        'discount' => $value['discount'],
                        'discount_amount' => $value['discount_amount'],
                        'type' =>  $closeTable ? ProductTransType::INTERNO : ProductTransType::VENTAS
                    ]);




                }
            });
        });


        return to_route('product-sale.create');

        //Devolver los datos con el PDF
//        return Inertia::render('ProductsSale/Create',[
//            'pdf' => $pdfString,
//            'products' => $products,
//            'clients' => $clients,
//            'saleOpen' => $saleOpen
//
//        ]);

    }

    /**
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return void
     */
    public function update(StoreProductSaleRequest $request, Sale $sale)
    {
        $close = $request->get('close_table');
        //Instanacia
        $saleHelper = new SaleHelper();

        //Llamar el metodo
        $saleHelper->updateSale($request, $sale);



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
     * @return Paginator
     */
    private  function get(Request $request)
    {
        //Obtner los datos de busqueda
        $search = $request->get('search');

        //Pasar los datos a la variable
        return Product::where('status', true)
            ->where('name','LIKE','%'.$search.'%')
            ->where('stock','>',0)
            ->latest()
            ->simplePaginate(15);
    }



    /**
     * @param Request $request
     * @return JsonResponse
     */
    private  function getJson(Request $request)
    {
        //Obtner los datos de busqueda
        $search = $request->get('search');

        //Pasar los datos a la variable
        $data = Product::where('status', true)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->limit(10)
            ->get();

        //Devolver los datos yla respuesta
        return response()->json($data);
    }

    private function getSaleOpen(Request $request)
    {
        //tomar los datos para buscar
        $search = $request->get('search');

        //Ralizar la busqueda en la base de datos de Sale cuando el campo close_table sea false
        return Sale::where([
            ['status','=', true],
            ['close_table','=',false]
        ])->where(function ($query) use ($search) {
               $query->where('client_name','LIKE','%'.$search.'%');
            })->latest()
            ->simplePaginate(15);


    }

}
