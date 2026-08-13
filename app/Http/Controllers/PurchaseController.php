<?php

namespace App\Http\Controllers;

use App\Dtos\EntranceDto;
use App\Dtos\PurchaseDto;
use App\Dtos\PurchaseRequestItemDto;
use App\Enums\PurchaseStatusEnum;
use App\Http\Requests\EntranceStoreRequest;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\PaProduct;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class PurchaseController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $request->validate([
            'productSearch' => ['nullable', 'string', 'min:2','max:60'],
        ]);


        $search = $request->input('productSearch','');


        $qProduct = Product::query();
        if ($search) {
            $qProduct->where(function ($query) use ($search) {
                $query->where('name', 'ILIKE', '%' . $search . '%')
                    ->orWhere('description', 'ILIKE', '%' . $search . '%');
            });

        }
        $qProduct->orderBy('name')->limit(15);

        $products = $qProduct->get();


        //Repuesta con datos
        return Inertia::render('Purchase/FRegisterPurchase',[
            'suppliers' => Supplier::all(),
            'products' => $products,
            'taxes'=>Tax::all(),
            'warehouses'=>Warehouse::all(),
        ]);
    }

    /**
     * @param PurchaseRequest $request
     * @return void
     * @throws Throwable
     */
    public function store(PurchaseRequest $request):void
    {
        // Asingar desde
        $purchaseDto = PurchaseDto::fromRequest($request->validated());

        // Proteger la transaction
        DB::transaction(function () use ($purchaseDto) {
            // Crear la compra

            $purchase = Purchase::create($purchaseDto->toArray());

            // Almacenar los modelos en el array
            $purchaseItemsModels = [];

            // Obtener los ids de productos
            $productIds = array_map(fn(PurchaseRequestItemDto $item) => $item->product_uuid, $purchaseDto->info);

            // Consultar los productos
            $productsDb = Product::WhereIn('uuid', $productIds)->get()->keyBy('uuid');

            // Recorrer los item
            foreach ($purchaseDto->info as $item) {

                // Almacenar los items
                $purchaseItemsModels[] = new PurchaseItem([
                    'product_uuid' => $item->product_uuid,
                    'purchase_uuid' => $purchase->supplier_uuid,
                    'quantity' => $item->quantity,
                    'cost' => $item->cost,
                    'discount' => $item->discount,
                    'amount' => $item->amount,
                    'tax' => $item->tax,
                    'tax_uuid' => $item->tax_uuid,
                    'warehouse_uuid' => $item->warehouse_uuid
                ]);

                // Guardar los datos en productos
                /** @var Product $product */
                $product = $productsDb->get($item->product_uuid);

                if($product)
                {
                    // Tomar el primero que exita con el almacen y con producto
                    /** @var WarehouseProduct | null $currentPivot */
                    $currentPivot = $product->warehouses()->where('warehouse_uuid', $item->warehouse_uuid)
                        ->first()?->pivot;

                    // Si existe se coloca el valor o 0
                    $currentPending = $currentPivot ? $currentPivot->purchase_pending : 0;
                    // Crear la nueva cantidad pendiente
                    $newPending = bcadd($currentPending, $item->quantity);
                    // Actualizar el producto con el almacen
                    $product->warehouses()->syncWithoutDetaching([
                        $item->warehouse_uuid => [
                            'purchase_pending' => $newPending,
                        ]
                    ]);
                }

            }

            // Guardar todos los los datos
            $purchase->items()->saveMany($purchaseItemsModels);

            // Devolver hacia atras
            return back();

        });

    }


    /**
     * @param PaginationRequest $request
     * @return Response
     */
    public function show(PaginationRequest $request)
    {
        return Inertia::render('Purchase/TablePurchase',[
            'purchases' =>  PurchaseSupplierResource::collection(Purchase::with(['supplier','items'])->get())
        ]);
    }


    /**
     * @param Purchase $purchase
     * @return void
     */
    public function approve(Purchase $purchase)
    {
        $purchase->status = PurchaseStatusEnum::Pendiente;
        $purchase->save();
    }

    /**
     * @param Purchase $purchase
     * @return RedirectResponse
     */
    public function cancel(Purchase $purchase)
    {
        $purchase->status = PurchaseStatusEnum::Cancelada;
        $purchase->save();

        return back();
    }

    /**
     * @return Response
     */
    public function receive()
    {

        return Inertia::render('Purchase/Receive',[
            'warehouses' => Warehouse::getAllCached(),
            'taxes' => Tax::getAllCached()
        ]);
    }


    /**
     * @throws Throwable
     */
    public function receiveStore(EntranceStoreRequest $request):void
    {
        $entranceDto = EntranceDto::fromRequest($request->validated());

        DB::transaction(function () use ($entranceDto) {
            PaProduct::create($entranceDto->toArray());
        });
    }

    /**
     * @return Response
     */
    public function output()
    {
        return Inertia::render('Purchase/Output');
    }


//    /**
//     * @param Purchase $purchase
//     * @param int $warehouseID
//     * @param float $quantity
//     * @param float $cost
//     * @param string $description
//     * @return void
//     */
//    private function createInventoryMovement(Purchase $purchase, int $warehouseID, float $quantity,float $cost, string $description = ""):void
//    {
//        $purchase->itemMovements()->create([
//            'type' => InventoryMovementConceptEnum::Entrada,
//            'warehouse_id' => $warehouseID,
//            'quantity' => $quantity,
//            'cost' => $cost,
//            'description' => $description,
//        ]);
//    }
}
