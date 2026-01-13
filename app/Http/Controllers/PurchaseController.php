<?php

namespace App\Http\Controllers;

use App\Helpers\ProductHelper;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        //Intancia de los datos
        $productHelper = new ProductHelper();



        //Repuesta con datos
        return Inertia::render('Purchase/FRegisterPurchase',[
            'suppliers' => Supplier::all(),
            'products' => $productHelper->get($request)
        ]);
    }
}
