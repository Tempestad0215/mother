<?php

namespace App\Http\Controllers;

use App\Dtos\ReceivingDto;
use App\Dtos\ReceivingItemDto;
use App\Enums\PurchaseStatusEnum;
use App\Helpers\SupplierHelper;
use App\Http\Requests\StorePurchaseReceivingRequest;
use App\Http\Resources\PurchaseReceiptResource;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\PurchaseReceipts;
use App\Models\PurchaseReceiptsItem;
use App\Models\Supplier;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Throwable;

class ReceivingController extends Controller
{
    public function index(Request $request, Supplier $supplier)
    {


        // Obtener los datos de la recepcion
        $purchaseAvailable = PurchaseReceiptResource::collection(
            $supplier->purchase()
                ->whereIn('status', [PurchaseStatusEnum::Pendiente, PurchaseStatusEnum::Parcial])
                ->with('items')
                ->with('supplier')
                ->get()
        );



        $purchaseStatus = collect(PurchaseStatusEnum::cases())
            ->filter(fn(PurchaseStatusEnum $item) =>
                $item !== PurchaseStatusEnum::Borrador &&
                $item !== PurchaseStatusEnum::Cancelada &&
                $item !== PurchaseStatusEnum::Pendiente)
            ->map(fn(PurchaseStatusEnum $item) => (object)[
                'name' => $item->name,
                'value' => $item->value,
            ])
            ->values()
            ->all();


        return Inertia::render('Purchase/Receiving', [
            'purchases' => $this->getPurchaseApprove($request),
            'suppliers' => SupplierHelper::getReceiving($request),
            'purchaseAvailable' => $purchaseAvailable,
            'purchaseStatus' => $purchaseStatus
        ]);
    }


    /**
     * @throws Throwable
     */
    public function store(StorePurchaseReceivingRequest $request)
    {
        // Convertir los datos
        $receivingDto = ReceivingDto::fromArray($request->validated());

        // ejecutar para proteger los datos
        DB::transaction(function () use ($receivingDto) {
            //Obtener la compra antigua
            $purchase = Purchase::with('items')->where('uuid', $receivingDto->purchase_uuid)->firstOrFail();

            // Obtener los items de la compra
            $purchaseItem = $purchase->items()->get()->keyBy('product_uuid');

            // Guardar los datos
            $purchaseReceipts = PurchaseReceipts::create($receivingDto->toArray());

            // Obtener los ids de productos
            $productUuids = array_map(fn(ReceivingItemDto $item) => $item->product_uuid, $receivingDto->items);
            ;

            // Almacenar los datos en un array
            $receivingItemModel = [];
            $productBatch = Product::with('warehouses')->whereIn('uuid', $productUuids)->get()->keyBy('uuid');
            ;

            // Reccorrer los datos del array
            foreach ($receivingDto->items as $item)
            {

                // Obtener el item actual viejo
                /** @var PurchaseItem|null $oldItemCurrent */
                $oldItemCurrent = $purchaseItem->get($item->product_uuid);

                // Continuar si no existe
                if (!$oldItemCurrent){
                    continue;
                }

                // Crear el arreglo de los item
                $receivingItemModel[$item->product_uuid] = new PurchaseReceiptsItem([
                    'purchase_receipt_uuid' => $purchaseReceipts->uuid,
                    'purchase_item_uuid' => $oldItemCurrent->uuid,
                    'product_uuid' => $item->product_uuid,
                    'cost' => $oldItemCurrent->cost,
                    'quantity_expected' => $oldItemCurrent->quantity,
                    'quantity_received' => $item->quantity,
                    'tax_uuid' => $item->tax_uuid,
                    'warehouse_uuid' =>$item->warehouse_uuid,
                    'tax_rate' => $item->tax_rate,
                    'tax_amount' => $item->tax_amount,
                    'discount' => $item->discount,
                    'amount' => $item->amount,
                ]);

                // Obtener el producto en la lista
                $product = $productBatch->get($item->product_uuid);


                // Buscar la primera coincidencia
                /** @var WarehouseProduct|null $warehousePivot */
                $warehousePivot = $product->warehouses()->where('uuid', $item->warehouse_uuid)->first()->pivot;

                // Verificar si existe
                if (!$warehousePivot) {
                    throw ValidationException::withMessages([
                        'warehouse_uuid' => "El producto {$item->product_name} no tiene asignado el almacén seleccionado en los registros.",
                    ]);
                }

                $warehousePivot->increment('stock_quantity', $item->quantity);

            }

            // Insertar los datos
            $purchaseReceipts->items()->saveMany($receivingItemModel);

            // Actualizar los datos del status
            $purchase->status = $receivingDto->status;
            $purchase->save();
        });




    }


    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    private function getPurchaseApprove(Request $request)
    {
        $validate = $request->validate([
            'search' => ['nullable','max:80','string']
        ]);

        $search = $request->get('search','');


        $purchases = Purchase::query()
            ->with('supplier')
            ->with('items')
            ->where('status', PurchaseStatusEnum::Pendiente)
            ->when($search !== '', function ($query, $search) {
                $query->whereHas('supplier', function ($q2) use ($search) {
                    $q2->where('company_name', 'like', '%' . $search . '%')
                    ->orWhere('contact', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
                });
            })->paginate(15);


        return PurchaseSupplierResource::collection($purchases);
    }


    /**
     * @param int $supplierId
     * @return AnonymousResourceCollection
     */
    public function getPurchaseAvailable(int  $supplierId)
    {
        $supplier = Purchase::query()
            ->with(['supplier','items.product'])
            ->whereHas('supplier', function ($q) use ($supplierId) {
                $q->where('id', $supplierId);
            })->where('status', PurchaseStatusEnum::Pendiente)->get();

        return PurchaseSupplierResource::collection($supplier);

    }
}
