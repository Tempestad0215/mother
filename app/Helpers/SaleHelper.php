<?php

namespace App\Helpers;

use App\Dtos\InventoryMovementDto;
use App\Dtos\SaleItemDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Enums\TransTypeEnum;
use App\Enums\ProductTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Factories\SaleFactory;
use App\Factories\SaleItemFactory;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleInfoResource;
use App\Models\DeletedSale;
use App\Models\Product;
use App\Models\ProTrans;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use LaravelIdea\Helper\App\Models\_IH_Sale_C;
use Throwable;

class SaleHelper
{
    /**
     * @param Request $request
     * @return Sale[]|Paginator|_IH_Sale_C
     */
    public function getSalePagination(Request $request): Paginator|array|_IH_Sale_C
    {
        //Tomar los datos de búsqueda
        $search = $request->input('search');

        //Buscar los datos
        return Sale::whereIn('type', [SaleTypeEnum::Ventas,SaleTypeEnum::Cotizacion])
            ->where(function (Builder $query) use ($search) {
                $query->where('client_name', 'like', "%$search%")
                    ->orWhere('code', 'like', "%$search%");
            })
            ->latest()
            ->simplePaginate(15);

    }


    /**
     * @param StoreProductSaleRequest $request
     * @return Sale|mixed
     * @throws Throwable
     *
     */
    public function store(StoreProductSaleRequest $request):Sale|null
    {

        //Para asegurar que se cumplan los registro
         return DB::transaction(function () use ($request) {
            //Obtener la configuración
             $setting = Setting::first();

            //Incrementar la secuencia enviada
            SequenceHelper::incrementSequence(SequenceSaleTypeEnum::from($request->invoice_type));

            //obtener notas de crédito
            $salePayload = SaleFactory::fromRequest($request, $setting);

            // Crear la venta
            $sale = Sale::create($salePayload->toArray());

            //Actualizar los datos de la notas de crédito
            CreditNoteHelper::updateAvailableFor($salePayload->credit_notes, $salePayload->amount);

            $rawInfoSale = $request->input('info_sale',[]);

             $infoSale = SaleItemFactory::fromArrayList($rawInfoSale);

             //Recorrer la venta para descontar los productos
             foreach ($infoSale as $value)
            {
                //Verificar si la mesa es cerrada
                $closeTable = (bool)$request->input('close_table');
                //Instancia
                //Descontar los productos del inventario
                self::processSale($closeTable, $value, $salePayload->type);

                $typeMovement = $this->movementType($salePayload->type);

                $movementPayload = new InventoryMovementDto(
                    type: $typeMovement,
                    product_id: $value->product_id,
                    quantity: $value->stock,
                    warehouse_id: $value->warehouse_id,
                    price: $value->price,
                );

                 ProductHelper::decrementStock($movementPayload);

//                // Buscar el producto para crear la transaction
//                $product = Product::find($value->product_id);
//
//                // Actualizar los datos del producto para actualizar
//                $product->stock -= $value->stock;
//                $product->reserved += $value->stock;
//                $product->save();

                //Crear la transaccion individual
                SaleItemHelper::createItem($sale, $value);

            }

            return $sale;
        });
    }


    /**
     * @param SaleTypeEnum $saleType
     * @return InventoryMovementTypeEnum
     */
    public function movementType(SaleTypeEnum $saleType): InventoryMovementTypeEnum
    {
        if($saleType == SaleTypeEnum::Ventas){
            return InventoryMovementTypeEnum::Venta;
        }else if($saleType == SaleTypeEnum::Devolucion){
            return InventoryMovementTypeEnum::Devolucion;
        }else{
            return InventoryMovementTypeEnum::Cotizacion;
        }
    }

    /**
     * @param bool $table
     * @param SaleItemDto $info
     * @param SaleTypeEnum $saleType
     * @return void
     */
    public static function processSale(bool $table, SaleItemDto $info, SaleTypeEnum $saleType):void
    {
        //Tomar los datos del producto
        $product = Product::find($info->product_id);

        if (!$info->is_service && $saleType !== SaleTypeEnum::Cotizacion && $saleType !== SaleTypeEnum::Devolucion)
        {
            //reducir el stock
            $product->stock -= $info->stock;
        }

        //si la cuenta es abierta
        if (!$table && !$info->is_service) {

            //Reducir los productos y aumentar el contador
            $product->reserved += $info->stock;
        }
        $product->save();

    }


