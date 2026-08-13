<?php

namespace App\Http\Controllers;

use App\Models\PaProduct;
use Illuminate\Http\Request;

class pa_productsController extends Controller
{
    public function index()
    {
        return PaProduct::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => ['required'],
            'supplier_uuid' => ['required'],
            'document_date' => ['required'],
            'comment' => ['required'],
            'total' => ['required'],
            'tax' => ['required'],
            'sub_total' => ['required'],
        ]);

        return PaProduct::create($data);
    }

    public function show(PaProduct $pa_products)
    {
        return $pa_products;
    }

    public function update(Request $request, PaProduct $pa_products)
    {
        $data = $request->validate([
            'code' => ['required'],
            'supplier_uuid' => ['required'],
            'document_date' => ['required'],
            'comment' => ['required'],
            'total' => ['required'],
            'tax' => ['required'],
            'sub_total' => ['required'],
        ]);

        $pa_products->update($data);

        return $pa_products;
    }

    public function destroy(PaProduct $pa_products)
    {
        $pa_products->delete();

        return response()->json();
    }
}
