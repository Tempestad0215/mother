<?php

namespace App\Helpers;

use App\Dtos\InventoryMovementDto;
use App\Dtos\SaleDto;
use App\Dtos\SaleItemApiDto;
use App\Dtos\SaleItemDto;
use App\Enums\InventoryMovementConceptEnum;
use App\Enums\InventoryMovementTypeEnum;
use App\Enums\ProductTransactionTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Enums\SequenceSaleTypeEnum;
use App\Factories\SaleFactory;
use App\Factories\SaleItemApiFactory;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleInfoResource;
use App\Models\DeletedSale;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
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
            //obtener notas de crédito
            $salePayload = SaleDto::fromArray($request->validated());
            //Obtener la configuración
            // $setting = Setting::first();

            //Incrementar la secuencia enviada
            SequenceHelper::incrementSequence($salePayload->invoice_type);

            // Crear la venta
            $sale = Sale::create($salePayload->toArray());

            //Actualizar los datos de la notas de crédito
            CreditNoteHelper::updateAvailableFor($salePayload->credit_notes, $salePayload->amount);

            // Obtener los ids de productos
            $productUuids = array_map(fn (SaleItemDto $item) => $item->product_uuid, $salePayload->info_sale);

            // Obteenr los productos y colocar el id en el key
            /** @var Collection<string, Product> $products */
            $products = Product::whereIn('uuid', $productUuids)->get()->keyBy('uuid');

            
            //Recorrer la venta para descontar los productos
            foreach ($salePayload->info_sale as $value)
            {
    
                // Obtener el products
                $product = $products->get($value->product_uuid);

                // Verificar si el producto existe
                if(!$product)
                {
                    // Si no existe, lanzar un error
                    throw ValidationException::withMessages(['El producto no existe']);
                }

            }

            // Crear los movimientos de inventario
            SaleItemHelper::multipleInsertWithSale($sale, $salePayload->info_sale);

            // Crear las transacciones de productos
            return $sale;
        });
    }


    // /**
    //  * @param SaleTypeEnum $saleType
    //  * @return InventoryMovementConceptEnum
    //  */
    // public function movementType(SaleTypeEnum $saleType): InventoryMovementConceptEnum
    // {
    //     if($saleType == SaleTypeEnum::Ventas){
    //         return InventoryMovementConceptEnum::Venta;
    //     }else if($saleType == SaleTypeEnum::Devolucion){
    //         return InventoryMovementConceptEnum::Devolucion;
    //     }else{
    //         return InventoryMovementConceptEnum::TransferenciaSalida;
    //     }
    // }



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


            ProductTransaction::where('uuid',$idTransProduct)->update([
                'deleted_at' => now(),
                'reserved_quantity' => 0,
                'type' => ProductTransactionTypeEnum::CANCELLED,
            ]);

            // si tiene reserva, pues se descuenta ese monto
//            if ($product->reserved > 0 && $transType == TransTypeEnum::RESERVA->value )
//            {
//                $product->reserved -= $productStock;
//            }

            //Solo actualizar si es producto
//            if($product->type === ProductTypeEnum::Producto->value )
//            {
//                $product->stock += $productStock;
//            }
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
        $saleDto = SaleDto::fromArray($request->validated());

        // Actualizar los datos de la venta
        $sale->update($saleDto->toArray());


        // Obtner los ids de productos
        $productUuids = array_map(fn (SaleItemDto $item) => [
            'product_uuid' => $item->product_uuid,
            'warehouse_uuid' => $item->warehouse_uuid
        ], $saleDto->info_sale);

        // Actualizar los datos de los items
        foreach ($saleDto->info_sale as $itemDto)
        {

            //Crear la key para buscar los datos

            $saleItem = $sale->items()->where('product_uuid', $itemDto->product_uuid)->first();

            if ($saleItem)
            {
                $saleItem->update([
                    'stock' => $itemDto->stock,
                    'price' => $itemDto->price,
                    'discount_amount' => $itemDto->discount_amount,
                    'tax_amount' => $itemDto->tax_amount,
                    'total' => $itemDto->total,
                ]);
            }else{
                SaleItemHelper::createSaleItem($sale, $itemDto);
            }
        }

        //Recorrer los datos
        $infoRequest->map(function ($item) use (&$sale, &$closeTable, &$request){

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

            // Agrager los ids de notas de creditos
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
                TransHelper::store($item, ProductTransactionTypeEnum::SALE, $sale, $product);

            }else{
                //Crear la transacciones
                TransHelper::store($item, ProductTransactionTypeEnum::RESERVATION, $sale, $product);
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
        $data = Sale::where('close_table', false) // Filtrar primero por la mesa/venta abierta
        ->where(function (Builder $query) {
            // Esto asegura que traiga las que tienen status true O las que aún están en NULL (abiertas)
            $query->where('status', true)
                  ->orWhereNull('status');
        })
        // filled() solo se ejecuta si el buscador tiene texto real escrito
        ->when($request->filled('search'), function (Builder $query) use ($search) {
            $query->where('client_name', 'ILIKE', "%{$search}%")
                  ->orWhere('code', 'ILIKE', "%{$search}%"); // Opcional: buscar también por el código FAC0002
        })
        ->with('items')
        ->latest()
        ->simplePaginate(15);

        // Devolver los datos
        return SaleInfoResource::collection($data);

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
