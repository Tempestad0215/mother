<?php

namespace App\Http\Controllers;

use App\Dtos\EntryDto;
use App\Enums\InventoryMovementTypeEnum;
use App\Http\Resources\ProductResource;
use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class OutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $entryController = new EntryController();

        // Obtener los productos utilizando el método del EntryController
        $products = $entryController->getProduct($request);
        $productResource = ProductResource::collection($products);
        // Obter los almacenenes
        $warehouses = Warehouse::all();

        // Devolver la vista con los datos
        return Inertia::render('ProductsIn/ProductIn', [
            'products' => $productResource,
            'warehouses' => $warehouses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
            $pivot->decrement('stock_quantity', $entryDto->quantity);

            // Actualizar los datos
            $product->warehouses()->updateExistingPivot($entryDto->warehouse_uuid,[
                'stock_quantity' => DB::raw("stock_quantity  {$entryDto->quantity}"),
                'updated_at' => now()
            ]);

            // Crear el movimiento de inventario
            InventoryMovement::create([
                'product_uuid' => $entryDto->product_uuid,
                'warehouse_uuid' => $entryDto->warehouse_uuid,
                'quantity' => $entryDto->quantity,
                'stock_before' => $oldStock,
                'stock_after' => $pivot->stock_quantity,
                'type' => InventoryMovementTypeEnum::OUT,
                'cost' => $entryDto->cost,
                'concept' => $entryDto->reference,
            ]);

        });

        //Devolver hacia atras
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
