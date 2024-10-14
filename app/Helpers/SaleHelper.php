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
                'discount' => $request->get('discount'),
                'tax' => $request->get('tax'),
                'sub_total' => $request->get('sub_total'),
                'amount' => $request->get('amount'),
                'type' => $request->get('type'),
                'close_table' => $request->get('close_table'),
                'type_payment' => $request->get('type_payment'),
                'received' => $request->get('received'),
                'returned' => $request->get('returned'),
                'credit_notes' => $ids,
                'credit_notes_amount' => $request->get('credit_notes_amount'),
            ]);

            //Actualizar los datos de la notas de credito
            CreditNoteHelper::updateAvailableFor($creditNotes, $request->get('amount'));


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
                    'product_id' => $value['product_id'],
                    'product_name' => $value['product_name'],
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
                $sale->close_table = $request->get('close_table');
                $sale->credit_notes = $ids;
                $sale->credit_notes_amount = $request->get('credit_notes_amount');
                $sale->save();

                //Actualizar el comentario
                $sale->comment()->updateOrCreate(
                    ['commentable_id' => $sale->id],
                    ['content' => $request->get('comment')]
                );

                //Reducir las notas de creditos seleccionada
                CreditNoteHelper::updateAvailableFor($creditNotes, $request->get('amount'));

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




}
