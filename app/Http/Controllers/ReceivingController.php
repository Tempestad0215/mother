<?php

namespace App\Http\Controllers;

use App\Enums\InventoryMovementTypeEnum;
use App\Enums\PurchaseStatusEnum;
use App\Helpers\ProductHelper;
use App\Helpers\SupplierHelper;
use App\Http\Requests\StorePurchaseReceivingRequest;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseReceipts;
use App\Models\PurchaseReceiptsItem;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class ReceivingController extends Controller
{
    public function index(Request $request, Supplier $supplier)
    {

        $purchaseAvailable = PurchaseSupplierResource::collection(
            $supplier->purchase()
                ->where('status', PurchaseStatusEnum::Pendiente)
                ->with('items')
                ->with('supplier')
                ->get()
        );


        $purchaseStatus = collect(PurchaseStatusEnum::cases())
            ->filter(fn(PurchaseStatusEnum $item) => $item !== PurchaseStatusEnum::Borrador && $item !== PurchaseStatusEnum::Cancelada && $item !== PurchaseStatusEnum::Pendiente)
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
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            //Obtener la compra antigua
            $purchase = Purchase::find($data['id']);

            $payloadReceipts = Arr::except($data, ['id']);
            $payloadReceipts['purchase_id'] = $data['id'];
            $purchaseReceipts = PurchaseReceipts::create($payloadReceipts);

            $purchaseItemsByProductId = $purchase->items->keyBy('product_id');

            foreach ($data['items'] as $line)
            {
                $purchaseItem = $purchaseItemsByProductId->get($line['product_id']);

                $line['purchase_receipts_id'] = $purchaseReceipts->id;
                $line['purchase_item_id'] = $purchaseItem->id;
                $line['product_id'] = $purchaseItem->product_id;
                $line['quantity_expected'] = $purchaseItem->quantity;
                $line['quantity_received'] = $line['quantity'];


                $payloadReceiptsItem = Arr::except($line, ['id']);
                PurchaseReceiptsItem::create($payloadReceiptsItem);


                $purchaseReceipts->itemMovements()->create([
                    'warehouse_id' => $line['warehouse_id'],
                    'type' => InventoryMovementTypeEnum::Recepcion->value,
                    'quantity' => $line['quantity'],
                    'cost' => $line['cost'],
                    'price' => $line['amount'],
                ]);

                $warehouse = Warehouse::find($line['warehouse_id']);
                $product = Product::find($line['product_id']);

                ProductHelper::incrementStock(
                    $product,
                    $warehouse,
                    $line['quantity'],
                    $line['cost']
                );

            }

            $purchase->status = $data['status'];
            $purchase->save();
        });




    }


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
