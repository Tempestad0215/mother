<?php

namespace App\Http\Controllers;

use App\Dtos\PurchaseDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Enums\PurchaseStatusEnum;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class PurchaseController extends Controller
{
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

            $itemsData = [];

            foreach ($purchaseDto->info as $item) {

                $itemsData[] = $item->toArray();

                // Guardar los datos en productos
                $warehouseProduct = WarehouseProduct::updateOrCreate([
                    'product_uuid' => $item->uuid,
                    'warehouse_uuid' => $item->warehouse_uuid,
                ],[
                   'purchase_pending' => DB::raw("COALESCE('purchase_pending, 0') + $item->quantity"),
                ]);

                $warehouseProduct->increment('purchase_pending', $item->quantity);
            }

            $purchase->items()->insert($itemsData);

            return back();

        });

    }


    public function show(PaginationRequest $request)
    {
        return Inertia::render('Purchase/TablePurchase',[
            'purchases' =>  PurchaseSupplierResource::collection(Purchase::with(['supplier','items'])->get())
        ]);
    }



    public function approve(Purchase $purchase)
    {
        $purchase->status = PurchaseStatusEnum::Pendiente;
        $purchase->save();
    }

    public function cancel(Purchase $purchase)
    {
        $purchase->status = PurchaseStatusEnum::Cancelada;
        $purchase->save();

        return back();
    }


    public function receive()
    {
        return Inertia::render('Purchase/Receive');
    }

    public function output()
    {
        return Inertia::render('Purchase/Output');
    }



    private function createInventoryMovement(Purchase $purchase, int $warehouseID, float $quantity,float $cost, string $description = ""):void
    {
        $purchase->itemMovements()->create([
            'type' => InventoryMovementTypeEnum::Entrada,
            'warehouse_id' => $warehouseID,
            'quantity' => $quantity,
            'cost' => $cost,
            'description' => $description,
        ]);
    }
}
