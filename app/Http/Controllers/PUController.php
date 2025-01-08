<?php

namespace App\Http\Controllers;

use App\Helpers\ProductHelper;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PUController extends Controller
{
    public function index(Request $request)
    {
        //Intancia de los datos
        $productHelper = new ProductHelper();


        //Repuesta con datos
        return Inertia::render('Purchase/PurchaseCreate',[
            'products' => $productHelper->get($request)
        ]);
    }
}
