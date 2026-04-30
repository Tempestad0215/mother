<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceListProductRequest;
use App\Models\PriceListProduct;

class PriceListProductController extends Controller
{
    public function index()
    {
        return PriceListProduct::all();
    }

    public function store(PriceListProductRequest $request)
    {
        return PriceListProduct::create($request->validated());
    }

    public function show(PriceListProduct $priceListProduct)
    {
        return $priceListProduct;
    }

    public function update(PriceListProductRequest $request, PriceListProduct $priceListProduct)
    {
        $priceListProduct->update($request->validated());

        return $priceListProduct;
    }

    public function destroy(PriceListProduct $priceListProduct)
    {
        $priceListProduct->delete();

        return response()->json();
    }
}
