<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Enums\ProductTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleInfoResource;
use App\Models\DeletedSale;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Models\_IH_Sale_C;

class SaleHelper
{
    /**
     * @param Request $request
     * @return Sale[]|Paginator|_IH_Sale_C
     */
    public function getSalePagination(Request $request): Paginator|array|_IH_Sale_C
    {
        //Tomar los datos de busqueda
        $search = $request->get('search');

        //Buscar los datos
        return Sale::where('status', true)
            ->where('type', [SaleTypeEnum::VENTAS,SaleTypeEnum::COTIZACION])
            ->where(function (Builder $query) use ($search) {
                $query->where('client_name','like','%'.$search.'%')
                    ->orWhere('tax','like','%'.$search.'%')
                    ->orWhere('sub_total','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
            })
            ->latest()
            ->simplePaginate(15);

    }

//    /**
//     * @param int $id
//     * @param StoreProductSaleRequest $request
//     * @return void
//     */
//    public function checkToInsert(int $id,StoreProductSaleRequest $request):void
//    {
//        // Tomar los datos para instroducir
//        $saleData = $request->only(['client_name','client_id','info','discount','tax','sub_total','amount']);
//
//        //Guardar si no existe ese
//        $sale = Sale::updateOrCreate(
//            ['id' => $id],
//            $saleData
//        );
//    }

    /**
     * @param StoreProductSaleRequest $request
     * @return void
     */
    public function store(StoreProductSaleRequest $request):void
    {


        //Para asegurar que se cumplan los registro
        DB::transaction(function () use ($request) {
            //Obtener la configuracion

            //Incrementar la secuencia enviada
            SequenceHelper::incrementSequence(SequenceTypeEnum::from($request->get('invoice_type')));

            // Crear la venta
            $sale = Sale::create([
                'client_name' => $request->get('client_name'),
                'client_id' => $request->get('client_id') ?: null,
                'client_rnc' => $request->get('client_rnc'),
                'ncf' => $request->get('ncf'),
                'ncf_m' => $request->get('ncf_m'),
                'discount_amount' => $request->get('discount_amount'),
                'discount' => $request->get('discount'),
                'tax' => $request->get('tax'),
                'sub_total' => $request->get('sub_total'),
                'amount' => $request->get('amount'),
                'type' => $request->get('type'),
                'close_table' => $request->get('close_table'),
                'type_payment' => $request->get('type_payment'),
                'received' => $request->get('received'),
                'returned' => $request->get('returned'),
            ]);


            //Crear el comentario
            $sale->comment()->create([
                'content' => $request->get('comment'),
            ]);

            //Recorrer la ventas para descontar los productos
            foreach ($request->get('info_sale') as $value)
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
                    'product_name' => $value['name'],
                    'sale_id' => $sale->id,
                    'stock' => $value['stock'],
                    'price' => $value['price'],
                    'tax_rate' => $value['tax_rate'],
                    'tax' => $value['tax'],
                    'amount' => $value['amount'],
                    'discount' => $value['discount'],
                    'discount_amount' => $value['discount_amount'],
                    'type' => $request->get('close_table') ? ProductTransType::VENTAS : ProductTransType::RESERVA
                ]);

            }
        });
    }

    /**
     * @param bool $table
     * @param array $info
     * @return void
     */
    public function processSale(bool $table, array $info):void
    {
        //Tomar los datos del producto
        $product = Product::find($info['id']);

        if ($info['type'] === ProductTypeEnum::PRODUCTO->value)
        {
            //reducir el stock
            $product->stock -= $info['stock'];
        }

        //si la cuenta es abierta
        if (!$table && $info['type'] === ProductTypeEnum::PRODUCTO->value ) {

            //Redicir los productos y aumentar el contador
            $product->reserved += $info['stock'];
        }
        $product->save();

    }


