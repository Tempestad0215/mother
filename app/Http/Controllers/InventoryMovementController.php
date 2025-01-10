<?php

namespace App\Http\Controllers;

use App\Models\InventoryMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        // DEvolver la vista con el mensaje
        return Inertia::render('Products/Inventory/EntryCreate',[
            'products' => Product::take(50)->get()
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
        //
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
