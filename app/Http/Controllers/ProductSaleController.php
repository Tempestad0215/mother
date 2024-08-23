<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Helpers\ClientHelper;
use App\Helpers\FacturaVentaA;
use App\Helpers\FacturaVentaB;
use App\Helpers\SaleHelper;
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductSaleRequest $request)
    {

        //Obtener los datos
        $products = $this->get($request);
        $clients = $this->clientHelper->get($request);
        $saleOpen = $this->getSaleOpen($request);

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
        Cache::lock('sale', 3)->get(function () use ($request) {
            //Para asegurar que se cumplan los registro
            DB::transaction(function () use ($request) {

                // Verificar si existe una venta como esa
                $sale = Sale::find($request->input('id'));


                //Actualizar o crear los productos
                if(isset($sale))
                {
                    //Actualizar los datos
                    $sale->update($request->validated());
                }else{

                    $sale = Sale::create($request->validated());
                }


                //Recorrer la ventas para descontar los productos
                foreach ($request->info as $key => $value)
                {
                    //Tomar los datos del producto
                    $product = Product::find($value['id']);

                    if ($request->close_table) {
                        // Verifica si el producto estaba previamente reservado
                        if ( $product->reserved > $value['quantity']  ) {
                            /**
                             * si la cantidad es mayor quie la reservas, pues se decuenta los productos
                             * en la base de datos y se pone en cero la reversa
                             */
                            $product->stock -= $value['quantity'];
                            $product->reserved -= $value['quantity'];  //Ajustar las reservas
                        } else {
                            // Si la cantidad aumentó antes de cerrar la mesa, calcula la diferencia.
                            $difference = $value['quantity'] - $product->reserved;

                            // Disminuye el stock en base a la cantidad total, no solo la diferencia.
                            $product->stock -= $difference;

                            // Elimina las reservas.
                            $product->reserved = 0;
                        }
                        //Gudardar los datos en la base de datios
                        $product->save();
                    } else {
                        // Verifica si la cantidad ha aumentado o disminuido
                        if ($value['quantity'] > $product->reserved) {
                            // Si la cantidad ha aumentado, reserva más stock
                            $difference = $value['quantity'] - $product->reserved;
                            $product->stock -= $difference;  // Disminuir el stock por la nueva cantidad
                            $product->reserved += $difference;  // Aumentar la reserva
                        } elseif ($value['quantity'] < $product->reserved) {
                            // Si la cantidad ha disminuido, libera parte del stock reservado
                            $difference = $product->reserved - $value['quantity'];
                            $product->stock += $difference;  // Aumentar el stock con la diferencia
                            $product->reserved -= $difference;  // Disminuir la reserva
                        }

                        $product->save();
                    }



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
     * Devolver la vista con los datos
     * @param Request $request
     * @return \Inertia\Response
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

    private function getSaleOpen(Request $request)
    {
        //tomar los datos para buscar
        $search = $request->get('search');

        //Ralizar la busqueda en la base de datos de Sale cuando el campo close_table sea false
        return Sale::where([
            ['status','=', false],
            ['close_table','=',false]
        ])->where(function ($query) use ($search) {
               $query->where('client_name','LIKE','%'.$search.'%');
            })->latest()
            ->simplePaginate(15);


    }

}