//    public function processQuote(StoreProductSaleRequest $request):void
//    {
//        //Guardar los datos en la venta
//        Sale::create($request->validated());
//    }

    /**
     * @param Request $request
     * @param Product $product
     * @param Sale $sale
     * @return void
     */
    public function deleteItem(Request $request, Product $product, Sale $sale):void
    {

        //Declarar las variables
        DB::transaction(function () use ($sale, $product,$request) {
            $productStock = $request->get('info')['stock'];
            $transType = $request->get('info')['type'];
            //Id de transaction producto
            $idTransProduct = $request->get('info')['id'];

            //Actualizar los datos
            ProTrans::where('id',$idTransProduct)->update([
                'deleted_at' => now()
            ]);

            // si tiene reserva pues se descuenta ese monto
            if ($product->reserved > 0 && $transType == ProductTransType::RESERVA->value )
            {
                $product->reserved -= $productStock;
            }

            //Solo actualizar si es producto
            if($product->type === ProductTypeEnum::PRODUCTO->value )
            {
                $product->stock += $productStock;
            }

            $product->save();


        });

    }


    /**
     * Eliminar Ventas Abiertas
     * @param Request $request
     * @param Sale $sale
     * @param bool $inventoried
     * @return void
     */
    public function deleteSale(Request $request,Sale $sale, bool $inventoried):void
    {
        DB::transaction(function () use ($request, $sale, $inventoried) {
            //Poner los datos en deshabilitado
            $sale->status = false;
            $sale->save();


            //recorrer los datos de la ventas
            if ($inventoried)
            {
                foreach ($sale->infoSale as $value)
                {
                    //Buscar el producto en la lista
                    $product = Product::find($value['id']);
                    //sumar el producto
                    $product->stock += $value['quantity'];
                    //Guardar los cambios
                    $product->save();
                }
            }



            //Crear la venta eliminada
            $deleteSale = DeletedSale::create([
                'sale_id' => $sale->id,
                'info' => $sale->infoSale,
                'discount_amount' => $sale->discount_amount,
                'amount' => $sale->amount,
                'tax' => $sale->tax,
                'sub_total' => $sale->sub_total,
                'close_table' => $sale->close_table,
            ]);

            $deleteSale->comment()->create([
                'content' => $request->get('comment'),
            ]);
        });
    }


    /**
     * Actualzar los datos de las ventas
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return void
     */
    public function updateSale(StoreProductSaleRequest $request, Sale $sale):void
    {

        //Obtener la info
        $infoRequest = collect($request->get('info_sale'));
        //Verificar si esta cerrada
        $closeTable = $request->get('close_table');


        DB::transaction(function () use ($sale, $infoRequest, $closeTable, $request) {
            //Recorrer los datos
            $infoRequest->map(callback: function ($item) use ($sale, $closeTable, $request) {

                //convertir la info sale a collection
                $infoSale = collect($sale->infoSale);

                //Econtrar la coincidencia y tomar el stock
                $stock = $infoSale->firstWhere('product_id', $item['id'])['stock'];

                //Buscar el producto existente
                $product = Product::find($item['id']);

                //Restar la cantidad que llega - la registrada
                $result = $item['stock'] - $stock;

                //Verificar el resultado
                if ($result > 0)
                {

                    //Disminuir la stock
                    $product->stock -= abs($result);
                    //Auemntar la reserva
                    $product->reserved += abs($result);
                    //Guardar los datos

                }else{

                    //Auemntar el stock
                    $product->stock += abs($result);
                    //Disminuir la reserva
                    $product->reserved -= abs($result);
                    //Guardar los datos

                }
                $product->save();

                //Actualizar los datos de la ventas
                $sale->client_id = $request->get('client_id');
                $sale->client_rnc = $request->get('client_rnc');
                $sale->client_name = $request->get('client_name');
                $sale->discount_amount = $request->get('discount_amount');
                $sale->tax = $request->get('tax');
                $sale->sub_total = $request->get('sub_total');
                $sale->amount = $request->get('amount');
                $sale->close_table = $request->get('close_table');
                $sale->save();

                //Actualizar el comentario
                $sale->comment()->updateOrCreate(
                    ['commentable_id' => $sale->id],
                    ['content' => $request->get('comment')]
                );

                //Crear la transaccion individual
                ProTrans::updateOrCreate(
                    ['id' => $request->get('id')],
                    [
                    'product_id' => $item['id'],
                    'sale_id' => $sale->id,
                    'stock' => $item['stock'],
                    'price' => $item['price'],
                    'tax' => $item['tax'],
                    'amount' => $item['amount'],
                    'discount' => $item['discount'],
                    'discount_amount' => $item['discount_amount'],
                    'type' => $closeTable ? ProductTransType::VENTAS : ProductTransType::RESERVA
                ]);



            });
        });



    }


