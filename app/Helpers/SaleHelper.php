<?php

namespace App\Helpers;

use App\Dtos\SaleDto;
use App\Dtos\SaleItemDto;
use App\Enums\ProductTransactionTypeEnum;
use App\Enums\SaleTypeEnum;
use App\Http\Requests\StoreProductSaleRequest;
use App\Http\Resources\SaleInfoResource;
use App\Models\DeletedSale;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return Sale::whereIn('type', [SaleTypeEnum::Ventas, SaleTypeEnum::Cotizacion])
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
    public function store(StoreProductSaleRequest $request): Sale|null
    {

        //Para asegurar que se cumplan los registro
        return DB::transaction(function () use ($request) {
            //obtener notas de crédito
            $salePayload = SaleDto::fromArray($request->validated());
            //Obtener la configuración


            //Incrementar la secuencia enviada
            SequenceHelper::incrementSequence($salePayload->invoice_type, $request);

            // Crear la venta
            $sale = Sale::create($salePayload->toArray());

            //Actualizar los datos de las notas de crédito
            CreditNoteHelper::updateAvailableFor($salePayload->credit_notes, $sale);

            // Obtener los ids de productos
//            $productUuids = array_map(fn(SaleItemDto $item) => $item->product_uuid, $salePayload->info_sale);
//
//            // Obtener los productos y colocar él, id en el key
//            /** @var Collection<string, Product> $products */
//            $products = Product::whereIn('uuid', $productUuids)->get()->keyBy('uuid');
//
//            //Recorrer la venta para descontar los productos
//            foreach ($salePayload->info_sale as $value) {
//
//                // Obtener el products
//                $product = $products->get($value->product_uuid);
//
//                // Verificar si el producto existe
//                if (!$product) {
//                    // Si no existe, lanzar un error
//                    throw ValidationException::withMessages(['El producto no existe']);
//                }
//
//            }

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
    public function deleteItem(Request $request, Product $product, Sale $sale): void
    {

        //Declarar las variables
        DB::transaction(function () use ($sale, $product, $request) {
            //Id de transaction producto
            $idTransProduct = $request->input('info')['transID'];


            ProductTransaction::where('uuid', $idTransProduct)->update([
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
    public function deleteSale(Request $request, Sale $sale, bool $inventoried): void
    {
        DB::transaction(function () use ($request, $sale, $inventoried) {
            //Poner los datos en deshabilitado
            $sale->status = false;
            $sale->save();


            //recorrer los datos de la ventas
            if ($inventoried) {
                foreach ($sale->infoSale as $value) {
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
                'sale_uuid' => $sale->uuid,
                'info' => $sale->infoSale,
                'discount_amount' => $sale->discount_amount,
                'amount' => $sale->amount,
                'tax' => $sale->tax,
                'sub_total' => $sale->sub_total,
                'close_table' => $sale->close_table,
            ]);

            // Crear el comentario
            $deleteSale->comment()->create([
                'content' => $request->input('comment'),
            ]);
        });
    }


    /**
     * @param StoreProductSaleRequest $request
     * @param Sale $sale
     * @return Sale
     * @throws Throwable
     */
    public function updateSale(StoreProductSaleRequest $request, Sale $sale): Sale
    {

        //Obtener la info
        $saleDto = SaleDto::fromArray($request->validated());

        // Actualizar los datos de la venta
        $sale->update($saleDto->toArray());

        // Obtener los ids de productos
        SaleItemHelper::multipleInsertWithSale(
            $sale,
            $saleDto->info_sale,
            $saleDto->update
        );




        return $sale;
    }


    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function getSaleOpen(Request $request): AnonymousResourceCollection
    {
        //tomar los datos para buscar
        $search = $request->input("search");

        //Realizar la búsqueda en la base de datos de Sale cuando el campo close_table sea false
        $data = Sale::query()
         ->with(['items.product','items'])
        ->where('close_table', false) // Filtrar primero por la mesa/venta abierta
        ->where(function (Builder $query) {
            // Esto asegura que traiga las que tienen status true O las que aún están en NULL (abiertas)
            $query->where('status', true)
                ->orWhereNull('status');
        })
            ->whereHas('items')
            // filled() solo se ejecuta si el buscador tiene texto real escrito
            ->when($request->filled('search'), function (Builder $query) use ($search) {
                $query->where('client_name', 'ILIKE', "%$search%")
                    ->orWhere('code', 'ILIKE', "%$search%"); // Opcional: buscar también por el código FAC0002
            })
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
