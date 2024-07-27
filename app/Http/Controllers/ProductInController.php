<?php

namespace App\Http\Controllers;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use App\Http\Requests\StoreProductInRequest;
use App\Http\Requests\UpdateProductInRequest;
use App\Models\ProTrans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ProductInController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            //conseguir  los datos
            $data = $this->getProduct($request);

            //Devolver la vista con los datos
            return Inertia::render('Products/In',[
                'products' => $data
            ]);

        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductInRequest $request)
    {
        try {

            DB::transaction(function () use ($request) {
                // Guardar los datos
                $product = Product::where('id', $request['product_id'])
                    ->firstOrFail();

                //Actulizar los datos
                $product->stock += $request['stock'];
                $product->cost = $request['cost'];
                $product->price = $request['price'];
                $product->save();

                //Crear la transaction del producto
                ProTrans::create([
                    'product_id' => $request['product_id'],
                    'stock' => $request['stock'],
                    'cost' => $request['cost'],
                    'price' => $request['price'],
                    'type' => ProductTypeEnum::ENTRADA
                ]);
            });


            //Devolver hacia atras
            return back();
        }catch (\throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductIn $productIn)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Product $productIn)
    {
        try {
            $data = $this->getProduct($request);

            return Inertia::render('Products/In',[
                'products' => $data,
                'productEdit' => $productIn,
                'update' => true
            ]);
        }catch (\throwable $th) {
            throw $th;
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductInRequest $request, Product $productIn)
    {
        try {
            //Tomar las fechas
            $updateDay = config('setting.document-update');
            $created_at  = Carbon::parse($productIn->created_at);
            $now = Carbon::now();

            // Evitar que sea mas grande
            if($created_at->greaterThan($now))
            {
                return 'No se puede actualizar el registro';
            }else{
                return 'Se ha actualizado correctamente';
            }





        }catch (\throwable $th) {
            throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $productIn)
    {
        try {

//            Actualziar los datos
            $productIn->status = true;
            $productIn->save();

//            Retornar hacia atras
            return back();
        }catch (\throwable $th) {
            throw $th;
        }
    }

    //Obteener los productos
    private function getProduct(Request $request):Paginator
    {
        $search = $request->get('search');

        $data = Product::where('status', false)
            ->where('name','LIKE','%'.$search.'%')
            ->latest()
            ->simplePaginate();

        return $data;
    }
}
