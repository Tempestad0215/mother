<?php

namespace App\Http\Controllers;

use App\Enums\InventoryMovementTypeEnum;
use App\Enums\PurchaseStatusEnum;
use App\Helpers\ProductHelper;
use App\Http\Requests\PaginationRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Resources\PurchaseSupplierResource;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Warehouse;
use Carbon\Carbon;
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

        //Intancia de los datos
        $productHelper = new ProductHelper();

        $search = $request->get('productSearch');


        $qProduct = Product::query();
        if ($search) {
            $qProduct->where(function ($query) use ($search) {
                $query->where('name', 'ilike', '%' . $search . '%')
                    ->orWhere('description', 'ilike', '%' . $search . '%');
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
     * @throws Throwable
     */
    public function store(PurchaseRequest $request)
    {
        DB::transaction(function () use ($request) {
            $purchaseData = $request->validated();
            $purchaseData['user_id'] = auth()->user()->id;
            $purchaseData['doc_date'] = Carbon::parse(
                $request->get('doc_date')
            )->toDateString();

            $purchase = Purchase::create($purchaseData);

            collect($request->info)->each(function (array $product) use ($purchase):void{
                $productId = (int)$product['id'];

                //Tomar el id del producto
                $productDB = Product::find($productId);
                //Tomar el el avg del costo
                $avgCost = ProductHelper::getAvgCost($productDB, $product['quantity'], $product['cost']);

                //Crare la el valor de product id
                $product['product_id'] = $productId;
                $product['tax_amount'] = $product['tax'];





                // Obtener los datos de inventario para sumar o crear
                $stock = Inventory::firstOrCreate([
                    'product_id' => $productId,
                    'warehouse_id' => $product['warehouse_id'],
                ],[
                    'qty_on_hand' => 0,
                    'committed' => 0,
                    'on_order_qty' => 0,
                    'avg_cost' => $avgCost,
                    'min_stock' => 0,
                    'max_stock' => 0,
                ]);

                // Actualizar los datos de inventario
                $stock->increment('on_order_qty', $product['quantity']);
                $stock->avg_cost = $avgCost;
                $stock->save();

                // Crear los item de la compra
                $purchase->items()->create($product);

                // Crear el movimiento de inventario
                $this->createInventoryMovement(
                    $purchase,
                    $product['warehouse_id'],
                    $product['quantity'],
                    $product['cost']
                );

            });

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
