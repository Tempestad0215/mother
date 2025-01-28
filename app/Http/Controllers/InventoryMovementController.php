<?php

namespace App\Http\Controllers;

use App\Enums\InventoryMovementTypeEnum;
use App\Helpers\ProductHelper;
use App\Http\Resources\InventoryProductResource;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class InventoryMovementController extends Controller implements HasMiddleware
{
    /**
     * Para los middleware del controllador
     * @return array
     */
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum'),
            new Middleware('role:Super Admin|Supervisor',),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //Intanciar
        $productHelper = new ProductController();

        //Para buscar los datos
        $productTable = $productHelper->get($request);

        //Obtner la transaccion de productos
        $productsTrans = $this->getProductsTrans($request);

        // DEvolver la vista con el mensaje
        return Inertia::render('Products/Inventory/EntryCreate',[
            'products' => Product::take(50)->get(),
            'productTable' => $productTable,
            'entries' => $productsTrans
        ]);
    }


    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function entry(Request $request): RedirectResponse
    {
        //Validar los datos
        $request->validate([
            'product_id' => ['required','numeric','exists:products,id'],
            'quantity' => ['required','numeric', Rule::notIn(0.00)],
            'cost' => ['required','numeric', Rule::notIn(0.00)],
            'description' => ['required','string','min:5','max:255'],
            'type' => ['required',new Enum(InventoryMovementTypeEnum::class)],
        ]);


        //Buscar el producto de la entrada
        $product = Product::find($request->get('product_id'));

        //Aumentar el stock de los productos
        $product->increment('stock', $request->get('quantity'));
        $product->cost = $request->get('cost');
        $product->save();

        //Crear el movimiento de la entrada
        InventoryMovement::create($request->toArray());

        //Devolver hacia atras
        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryMovement $inventoryMovement)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, InventoryMovement $entry)
    {
        //Intanciar
        $productHelper = new ProductHelper();

        //Para buscar los datos
        $productTable = $productHelper->get($request);

        // DEvolver la vista con el mensaje
        return Inertia::render('Products/Inventory/EntryCreate',[
            'products' => Product::take(50)->get(),
            'productTable' => $productTable,
            'entry_edit' => new InventoryProductResource($entry),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryMovement $inventoryMovement)
    {
        //
    }

    /**
     * @param InventoryMovement $entry
     * @return RedirectResponse
     */
    public function destroy(InventoryMovement $entry)
    {

        //Verificar el stock del producto
        if ($entry->product->stock > 0) {
            // Decrementar el stock si hay suficiente
            $entry->product->decrement('stock', $entry->quantity);
        }

        // Asegurar que el stock no sea negativo
        if ($entry->product->stock < 0) {
            $entry->product->stock = 0;
            $entry->product->save();
        }

        //Eliminar la entrada
        $entry->delete();

        // Devolver los datos
        return back();
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function getProductsTrans(Request $request):Mixed
    {
        //Obtner los datos de busqueda
        $search = $request->search;
        $perPage = $request->perPage;

        //Enviar los datos
        $data = InventoryMovement::where('status', true)
            ->whereHas('product', function($query) use($search){
            $query->where('products.name','LIKE','%'.$search.'%')
            ->orWhere('products.description','LIKE','%'.$search.'%')
            ->orWhere('products.sku','LIKE','%'.$search.'%');
        })->simplePaginate($perPage);


        //Devolver los datos
        return InventoryProductResource::collection($data)->response()->getData(true);
    }
}