//    /**
//     * @param Request $request
//     * @return Sale[]|Paginator|_IH_Sale_C
//     */
    /**
     * @param Request $request
     * @return mixed
     */
    public function getSaleOpen(Request $request):mixed
    {
        //tomar los datos para buscar
        $search = $request->get("search", "");


        //Ralizar la busqueda en la base de datos de Sale cuando el campo close_table sea false
        $data = Sale::where(function (Builder $query) {
            $query->where('status', true)
                ->where('close_table', false);
        })->where(function (Builder $query) use ($search) {
            $query->where('client_name', 'LIKE', "%$search%")
                ->orWhereNull('client_name')
                ->orWhere('client_name','=','');
        })->with('infoSale')
            ->latest()
            ->simplePaginate(15);

        return SaleInfoResource::collection($data)->response()->getData(true);

    }



    public function creditNoteStore(StoreProductSaleRequest $request, Sale $sale)
    {
        //Asegurar que los procesos se cumplan
        DB::transaction(function () use ($request, $sale) {

            //Convertir a collection
            $infoCollect = collect($request->get('info_sale'));
            $saleCollect = collect($sale->infoSale);

            //Obtener el tipo de devolucion
            $type = $request->get('type');

            //Verificar si existe para aumentar el contador de la nota de credito
            if ($type == SaleTypeEnum::DEVOLUCION->value)
            {
                //Crear el aumento de los comprobante
                SequenceHelper::incrementSequence(SequenceTypeEnum::B04);
            }

            //Crear la devolucion
            $saleNew = Sale::create([
                'client_id' => $request->get('client_id') ?: null,
                'client_name' => $request->get('client_name'),
                'client_rnc' => $request->get('client_rnc'),
                'ncf' => $request->get('ncf'),
                'ncf_m' => $request->get('ncf_m'),
                'discount_amount' => $request->get('discount_amount'),
                'discount' => $request->get('discount'),
                'tax' => $request->get('tax'),
                'sub_total' => $request->get('sub_total'),
                'amount' => $request->get('amount'),
                'type' => SaleTypeEnum::DEVOLUCION,
                'n_available' => $request->get('amount'),
                'n_used' => $request->get('amount'),
                'close_table' => $request->get('close_table'),
                'type_payment' => $request->get('type_payment'),
                'received' => $request->get('received'),
                'returned' => $request->get('returned')
            ]);

            //Crear el comentario de la devolucion
            $saleNew->comment()->create(
                ['content' => $request->get('comment')]
            );

            //Recorrer los datos
            $infoCollect->map(callback: function ($item) use ($saleCollect, $sale) {
                $product = Product::find($item['product_id']);
                //Buscar la concidencia en los datos antiguo
                $saleInfo = $saleCollect->firstWhere('product_id', $item['product_id']);
                //sacar el resultado
                $result  =  $saleInfo['stock'] - $item['stock'];

                //Si el producto es de servicio el resultado debe ser 0
                if ($product->type === ProductTypeEnum::SERVICIO->value && $result != 0)
                {
                    // Devolver error si no coincide
                    return back()->withErrors([
                        'general' => "Por Favor, No Puede Modificar La Cantidad Del Item: $product->name "
                    ]);

                }else if ($result < 0)
                {
                    // Devolver error si no coincide
                    return back()->withErrors([
                        'general' => "Por Favor, El Item: $product->name, La Cantidad Es Mayor Que La Factura"
                    ]);
                }
                else{

                    //Crear la transaccion individual
                    ProTrans::create([
                        'product_id' => $item['product_id'],
                        'product_name' => $item['product_name'],
                        'sale_id' => $sale->id,
                        'stock' => $item['stock'],
                        'price' => $item['price'],
                        'tax_rate' => $item['tax_rate'],
                        'tax' => $item['tax'],
                        'amount' => $item['amount'],
                        'discount' => $item['discount'],
                        'discount_amount' => $item['discount_amount'],
                        'type' => ProductTransType::DEVOLUCION
                    ]);

                    //DEvolver exito
                    return  back();
                }
            });
        });

    }

}
