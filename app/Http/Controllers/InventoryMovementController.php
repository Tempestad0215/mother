<?php

namespace App\Http\Controllers;

use App\Enums\INTYEnum;
use App\Helpers\ProductHelper;
use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class InventoryMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        //Intanciar
        $productHelper = new ProductHelper();

        //Para buscar los datos
        $productTable = $productHelper->get($request);

        // DEvolver la vista con el mensaje
        return Inertia::render('Products/Inventory/EntryCreate',[
            'products' => Product::take(50)->get(),
            'productTable' => $productTable,
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
            'type' => ['required',new Enum(INTYEnum::class)],
        ]);

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
    public function edit(InventoryMovement $inventoryMovement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryMovement $inventoryMovement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryMovement $inventoryMovement)
    {
        //
    }
}
