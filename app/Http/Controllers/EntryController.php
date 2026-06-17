<?php

namespace App\Http\Controllers;

use App\Dtos\EntryDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Helpers\InHelper;
use App\Helpers\TransHelper;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductTransResource;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Http\Requests\StoreProductInRequest;
use App\Http\Requests\UpdateProductInRequest;
use App\Models\ProductTransaction;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class EntryController extends Controller
{

    /**
     *
     */
    public function __construct()
    {

    }


    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        //conseguir los datos
        $data = $this->getProduct($request);
        $productResource = ProductResource::collection($data);

        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/ProductIn', [
            'products' => $productResource,
            'warehouses' => Warehouse::all(),
        ]);
    }


    /**
     * @param StoreProductInRequest $request
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function store(Request $request): RedirectResponse
    {

        // Validar los datos
        $validated = $request->validate([
            'product_uuid'   => ['required','uuid','exists:products,uuid'],
            'warehouse_uuid' => ['required','uuid','exists:warehouses,uuid'],
            'quantity'       => ['required','numeric','min:0.0001'],
            'cost'           => ['required','numeric','min:0'], // Con qué costo entra al almacén
            'reference'      => ['nullable','string','max:255'],
        ]);

        // Crear el dto de la entrada
        $entryDto = EntryDto::fromArray($validated);

        // Para asegurar la transaccion
        DB::transaction(function () use ($entryDto) {

            // Tomar el producto
            $product = Product::with(['warehouses'])
                ->where('products.uuid','=',$entryDto->product_uuid)->first();

            // Incremental el stock
            $product->cost = $entryDto->cost;
            $product->save();

            // Tomar el warehouse por el id
            /**
             * @var Warehouse|null $warehouse
             */
            $warehouse = $product->warehouses()->where('uuid', $entryDto->warehouse_uuid)->first();
            // Tomar la tabal pivot
            /**
             * @var WarehouseProduct $pivot
             */
            $pivot = $warehouse->pivot;

            /**
             * @var float $oldStock
             */
            $oldStock = $pivot->stock_quantity;
            $newStock = bcadd((string)$oldStock, (string)$entryDto->quantity);


            // Crear el movimiento de inventario
            InventoryMovement::create([
                'product_uuid' => $entryDto->product_uuid,
                'warehouse_uuid' => $entryDto->warehouse_uuid,
                'quantity' => $entryDto->quantity,
                'stock_before' => $oldStock,
                'stock_after' => $newStock,
                'type' => InventoryMovementTypeEnum::IN,
                'inventoryable_uuid' => $entryDto->product_uuid,
                'inventoryable_type' => Product::class,
                'cost' => $entryDto->cost,
                'concept' => $entryDto->reference,
            ]);

        });

        //Devolver hacia atras
        return back();

    }

    /**
     * @param Request $request
     * @param Product $productIn
     * @return Response
     */
    public function entrance(Request $request, Product $productIn): Response
    {

        // Tomar lo datos de todos los produtos
        $data = $this->getProduct($request);

        // Devolver la vista con los datos
        return Inertia::render('ProductsIn/ProductIn', [
            'products' => $data,
            'productEntrance' => $productIn,
        ]);

    }

    /**
     * @param Request $request
     * @param ProductTransaction $trans
     * @return Response
     */
    public function edit(Request $request, ProductTransaction $trans): Response
    {

        //conseguir  los datos
        $data = $this->getProduct($request);

        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/ProductIn', [
            'trans' => new ProductTransResource($trans),
            'products' => $data,
            'update' => true
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {

        //Instancia
        $inHelper = new inHelper();

        //Tomar los datos
        $data = $inHelper->getTransIn($request);


        //Devolver la vista con los datos
        return Inertia::render('ProductsIn/ProductShowTrans', [
            'trans' => $data
        ]);
    }

    /**
     * @param UpdateProductInRequest $request
     * @param ProductTransaction $trans
     * @return RedirectResponse|void
     */
    public function update(UpdateProductInRequest $request, ProductTransaction $trans)
    {
        //        Tomar las fechas
        $updateDay = config('appconfig.document-update');

        // Formatear la fecha de creacion
        $createdAtLimit  = Carbon::parse($trans->created_at)->addDays($updateDay);

        // tomar la fecha actual
        $now = Carbon::now();

        // si el parametro de actualiacion es mayor  a updated at
        if($createdAtLimit->lessThan($now))
        {

            //Mensaje de error
            return back()->withErrors([
                'general' => 'El Documento de ID:'.$trans->id.' Esta Fuera Del Rango De Fecha Permitido Para Actualizar Documento'
            ]);

        }else{

            DB::transaction(function () use ($request, $trans) {

                //Instancia
                $inHelper = new Inhelper();
                $transHelper = new TransHelper();


                //conseguir los datos del producto
                $product = Product::find($trans->product_uuid);


                //Actualizar la transaciom
//                $transHelper->store($request->toArray(), TransTypeEnum::AJUSTE, 0, $product->id);
                //Actualizar los productos
                $inHelper->adjustProduct($request, $product);
            });

            //Actualizar todos los datos

        }

    }

    /**
     * @param ProductTransaction $trans
     * @return RedirectResponse
     */
    public function destroy(ProductTransaction $trans)
    {

        //dia para eliminar documento
        $deleteDate = config('appconfig.document-delete');

        //limite para eliminar documento
        $createDeleteLimit = Carbon::parse($trans->created_at)->addDays($deleteDate);
        $now = Carbon::now();

        if($createDeleteLimit->lessThan($now))
        {
            return back()->withErrors([
                'general' => 'Este Docuemento No Puede Ser Eliminado Excede La Fecha Limite'
            ]);
        }else{

            //Actualizar los datos
            $trans->status = false;
            $trans->save();

            //Obtener el producto
            $prodcut = Product::find($trans->product_uuid);
            $prodcut->stock -= $trans->quantity;
            $prodcut->save();

            //Retornar hacia atras
            return back();
        }

    }


    /**
     * @param Request $request
     * @return Paginator
     */
    public function getProduct(Request $request):Paginator
    {
        //Obtener los datos de busqueda
        $search = $request->input('search');
        $perPage = $request->input('perPage',15);


        //Devolver los datos
        return Product::with(['priceList','warehouses'])
            ->where('status', true)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->simplePaginate($perPage);

    }
}
