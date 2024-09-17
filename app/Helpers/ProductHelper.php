<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Http\Request;


class ProductHelper
{
    public function update(Request $request, PRoduct $product)
    {
        $product->stock = $request->get('stock');
        $product->save();
    }





}
