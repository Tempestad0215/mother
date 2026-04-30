<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceListRequest;
use App\Models\PriceList;

class PriceListController extends Controller
{
    public function index()
    {
        return PriceList::all();
    }

    public function store(PriceListRequest $request)
    {
        return PriceList::create($request->validated());
    }

    public function show(PriceList $priceList)
    {
        return $priceList;
    }

    public function update(PriceListRequest $request, PriceList $priceList)
    {
        $priceList->update($request->validated());

        return $priceList;
    }

    public function destroy(PriceList $priceList)
    {
        $priceList->delete();

        return response()->json();
    }
}
