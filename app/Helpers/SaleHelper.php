<?php

namespace App\Helpers;

use App\Enums\ProductTransType;
use App\Http\Requests\StoreProductSaleRequest;
use App\Models\DeletedSale;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleHelper
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function getSalePagination(Request $request):mixed
    {
        //Tomar los datos de busqueda
        $search = $request->get('search');

        //Buscar los datos
        return Sale::where('status', true)
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
     * @param int $id
     * @param StoreProductSaleRequest $request
     * @return void
     */
    public function checkToInsert(int $id,StoreProductSaleRequest $request):void
    {
        // Tomar los datos para instroducir
        $saleData = $request->only(['client_name','client_id','info','discount','tax','sub_total','amount']);

        //Guardar si no existe ese
        $sale = Sale::updateOrCreate(
            ['id' => $id],
            $saleData
        );
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

        //reducir el stock
        $product->stock -= $info['quantity'];

        //si la cuenta es abierta
        if (!$table) {

            //Redicir los productos y aumentar el contador
            $product->reserved += $info['quantity'];
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
        $exits = false;
        $stock = 0;


        //Verificar si existe una considencia
        foreach ($sale->info as $key => $value)
        {
            if ($value['id'] === $product->id)
            {
                $exits = true;
                $stock = $value['quantity'];
                break;
            }
        }

        //Verificar si existe algo
        if ($exits )
        {
            DB::transaction(function () use ($sale, $product, $stock, $request) {
                // si tiene reserva pues se descuenta ese monto
                if ($product->reserved > 0)
                {
                    $product->reserved -= $stock;
                }
                $product->stock += $stock;
                $product->save();

                //Actulizar los datos en la ventas
                $sale->info = $request->get('info');
                $sale->save();

            });


        }
    }


    /**
     * Eliminar Ventas Abiertas
     * @param Request $request
     * @param Sale $sale
     * @param bool $inventoried
     * @return void
     */
    public function deleteSale(Request $request,sale $sale, bool $inventoried):void
    {
        DB::transaction(function () use ($request, $sale, $inventoried) {
            //Poner los datos en deshabilitado
            $sale->status = false;
            $sale->save();


            //recorrer los datos de la ventas
            if ($inventoried)
            {
                foreach ($sale->info as $key => $value)
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
                'info' => $sale->info,
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
        $infoRequest = collect($request->get('info'));
        $infoSale = collect($sale->info);
        //Verificar si esta cerrada
        $closeTable = $request->get('close_table');


        DB::transaction(function () use ($sale, $infoRequest, $infoSale, $closeTable, $request) {
            //Recorrer los datos
            $infoRequest->map(function ($item) use ($infoSale, $sale, $closeTable, $request) {
                //buscar si existe la info
                $existsInfosale = collect($infoSale->firstWhere('id', $item['id']));
                //Toamr la cantidad de la venta
                $quantityProduct = $existsInfosale['quantity'];
                //Buscar el producto existente
                $product = Product::find($existsInfosale['id']);
                //Restar la cantidad que llega - la registrada
                $result = $item['quantity'] - $quantityProduct;

                //Verificar el resultado
                if ($result > 0)
                {

                    //Disminuir la stock
                    $product->stock -= $result;
                    //Auemntar la reserva
                    $product->reserved += $result;
                    //Guardar los datos
                    $product->save();

                }else{

                    //Auemntar el stock
                    $product->stock += abs($result);
                    //Disminuir la reserva
                    $product->reserved -= abs($result);
                    //Guardar los datos
                    $product->save();

                }

                //Actualizar los datos de la ventas
                $sale->client_id = $request->get('client_id');
                $sale->client_name = $request->get('client_name');
                $sale->info = $request->get('info');
                $sale->discount_amount = $request->get('discount_amount');
                $sale->tax = $request->get('tax');
                $sale->sub_total = $request->get('sub_total');
                $sale->amount = $request->get('amount');
                $sale->close_table = $request->get('close_table');
                $sale->save();

                //Crear la transaccion individual
                ProTrans::create([
                    'product_id' => $item['id'],
                    'sale_id' => $sale->id,
                    'stock' => $item['quantity'],
                    'price' => $item['price'],
                    'tax' => $item['tax'],
                    'cost' => $item['cost'],
                    'amount' => $item['amount'],
                    'discount' => $item['discount'],
                    'discount_amount' => $item['discount_amount'],
                    'type' =>  $closeTable ? ProductTransType::INTERNO : ProductTransType::VENTAS
                ]);

            });
        });



    }

}