    /**
     * @param Request $request
     * @param Product $product
     * @param Sale $sale
     * @return void
     * @throws Throwable
     */
    public function deleteItem(Request $request, Product $product, Sale $sale):void
    {

        //Declarar las variables
        DB::transaction(function () use ($sale, $product,$request) {
            $productStock = $request->input('info')['stock'];
            $transType = $request->input('info')['type'];
            //Id de transaction producto
            $idTransProduct = $request->input('info')['transID'];


            ProTrans::where('id',$idTransProduct)->update([
                'deleted_at' => now(),
                'reserved' => 0,
                'type' => TransTypeEnum::ELIMINADO,
            ]);

            // si tiene reserva, pues se descuenta ese monto
            if ($product->reserved > 0 && $transType == TransTypeEnum::RESERVA->value )
            {
                $product->reserved -= $productStock;
            }

            //Solo actualizar si es producto
            if($product->type === ProductTypeEnum::Producto->value )
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
     * @throws Throwable
     */
    public function deleteSale(Request $request, Sale $sale, bool $inventoried):void
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
                'content' => $request->input('comment'),
            ]);
        });
    }


    /**
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return Sale
     */
    public function updateSale(StoreProductSaleRequest $request, Sale $sale): Sale
    {

        //Obtener la info
        $infoRequest = collect($request->input('info_sale'));
        //Verificar si esta cerrada
        $closeTable = $request->input('close_table');

        //Recorrer los datos
        $infoRequest->map(callback: function ($item) use (&$sale, &$closeTable, &$request){

            //convertir la info sale a collection
            $infoSale = collect($sale->infoSale);

            //Poner la variable en 0
            $stock = 0;


            //Verificar si el item existe
            if (count($infoSale) !== 0)
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

            // Actualizar los cambios realizados
            $product->save();


            // Tomar los datos de validacion
            $data = $request->validated();
            //Conseguiir notas de creditos
            $creditNotes = $request->input('credit_notes');
            //Obtener los ids
            $ids = array_column($creditNotes, 'id');
//            Agrager los ids de notas de creditos
            $data['credit_notes'] = $ids;
            //Actualizar los datos de la ventas
            $sale->update($data);

//            $sale->client_id = $request->input('client_id');
//            $sale->client_rnc = $request->input('client_rnc');
//            $sale->client_name = $request->input('client_name');
//            $sale->discount_amount = $request->input('discount_amount');
//            $sale->tax = $request->input('tax');
//            $sale->sub_total = $request->input('sub_total');
//            $sale->amount = $request->input('amount');
//            $sale->credit_notes = $ids;
//            $sale->close_table = $request->input('close_table');
//            $sale->returned = $request->input('returned');
//            $sale->received = $request->input('received');
//            $sale->comment = $request->input('comment');
//            $sale->save();

            //Reducir las notas de creditos seleccionada
            CreditNoteHelper::updateAvailableFor($creditNotes, $request->input('amount'));

            if ($closeTable)
            {
                //Crear la transacciones
                TransHelper::store($item, TransTypeEnum::VENTAS, $sale, $product);

            }else{
                //Crear la transacciones
                TransHelper::store($item, TransTypeEnum::RESERVA, $sale, $product);
            }
        });


        return $sale;
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getSaleOpen(Request $request):mixed
    {
        //tomar los datos para buscar
        $search = $request->input("search");


        //Ralizar la busqueda en la base de datos de Sale cuando el campo close_table sea false
        $data = Sale::where(function (Builder $query) {
            $query->where('status', true)
                ->where('close_table', false);
        })->when($search != null ,function (Builder $query) use ($search) {
            $query->where('client_name', 'LIKE', "%$search%") ;
        })->with('infoSale')
            ->latest()
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
//        //Tonmar Los Gatos para verificar la altura
//        $checkHeight = $sale->infoSale->where('type',TransTypeEnum::VENTAS);
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
