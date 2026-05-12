<?php

namespace App\Http\Controllers;

use App\Http\Requests\PriceListRequest;
use App\Models\PriceList;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class PriceListController extends Controller
{
    public function index()
    {
        return Inertia::render('PriceList/PriceListIndex',[
            'priceLists' => PriceList::all()
        ]);
    }

    public function store(PriceListRequest $request)
    {
        $price = PriceList::create($request->validated());

        return redirect()->back();
    }

    public function show(PriceList $priceList, Product $product): JsonResponse
    {


        return response()->json($product);


    }

    public function update(PriceListRequest $request, PriceList $priceList)
    {
        $priceList->update($request->validated());

        return $priceList;
    }

    public function destroy(PriceList $priceList)
    {
        $priceList->delete;

        return response()->json();
    }
}
