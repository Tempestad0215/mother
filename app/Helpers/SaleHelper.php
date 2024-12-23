<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Enums\ProductTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleInfoResource;
use App\Invoices\SaleInvoiceA;
use App\Models\DeletedSale;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
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
        return Sale::where('type', [SaleTypeEnum::VENTAS,SaleTypeEnum::COTIZACION])
            ->where(function (Builder $query) use ($search) {
                $query->where('client_name','like','%'.$search.'%')
                    ->orWhere('tax','like','%'.$search.'%')
                    ->orWhere('sub_total','like','%'.$search.'%')
                    ->orWhere('amount','like','%'.$search.'%');
            })
            ->latest()
            ->simplePaginate(15);

    }


    /**
     * @param StoreProductSaleRequest $request
     * @return null|string
     */
    public function store(StoreProductSaleRequest $request):string|null
    {

        //Para asegurar que se cumplan los registro
        return DB::transaction(function () use ($request) {
            //Obtener la configuracion

            //Incrementar la secuencia enviada
            SequenceHelper::incrementSequence(SequenceTypeEnum::from($request->get('invoice_type')));

            //obtener notas de credito
            $creditNotes = $request->get('credit_notes');
            //Sacar los IDS
            $ids = array_column($creditNotes, 'id');

            // Crear la venta
            $sale = Sale::create([
                'client_name' => $request->get('client_name'),
                'client_id' => $request->get('client_id') ?: null,
                'client_rnc' => $request->get('client_rnc'),
                'ncf' => $request->get('ncf'),
                'discount_amount' => $request->get('discount_amount'),
                'tax' => $request->get('tax'),
                'sub_total' => $request->get('sub_total'),
                'amount' => $request->get('amount'),
                'type' => $request->get('type'),
                'close_table' => $request->get('close_table'),
                'type_payment' => $request->get('type_payment'),
                'received' => $request->get('received'),
                'returned' => $request->get('returned'),
                'credit_notes' => $ids
            ]);

            //Actualizar los datos de la notas de credito
            CreditNoteHelper::updateAvailableFor($creditNotes, $request->get('amount'));


            //Verificar si existe el comentario
            if ($request->get('comment') !== null)
            {
                //Crear el comentario
                $sale->comment()->create([
                    'content' => $request->get('comment'),
                ]);
            }


            //Recorrer la ventas para descontar los productos
            foreach ($request->get('info_sale') as $value)
            {
                //Verificar si la mesa es cerrada
                $closeTable = $request->get('close_table');
                //Instancia
                $saleHelper = new SaleHelper();
                //Descontar los productos del inventario
                $saleHelper->processSale($closeTable, $value);

                //Para colocar el tipo
                $transType =  null;

                //Cambiar el valor dependiendo el tipo de la mesa
                if ($closeTable)
                {
                    $transType = ProductTransType::VENTAS;
                }else{
                    $transType = ProductTransType::RESERVA;
                }

                //Crear la transaccion individual
                TransHelper::store($value, $transType, $sale->uuid);

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
        $product = Product::find($info['product_id']);

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
            $idTransProduct = $request->get('info')['transID'];


            //Actualizar los datos
            ProTrans::where('id',$idTransProduct)->update([
                'deleted_at' => now(),
                'type' => ProductTransType::ELIMINADO,
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

            //Guardar los datos
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

        //Recorrer los datos
        $infoRequest->map(callback: function ($item) use (&$sale, &$closeTable, &$request) {

            //convertir la info sale a collection
            $infoSale = collect($sale->infoSale);

            //Poner la variable en 0
            $stock = 0;

            //Verificar si el item existe
            if ($infoSale->has('id'))
            {
                //Econtrar la coincidencia y tomar el stock
                $stock = $infoSale->firstWhere('product_id', $item['product_id'])['stock'];
            }

            //Buscar el producto existente
            $product = Product::find($item['product_id']);

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

            //Conseguiir notas de creditos
            $creditNotes = $request->get('credit_notes');
            //Obtener los ids
            $ids = array_column($creditNotes, 'id');

            //Actualizar los datos de la ventas
            $sale->client_id = $request->get('client_id');
            $sale->client_rnc = $request->get('client_rnc');
            $sale->client_name = $request->get('client_name');
            $sale->discount_amount = $request->get('discount_amount');
            $sale->tax = $request->get('tax');
            $sale->sub_total = $request->get('sub_total');
            $sale->amount = $request->get('amount');
            $sale->credit_notes = $ids;
            $sale->close_table = $request->get('close_table');
            $sale->returned = $request->get('returned');
            $sale->received = $request->get('received');
            $sale->save();

            //Solo guardar el comentario si es diferente de nulo
            if ($request->get('comment') !== null)
            {
                //Actualizar el comentario
                $sale->comment()->updateOrCreate(
                    ['commentable_id' => $sale->id],
                    ['content' => $request->get('comment')]
                );
            }


            //Reducir las notas de creditos seleccionada
            CreditNoteHelper::updateAvailableFor($creditNotes, $request->get('amount'));


            if ($closeTable)
            {

                //Crear la transacciones
                TransHelper::store($item, ProductTransType::VENTAS, $sale->id);


            }else{


                //Crear la transacciones
                TransHelper::store($item, ProductTransType::RESERVA, $sale->id);
                //Crear la transaccion individual
//                ProTrans::updateOrCreate([
//                    'id' => $request->get('id'),
//                    'product_id' => $item['product_id'],
//                ],
//                    [
//                        'product_id' => $item['product_id'],
//                        'product_name' => $item['product_name'],
//                        'stock' => $item['stock'],
//                        'price' => $item['price'],
//                        'tax' => $item['tax'],
//                        'tax_rate' => $item['tax_rate'],
//                        'amount' => $item['amount'],
//                        'discount' => $item['discount'],
//                        'discount_amount' => $item['discount_amount'],
//                        'type' => ProductTransType::RESERVA
//                    ]);
            }





        });

    }


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
            $query->where('client_name', 'LIKE', "%$search%");
        })->with('infoSale')
            ->latest('created_at')
            ->simplePaginate(15);



        return SaleInfoResource::collection($data)->response()->getData(true);

    }

    /**
     * Verificar la altura total
     * @param Sale $sale
     * @return int
     */
//    private function getHeigtPdf(Sale $sale):int
//    {
//        //Tonmar los datos para verificar la altura
//        $checkHeight = $sale->infoSale->where('type',ProductTransType::VENTAS);
//        //Altura total
//        $heightTotal = 200;
//        //Verificar si la altura correcta
//        $checkHeight->map(callback: function ($item, $index) use (&$heightTotal) {
//            if ($index > 4) $heightTotal += 15;
//        });
//
//        return $heightTotal;
//    }




}
