<?php

namespace App\Http\Controllers;

use App\Helpers\ProductHelper;
use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Tax;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Throwable;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'productSearch' => ['nullable', 'string', 'min:2','max:60'],
        ]);

        //Intancia de los datos
        $productHelper = new ProductHelper();

        $search = $request->get('productSearch');

        $qProduct = Product::query();
        if ($search) {
            $qProduct->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });

        }
        $qProduct->orderBy('name')->limit(15);

        $products = $qProduct->get();

        //Repuesta con datos
        return Inertia::render('Purchase/FRegisterPurchase',[
            'suppliers' => Supplier::all(),
            'products' => $products,
            'taxes'=>Tax::all(),
            'warehouses'=>Warehouse::all(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function store(PurchaseRequest $request)
    {
        DB::transaction(function () use ($request) {

            Purchase::create($request->validated());

            collect($request->info)->each(function (array $product):void{
                dd($product['id']);
            });

        });

    }
}
