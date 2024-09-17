<?php

namespace App\Helpers;

use App\Http\Requests\StoreProductSaleRequest;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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

        //Verifica si la mesa fue cerrada
        if ($table) {


            // Verifica si el producto estaba previamente reservado
            if ( $product->reserved > $info['quantity']  ) {
                /**
                 * si la cantidad es mayor quie la reservas, pues se decuenta los productos
                 * en la base de datos y se pone en cero la reversa
                 */
                $product->reserved -= $info['quantity'];
                $product->stock -= $info['quantity'];
                //Ajustar las reservas


            } else {
                // Si la cantidad aumentó antes de cerrar la mesa, calcula la diferencia.
                $difference = $info['quantity'] - $product->reserved;

                // Disminuye el stock en base a la cantidad total, no solo la diferencia.
                $product->stock -= $difference;
                // Elimina las reservas.
                $product->reserved = 0;
            }
            //Gudardar los datos en la base de datios
            $product->save();



        } else {


            // Verifica si la cantidad ha aumentado o disminuido
            if ($info['quantity'] > $product->reserved) {
                // Si la cantidad ha aumentado, reserva más stock
                $difference = $info['quantity'] - $product->reserved;

                //Aumentar la reserva y disminuir el stock
                $product->reserved += $difference;
                $product->stock -= $difference;


            } else {
                // Si la cantidad ha disminuido, libera parte del stock reservado
                $difference = $product->reserved - $info['quantity'];

                //Aumentar el stock y disminuir la reserva
                $product->stock += $difference;
                $product->reserved -= $difference;
            }

            $product->save();
        }

    }


}
