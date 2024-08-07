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
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {

        //conseguir  los datos
        $data = $this->getProduct($request);

        //Devolver la vista con los datos
        return Inertia::render('Products/In',[
            'products' => $data
        ]);


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Summary of store
     * @param \App\Http\Requests\StoreProductInRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreProductInRequest $request)
    {
        DB::transaction(function () use ($request) {
            // Guardar los datos
            $product = Product::where('id', $request['product_id'])
                ->firstOrFail();

            //Actulizar los datos
            $product->stock += $request['stock'];
            $product->cost = $request['cost'];
            $product->price = $request['price'];
            $product->discount = $request['discount'];
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

    }

    /**
     * Display the specified resource.
     */
    // public function show(ProductIn $productIn)
    // {
    //     //
    // }

    /**
     * Summary of entrance
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $productIn
     * @return \Inertia\Response
     */
    public function entrance(Request $request, Product $productIn)
    {

        // Tomar lo datos de todos los produtos
        $data = $this->getProduct($request);

        // Devolver la vista con los datos
        return Inertia::render('Products/In',[
            'products' => $data,
            'productEntrance' => $productIn,
        ]);

    }

    /**
     * Summary of update
     * @param \App\Http\Requests\UpdateProductInRequest $request
     * @param \App\Models\Product $productIn
     * @return string
     */
    public function update(UpdateProductInRequest $request, Product $productIn)
    {

        return $request;

        //Tomar las fechas
        $updateDay = config('setting.document-update');

        // Formatear la fecha de creacion
        $created_at  = Carbon::parse($productIn->created_at);

        // tomar la fecha actual
        $now = Carbon::now();

        // si el parametro de actualiacion es mayor  a updated at
        if($created_at->greaterThan($now))
        {
            return 'No se puede actualizar el registro';
        }else{
            return 'Se ha actualizado correctamente';
        }

    }

    /**
     * Summary of destroy
     * @param \App\Models\Product $productIn
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $productIn)
    {

        //Actualziar los datos
        $productIn->status = true;
        $productIn->save();

        //Retornar hacia atras
        return back();

    }

    /**
     * Summary of getProduct
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Pagination\Paginator
     */
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
