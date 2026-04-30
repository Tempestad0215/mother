<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseProductRequest;
use App\Models\WarehouseProduct;

class WarehouseProductController extends Controller
{
    public function index()
    {
        return WarehouseProduct::all();
    }

    public function store(WarehouseProductRequest $request)
    {
        return WarehouseProduct::create($request->validated());
    }

    public function show(WarehouseProduct $warehouseProduct)
    {
        return $warehouseProduct;
    }

    public function update(WarehouseProductRequest $request, WarehouseProduct $warehouseProduct)
    {
        $warehouseProduct->update($request->validated());

        return $warehouseProduct;
    }

    public function destroy(WarehouseProduct $warehouseProduct)
    {
        $warehouseProduct->delete();

        return response()->json();
    }
}
